<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class contactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'full_name'  => 'required|string|max:255',
            'email'      => 'required|email',
            'phone'      => 'required|numeric',
            'subject'    => 'required|string|max:255',
            'message'    => 'required|string',
            'country_id' => 'required|exists:countries,id',
        ];

        // ✅ Only enable captcha outside local
        if (config('app.env') !== 'local') {
            $rules['recaptcha_token'] = ['required', 'string'];
        }

        return $rules;
    }
    public function messages()
    {
        return [
            'full_name.required'  => 'Please enter your full name.',
            'email.required'      => 'Please enter your email address.',
            'email.email'         => 'Please enter a valid email.',

            'country_id.required' => 'Please select phone code.',
            'country_id.exists'   => 'Invalid country selected.',

            'phone.required'      => 'Please enter your phone number.',
            'phone.numeric'       => 'Phone number must be numeric.',

            'subject.required'    => 'Please enter a subject.',
            'message.required'    => 'Please enter your message.',
        ];
    }
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
            throw ValidationException::withMessages([
                'message' => 'Captcha verification failed.',
            ]);
        }

        // ✅ Action must match frontend
        if (($response['action'] ?? '') !== 'contact') {
            throw ValidationException::withMessages([
                'message' => 'Captcha action mismatch.',
            ]);
        }

        // ✅ Score threshold (adjust as needed)
        if (($response['score'] ?? 0) < 0.3) {
            throw ValidationException::withMessages([
                'message' => 'Captcha score too low. Please try again.',
            ]);
        }
    }

    /**
     * Run after validation passes
     */
    protected function passedValidation(): void
    {
        if (config('app.env') !== 'local') {
            $this->verifyCaptcha();
        }
    }

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

}
