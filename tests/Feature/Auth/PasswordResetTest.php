<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        Role::firstOrCreate(['name' => 'super_admin']);
        Role::firstOrCreate(['name' => 'organization']);
    }

    // ── Admin OTP Password Reset ──

    public function test_admin_forgot_password_screen_can_be_rendered()
    {
        $response = $this->get(route('admin.password.request'));

        $response->assertStatus(200);
    }

    public function test_admin_can_request_otp()
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('super_admin');

        $response = $this->post(route('admin.password.email'), [
            'email' => $user->email,
        ]);

        $response->assertRedirect(route('admin.password.verify'));
        $this->assertEquals(session('admin_reset_email'), $user->email);
    }

    public function test_admin_verify_otp_screen_can_be_rendered()
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('super_admin');

        $this->withSession(['admin_reset_email' => $user->email])
            ->get(route('admin.password.verify'))
            ->assertStatus(200);
    }

    public function test_admin_verify_otp_redirects_if_no_email_in_session()
    {
        $this->get(route('admin.password.verify'))
            ->assertRedirect(route('admin.password.request'));
    }

    public function test_admin_can_reset_password_with_valid_otp()
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('super_admin');

        Cache::put('otp_reset_password_' . $user->email, '123456', now()->addMinutes(10));

        $this->withSession(['admin_reset_email' => $user->email])
            ->post(route('admin.password.verify.submit'), ['otp' => '123456'])
            ->assertRedirect(route('admin.password.reset'));

        $this->assertEquals(session('admin_reset_email_verified'), $user->email);

        $response = $this->withSession([
            'admin_reset_email' => $user->email,
            'admin_reset_email_verified' => $user->email,
        ])->post(route('admin.password.store'), [
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertRedirect(route('admin.login'));
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('new-password', $user->fresh()->password));
    }

    public function test_admin_cannot_reset_password_with_invalid_otp()
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('super_admin');

        Cache::put('otp_reset_password_' . $user->email, '123456', now()->addMinutes(10));

        $this->withSession(['admin_reset_email' => $user->email])
            ->post(route('admin.password.verify.submit'), ['otp' => '000000'])
            ->assertSessionHasErrors(['otp']);
    }

    // ── Organization OTP Password Reset ──

    public function test_organization_forgot_password_screen_can_be_rendered()
    {
        $response = $this->get(route('organization.password.request'));

        $response->assertStatus(200);
    }

    public function test_organization_can_request_otp()
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('organization');

        $response = $this->post(route('organization.password.email'), [
            'email' => $user->email,
        ]);

        $response->assertRedirect(route('organization.password.verify'));
        $this->assertEquals(session('org_reset_email'), $user->email);
    }

    public function test_organization_can_reset_password_with_valid_otp()
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('organization');

        Cache::put('otp_reset_password_' . $user->email, '654321', now()->addMinutes(10));

        $this->withSession(['org_reset_email' => $user->email])
            ->post(route('organization.password.verify.submit'), ['otp' => '654321'])
            ->assertRedirect(route('organization.password.reset'));

        $this->assertEquals(session('org_reset_email_verified'), $user->email);

        $response = $this->withSession([
            'org_reset_email' => $user->email,
            'org_reset_email_verified' => $user->email,
        ])->post(route('organization.password.store'), [
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertRedirect(route('organization.login'));
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('new-password', $user->fresh()->password));
    }
}
