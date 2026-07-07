<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    private function createUser(): User
    {
        return User::factory()->create(['is_active' => true]);
    }

    public function test_guest_cannot_access_profile()
    {
        $response = $this->getJson('/api/v1/user/profile');

        $response->assertStatus(401);
    }

    public function test_can_show_profile_when_not_created_yet()
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)->getJson('/api/v1/user/profile');

        $response->assertOk()
            ->assertJson([
                'is_profile_complete' => false,
                'profile' => null,
            ]);
    }

    public function test_can_show_profile_when_created()
    {
        $user = $this->createUser();
        $user->profile()->create([
            'housing_type' => 'apartment',
            'employment_status' => 'employed',
            'family_members_count' => 2,
            'has_other_pets' => false,
            'experience_with_pets' => 'intermediate',
            'has_yard' => true,
            'is_allergic' => false,
        ]);

        $response = $this->actingAs($user)->getJson('/api/v1/user/profile');

        $response->assertOk()
            ->assertJson([
                'is_profile_complete' => true,
            ])
            ->assertJsonStructure([
                'is_profile_complete',
                'profile' => [
                    'housing_type',
                    'employment_status',
                    'family_members_count',
                    'experience_with_pets',
                ],
            ]);
    }

    public function test_can_store_profile()
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)->postJson('/api/v1/user/profile', [
            'housing_type' => 'house',
            'employment_status' => 'employed',
            'family_members_count' => 3,
            'has_other_pets' => true,
            'experience_with_pets' => 'experienced',
            'has_yard' => true,
            'is_allergic' => false,
            'bio' => 'I love animals!',
        ]);

        $response->assertOk()
            ->assertJson(['message' => 'Profile updated successfully!']);

        $this->assertDatabaseHas('user_profiles', [
            'user_id' => $user->id,
            'housing_type' => 'house',
        ]);
    }

    public function test_can_update_existing_profile()
    {
        $user = $this->createUser();
        $user->profile()->create([
            'housing_type' => 'apartment',
            'employment_status' => 'student',
            'family_members_count' => 1,
            'has_other_pets' => false,
            'experience_with_pets' => 'beginner',
            'has_yard' => false,
            'is_allergic' => false,
        ]);

        $response = $this->actingAs($user)->postJson('/api/v1/user/profile', [
            'housing_type' => 'condo',
            'employment_status' => 'employed',
            'family_members_count' => 2,
            'has_other_pets' => true,
            'experience_with_pets' => 'intermediate',
            'has_yard' => true,
            'is_allergic' => false,
        ]);

        $response->assertOk();

        $this->assertEquals('condo', $user->fresh()->profile->housing_type);
        $this->assertEquals(2, $user->fresh()->profile->family_members_count);
    }

    public function test_store_profile_validates_required_fields()
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)->postJson('/api/v1/user/profile', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'housing_type', 'employment_status', 'family_members_count',
                'has_other_pets', 'experience_with_pets', 'has_yard', 'is_allergic',
            ]);
    }
}
