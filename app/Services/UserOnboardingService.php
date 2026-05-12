<?php

namespace App\Services;

use App\Models\User;

class UserOnboardingService
{
    /**
     * Complete onboarding for the given user by setting the password
     * and marking the account as active (not pending).
     */
    public function complete(User $user, string $plainPassword): void
    {
        // User model has 'password' => 'hashed' cast, so assigning the plain
        // password will automatically hash it exactly once.
        $user->password = $plainPassword;
        $user->pending = false;
        $user->save();
    }
}

