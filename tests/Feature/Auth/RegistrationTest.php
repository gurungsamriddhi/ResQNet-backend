<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        Role::firstOrCreate(['name' => 'organization']);
    }

    public function test_registration_screen_can_be_rendered()
    {
        $response = $this->get(route('register.organization'));

        $response->assertStatus(200);
    }

    public function test_organization_can_register()
    {
        $file = UploadedFile::fake()->create('document.pdf', 500, 'application/pdf');

        $response = $this->post(route('register.organization.submit'), [
            'user_name' => 'Test User',
            'user_email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'user_phone' => '9812345678',
            'org_name' => 'Test Organization',
            'org_email' => 'org@example.com',
            'registration_number' => 'REG-001',
            'document' => $file,
        ]);

        $response->assertRedirect(route('registration.pending'));

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User',
            'is_active' => false,
        ]);

        $this->assertDatabaseHas('organizations', [
            'email' => 'org@example.com',
            'name' => 'Test Organization',
            'status' => 'pending',
        ]);

        $user = User::where('email', 'test@example.com')->first();
        $this->assertTrue($user->hasRole('organization'));
    }

    public function test_registration_validates_required_fields()
    {
        $response = $this->post(route('register.organization.submit'), []);

        $response->assertSessionHasErrors(['user_name', 'user_email', 'password', 'org_name', 'org_email', 'registration_number', 'document']);
    }

    public function test_registration_requires_unique_email()
    {
        User::factory()->create(['email' => 'existing@example.com']);

        $file = UploadedFile::fake()->create('document.pdf', 500, 'application/pdf');

        $response = $this->post(route('register.organization.submit'), [
            'user_name' => 'Test User',
            'user_email' => 'existing@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'org_name' => 'Test Organization',
            'org_email' => 'org@example.com',
            'registration_number' => 'REG-001',
            'document' => $file,
        ]);

        $response->assertSessionHasErrors(['user_email']);
    }
}
