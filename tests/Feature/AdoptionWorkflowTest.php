<?php

namespace Tests\Feature;

use App\Events\NewAdoptionRequestEvent;
use App\Events\AdoptionStatusUpdatedEvent;
use App\Models\AdoptionRequest;
use App\Models\Organization;
use App\Models\Pet;
use App\Models\User;
use App\Models\UserProfile;
use App\Notifications\NewAdoptionRequestNotification;
use App\Notifications\AdoptionStatusUpdatedNotification;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AdoptionWorkflowTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
        Notification::fake();
    }

    public function test_complete_adoption_workflow()
    {
        // 1. Setup Data
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'organization']);
        $orgOwner = User::factory()->create(['is_active' => true]);
        $orgOwner->assignRole('organization');
        $organization = Organization::factory()->create([
            'user_id' => $orgOwner->id,
            'status' => 'active',
            'is_verified' => true,
        ]);
        
        $pet = Pet::factory()->create([
            'organization_id' => $organization->id,
            'status' => 'available',
            'qr_code_id' => \Illuminate\Support\Str::random(10),
        ]);
        
        $applicant = User::factory()->create(['is_active' => true]);
        
        // Ensure user has a profile
        UserProfile::factory()->create([
            'user_id' => $applicant->id,
            'has_other_pets' => false,
            'experience_with_pets' => 'beginner',
            'housing_type' => 'apartment',
            'has_yard' => false,
        ]);

        // 2. Submit Adoption Request (API)
        $response = $this->actingAs($applicant)->postJson('/api/v1/adoptions', [
            'pet_id' => $pet->id,
            'message' => 'I would love to adopt this pet.',
            'adoption_reason' => 'I love animals.',
            'previous_experience' => 'None',
            'home_environment' => 'House',
            'has_other_pets' => false,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('adoption_requests', [
            'pet_id' => $pet->id,
            'user_id' => $applicant->id,
            'status' => 'pending',
        ]);

        $adoptionRequest = AdoptionRequest::where('pet_id', $pet->id)->where('user_id', $applicant->id)->first();

        // Check if Event was dispatched
        Event::assertDispatched(NewAdoptionRequestEvent::class, function ($event) use ($adoptionRequest) {
            return $event->adoption->id === $adoptionRequest->id;
        });

        // Check if Notification was sent to the organization
        Notification::assertSentTo(
            $organization,
            NewAdoptionRequestNotification::class,
            function ($notification) use ($adoptionRequest) {
                return $notification->adoption->id === $adoptionRequest->id;
            }
        );

        // 3. Organization Approves the Request (Web)
        $this->actingAs($orgOwner);
        
        // Simulate org active state
        $orgOwner->update(['organization_id' => $organization->id]);

        $response = $this->post(route('organization.adoptions.status', $adoptionRequest->id), [
            'status' => 'approved',
            'admin_notes' => 'Approved!',
        ]);

        $response->assertStatus(200);

        // Check DB Status
        $this->assertDatabaseHas('adoption_requests', [
            'id' => $adoptionRequest->id,
            'status' => 'approved',
        ]);

        $this->assertDatabaseHas('pets', [
            'id' => $pet->id,
            'status' => 'adopted',
        ]);

        // Check if Event was dispatched
        Event::assertDispatched(AdoptionStatusUpdatedEvent::class, function ($event) use ($adoptionRequest) {
            return $event->adoption->id === $adoptionRequest->id;
        });

        // Check if Notification was sent to the applicant
        Notification::assertSentTo(
            $applicant,
            AdoptionStatusUpdatedNotification::class,
            function ($notification) use ($adoptionRequest) {
                return $notification->adoption->id === $adoptionRequest->id;
            }
        );
    }
}
