<?php

namespace App\Listeners;

use App\Events\GalleryInvitationCreated;
use App\Mail\GalleryInvitation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendGalleryInvitationEmail
{
    private static array $processedInvitations = [];

    public function handle(GalleryInvitationCreated $event): void
    {
        $invitationId = $event->invitation->invitation_id;

        if (isset(self::$processedInvitations[$invitationId])) {
            Log::debug('Gallery invitation email skipped (already processed)', [
                'invitation_id' => $invitationId,
            ]);
            return;
        }

        try {
            Mail::to($event->invitation->email)->send(new GalleryInvitation($event->invitation));

            Log::info('Gallery invitation email sent', [
                'invitation_id' => $invitationId,
                'email' => $event->invitation->email,
            ]);

            self::$processedInvitations[$invitationId] = true;
        } catch (Throwable $e) {
            Log::error('Failed to send gallery invitation email', [
                'invitation_id' => $invitationId,
                'email' => $event->invitation->email,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}

