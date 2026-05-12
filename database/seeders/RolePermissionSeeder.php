<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Clear cached permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // -------------------------------------------------------
        // Permissions
        // -------------------------------------------------------
        $permissions = [
            'dashboard.view',

            // Adai Admin Permissions
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // -------------------------------------------------------
        // Roles
        // -------------------------------------------------------
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $agentRole = Role::firstOrCreate(['name' => 'agent']);
        $leaderRole = Role::firstOrCreate(['name' => 'leader']);

        // Assign permissions

        // adai_admin gets everything
        $adminRole->givePermissionTo(Permission::all());

        // gallery_admin gets only gallery permissions
        $agentRole->givePermissionTo([
            'dashboard.view',
        ]);
        $leaderRole->givePermissionTo([
            'dashboard.view',
        ]);

        // -------------------------------------------------------
        // Create Users
        // -------------------------------------------------------

        // Adai Admin User
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@yopmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('12345678'), // change as needed
            ]
        );

        $adminUser->assignRole($adminRole);

        // agentUser User
        $agentUser = User::firstOrCreate(
            ['email' => 'agent@yopmail.com'],
            [
                'name' => 'Agent',
                'password' => Hash::make('12345678'), // change as needed
            ]
        );

        $agentUser->assignRole($agentRole);

         // leaderUser User
        $leaderUser = User::firstOrCreate(
            ['email' => 'leader@yopmail.com'],
            [
                'name' => 'Leader',
                'password' => Hash::make('12345678'), // change as needed
            ]
        );

        $leaderUser->assignRole($leaderRole);
    }
}
