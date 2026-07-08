<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PetModuleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        
        // Create required roles
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

    public function test_org_can_view_their_pets()
    {
        [$orgUser, $org] = $this->createOrgUser();
        Pet::factory()->count(3)->create(['organization_id' => $org->id]);
        
        [$otherOrgUser, $otherOrg] = $this->createOrgUser();
        Pet::factory()->count(2)->create(['organization_id' => $otherOrg->id]);

        $response = $this->actingAs($orgUser)->get(route('organization.pets.index'));
        
        $response->assertStatus(200);
        $response->assertViewHas('pets');
        $pets = $response->viewData('pets');
        $this->assertEquals(3, $pets->count());
    }

    public function test_org_can_create_a_pet()
    {
        Storage::fake('public');
        [$orgUser, $org] = $this->createOrgUser();

        $response = $this->actingAs($orgUser)->post(route('organization.pets.store'), [
            'name' => 'Buddy',
            'species' => 'dog',
            'gender' => 'male',
            'size' => 'medium',
            'status' => 'available',
            'primary_image' => UploadedFile::fake()->image('pet.jpg'),
        ]);

        $response->assertSuccessful();
        $this->assertDatabaseHas('pets', [
            'name' => 'Buddy',
            'organization_id' => $org->id,
            'species' => 'dog',
        ]);
    }

    public function test_org_can_update_pet_status()
    {
        [$orgUser, $org] = $this->createOrgUser();
        $pet = Pet::factory()->create(['organization_id' => $org->id, 'status' => 'available']);

        $response = $this->actingAs($orgUser)->postJson(route('organization.pets.status', $pet), [
            'status' => 'adopted',
        ]);

        $response->assertSuccessful();
        $this->assertEquals('adopted', $pet->fresh()->status);
    }

    public function test_admin_can_suspend_a_pet()
    {
        $admin = $this->createAdminUser();
        [$orgUser, $org] = $this->createOrgUser();
        $pet = Pet::factory()->create(['organization_id' => $org->id, 'status' => 'available']);

        $response = $this->actingAs($admin)->post(route('admin.pets.suspend', $pet), [
            'suspension_reason' => 'Violation of terms',
        ]);

        $response->assertStatus(302); // Redirect back
        $pet->refresh();
        $this->assertEquals('suspended', $pet->status);
        $this->assertEquals('Violation of terms', $pet->suspension_reason);
    }

    public function test_org_can_delete_pet()
    {
        [$orgUser, $org] = $this->createOrgUser();
        $pet = Pet::factory()->create(['organization_id' => $org->id]);

        $response = $this->actingAs($orgUser)->delete(route('organization.pets.destroy', $pet));

        $response->assertRedirect(route('organization.pets.index'));
        $response->assertSessionHas('success', "{$pet->name} deleted successfully.");
        $this->assertSoftDeleted($pet);
    }

    public function test_org_can_delete_pet_via_json()
    {
        [$orgUser, $org] = $this->createOrgUser();
        $pet = Pet::factory()->create(['organization_id' => $org->id]);

        $response = $this->actingAs($orgUser)->deleteJson(route('organization.pets.destroy', $pet));

        $response->assertSuccessful();
        $response->assertJson(['message' => "{$pet->name} deleted successfully."]);
        $this->assertSoftDeleted($pet);
    }
}
