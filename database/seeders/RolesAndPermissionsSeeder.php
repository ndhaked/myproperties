<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'manage admins',
            'manage roles',
            'manage properties',
            'manage users',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'admin']);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'admin']);
        $superAdmin->syncPermissions($permissions);

        Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'admin']);

        // Buyer/Seller are the mobile app's user types. App\Models\User::booted() keeps
        // these spatie role assignments in sync with the plain `users.role` column
        // automatically, but the API middleware (EnsureUserRole) still checks that
        // column directly since it's cheaper than a role lookup.
        Role::firstOrCreate(['name' => 'Buyer', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Seller', 'guard_name' => 'web']);
    }
}
