<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
        ]);

        $admin = User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@resqnet.com')],
            [
                'name' => env('ADMIN_NAME', 'Super Admin'),
                'password' => bcrypt(env('ADMIN_PASSWORD', '12345678')),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        if (!$admin->hasRole('super_admin')) {
            $admin->assignRole('super_admin');
        }

        $this->call([
            DemoUserSeeder::class,
            OrganizationSeeder::class,
            VetSeeder::class,
            PetSeeder::class,
            PetHealthSeeder::class,
            AdoptionRequestSeeder::class,
            RescueReportSeeder::class,
            DonationSeeder::class,
        ]);
    }
}
