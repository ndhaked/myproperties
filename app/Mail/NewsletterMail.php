<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterMail extends Mailable
{
    use Queueable, SerializesModels;
    public $email;


    /**
     * Create a new message instance.
     */
    public function __construct($email)
    {
        //
        $this->email = $email;
    }

    /**
     * Get the message envelope.
     */
     public function build()
    {
        return $this->subject('ADAI Newsletter')
            ->view('emails.newsletter');
    }
    /**
     * Get the message content definition.
     */
    
}
