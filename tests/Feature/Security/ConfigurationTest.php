<?php

namespace Tests\Feature\Security;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;

class ConfigurationTest extends TestCase
{
    use RefreshDatabase;

    public function test_mobile_tokens_expire_after_the_configured_lifetime()
    {
        $user = User::factory()->create(['is_active' => true]);
        $plain = $user->createToken('mobile-app')->plainTextToken;

        $this->withToken($plain)->getJson('/api/v1/user')->assertOk();

        PersonalAccessToken::query()->update(['created_at' => now()->subDays(31)]);
        $this->app['auth']->forgetGuards();

        $this->withToken($plain)->getJson('/api/v1/user')->assertUnauthorized();
    }

    public function test_forced_app_url_pins_generated_links_to_app_url()
    {
        config(['app.url' => 'https://api.resqnet.test', 'app.force_url' => true, 'app.dynamic_url' => false]);
        (new \App\Providers\AppServiceProvider($this->app))->boot();

        $this->assertStringStartsWith('https://api.resqnet.test/', route('organization.login'));
    }
}
