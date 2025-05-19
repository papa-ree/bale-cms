<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Permission list================================================================================================

        $general = [
            'dashboard',
        ];

        // dev permissions
        $dev = [
            "optimize clear",
            "migrate fresh",
            "view logs",
            "api management",
        ];

        // admin permissions
        $admin = [
            'role management',
            'permission management',
            'manage account',
            'manage theme',
        ];

        // user permissions
        $user = [
            'manage post',
            'manage profile',
            'manage site',
            'manage gallery',
            'manage announcement',
            'manage hero',
            'manage service',
            'manage navigation',
            'manage page',
            'manage user profile',
            'manage category',
            'view activity log',
            'view login log',
        ];

        $permissions = array_merge($general, $dev, $admin, $user);
        $admin_permissions = array_merge($general, $admin, $user);

        foreach ($permissions as $permission) {
            Permission::create([
             'name' => $permission,
             'guard_name' => 'web'
           ]);
        }

        Role::create(['name' => 'developer', 'guard_name' => 'web'])->givePermissionTo(Permission::all());

        Role::create(['name' => 'admin', 'guard_name' => 'web'])
        ->givePermissionTo($admin_permissions);
        
        Role::create(['name' => 'tenant', 'guard_name' => 'web'])
        ->givePermissionTo($admin_permissions);
    }
}
