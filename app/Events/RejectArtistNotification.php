<?php

namespace App\Events;

use App\Models\Artist;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RejectArtistNotification
{
    use Dispatchable, SerializesModels;

    public function __construct(public Artist $artist)
    {
    }
}

