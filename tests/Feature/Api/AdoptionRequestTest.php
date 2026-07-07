<?php

namespace Tests\Feature\Api;

use App\Models\Organization;
use App\Models\Pet;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdoptionRequestTest extends TestCase
{
    use RefreshDatabase;

    private function createUserWithProfile(): User
    {
        $user = User::factory()->create(['is_active' => true]);
        UserProfile::factory()->create([
            'user_id' => $user->id,
            'housing_type' => 'house',
            'employment_status' => 'employed',
            'family_members_count' => 3,
            'experience_with_pets' => 'intermediate',
            'has_other_pets' => false,
            'has_yard' => true,
            'is_allergic' => false,
        ]);
        return $user;
    }

    private function createPet(): Pet
    {
        $orgUser = User::factory()->create(['is_active' => true]);
        $org = Organization::factory()->create(['user_id' => $orgUser->id]);
        return Pet::factory()->create(['organization_id' => $org->id, 'status' => 'available']);
    }

    public function test_guest_cannot_access_adoptions()
    {
        $response = $this->getJson('/api/v1/adoptions');

        $response->assertStatus(401);
    }

    public function test_can_list_adoption_requests_when_empty()
    {
        $user = User::factory()->create(['is_active' => true]);

        $response = $this->actingAs($user)->getJson('/api/v1/adoptions');

        $response->assertOk();
    }

    public function test_can_submit_adoption_request()
    {
        $user = $this->createUserWithProfile();
        $pet = $this->createPet();

        $response = $this->actingAs($user)->postJson('/api/v1/adoptions', [
            'pet_id' => $pet->id,
            'message' => 'I would love to give this pet a home!',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Adoption request submitted successfully!',
            ]);

        $this->assertDatabaseHas('adoption_requests', [
            'user_id' => $user->id,
            'pet_id' => $pet->id,
            'status' => 'pending',
        ]);
    }

    public function test_cannot_submit_without_complete_profile()
    {
        $user = User::factory()->create(['is_active' => true]);
        $pet = $this->createPet();

        $response = $this->actingAs($user)->postJson('/api/v1/adoptions', [
            'pet_id' => $pet->id,
            'message' => 'I want to adopt!',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Please complete your adoption profile before applying.',
                'redirect' => 'user_profile_form',
            ]);
    }

    public function test_cannot_submit_for_unavailable_pet()
    {
        $user = $this->createUserWithProfile();
        $pet = $this->createPet();
        $pet->update(['status' => 'adopted']);

        $response = $this->actingAs($user)->postJson('/api/v1/adoptions', [
            'pet_id' => $pet->id,
            'message' => 'I want to adopt!',
        ]);

        $response->assertStatus(422)
            ->assertJson(['message' => 'This pet is no longer available.']);
    }

    public function test_cannot_submit_duplicate_adoption()
    {
        $user = $this->createUserWithProfile();
        $pet = $this->createPet();

        $this->actingAs($user)->postJson('/api/v1/adoptions', [
            'pet_id' => $pet->id,
            'message' => 'First request',
        ]);

        $response = $this->actingAs($user)->postJson('/api/v1/adoptions', [
            'pet_id' => $pet->id,
            'message' => 'Second request',
        ]);

        $response->assertStatus(422)
            ->assertJson(['message' => 'You already have an active application running for this specific pet.']);
    }

    public function test_can_show_single_adoption_request()
    {
        $user = $this->createUserWithProfile();
        $pet = $this->createPet();

        $adoption = $this->actingAs($user)->postJson('/api/v1/adoptions', [
            'pet_id' => $pet->id,
            'message' => 'I want to adopt!',
        ]);

        $adoptionId = $adoption->json('data.id');

        $response = $this->actingAs($user)->getJson("/api/v1/adoptions/{$adoptionId}");

        $response->assertOk()
            ->assertJsonPath('data.id', $adoptionId);
    }

    public function test_cannot_view_other_users_adoption()
    {
        $user = $this->createUserWithProfile();
        $pet = $this->createPet();

        $adoption = $this->actingAs($user)->postJson('/api/v1/adoptions', [
            'pet_id' => $pet->id,
            'message' => 'I want to adopt!',
        ]);

        $adoptionId = $adoption->json('data.id');
        $otherUser = User::factory()->create(['is_active' => true]);

        $response = $this->actingAs($otherUser)->getJson("/api/v1/adoptions/{$adoptionId}");

        $response->assertStatus(403);
    }

    public function test_enforces_max_three_active_requests()
    {
        $user = $this->createUserWithProfile();

        $pet1 = $this->createPet();
        $pet2 = $this->createPet();
        $pet3 = $this->createPet();
        $pet4 = $this->createPet();

        $this->actingAs($user)->postJson('/api/v1/adoptions', ['pet_id' => $pet1->id, 'message' => 'Home 1']);
        $this->actingAs($user)->postJson('/api/v1/adoptions', ['pet_id' => $pet2->id, 'message' => 'Home 2']);
        $this->actingAs($user)->postJson('/api/v1/adoptions', ['pet_id' => $pet3->id, 'message' => 'Home 3']);

        $response = $this->actingAs($user)->postJson('/api/v1/adoptions', ['pet_id' => $pet4->id, 'message' => 'Home 4']);

        $response->assertStatus(422)
            ->assertJson(['message' => 'Your previous requests are currently under review. Please wait a few days for their outcome before applying for more pets.']);
    }

    public function test_validates_pet_id_and_message()
    {
        $user = $this->createUserWithProfile();

        $response = $this->actingAs($user)->postJson('/api/v1/adoptions', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['pet_id', 'message']);
    }
}
