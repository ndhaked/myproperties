<?php

namespace Tests\Feature\Api;

use App\Models\Property;
use App\Models\PropertyType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SellerListingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_buyer_cannot_access_seller_endpoints(): void
    {
        $buyer = User::factory()->create();

        $this->actingAs($buyer, 'sanctum')
            ->getJson('/api/v1/seller/dashboard')
            ->assertStatus(403);
    }

    public function test_seller_can_create_a_listing_with_highlights_and_amenities_by_name(): void
    {
        $seller = User::factory()->seller()->create();

        $response = $this->actingAs($seller, 'sanctum')
            ->postJson('/api/v1/seller/listings', [
                'propertyType' => 'Plot / Land',
                'title' => 'Greenfield Paradise',
                'city' => 'Bangalore',
                'price' => 4850000,
                'highlights' => ['Corner Plot', 'Clear Title'],
                'amenities' => ['Water Supply'],
                'nearbyPlaces' => [['name' => 'DPS School', 'distanceKm' => 1.5]],
            ])
            ->assertCreated();

        $response->assertJsonPath('data.title', 'Greenfield Paradise');
        $response->assertJsonCount(2, 'data.highlights');
        $response->assertJsonCount(1, 'data.amenities');

        $this->assertDatabaseHas('properties', ['title' => 'Greenfield Paradise', 'owner_id' => $seller->id]);
        $this->assertDatabaseHas('highlights', ['name' => 'Corner Plot']);
    }

    public function test_seller_can_attach_previously_uploaded_images_when_creating_a_listing(): void
    {
        $seller = User::factory()->seller()->create();

        $upload = $this->actingAs($seller, 'sanctum')
            ->postJson('/api/v1/uploads/images', [
                'images' => [\Illuminate\Http\UploadedFile::fake()->image('plot.jpg')],
            ])->assertOk()->json('data.urls');

        $response = $this->actingAs($seller, 'sanctum')
            ->postJson('/api/v1/seller/listings', [
                'propertyType' => 'Plot / Land',
                'title' => 'With Image',
                'city' => 'Pune',
                'price' => 2000000,
                'imageUrls' => $upload,
            ])->assertCreated();

        $response->assertJsonCount(1, 'data.images');
    }

    public function test_seller_cannot_update_another_sellers_listing(): void
    {
        $ownerA = User::factory()->seller()->create();
        $ownerB = User::factory()->seller()->create();

        $property = Property::create([
            'title' => 'A Listing',
            'owner_id' => $ownerA->id,
            'property_type_id' => PropertyType::create(['name' => 'Plot / Land'])->id,
            'city_id' => \App\Models\City::create(['name' => 'Bangalore'])->id,
            'price_amount' => 1000000,
        ]);

        $this->actingAs($ownerB, 'sanctum')
            ->patchJson("/api/v1/seller/listings/{$property->id}", ['propertyType' => 'Plot / Land', 'title' => 'Hijacked', 'city' => 'Pune', 'price' => 1])
            ->assertStatus(403);
    }

    public function test_seller_can_delete_own_listing(): void
    {
        $seller = User::factory()->seller()->create();

        $property = Property::create([
            'title' => 'To Delete',
            'owner_id' => $seller->id,
            'property_type_id' => PropertyType::create(['name' => 'Plot / Land'])->id,
            'city_id' => \App\Models\City::create(['name' => 'Bangalore'])->id,
            'price_amount' => 1000000,
        ]);

        $this->actingAs($seller, 'sanctum')
            ->deleteJson("/api/v1/seller/listings/{$property->id}")
            ->assertOk();

        $this->assertDatabaseMissing('properties', ['id' => $property->id]);
    }

    public function test_seller_dashboard_reports_listing_stats(): void
    {
        $seller = User::factory()->seller()->create();

        Property::create([
            'title' => 'Listing 1',
            'owner_id' => $seller->id,
            'property_type_id' => PropertyType::create(['name' => 'Plot / Land'])->id,
            'city_id' => \App\Models\City::create(['name' => 'Bangalore'])->id,
            'price_amount' => 1000000,
        ]);

        $this->actingAs($seller, 'sanctum')
            ->getJson('/api/v1/seller/dashboard')
            ->assertOk()
            ->assertJsonPath('data.stats.myListings.value', 1);
    }
}
