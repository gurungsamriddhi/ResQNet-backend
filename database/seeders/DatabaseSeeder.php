<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call the roles and permissions seeder first
        $this->call(RolesAndPermissionsSeeder::class);

        // Create the Super Admin user
        $admin = User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@resqnet.com')],
            [
                'name' => env('ADMIN_NAME', 'Super Admin'), 
                'password' => bcrypt(env('ADMIN_PASSWORD', 'password')),
                'is_active' => true,
            ]
        );

        // Assign the super_admin role
        if (!$admin->hasRole('super_admin')) {
            $admin->assignRole('super_admin');
        }
    }
}
