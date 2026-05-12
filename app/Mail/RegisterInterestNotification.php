<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterInterestNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user)
    {
    }

    public function build(): self
    {
        $fullPhone = trim(($this->user->country_code ?? '') . ' ' . ($this->user->phone_number ?? ''));
        $galleryName = $this->user->gallery_name ?? $this->user->name ?? 'Unknown Gallery';

        return $this->subject('[ADAI] Gallery Registration of Interest — ' . $galleryName)
            ->replyTo($this->user->email, $galleryName)
            ->view('emails.register-interest-notification')
            ->with([
                'user' => $this->user,
                'reference' => $this->user->interest_reference,
                'fullPhone' => $fullPhone,
            ]);
    }
}

