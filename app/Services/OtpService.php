<?php

namespace App\Services;

use App\Models\OtpRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OtpService
{
    protected const EXPIRES_IN_SECONDS = 120;

    // Temporary: always issue this fixed code instead of a random one, to make manual/QA
    // testing easier while there's no real SMS provider wired up. Swap generateCode() back
    // to random_int(100000, 999999) once that's in place.
    protected const FIXED_OTP = '123456';

    public function generate(string $phone, string $countryCode, ?string $role = null, ?string $fullName = null): OtpRequest
    {
        $otp = $this->generateCode();

        $request = OtpRequest::create([
            'request_id' => (string) Str::uuid(),
            'phone' => $phone,
            'country_code' => $countryCode,
            'role' => $role,
            'full_name' => $fullName,
            'otp_hash' => Hash::make($otp),
            'expires_at' => now()->addSeconds(self::EXPIRES_IN_SECONDS),
        ]);

        $this->deliver($phone, $otp);

        $request->setAttribute('plain_otp', $otp);

        return $request;
    }

    public function resend(OtpRequest $request): OtpRequest
    {
        $otp = $this->generateCode();

        $request->update([
            'otp_hash' => Hash::make($otp),
            'expires_at' => now()->addSeconds(self::EXPIRES_IN_SECONDS),
            'attempts' => 0,
        ]);

        $this->deliver($request->phone, $otp);

        $request->setAttribute('plain_otp', $otp);

        return $request;
    }

    public function verify(OtpRequest $request, string $otp): bool
    {
        if ($request->verified_at || $request->expires_at->isPast()) {
            return false;
        }

        $request->increment('attempts');

        if (! Hash::check($otp, $request->otp_hash)) {
            return false;
        }

        $request->update(['verified_at' => now()]);

        return true;
    }

    public function expiresInSeconds(): int
    {
        return self::EXPIRES_IN_SECONDS;
    }

    protected function deliver(string $phone, string $otp): void
    {
        Log::info("OTP for {$phone}: {$otp}");
    }

    protected function generateCode(): string
    {
        // Only short-circuit to the fixed code while debugging locally/QA; a real
        // deployment (APP_DEBUG=false) always gets a proper random OTP.
        if (config('app.debug')) {
            return self::FIXED_OTP;
        }

        return (string) random_int(100000, 999999);
    }
}
