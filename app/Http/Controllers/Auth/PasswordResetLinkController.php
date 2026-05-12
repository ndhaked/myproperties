<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    protected function verifyCaptcha(Request $request, string $expectedAction): void
    {
        $response = Http::asForm()->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'secret'   => config('services.recaptcha.secret_key'),
                'response' => $request->recaptcha_token,
                'remoteip' => $request->ip(),
            ]
        )->json();

        if (! ($response['success'] ?? false)) {
            throw ValidationException::withMessages([
                'email' => 'Captcha verification failed.',
            ]);
        }

        if (($response['action'] ?? '') !== $expectedAction) {
            throw ValidationException::withMessages([
                'email' => 'Captcha action mismatch.',
            ]);
        }

        if (($response['score'] ?? 0) < 0.5) {
            throw ValidationException::withMessages([
                'email' => 'Captcha score too low. Please try again.',
            ]);
        }
    }


    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        // $request->validate([
        //     'email' => ['required', 'email'],
        // ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        if ($request->ajax()) {
            $this->verifyCaptcha($request, 'forgot_password');
            $user = User::where('email', operator: $request->email)->first();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => "Email not found",
                    'errors' => [["We can't find a user with that email address"]]
                ], 422);
            }
            $otp = rand(100000, 999999);

            $user->reset_otp = $otp;
            $user->reset_otp_expires_at = now()->addMinutes(10);
            $user->save();
            Mail::to($user->email)->send(new OtpMail($otp));
            return response()->json([
                'status_code'    => 200,
                'type'    => 'success',
                'error'   => false,
                'message' => 'OTP sent to your email.',
                'url'    => route('verify.otp.page', ['email' => base64_encode($user->email)]),
            ], 200);
        }
    }
    public function verifyOtpPage($email)
    {
        $email = base64_decode($email);
        $maskedEmail = $this->maskEmail($email);

        return view('auth.verify-otp', compact('email','maskedEmail'));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();
        $otp = implode('', $request->otp);   // Combine 6 digits
        if (!$user || $user->reset_otp != $otp) {
            return response()->json([
                'status' => false,
                'message' => "Invalid code. Please try again.",
                'errors' => [['Invalid code. Please try again.']]
            ], 422);
        }

        if ($user->reset_otp_expires_at     < now()) {
            return response()->json([
                'message' => "OTP expired",
                'errors' => [['Expired code. Please try again.']]
            ], 422);
        }

        $token = Password::createToken($user);

        // Build password reset URL
        $resetUrl = url('/reset-password/' . $token . '?email=' . urlencode($user->email));

        return response()->json([
            'status_code'    => 200,
            'type'    => 'success',
            'error'   => false,
            'message' => 'OTP verified successfully.',
            'url'    => $resetUrl
        ]);
    }
    public function resendOtp(Request $request)
    {
        $user = User::where('email', operator: $request->email)->first();
        $otp = rand(100000, 999999);
        $user->reset_otp = $otp;
        $user->reset_otp_expires_at = now()->addMinutes(10);
        $user->save();
        Mail::to($user->email)->send(new OtpMail($otp));
        return response()->json([
            'status_code'    => 200,
            'type'    => 'success',
            'error'   => false,
            'message' => 'OTP sent to your email.',
        ], 200);
    }
    private function maskEmail($email)
    {
        [$username, $domain] = explode('@', $email);

        // Username mask
        $first = strtoupper(substr($username, 0, 1));
        $last = strtoupper(substr($username, -1));
        $middleMask = '•••••';

        // Domain mask
        $domainName = explode('.', $domain)[0];
        $extension = explode('.', $domain)[1];

        $domainFirst = strtoupper(substr($domainName, 0, 1));
        $domainMask = '•••';
        $domainLast = strtoupper(substr($domainName, -1));

        return "{$first}{$middleMask}{$last}@{$domainFirst}{$domainMask}{$domainLast}.{$extension}";
    }
}
