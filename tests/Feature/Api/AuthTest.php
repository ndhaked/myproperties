<?php

namespace Tests\Feature\Api;

use App\Models\OtpRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_signup_sends_otp_and_verify_creates_a_user(): void
    {
        config(['app.debug' => true]);

        $send = $this->postJson('/api/v1/auth/otp/send', [
            'phone' => '9876543210',
            'countryCode' => '+91',
            'role' => 'Buyer',
            'fullName' => 'Rajendra Sharma',
        ])->assertOk()->json('data');

        $this->assertArrayHasKey('requestId', $send);
        $this->assertArrayHasKey('debugOtp', $send);

        $verify = $this->postJson('/api/v1/auth/otp/verify', [
            'requestId' => $send['requestId'],
            'phone' => '9876543210',
            'otp' => $send['debugOtp'],
        ])->assertOk()->json('data');

        $this->assertTrue($verify['isNewUser']);
        $this->assertSame('Rajendra Sharma', $verify['user']['fullName']);
        $this->assertNotEmpty($verify['accessToken']);
        $this->assertNotEmpty($verify['refreshToken']);

        $this->assertDatabaseHas('users', ['phone' => '9876543210', 'role' => 'Buyer']);

        $user = User::where('phone', '9876543210')->first();
        $this->assertTrue($user->hasRole('Buyer'), 'User::role should auto-sync into spatie roles on creation.');
    }

    public function test_signup_otp_rejects_an_already_registered_phone(): void
    {
        User::factory()->create(['phone' => '9876543210']);

        $this->postJson('/api/v1/auth/otp/send', [
            'phone' => '9876543210',
            'fullName' => 'New Name',
        ])->assertStatus(422)->assertJsonPath('error.code', 'USER_ALREADY_EXISTS');
    }

    public function test_login_otp_rejects_an_unregistered_phone(): void
    {
        $this->postJson('/api/v1/auth/otp/send', [
            'phone' => '9999999999',
        ])->assertStatus(404)->assertJsonPath('error.code', 'USER_NOT_FOUND');
    }

    public function test_wrong_otp_is_rejected(): void
    {
        config(['app.debug' => true]);

        User::factory()->create(['phone' => '9876543210']);

        $send = $this->postJson('/api/v1/auth/otp/send', ['phone' => '9876543210'])
            ->assertOk()->json('data');

        $this->postJson('/api/v1/auth/otp/verify', [
            'requestId' => $send['requestId'],
            'phone' => '9876543210',
            'otp' => '000000',
        ])->assertStatus(422)->assertJsonPath('error.code', 'INVALID_OTP');
    }

    public function test_resend_otp_issues_a_new_code(): void
    {
        config(['app.debug' => true]);

        User::factory()->create(['phone' => '9876543210']);

        $send = $this->postJson('/api/v1/auth/otp/send', ['phone' => '9876543210'])
            ->assertOk()->json('data');

        $resend = $this->postJson('/api/v1/auth/otp/resend', ['requestId' => $send['requestId']])
            ->assertOk()->json('data');

        $this->assertSame($send['requestId'], $resend['requestId']);

        $this->postJson('/api/v1/auth/otp/verify', [
            'requestId' => $resend['requestId'],
            'phone' => '9876543210',
            'otp' => $resend['debugOtp'],
        ])->assertOk();
    }

    public function test_refresh_token_issues_a_new_access_token(): void
    {
        config(['app.debug' => true]);

        User::factory()->create(['phone' => '9876543210']);

        $send = $this->postJson('/api/v1/auth/otp/send', ['phone' => '9876543210'])->json('data');

        $verify = $this->postJson('/api/v1/auth/otp/verify', [
            'requestId' => $send['requestId'],
            'phone' => '9876543210',
            'otp' => $send['debugOtp'],
        ])->json('data');

        $refreshed = $this->postJson('/api/v1/auth/refresh-token', [
            'refreshToken' => $verify['refreshToken'],
        ])->assertOk()->json('data');

        $this->assertNotEmpty($refreshed['accessToken']);
    }

    public function test_logout_revokes_the_access_token(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('mobile');

        $this->withHeader('Authorization', "Bearer {$token->plainTextToken}")
            ->postJson('/api/v1/auth/logout')
            ->assertOk();

        // Sequential in-process test calls share one booted app, and Sanctum's
        // RequestGuard caches the resolved user for the request lifetime, so a second
        // call here would still read as authenticated. Assert the token row itself instead.
        $this->assertDatabaseMissing('personal_access_tokens', ['id' => $token->accessToken->id]);
    }
}
