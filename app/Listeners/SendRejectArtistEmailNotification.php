<?php

namespace App\Listeners;

use App\Events\RejectArtistNotification;
use App\Mail\RejectArtistNotificationMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendRejectArtistEmailNotification
{
    private static array $processedReferences = [];

    public function handle(RejectArtistNotification $event): void
    {
        $recipient = $event->artist->user->email;

        // Send admin notification
        if ($recipient) {
            try {
                Mail::to($recipient)->send(new RejectArtistNotificationMail($event->artist));

                Log::info('RejectArtistNotificationMail', [
                    'recipient' => $recipient,
                    'user_email' => $event->artist->user->email,
                ]);
            } catch (Throwable $e) {
                Log::error('Failed to send register interest notification email', [
                    'recipient' => $recipient,
                    'user_email' => $event->artist->user->email,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                throw $e;
            }
        } else {
            Log::warning('Register interest notification skipped: recipient not configured', [
                'user_email' => $event->artist->user->email,
            ]);
        }
    }
}

