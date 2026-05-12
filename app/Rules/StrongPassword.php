<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StrongPassword implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check password strength
        if (!preg_match('/[A-Z]/', $value)) {
            $fail('Password must contain at least one uppercase letter.');
            return;
        }

        if (!preg_match('/[a-z]/', $value)) {
            $fail('Password must contain at least one lowercase letter.');
            return;
        }

        if (!preg_match('/[0-9]/', $value)) {
            $fail('Password must contain at least one number.');
            return;
        }

        if (!preg_match('/[^A-Za-z0-9]/', $value)) {
            $fail('Password must contain at least one special character.');
            return;
        }

        if (strlen($value) < 8) {
            $fail('Password must be at least 8 characters long.');
            return;
        }
    }
}
