<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Order matters — each seeder depends on the ones before it:
     *   roles → users → organizations → vets → pets → health → adoptions → rescues → donations
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            DemoUserSeeder::class,
            OrganizationSeeder::class,
            VetSeeder::class,
            PetSeeder::class,
            PetHealthSeeder::class,
            AdoptionRequestSeeder::class,
            RescueReportSeeder::class,
            DonationSeeder::class,
        ]);

        $admin = User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@resqnet.com')],
            [
                'name' => env('ADMIN_NAME', 'Super Admin'),
                'password' => bcrypt(env('ADMIN_PASSWORD', 'password')),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        if (!$admin->hasRole('super_admin')) {
            $admin->assignRole('super_admin');
        }
    }
}
