<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\City;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertiesTest extends TestCase
{
    use RefreshDatabase;

    protected function makeProperty(array $overrides = []): Property
    {
        return Property::create(array_merge([
            'title' => 'Greenfield Paradise',
            'property_type_id' => PropertyType::firstOrCreate(['name' => 'Plot / Land'])->id,
            'city_id' => City::firstOrCreate(['name' => 'Bangalore'], ['is_popular' => true])->id,
            'category_id' => Category::firstOrCreate(['title' => 'Pre-Launch'], ['is_active' => true])->id,
            'price_amount' => 4850000,
            'status' => 'Active',
            'is_featured' => true,
        ], $overrides));
    }

    public function test_home_feed_returns_categories_cities_and_featured_properties(): void
    {
        $this->makeProperty();

        $this->getJson('/api/v1/home')
            ->assertOk()
            ->assertJsonPath('data.categories.0.title', 'Pre-Launch')
            ->assertJsonPath('data.popularCities.0.name', 'Bangalore')
            ->assertJsonCount(1, 'data.featuredProperties');
    }

    public function test_property_list_filters_by_city_and_price(): void
    {
        $this->makeProperty(['title' => 'Cheap Plot', 'price_amount' => 1000000]);
        $this->makeProperty(['title' => 'Expensive Plot', 'price_amount' => 9000000]);

        $response = $this->getJson('/api/v1/properties?maxPrice=2000000')->assertOk();

        $response->assertJsonCount(1, 'data.items');
        $this->assertSame('Cheap Plot', $response->json('data.items.0.title'));
    }

    public function test_property_detail_increments_views_and_includes_full_shape(): void
    {
        $property = $this->makeProperty();

        $response = $this->getJson("/api/v1/properties/{$property->id}")->assertOk();

        $response->assertJsonPath('data.id', 'prop_' . $property->id);
        $response->assertJsonPath('data.location.city', 'Bangalore');
        $response->assertJsonPath('data.price.amount', 4850000);

        $this->assertSame(1, $property->fresh()->views);
    }

    public function test_authenticated_buyer_can_save_and_unsave_a_property(): void
    {
        $user = User::factory()->create();
        $property = $this->makeProperty();

        $this->actingAs($user, 'sanctum')
            ->postJson("/api/v1/properties/{$property->id}/save")
            ->assertOk()
            ->assertJsonPath('data.isSaved', true);

        $this->assertDatabaseHas('saved_properties', ['user_id' => $user->id, 'property_id' => $property->id]);

        $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/v1/properties/{$property->id}/save")
            ->assertOk()
            ->assertJsonPath('data.isSaved', false);

        $this->assertDatabaseMissing('saved_properties', ['user_id' => $user->id, 'property_id' => $property->id]);
    }

    public function test_contact_owner_creates_a_lead(): void
    {
        $owner = User::factory()->seller()->create(['phone' => '9000000000']);
        $buyer = User::factory()->create();
        $property = $this->makeProperty(['owner_id' => $owner->id]);

        $response = $this->actingAs($buyer, 'sanctum')
            ->postJson("/api/v1/properties/{$property->id}/contact", ['message' => 'Interested!'])
            ->assertOk();

        $this->assertDatabaseHas('leads', ['property_id' => $property->id, 'buyer_id' => $buyer->id]);
        $this->assertSame($owner->country_code . $owner->phone, $response->json('data.ownerPhone'));
    }

    public function test_filter_options_endpoint_is_public(): void
    {
        PropertyType::create(['name' => 'Plot / Land']);

        $this->getJson('/api/v1/properties/filter-options')
            ->assertOk()
            ->assertJsonPath('data.propertyTypes.0', 'Plot / Land');
    }
}
