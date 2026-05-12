<?php

namespace App\Mail;

use App\Models\Artist;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RejectArtistNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Artist $artist)
    {
    }

    public function build(): self
    {
        $artistName = $this->artist->FullArtistName;

        return $this->subject('Artist Rejection Email — ' . $artistName)
            ->replyTo($this->artist->user->email, $artistName)
            ->view('emails.artist-rejected-notification') // <-- use correct view
            ->with([
                'artist' => $this->artist, // <-- correct variable
            ]);
    }
}
