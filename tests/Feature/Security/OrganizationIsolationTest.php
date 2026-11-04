<?php

namespace Tests\Feature\Security;

use App\Models\MedicalRecord;
use App\Models\Organization;
use App\Models\OrganizationTeam;
use App\Models\Pet;
use App\Models\RescueReport;
use App\Models\User;
use App\Models\Vet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class OrganizationIsolationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        Role::firstOrCreate(['name' => 'super_admin']);
        Role::firstOrCreate(['name' => 'organization']);

        Notification::fake();
        Mail::fake();
    }

    private function createOrgUser(): array
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('organization');
        $org = Organization::factory()->create(['user_id' => $user->id]);
        $user->update(['organization_id' => $org->id]);

        return [$user, $org];
    }

    private function claimedReport(Organization $org, string $status = 'assigned'): RescueReport
    {
        return RescueReport::create([
            'user_id' => User::factory()->create()->id,
            'organization_id' => $org->id,
            'case_code' => 'RQN-' . strtoupper(bin2hex(random_bytes(3))),
            'title' => 'Injured dog',
            'description' => 'Near the river.',
            'latitude' => 27.7,
            'longitude' => 85.3,
            'urgency' => 'high',
            'status' => $status,
        ]);
    }

    public function test_org_cannot_dispatch_another_organizations_team_member()
    {
        [$orgUser, $org] = $this->createOrgUser();
        [, $otherOrg] = $this->createOrgUser();
        $report = $this->claimedReport($org);
        $outsider = OrganizationTeam::create([
            'organization_id' => $otherOrg->id,
            'name' => 'Other rescuer',
            'email' => 'other@example.com',
            'role' => 'rescuer',
            'status' => 'active',
        ]);

        $this->actingAs($orgUser)
            ->postJson(route('organization.rescues.assign-team', $report), [
                'team_members' => [['team_member_id' => $outsider->id]],
            ])
            ->assertStatus(422);

        $this->assertDatabaseMissing('rescue_assignments', ['organization_team_id' => $outsider->id]);
    }

    public function test_org_cannot_link_a_rescue_to_another_organizations_pet()
    {
        [$orgUser, $org] = $this->createOrgUser();
        [, $otherOrg] = $this->createOrgUser();
        $report = $this->claimedReport($org);
        $foreignPet = Pet::factory()->create(['organization_id' => $otherOrg->id]);

        $this->actingAs($orgUser)
            ->postJson(route('organization.rescues.link', $report), ['pet_id' => $foreignPet->id])
            ->assertStatus(422);

        $this->assertNull($report->fresh()->pet_id);
    }

    public function test_org_user_without_an_organization_cannot_see_any_pets()
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('organization');
        Pet::factory()->create();

        $this->actingAs($user)->get(route('organization.pets.index'))->assertForbidden();
    }

    public function test_org_cannot_suspend_or_unsuspend_pets()
    {
        [$orgUser, $org] = $this->createOrgUser();
        $pet = Pet::factory()->create(['organization_id' => $org->id, 'status' => 'available']);
        $suspended = Pet::factory()->create(['organization_id' => $org->id, 'status' => 'suspended']);

        $this->actingAs($orgUser)
            ->postJson(route('organization.pets.status', $pet), ['status' => 'suspended'])
            ->assertStatus(422);

        $this->actingAs($orgUser)
            ->postJson(route('organization.pets.status', $suspended), ['status' => 'available'])
            ->assertForbidden();

        $this->assertSame('suspended', $suspended->fresh()->status);
    }

    public function test_health_records_cannot_reference_another_organizations_private_vet()
    {
        [$orgUser, $org] = $this->createOrgUser();
        [, $otherOrg] = $this->createOrgUser();
        $pet = Pet::factory()->create(['organization_id' => $org->id]);
        $record = MedicalRecord::factory()->create([
            'pet_id' => $pet->id,
            'vet_id' => Vet::factory()->create(['organization_id' => $org->id])->id,
        ]);
        $foreignVet = Vet::factory()->create(['organization_id' => $otherOrg->id, 'is_global' => false]);

        $this->actingAs($orgUser)
            ->putJson(route('organization.health.medical-records.update', $record), ['vet_id' => $foreignVet->id])
            ->assertStatus(422);

        $this->assertNotEquals($foreignVet->id, $record->fresh()->vet_id);
    }

    public function test_suspending_an_organization_deactivates_all_its_members()
    {
        $admin = User::factory()->create(['is_active' => true]);
        $admin->assignRole('super_admin');
        [$owner, $org] = $this->createOrgUser();
        $staff = User::factory()->create(['is_active' => true, 'organization_id' => $org->id]);
        $staff->assignRole('organization');

        $this->actingAs($admin)
            ->post(route('admin.organizations.suspend', $org), ['reasons' => ['Policy violation']])
            ->assertRedirect();

        $this->assertFalse($owner->fresh()->is_active);
        $this->assertFalse($staff->fresh()->is_active);
    }

    public function test_mobile_api_hides_suspended_pets()
    {
        $pet = Pet::factory()->create(['status' => 'suspended']);

        $this->getJson("/api/v1/pets/{$pet->id}")->assertNotFound();
        $this->getJson("/api/v1/pets/qr/{$pet->qr_code_id}")->assertNotFound();
    }

    public function test_magic_link_session_only_unlocks_its_own_case()
    {
        [, $org] = $this->createOrgUser();
        $member = OrganizationTeam::create([
            'organization_id' => $org->id,
            'name' => 'Rescuer',
            'email' => 'rescuer@example.com',
            'role' => 'rescuer',
            'status' => 'active',
        ]);
        $mine = $this->claimedReport($org);
        $other = $this->claimedReport($org);

        $this->get(URL::signedRoute('rescue.access', ['report' => $mine, 'teamMember' => $member]))->assertOk();

        // The session from case A must not mark arrival on case B
        $this->post(URL::signedRoute('rescue.arrived', ['report' => $other, 'teamMember' => $member]))
            ->assertForbidden();
        $this->assertSame('assigned', $other->fresh()->status);
    }

    public function test_marking_arrival_does_not_reopen_a_resolved_case()
    {
        [, $org] = $this->createOrgUser();
        $member = OrganizationTeam::create([
            'organization_id' => $org->id,
            'name' => 'Rescuer',
            'email' => 'rescuer2@example.com',
            'role' => 'rescuer',
            'status' => 'active',
        ]);
        $report = $this->claimedReport($org, 'resolved');

        $this->get(URL::signedRoute('rescue.access', ['report' => $report, 'teamMember' => $member]))->assertOk();
        $this->post(URL::signedRoute('rescue.arrived', ['report' => $report, 'teamMember' => $member]));

        $this->assertSame('resolved', $report->fresh()->status);
    }
}
