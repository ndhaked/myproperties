<?php

namespace App\Mail;

use App\Models\Gallery;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GalleryPasswordChangedMail extends Mailable
{
    use SerializesModels;

    public function __construct(
        public Gallery $gallery
    ) {}

    public function build()
    {
        return $this->subject('Security Alert: Gallery Password Changed')
            ->view('emails.gallery-password-changed')
            ->with([
                'adminName'   => optional($this->gallery->user)->name ?? 'Gallery Admin',
                'galleryName' => $this->gallery->gallery_name,
                'loginUrl'    => route('login'),
                'changedAt'   => now()->timezone(config('app.timezone')),
            ]);
    }
}
