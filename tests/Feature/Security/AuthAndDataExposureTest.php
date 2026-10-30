<?php

namespace Tests\Feature\Security;

use App\Models\RescueReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class AuthAndDataExposureTest extends TestCase
{
    use RefreshDatabase;

    private function createReport(User $reporter, string $title): RescueReport
    {
        return RescueReport::create([
            'user_id' => $reporter->id,
            'case_code' => 'RQN-' . strtoupper(bin2hex(random_bytes(3))),
            'title' => $title,
            'description' => 'Injured dog near the road.',
            'latitude' => 27.7172,
            'longitude' => 85.3240,
            'address' => 'Kathmandu',
            'urgency' => 'high',
            'status' => 'reported',
        ]);
    }

    public function test_rescue_list_only_returns_the_callers_own_reports()
    {
        $alice = User::factory()->create(['is_active' => true]);
        $bob = User::factory()->create(['is_active' => true, 'fcm_token' => 'bob-device-token']);

        $this->createReport($alice, 'Alice report');
        $this->createReport($bob, 'Bob report');

        $response = $this->actingAs($alice)->getJson('/api/v1/rescues');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Alice report')
            ->assertJsonPath('meta.total', 1);

        $this->assertStringNotContainsString('Bob report', $response->getContent());
        $this->assertStringNotContainsString($bob->email, $response->getContent());
    }

    public function test_rescue_detail_does_not_expose_push_tokens()
    {
        $reporter = User::factory()->create(['is_active' => true, 'fcm_token' => 'secret-device-token']);
        $report = $this->createReport($reporter, 'My report');

        $response = $this->actingAs($reporter)->getJson("/api/v1/rescues/{$report->id}");

        $response->assertOk();
        $this->assertStringNotContainsString('secret-device-token', $response->getContent());
    }

    public function test_user_cannot_view_another_users_rescue_report()
    {
        $owner = User::factory()->create(['is_active' => true]);
        $other = User::factory()->create(['is_active' => true]);
        $report = $this->createReport($owner, 'Private report');

        $this->actingAs($other)->getJson("/api/v1/rescues/{$report->id}")->assertForbidden();
    }

    public function test_otp_is_invalidated_after_too_many_wrong_guesses()
    {
        $user = User::factory()->create(['email_verified_at' => null]);
        Cache::put('otp_email_' . $user->email, 123456, now()->addMinutes(10));

        for ($i = 0; $i < User::MAX_OTP_ATTEMPTS; $i++) {
            $this->postJson('/api/v1/auth/verify-otp', ['email' => $user->email, 'otp' => '000000'])
                ->assertStatus(400);
        }

        // The right code no longer works once the attempt budget is spent
        $this->postJson('/api/v1/auth/verify-otp', ['email' => $user->email, 'otp' => '123456'])
            ->assertStatus(400);

        $this->assertNull($user->fresh()->email_verified_at);
    }

    public function test_correct_otp_still_verifies_after_a_few_wrong_guesses()
    {
        $user = User::factory()->create(['email_verified_at' => null]);
        Cache::put('otp_email_' . $user->email, 123456, now()->addMinutes(10));

        $this->postJson('/api/v1/auth/verify-otp', ['email' => $user->email, 'otp' => '111111'])->assertStatus(400);
        $this->postJson('/api/v1/auth/verify-otp', ['email' => $user->email, 'otp' => '123456'])->assertOk();

        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    public function test_api_auth_routes_are_rate_limited_per_ip()
    {
        // Different emails each time, so only the per-IP route limit applies
        for ($i = 0; $i < 10; $i++) {
            $this->postJson('/api/v1/auth/login', ['email' => "nobody{$i}@example.com", 'password' => 'wrong-password'])
                ->assertStatus(422);
        }

        $this->postJson('/api/v1/auth/login', ['email' => 'nobody10@example.com', 'password' => 'wrong-password'])
            ->assertStatus(429);
    }

    public function test_api_login_locks_an_account_after_five_failures()
    {
        $user = User::factory()->create(['is_active' => true, 'password' => bcrypt('Correct-Pass1!')]);

        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/v1/auth/login', ['email' => $user->email, 'password' => 'wrong-password'])
                ->assertStatus(422);
        }

        // Even the right password is refused while locked
        $this->postJson('/api/v1/auth/login', ['email' => $user->email, 'password' => 'Correct-Pass1!'])
            ->assertStatus(429);
    }

    public function test_dashboard_login_locks_an_account_after_five_failures()
    {
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'organization']);
        $user = User::factory()->create(['is_active' => true, 'password' => bcrypt('Correct-Pass1!')]);
        $user->assignRole('organization');

        for ($i = 0; $i < 5; $i++) {
            $this->post(route('organization.login.submit'), ['email' => $user->email, 'password' => 'wrong-password'])
                ->assertSessionHasErrors('email');
        }

        $this->post(route('organization.login.submit'), ['email' => $user->email, 'password' => 'Correct-Pass1!'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_seeder_refuses_default_admin_password_outside_local()
    {
        $this->app['env'] = 'production';
        putenv('ADMIN_PASSWORD');
        unset($_ENV['ADMIN_PASSWORD'], $_SERVER['ADMIN_PASSWORD']);

        try {
            $this->expectException(\RuntimeException::class);
            // Run the seeder directly; db:seed would prompt for confirmation in production
            (new \Database\Seeders\DatabaseSeeder())->setContainer($this->app)->run();
        } finally {
            $this->app['env'] = 'testing';
        }
    }

    public function test_admin_login_is_rate_limited()
    {
        for ($i = 0; $i < 10; $i++) {
            $this->post(route('admin.login.submit'), ['email' => 'nobody@example.com', 'password' => 'wrong-password']);
        }

        $this->post(route('admin.login.submit'), ['email' => 'nobody@example.com', 'password' => 'wrong-password'])
            ->assertStatus(429);
    }
}
