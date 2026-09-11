<?php

namespace Tests;

use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Every test using RefreshDatabase gets the Buyer/Seller/Admin role rows seeded
     * automatically — User::syncRoles() (see App\Models\User::booted()) needs them to
     * exist whenever a user with a `role` is created.
     *
     * This can't be done via the afterRefreshingDatabase() hook: RefreshDatabase is
     * `use`d directly in each test class, so its (no-op) version of that method takes
     * precedence over this parent class's override — traits win over inherited methods
     * in PHP. setUp() isn't defined by the trait, so it's safe to hook here instead.
     */
    protected function setUp(): void
    {
        parent::setUp();

        if (in_array(\Illuminate\Foundation\Testing\RefreshDatabase::class, class_uses_recursive(static::class))) {
            app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
            $this->seed(RolesAndPermissionsSeeder::class);
        }
    }
}
