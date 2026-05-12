<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        $rules = [
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];

        if (config('app.env') !== 'local') {
            //$rules['recaptcha_token'] = ['required', 'string'];
        }

        return $rules;
    }

    /**
     * Handle rule-based validation failure
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'ga4Event' => 'requestFailed',
                'message'  => 'Validation failed.',
                'errors'   => $validator->errors(),
            ], 422)
        );
    }

    /**
     * Centralized failure response
     */
    protected function fail(string $message, array $errors = []): void
    {
        throw new HttpResponseException(
            response()->json([
                'ga4Event' => 'requestFailed',
                'message'  => $message,
                'errors'   => $errors ?: [
                    'email' => [$message],
                ],
            ], 422)
        );
    }

    /**
     * Verify Google reCAPTCHA v3
     */
    protected function verifyCaptcha(): void
    {
        $response = Http::asForm()->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'secret'   => config('services.recaptcha.secret_key'),
                'response' => $this->input('recaptcha_token'),
                'remoteip' => $this->ip(),
            ]
        )->json();

        if (! ($response['success'] ?? false)) {
            $this->fail('Captcha verification failed.');
        }

        if (($response['action'] ?? '') !== 'login') {
            $this->fail('Captcha action mismatch.');
        }

        if (($response['score'] ?? 0) < 0.2) {
            $this->fail('Captcha score too low. Please try again.');
        }
    }

    /**
     * Authenticate user
     */
    public function authenticate(): void
    {
        if (config('app.env') !== 'local') {
            //$this->verifyCaptcha();
        }

        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());
            $this->fail(trans('auth.failed'));
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Rate limiting
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        $this->fail(
            trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ])
        );
    }

    /**
     * Throttle key
     */
    public function throttleKey(): string
    {
        return Str::transliterate(
            Str::lower($this->string('email')) . '|' . $this->ip()
        );
    }
}
