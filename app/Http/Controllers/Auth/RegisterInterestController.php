<?php

namespace App\Http\Controllers\Auth;

use App\Events\RegisterInterestSubmitted;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class RegisterInterestController extends Controller
{
    protected function verifyCaptcha(Request $request): void
    {
        $response = Http::asForm()->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'secret'   => config('services.recaptcha.secret_key'),
                'response' => $request->input('recaptcha_token'),
                'remoteip' => $request->ip(),
            ]
        )->json();

        if (! ($response['success'] ?? false)) {
            throw ValidationException::withMessages([
                'recaptcha' => 'Captcha verification failed.',
            ]);
        }

        // ✅ v3 action check (must match frontend)
        if (($response['action'] ?? '') !== 'register_interest') {
            throw ValidationException::withMessages([
                'recaptcha' => 'Captcha action mismatch.',
            ]);
        }

        // ✅ score threshold
        if (($response['score'] ?? 0) < 0.3) {
            throw ValidationException::withMessages([
                'recaptcha' => 'Captcha score too low. Please try again.',
            ]);
        }
    }


    public function store(Request $request): JsonResponse
    {
        $existingUser = User::where('email', $request->input('email'))->first();

        $data = $request->validate(
           array_merge([
                'gallery_name' => ['required', 'string', 'max:255'],
                'country_id' => ['required', 'exists:countries,id'],
                'city' => ['required', 'string', 'max:150'],
                'address' => ['required', 'string', 'max:255'],
                'email' => [
                    'required',
                    'email',
                    'max:255',
                    Rule::unique('users', 'email')->ignore($existingUser?->id),
                ],
                'country_code' => ['required', 'regex:/^\+\d{1,4}$/'],
                'phone_number' => ['required', 'regex:/^[1-9][0-9]{6,14}$/'],
                'external_link' => ['nullable', 'url', 'max:255'],
                'agree_to_terms' => ['accepted'],
            ],
            config('app.env') !== 'local'
                ? ['recaptcha_token' => ['required', 'string']]
                : []
            ),
            [
                'country_code.regex' => 'Please select a valid country code.',
                'phone_number.regex' => 'Please enter a valid phone number.',
                'external_link.url' => 'Please enter a valid URL.',
            ],
            [
                'agree_to_terms' => 'terms',
            ]
        );
        if (config('app.env') !== 'local') {
            $this->verifyCaptcha($request);
        }

        if ($existingUser && $this->hasRecentSubmission($existingUser)) {
            Log::info('Register interest duplicate attempt', [
                'email' => $existingUser->email,
                'reference' => $existingUser->interest_reference,
                'attempted_at' => now()->toIso8601String(),
            ]);

            return response()->json([
                'status_code' => 429,
                'type' => 'warning',
                'error' => true,
                'ga4Event' => 'requestFailed',
                'message' => 'We already have a recent request from this email. Our team will be in touch.',
            ], 429);
        }


        $reference = $this->generateReference();
        $timestamp = now();

        $payload = [
            'name' => $data['gallery_name'],
            'pending' => true,
            'country_id' => $data['country_id'],
            'city' => $data['city'],
            'address' => $data['address'],
            'country_code' => $data['country_code'],
            'phone_number' => $data['phone_number'],
            'external_links' => $data['external_link'] ? [$data['external_link']] : null,
            'interest_reference' => $reference,
            'interest_status' => false,
            'interest_submitted_at' => $timestamp,
            'consent_at' => $timestamp,
        ];

        if ($existingUser) {
            $existingUser->fill($payload);
            $existingUser->email = $data['email'];
            $existingUser->save();
            $user = $existingUser->fresh();
        } else {
            $user = User::create(array_merge(
                $payload,
                [
                    'email' => $data['email'],
                    'password' => Hash::make(Str::random(32)),
                ]
            ));
        }

        $user->loadMissing('country');

        Log::info('Register interest submitted', [
            'email' => $user->email,
            'reference' => $reference,
            'submitted_at' => $timestamp->toIso8601String(),
        ]);

        event(new RegisterInterestSubmitted($user));

        return response()->json([
            'status_code' => 200,
            'type' => 'success',
            'error' => false,
            'message' => 'Thank you—your request has been received. We’ll be in touch soon.',
            'ga4Event' => 'requestSuccess',
            'reset' => true,
            'url' => route('register-interest-success'),
        ]);
    }
    public function registerInterestSuccess(): View
    {
        return view('front/register-interest-success');
    }


    protected function hasRecentSubmission(?User $user): bool
    {
        if (!$user || !$user->interest_submitted_at) {
            return false;
        }

        return $user->interest_submitted_at->greaterThan(now()->subDay());
    }

    protected function generateReference(): string
    {
        do {
            $reference = 'RI-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        } while (User::where('interest_reference', $reference)->exists());

        return $reference;
    }
}

