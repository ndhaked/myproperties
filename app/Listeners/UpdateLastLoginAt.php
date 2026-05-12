<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Events\Attributes\AsListener;

#[AsListener]
class UpdateLastLoginAt
{
    public function handle(Login $event): void
    {
        $event->user->last_login_at = now();
        $event->user->save();
    }
}
