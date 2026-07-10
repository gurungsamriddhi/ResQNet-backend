<?php

namespace Tests\Feature;

use App\Models\MedicalRecord;
use App\Models\Medication;
use App\Models\Organization;
use App\Models\Pet;
use App\Models\User;
use App\Models\Vaccination;
use App\Models\Vet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class HealthRecordsModuleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        
        Role::firstOrCreate(['name' => 'super_admin']);
        Role::firstOrCreate(['name' => 'organization']);
    }

    private function createOrgUser()
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('organization');
        $org = Organization::factory()->create(['user_id' => $user->id]);
        $user->update(['organization_id' => $org->id]);
        return [$user, $org];
    }

    public function test_org_can_add_medical_record_to_their_pet()
    {
        [$orgUser, $org] = $this->createOrgUser();
        $pet = Pet::factory()->create(['organization_id' => $org->id]);
        $vet = Vet::factory()->global()->create();

        $response = $this->actingAs($orgUser)->postJson(route('organization.health.medical-records.store'), [
            'pet_id' => $pet->id,
            'vet_id' => $vet->id,
            'type' => 'checkup',
            'title' => 'Annual Checkup',
            'record_date' => now()->format('Y-m-d'),
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseHas('medical_records', [
            'pet_id' => $pet->id,
            'title' => 'Annual Checkup',
        ]);
    }

    public function test_org_cannot_add_medical_record_to_other_orgs_pet()
    {
        [$orgUser, $org] = $this->createOrgUser();
        
        [$otherOrgUser, $otherOrg] = $this->createOrgUser();
        $otherPet = Pet::factory()->create(['organization_id' => $otherOrg->id]);

        $response = $this->actingAs($orgUser)->postJson(route('organization.health.medical-records.store'), [
            'pet_id' => $otherPet->id,
            'type' => 'checkup',
            'title' => 'Hacked Checkup',
            'record_date' => now()->format('Y-m-d'),
        ]);

        // Should return 403 Forbidden because of AuthorizePet middleware / logic
        $response->assertStatus(403);
    }

    public function test_org_can_add_vaccination_to_their_pet()
    {
        [$orgUser, $org] = $this->createOrgUser();
        $pet = Pet::factory()->create(['organization_id' => $org->id]);

        $response = $this->actingAs($orgUser)->postJson(route('organization.health.vaccinations.store'), [
            'pet_id' => $pet->id,
            'vaccine_name' => 'Rabies',
            'date_administered' => now()->format('Y-m-d'),
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseHas('vaccinations', [
            'pet_id' => $pet->id,
            'vaccine_name' => 'Rabies',
        ]);
    }

    public function test_org_can_add_medication_to_their_pet()
    {
        [$orgUser, $org] = $this->createOrgUser();
        $pet = Pet::factory()->create(['organization_id' => $org->id]);

        $response = $this->actingAs($orgUser)->postJson(route('organization.health.medications.store'), [
            'pet_id' => $pet->id,
            'medicine_name' => 'Amoxicillin',
            'dosage' => '50mg',
            'start_date' => now()->format('Y-m-d'),
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseHas('medications', [
            'pet_id' => $pet->id,
            'medicine_name' => 'Amoxicillin',
        ]);
    }
}
