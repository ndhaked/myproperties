<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\OtpRequest;
use App\Models\RefreshToken;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function __construct(protected OtpService $otpService)
    {
    }

    public function sendOtp(Request $request)
    {
        $data = $request->validate([
            'phone' => ['required', 'string'],
            'countryCode' => ['nullable', 'string'],
            'role' => ['nullable', 'in:Buyer,Seller'],
            'fullName' => ['nullable', 'string', 'max:255'],
        ]);

        $countryCode = $data['countryCode'] ?? '+91';
        $existingUser = User::where('phone', $data['phone'])->first();

        $isSignup = ! empty($data['fullName']);

        if ($isSignup && $existingUser) {
            return ApiResponse::error('USER_ALREADY_EXISTS', 'An account with this phone number already exists.', 422);
        }

        if (! $isSignup && ! $existingUser) {
            return ApiResponse::error('USER_NOT_FOUND', 'No account found with this phone number.', 404);
        }

        $otpRequest = $this->otpService->generate(
            $data['phone'],
            $countryCode,
            $data['role'] ?? null,
            $data['fullName'] ?? null
        );

        return ApiResponse::success($this->otpResponse($otpRequest));
    }

    public function resendOtp(Request $request)
    {
        $data = $request->validate([
            'requestId' => ['required', 'string'],
        ]);

        $otpRequest = OtpRequest::where('request_id', $data['requestId'])->first();

        if (! $otpRequest) {
            return ApiResponse::error('OTP_NOT_FOUND', 'This OTP request could not be found.', 404);
        }

        $otpRequest = $this->otpService->resend($otpRequest);

        return ApiResponse::success($this->otpResponse($otpRequest));
    }

    public function verifyOtp(Request $request)
    {
        $data = $request->validate([
            'requestId' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'otp' => ['required', 'string'],
        ]);

        $otpRequest = OtpRequest::where('request_id', $data['requestId'])
            ->where('phone', $data['phone'])
            ->first();

        if (! $otpRequest || ! $this->otpService->verify($otpRequest, $data['otp'])) {
            return ApiResponse::error('INVALID_OTP', 'The OTP entered is incorrect or expired.', 422);
        }

        $isNewUser = false;
        $user = User::where('phone', $data['phone'])->first();

        if (! $user) {
            $user = User::create([
                'name' => $otpRequest->full_name ?? 'User',
                'phone' => $otpRequest->phone,
                'country_code' => $otpRequest->country_code,
                'role' => $otpRequest->role ?? 'Buyer',
                'is_verified' => true,
            ]);
            $isNewUser = true;
        } elseif (! $user->is_verified) {
            $user->update(['is_verified' => true]);
        }

        return ApiResponse::success($this->authResponse($user, $isNewUser));
    }

    public function socialLogin(Request $request)
    {
        // Stub: real Google/Apple token verification is not wired up yet (no OAuth
        // credentials configured). The client-supplied profile fields are trusted as-is.
        $data = $request->validate([
            'provider' => ['required', 'in:google,apple'],
            'idToken' => ['required', 'string'],
            'role' => ['nullable', 'in:Buyer,Seller'],
            'email' => ['nullable', 'email'],
            'fullName' => ['nullable', 'string', 'max:255'],
            'providerId' => ['nullable', 'string'],
        ]);

        $identifier = $data['email'] ?? $data['providerId'] ?? null;

        if (! $identifier) {
            return ApiResponse::error('SOCIAL_PROFILE_MISSING', 'No profile information supplied for social login.', 422);
        }

        $user = User::where('email', $identifier)->first();
        $isNewUser = false;

        if (! $user) {
            $user = User::create([
                'name' => $data['fullName'] ?? 'User',
                'email' => $data['email'] ?? null,
                'role' => $data['role'] ?? 'Buyer',
                'is_verified' => true,
            ]);
            $isNewUser = true;
        }

        return ApiResponse::success($this->authResponse($user, $isNewUser));
    }

    public function refreshToken(Request $request)
    {
        $data = $request->validate([
            'refreshToken' => ['required', 'string'],
        ]);

        $tokenHash = hash('sha256', $data['refreshToken']);
        $refreshToken = RefreshToken::where('token_hash', $tokenHash)->first();

        if (! $refreshToken || ! $refreshToken->isValid()) {
            return ApiResponse::error('INVALID_REFRESH_TOKEN', 'The refresh token is invalid or expired.', 401);
        }

        $accessToken = $this->issueAccessToken($refreshToken->user);

        return ApiResponse::success([
            'accessToken' => $accessToken['token'],
            'expiresIn' => $accessToken['expiresIn'],
        ]);
    }

    public function logout(Request $request)
    {
        $data = $request->validate([
            'refreshToken' => ['nullable', 'string'],
        ]);

        $request->user()?->currentAccessToken()?->delete();

        if (! empty($data['refreshToken'])) {
            RefreshToken::where('token_hash', hash('sha256', $data['refreshToken']))
                ->update(['revoked_at' => now()]);
        }

        return ApiResponse::success(null, 'Logged out.');
    }

    protected function otpResponse(OtpRequest $otpRequest): array
    {
        $response = [
            'requestId' => $otpRequest->request_id,
            'phone' => $otpRequest->phone,
            'expiresInSeconds' => $this->otpService->expiresInSeconds(),
        ];

        if (config('app.debug')) {
            $response['debugOtp'] = $otpRequest->getAttribute('plain_otp');
        }

        return $response;
    }

    protected function authResponse(User $user, bool $isNewUser): array
    {
        $access = $this->issueAccessToken($user);
        $refreshToken = $this->issueRefreshToken($user);

        return [
            'accessToken' => $access['token'],
            'refreshToken' => $refreshToken,
            'expiresIn' => $access['expiresIn'],
            'isNewUser' => $isNewUser,
            'user' => [
                'id' => 'user_' . $user->id,
                'fullName' => $user->name,
                'phone' => $user->phone ? $user->country_code . $user->phone : null,
                'role' => $user->role,
                'avatar' => $user->avatar,
                'isVerified' => (bool) $user->is_verified,
            ],
        ];
    }

    protected function issueAccessToken(User $user): array
    {
        $expiresInMinutes = 60;
        $token = $user->createToken('mobile', ['*'], now()->addMinutes($expiresInMinutes));

        return [
            'token' => $token->plainTextToken,
            'expiresIn' => $expiresInMinutes * 60,
        ];
    }

    protected function issueRefreshToken(User $user): string
    {
        $plain = Str::random(64);

        RefreshToken::create([
            'user_id' => $user->id,
            'token_hash' => hash('sha256', $plain),
            'expires_at' => now()->addDays(30),
        ]);

        return $plain;
    }
}
