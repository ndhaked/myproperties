<?php

namespace App\Listeners;

use App\Events\RegisterInterestSubmitted;
use App\Mail\RegisterInterestConfirmation;
use App\Mail\RegisterInterestNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendRegisterInterestNotification
{
    private static array $processedReferences = [];

    public function handle(RegisterInterestSubmitted $event): void
    {
        $reference = $event->user->interest_reference;

        // Prevent duplicate sends for the same reference within the same request
        if (isset(self::$processedReferences[$reference])) {
            Log::debug('Register interest notification skipped: already processed', [
                'reference' => $reference,
                'user_email' => $event->user->email,
            ]);
            return;
        }

        $recipient = config('services.register_interest_recipient');

        // Send admin notification
        if ($recipient) {
            try {
                Mail::to($recipient)->send(new RegisterInterestNotification($event->user));

                Log::info('Register interest notification sent successfully', [
                    'recipient' => $recipient,
                    'user_email' => $event->user->email,
                    'reference' => $reference,
                ]);
            } catch (Throwable $e) {
                Log::error('Failed to send register interest notification email', [
                    'recipient' => $recipient,
                    'user_email' => $event->user->email,
                    'reference' => $reference,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                throw $e;
            }
        } else {
            Log::warning('Register interest notification skipped: recipient not configured', [
                'user_email' => $event->user->email,
            ]);
        }

        // Send user confirmation email
        try {
            Mail::to($event->user->email)->send(new RegisterInterestConfirmation($event->user));

            Log::info('Register interest confirmation email sent successfully', [
                'user_email' => $event->user->email,
                'reference' => $reference,
            ]);
        } catch (Throwable $e) {
            Log::error('Failed to send register interest confirmation email', [
                'user_email' => $event->user->email,
                'reference' => $reference,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Don't throw error for user email - log and continue
            // Admin email is more critical, but we still log user email failures
        }

        // Mark as processed
        self::$processedReferences[$reference] = true;
    }
}

