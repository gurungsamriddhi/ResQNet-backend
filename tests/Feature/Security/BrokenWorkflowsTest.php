<?php

namespace Tests\Feature\Security;

use App\Events\DonationCompletedEvent;
use App\Models\Donation;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class BrokenWorkflowsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['khalti.secret_key' => 'test-secret', 'khalti.base_url' => 'https://khalti.test/api/v2/']);
    }

    private function pendingDonation(User $donor, string $pidx, string $amount = '500.00'): Donation
    {
        return Donation::create([
            'user_id' => $donor->id,
            'purchase_order_id' => 'DON-' . $pidx,
            'pidx' => $pidx,
            'donor_email' => 'donor@example.com',
            'is_anonymous' => false,
            'frequency' => 'one_time',
            'amount' => $amount,
            'payment_method' => 'khalti',
            'status' => 'pending',
        ]);
    }

    private function fakeKhaltiLookup(string $status, int $totalAmountPaisa): void
    {
        Http::fake([
            'khalti.test/*' => Http::response([
                'pidx' => 'any',
                'status' => $status,
                'total_amount' => $totalAmountPaisa,
                'transaction_id' => 'TXN123',
            ]),
        ]);
    }

    public function test_organization_can_change_password_from_profile()
    {
        Role::firstOrCreate(['name' => 'organization']);
        $user = User::factory()->create(['is_active' => true, 'password' => bcrypt('Old-Password1')]);
        $user->assignRole('organization');
        $org = Organization::factory()->create(['user_id' => $user->id]);
        $user->update(['organization_id' => $org->id]);

        $this->actingAs($user)
            ->put(route('organization.password.update'), [
                'current_password' => 'Old-Password1',
                'new_password' => 'New-Password2',
                'new_password_confirmation' => 'New-Password2',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertTrue(Hash::check('New-Password2', $user->fresh()->password));
    }

    public function test_khalti_callback_is_public_and_completes_once()
    {
        Event::fake([DonationCompletedEvent::class]);
        $donation = $this->pendingDonation(User::factory()->create(), 'pidx-ok');
        $this->fakeKhaltiLookup('Completed', 50000);

        // No API token: Khalti redirects a WebView here
        $this->getJson('/api/v1/donations/khalti/callback?pidx=pidx-ok')->assertOk();
        $this->getJson('/api/v1/donations/khalti/callback?pidx=pidx-ok')->assertOk();

        $this->assertSame('completed', $donation->fresh()->status);
        Event::assertDispatchedTimes(DonationCompletedEvent::class, 1);
    }

    public function test_khalti_amount_mismatch_is_not_marked_completed()
    {
        Event::fake([DonationCompletedEvent::class]);
        $donation = $this->pendingDonation(User::factory()->create(), 'pidx-short');
        $this->fakeKhaltiLookup('Completed', 1000); // paid Rs 10 for a Rs 500 donation

        $this->getJson('/api/v1/donations/khalti/callback?pidx=pidx-short')->assertStatus(422);

        $this->assertSame('failed', $donation->fresh()->status);
        Event::assertNotDispatched(DonationCompletedEvent::class);
    }

    public function test_user_cannot_verify_someone_elses_donation()
    {
        $this->pendingDonation(User::factory()->create(), 'pidx-theirs');
        $this->fakeKhaltiLookup('Completed', 50000);

        $this->actingAs(User::factory()->create(['is_active' => true]))
            ->postJson('/api/v1/donations/khalti/verify', ['pidx' => 'pidx-theirs'])
            ->assertNotFound();
    }

    public function test_unknown_pidx_returns_not_found_instead_of_crashing()
    {
        $this->actingAs(User::factory()->create(['is_active' => true]))
            ->postJson('/api/v1/donations/khalti/verify', ['pidx' => 'does-not-exist'])
            ->assertNotFound();
    }
}
