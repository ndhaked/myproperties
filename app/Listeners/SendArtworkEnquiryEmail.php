<?php

namespace App\Listeners;

use App\Events\ArtworkEnquiryCreated;
use App\Mail\AdminArtworkEnquiryNotification;
use App\Mail\ArtworkEnquiryConfirmation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue; // Optional: implement this to send in background

class SendArtworkEnquiryEmail /*implements ShouldQueue*/
{
    /**
     * Handle the event.
     */
    public function handle(ArtworkEnquiryCreated $event): void
    {
        //\Log::info($event->enquiry);
        // Send email to the person who made the enquiry
        if ($event->enquiry->email) {
            Mail::to($event->enquiry->email)->send(new ArtworkEnquiryConfirmation($event->enquiry));
        }
        // Send email to admin - only if recipient email is configured
        $recipientEmail = config('mail.recipient_email');
        if ($recipientEmail && !empty(trim($recipientEmail))) {
            Mail::to($recipientEmail)
                ->send(new AdminArtworkEnquiryNotification($event->enquiry));
        }
    }
}
