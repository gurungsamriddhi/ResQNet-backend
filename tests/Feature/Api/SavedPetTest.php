<?php

namespace Tests\Feature\Api;

use App\Models\Organization;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SavedPetTest extends TestCase
{
    use RefreshDatabase;

    private function createUserWithPet(): array
    {
        $user = User::factory()->create(['is_active' => true]);
        $orgUser = User::factory()->create(['is_active' => true]);
        $org = Organization::factory()->create(['user_id' => $orgUser->id]);
        $pet = Pet::factory()->create(['organization_id' => $org->id, 'status' => 'available']);

        return [$user, $pet];
    }

    public function test_guest_cannot_access_saved_pets()
    {
        $response = $this->getJson('/api/v1/saved-pets');

        $response->assertStatus(401);
    }

    public function test_can_list_saved_pets_when_empty()
    {
        $user = User::factory()->create(['is_active' => true]);

        $response = $this->actingAs($user)->getJson('/api/v1/saved-pets');

        $response->assertOk();
    }

    public function test_can_save_a_pet()
    {
        [$user, $pet] = $this->createUserWithPet();

        $response = $this->actingAs($user)->postJson("/api/v1/saved-pets/{$pet->id}");

        $response->assertOk()
            ->assertJson([
                'saved' => true,
                'message' => 'Pet saved successfully.',
            ]);

        $this->assertDatabaseHas('saved_pets', [
            'user_id' => $user->id,
            'pet_id' => $pet->id,
        ]);
    }

    public function test_can_unsave_a_pet()
    {
        [$user, $pet] = $this->createUserWithPet();
        $user->savedPets()->attach($pet->id);

        $response = $this->actingAs($user)->postJson("/api/v1/saved-pets/{$pet->id}");

        $response->assertOk()
            ->assertJson([
                'saved' => false,
                'message' => 'Pet removed from saved list.',
            ]);

        $this->assertDatabaseMissing('saved_pets', [
            'user_id' => $user->id,
            'pet_id' => $pet->id,
        ]);
    }

    public function test_can_list_saved_pets()
    {
        [$user, $pet] = $this->createUserWithPet();
        $user->savedPets()->attach($pet->id);

        $response = $this->actingAs($user)->getJson('/api/v1/saved-pets');

        $response->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_toggle_returns_404_for_nonexistent_pet()
    {
        $user = User::factory()->create(['is_active' => true]);

        $response = $this->actingAs($user)->postJson('/api/v1/saved-pets/99999');

        $response->assertStatus(404);
    }
}
