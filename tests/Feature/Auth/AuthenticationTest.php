<?php

namespace Tests\Feature\Auth;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        Role::firstOrCreate(['name' => 'super_admin']);
        Role::firstOrCreate(['name' => 'organization']);
    }

    // ── Admin Login ──

    public function test_admin_login_screen_can_be_rendered()
    {
        $response = $this->get(route('admin.login'));

        $response->assertStatus(200);
    }

    public function test_admin_can_authenticate()
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('super_admin');

        $response = $this->post(route('admin.login.submit'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_admin_cannot_authenticate_with_invalid_password()
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('super_admin');

        $this->post(route('admin.login.submit'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_admin_cannot_login_without_super_admin_role()
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('organization');

        $this->post(route('admin.login.submit'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
    }

    public function test_admin_cannot_login_when_inactive()
    {
        $user = User::factory()->create(['is_active' => false]);
        $user->assignRole('super_admin');

        $this->post(route('admin.login.submit'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
    }

    public function test_admin_can_logout()
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('super_admin');

        $response = $this->actingAs($user)->post(route('admin.logout'));

        $this->assertGuest();
        $response->assertRedirect(route('admin.login'));
    }

    // ── Organization Login ──

    public function test_organization_login_screen_can_be_rendered()
    {
        $response = $this->get(route('organization.login'));

        $response->assertStatus(200);
    }

    public function test_organization_can_authenticate()
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('organization');
        Organization::factory()->create(['user_id' => $user->id]);

        $response = $this->post(route('organization.login.submit'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('organization/dashboard');
    }

    public function test_organization_cannot_authenticate_with_invalid_password()
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('organization');
        Organization::factory()->create(['user_id' => $user->id]);

        $this->post(route('organization.login.submit'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_organization_cannot_login_without_organization_role()
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('super_admin');

        $this->post(route('organization.login.submit'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
    }

    public function test_organization_cannot_login_when_inactive()
    {
        $user = User::factory()->create(['is_active' => false]);
        $user->assignRole('organization');

        $this->post(route('organization.login.submit'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
    }

    public function test_organization_can_logout()
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('organization');
        Organization::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post(route('organization.logout'));

        $this->assertGuest();
        $response->assertRedirect(route('organization.login'));
    }

    public function test_general_login_redirects_to_admin_login()
    {
        $response = $this->get('/login');

        $response->assertRedirect('/admin/login');
    }
}
