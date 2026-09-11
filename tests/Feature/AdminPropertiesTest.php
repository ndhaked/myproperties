<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Amenity;
use App\Models\Category;
use App\Models\City;
use App\Models\Highlight;
use App\Models\Property;
use App\Models\PropertyType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;
use Tests\TestCase;

class AdminPropertiesTest extends TestCase
{
    use RefreshDatabase;

    protected function actingAsSuperAdmin(): Admin
    {
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);

        $admin = Admin::factory()->create();
        $admin->assignRole('Super Admin');

        return $admin;
    }

    public function test_admin_without_permission_cannot_view_properties_page(): void
    {
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);

        $admin = Admin::factory()->create();
        $admin->assignRole('Admin');

        $this->actingAs($admin, 'admin')
            ->get(route('admin.properties.index'))
            ->assertForbidden();
    }

    public function test_super_admin_can_view_properties_index(): void
    {
        $admin = $this->actingAsSuperAdmin();

        $this->actingAs($admin, 'admin')
            ->get(route('admin.properties.index'))
            ->assertOk()
            ->assertSee('Properties');
    }

    public function test_super_admin_can_create_a_property_with_relations_and_uploads(): void
    {
        $admin = $this->actingAsSuperAdmin();

        $category = Category::create(['title' => 'Pre-Launch', 'sort_order' => 0]);
        $propertyType = PropertyType::create(['name' => 'Plot / Land', 'sort_order' => 0]);
        $city = City::create(['name' => 'Bangalore']);
        $amenity = Amenity::create(['name' => 'Water Supply', 'icon' => 'water_drop']);
        $highlight = Highlight::create(['name' => 'Corner Plot']);

        Livewire::actingAs($admin, 'admin')
            ->test('pages::admin.properties.form')
            ->set('title', 'Greenfield Paradise')
            ->set('categoryId', $category->id)
            ->set('propertyTypeId', $propertyType->id)
            ->set('cityId', $city->id)
            ->set('priceAmount', 4850000)
            ->set('selectedAmenities', [$amenity->id])
            ->set('selectedHighlights', [$highlight->id])
            ->set('nearbyPlaces', [['name' => 'Oakridge School', 'distance_km' => 2.5, 'category' => 'school']])
            ->set('newImages', [UploadedFile::fake()->image('plot.jpg')])
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('properties', ['title' => 'Greenfield Paradise', 'price_amount' => 4850000]);

        $property = Property::where('title', 'Greenfield Paradise')->first();
        $this->assertTrue($property->amenities->contains($amenity));
        $this->assertTrue($property->highlights->contains($highlight));
        $this->assertCount(1, $property->nearbyPlaces);
        $this->assertCount(1, $property->images);
    }

    public function test_super_admin_can_update_property_status(): void
    {
        $admin = $this->actingAsSuperAdmin();

        $property = Property::create([
            'title' => 'Test Property',
            'property_type_id' => PropertyType::create(['name' => 'Plot / Land'])->id,
            'city_id' => City::create(['name' => 'Pune'])->id,
            'price_amount' => 1000000,
        ]);

        Livewire::actingAs($admin, 'admin')
            ->test('pages::admin.properties.index')
            ->call('updateStatus', $property->id, 'Sold')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('properties', ['id' => $property->id, 'status' => 'Sold']);
    }

    public function test_super_admin_can_toggle_featured(): void
    {
        $admin = $this->actingAsSuperAdmin();

        $property = Property::create([
            'title' => 'Test Property',
            'property_type_id' => PropertyType::create(['name' => 'Plot / Land'])->id,
            'city_id' => City::create(['name' => 'Pune'])->id,
            'price_amount' => 1000000,
        ]);

        Livewire::actingAs($admin, 'admin')
            ->test('pages::admin.properties.index')
            ->call('toggleFeatured', $property->id);

        $this->assertDatabaseHas('properties', ['id' => $property->id, 'is_featured' => true]);
    }

    public function test_super_admin_can_delete_a_property(): void
    {
        $admin = $this->actingAsSuperAdmin();

        $property = Property::create([
            'title' => 'Test Property',
            'property_type_id' => PropertyType::create(['name' => 'Plot / Land'])->id,
            'city_id' => City::create(['name' => 'Pune'])->id,
            'price_amount' => 1000000,
        ]);

        Livewire::actingAs($admin, 'admin')
            ->test('pages::admin.properties.index')
            ->call('delete', $property->id);

        $this->assertDatabaseMissing('properties', ['id' => $property->id]);
    }

    public function test_super_admin_can_manage_categories(): void
    {
        $admin = $this->actingAsSuperAdmin();

        Livewire::actingAs($admin, 'admin')
            ->test('pages::admin.categories.index')
            ->call('create')
            ->set('title', 'Ready to Move')
            ->set('subtitle', 'Move in today')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('categories', ['title' => 'Ready to Move']);
    }
}
