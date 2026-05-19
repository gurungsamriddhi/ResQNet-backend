<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'super_admin']);
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'organization']);
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'public_user']);
    }
}
