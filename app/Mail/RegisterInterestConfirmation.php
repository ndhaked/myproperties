<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterInterestConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user)
    {
    }

    public function build(): self
    {
        $fullPhone = trim(($this->user->country_code ?? '') . ' ' . ($this->user->phone_number ?? ''));
        $supportEmail = config('services.support_email', 'info@laravelexpert.in');

        return $this->subject('Thank you for your interest in ADAI')
            ->replyTo($supportEmail, 'ADAI Support')
            ->view('emails.register-interest-confirmation')
            ->with([
                'user' => $this->user,
                'reference' => $this->user->interest_reference,
                'fullPhone' => $fullPhone,
                'supportEmail' => $supportEmail,
            ]);
    }
}

