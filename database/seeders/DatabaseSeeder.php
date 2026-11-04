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

        $isDevelopment = app()->environment(['local', 'testing']);
        $adminPassword = env('ADMIN_PASSWORD');

        // Outside local/testing, never create the admin with a guessable default password
        if (!$adminPassword) {
            if (!$isDevelopment) {
                throw new \RuntimeException('Set ADMIN_PASSWORD before seeding a non-local environment.');
            }
            $adminPassword = '12345678';
        }

        $admin = User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@resqnet.com')],
            [
                'name' => env('ADMIN_NAME', 'Super Admin'),
                'password' => bcrypt($adminPassword),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        if (!$admin->hasRole('super_admin')) {
            $admin->assignRole('super_admin');
        }

        // Demo accounts share a known password, so they only exist in local/testing
        if (!$isDevelopment) {
            return;
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
