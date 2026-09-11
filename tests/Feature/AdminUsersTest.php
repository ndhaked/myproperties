<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminUsersTest extends TestCase
{
    use RefreshDatabase;

    protected function actingAsSuperAdmin(): Admin
    {
        $admin = Admin::factory()->create();
        $admin->assignRole('Super Admin');

        return $admin;
    }

    public function test_admin_without_permission_cannot_view_users_page(): void
    {
        $admin = Admin::factory()->create();
        $admin->assignRole('Admin');

        $this->actingAs($admin, 'admin')
            ->get(route('admin.users.index'))
            ->assertForbidden();
    }

    public function test_super_admin_can_view_and_filter_users(): void
    {
        $admin = $this->actingAsSuperAdmin();

        User::factory()->create(['name' => 'A Buyer', 'role' => 'Buyer']);
        User::factory()->seller()->create(['name' => 'A Seller', 'role' => 'Seller']);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.users.index'))
            ->assertOk()
            ->assertSee('A Buyer')
            ->assertSee('A Seller');

        Livewire::actingAs($admin, 'admin')
            ->test('pages::admin.users.index')
            ->set('role', 'Seller')
            ->assertSee('A Seller')
            ->assertDontSee('A Buyer');
    }

    public function test_super_admin_can_edit_a_users_role_and_it_syncs_to_spatie(): void
    {
        $admin = $this->actingAsSuperAdmin();
        $user = User::factory()->create(['name' => 'Test User', 'role' => 'Buyer']);

        Livewire::actingAs($admin, 'admin')
            ->test('pages::admin.users.index')
            ->call('edit', $user->id)
            ->set('editRole', 'Seller')
            ->set('isVerified', true)
            ->call('save')
            ->assertHasNoErrors();

        $user->refresh();
        $this->assertSame('Seller', $user->role);
        $this->assertTrue($user->is_verified);
        $this->assertTrue($user->hasRole('Seller'));
        $this->assertFalse($user->hasRole('Buyer'));
    }

    public function test_super_admin_can_delete_a_user(): void
    {
        $admin = $this->actingAsSuperAdmin();
        $user = User::factory()->create();

        Livewire::actingAs($admin, 'admin')
            ->test('pages::admin.users.index')
            ->call('delete', $user->id);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
