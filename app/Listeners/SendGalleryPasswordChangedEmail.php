<?php

namespace App\Listeners;

use App\Events\GalleryPasswordChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\GalleryPasswordChangedMail;
use Illuminate\Support\Facades\Mail;

class SendGalleryPasswordChangedEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(GalleryPasswordChanged $event): void
    {
        $gallery = $event->gallery;

        // Decide recipient
        $email = optional($gallery->user)->email;

        if (!$email) {
            return;
        }

        Mail::to($email)
        ->send(new GalleryPasswordChangedMail($gallery));
    }
}
