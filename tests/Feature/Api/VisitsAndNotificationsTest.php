<?php

namespace Tests\Feature\Api;

use App\Models\City;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VisitsAndNotificationsTest extends TestCase
{
    use RefreshDatabase;

    protected function makeProperty(?User $owner = null): Property
    {
        return Property::create([
            'title' => 'Test Property',
            'owner_id' => $owner?->id,
            'property_type_id' => PropertyType::create(['name' => 'Plot / Land'])->id,
            'city_id' => City::create(['name' => 'Bangalore'])->id,
            'price_amount' => 1000000,
        ]);
    }

    public function test_buyer_can_book_a_visit_and_seller_gets_notified(): void
    {
        $seller = User::factory()->seller()->create();
        $buyer = User::factory()->create();
        $property = $this->makeProperty($seller);

        $this->actingAs($buyer, 'sanctum')
            ->postJson("/api/v1/properties/{$property->id}/visits", [
                'date' => now()->addDays(3)->format('Y-m-d'),
                'time' => '10:30 AM',
            ])
            ->assertOk()
            ->assertJsonPath('data.status', 'Upcoming');

        $this->assertDatabaseHas('visits', ['property_id' => $property->id, 'buyer_id' => $buyer->id]);
        $this->assertDatabaseHas('app_notifications', ['user_id' => $seller->id]);
    }

    public function test_buyer_sees_own_visits_and_seller_sees_visits_on_their_listings(): void
    {
        $seller = User::factory()->seller()->create();
        $buyer = User::factory()->create();
        $property = $this->makeProperty($seller);

        \App\Models\Visit::create([
            'property_id' => $property->id,
            'buyer_id' => $buyer->id,
            'date' => now()->addDay(),
            'time' => '11:00 AM',
        ]);

        $this->actingAs($buyer, 'sanctum')->getJson('/api/v1/visits')
            ->assertOk()->assertJsonCount(1, 'data.items');

        $this->actingAs($seller, 'sanctum')->getJson('/api/v1/visits?role=seller')
            ->assertOk()->assertJsonCount(1, 'data.items');
    }

    public function test_only_the_owning_seller_can_update_a_visit(): void
    {
        $seller = User::factory()->seller()->create();
        $otherSeller = User::factory()->seller()->create();
        $buyer = User::factory()->create();
        $property = $this->makeProperty($seller);

        $visit = \App\Models\Visit::create([
            'property_id' => $property->id,
            'buyer_id' => $buyer->id,
            'date' => now()->addDay(),
            'time' => '11:00 AM',
        ]);

        $this->actingAs($otherSeller, 'sanctum')
            ->patchJson("/api/v1/visits/{$visit->id}", ['status' => 'Completed'])
            ->assertStatus(403);

        $this->actingAs($seller, 'sanctum')
            ->patchJson("/api/v1/visits/{$visit->id}", ['status' => 'Completed'])
            ->assertOk()
            ->assertJsonPath('data.status', 'Completed');

        $this->assertDatabaseHas('app_notifications', ['user_id' => $buyer->id]);
    }

    public function test_notifications_index_and_unread_count(): void
    {
        $user = User::factory()->create();

        \App\Models\AppNotification::create(['user_id' => $user->id, 'title' => 'Hello', 'is_read' => false]);
        \App\Models\AppNotification::create(['user_id' => $user->id, 'title' => 'Read one', 'is_read' => true]);

        $this->actingAs($user, 'sanctum')->getJson('/api/v1/notifications/unread-count')
            ->assertOk()->assertJsonPath('data.count', 1);

        $notification = $user->appNotifications()->where('is_read', false)->first();

        $this->actingAs($user, 'sanctum')
            ->patchJson("/api/v1/notifications/{$notification->id}/read")
            ->assertOk();

        $this->assertTrue($notification->fresh()->is_read);
    }
}
