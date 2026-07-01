<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\User;
use App\Models\Vet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class VetModuleTest extends TestCase
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
        return [$user, $org];
    }

    private function createAdminUser()
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('super_admin');
        return $user;
    }

    public function test_org_can_view_their_vets_and_global_vets()
    {
        [$orgUser, $org] = $this->createOrgUser();
        
        Vet::factory()->count(2)->create(['organization_id' => $org->id]);
        Vet::factory()->global()->count(3)->create();
        
        [$otherOrgUser, $otherOrg] = $this->createOrgUser();
        Vet::factory()->count(4)->create(['organization_id' => $otherOrg->id]);

        $response = $this->actingAs($orgUser)->get(route('organization.vets.index'));
        
        $response->assertStatus(200);
        $response->assertViewHas('vets');
        $response->assertViewHas('globalVets');
        
        $vets = $response->viewData('vets');
        $globalVets = $response->viewData('globalVets');
        
        $this->assertEquals(2, $vets->count());
        $this->assertEquals(3, $globalVets->count());
    }

    public function test_org_can_create_org_specific_vet()
    {
        [$orgUser, $org] = $this->createOrgUser();

        $response = $this->actingAs($orgUser)->post(route('organization.vets.store'), [
            'name' => 'Dr. Smith',
            'email' => 'smith@example.com',
            'status' => 'active',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('vets', [
            'name' => 'Dr. Smith',
            'organization_id' => $org->id,
            'is_global' => false,
        ]);
    }

    public function test_admin_can_create_global_vet()
    {
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->post(route('admin.vets.store'), [
            'name' => 'Dr. Global',
            'email' => 'global@example.com',
            'status' => 'active',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('vets', [
            'name' => 'Dr. Global',
            'organization_id' => null,
            'is_global' => true,
        ]);
    }
}
