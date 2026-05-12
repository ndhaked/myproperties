<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Normalize empty password fields to null to prevent validation issues
        $passwordFields = ['current_password', 'password', 'password_confirmation'];
        
        foreach ($passwordFields as $field) {
            $value = $this->input($field);
            if (is_string($value) && trim($value) === '') {
                $this->merge([$field => null]);
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'timezone' => ['nullable', 'string', 'max:100'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:5120', 'dimensions:min_width=200,min_height=200'],
            'profile_photo_filename' => ['nullable', 'string', 'max:255'],
            'delete_profile_photo' => ['nullable', 'string', 'in:0,1'],
            'current_password' => ['nullable', 'string'],
            'password' => ['nullable', 'string'],
            'password_confirmation' => ['nullable', 'string'],
        ];

        // Only validate password fields if at least one password field has a value
        $currentPassword = $this->input('current_password');
        $password = $this->input('password');
        $passwordConfirmation = $this->input('password_confirmation');
        
        $hasAnyPasswordValue = !empty($currentPassword) || 
                              !empty($password) || 
                              !empty($passwordConfirmation);

        if ($hasAnyPasswordValue) {
            // If any password field is filled, all three are required
            $rules['current_password'] = ['required', 'string', 'current_password'];
            $rules['password'] = [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/',
                'confirmed'
            ];
            $rules['password_confirmation'] = ['required', 'string'];
        }

        return $rules;
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'email.required' => 'Email Address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already taken.',
            'current_password.required' => 'Current password is required when changing password.',
            'current_password.current_password' => 'The current password is incorrect.',
            'password.required' => 'New password is required when changing password.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.regex' => 'Password must include uppercase, lowercase, number, and special character.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password_confirmation.required' => 'Password confirmation is required when changing password.',
        ];
    }
}
