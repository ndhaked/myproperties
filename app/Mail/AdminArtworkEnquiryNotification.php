<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\ArtworkEnquiry;

class AdminArtworkEnquiryNotification extends Mailable
{
    use Queueable, SerializesModels;
    public $enquiry;

    /**
     * Create a new message instance.
     */
    public function __construct(ArtworkEnquiry $enquiry)
    {
        //
        $this->enquiry = $enquiry;
    }
     public function build()
    {
        return $this->subject('admin We received your enquiry - ADAI')
                    ->view('emails.enquiries.adminNotification');
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Admin Artwork Enquiry Notification',
        );
    }

  

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
