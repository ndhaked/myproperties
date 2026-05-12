<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewsletterSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // allow public access
    }

    public function rules(): array
    {
        return [
             'email' => 'required|email|unique:newsletter_subscriptions,email',

        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email address is required.',
            'email.email'    => 'Please enter a valid email address.',
            'email.unique'   => 'This email is already subscribed.',
        ];
    }
}
