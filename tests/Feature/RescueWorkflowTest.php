<?php

namespace Tests\Feature;

use App\Events\RescueCompletedEvent;
use App\Models\Organization;
use App\Models\RescueReport;
use App\Models\User;
use App\Models\OrganizationTeam;
use App\Notifications\NewRescueReportNotification;
use App\Notifications\RescueCompletedNotification;
use App\Notifications\TeamDispatchNotification;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RescueWorkflowTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
        Notification::fake();
    }

    public function test_complete_rescue_workflow_and_rate_limiting()
    {
        // 1. Setup Data
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'organization']);
        $reporter = User::factory()->create(['is_active' => true]);
        $orgOwner = User::factory()->create(['is_active' => true]);
        $orgOwner->assignRole('organization');
        $organization = Organization::factory()->create([
            'user_id' => $orgOwner->id,
            'status' => 'active',
            'is_verified' => true,
        ]);
        
        $orgOwner->update(['organization_id' => $organization->id]);
        
        $teamMemberUser = User::factory()->create();
        $teamMember = OrganizationTeam::create([
            'organization_id' => $organization->id,
            'user_id' => $teamMemberUser->id,
            'name' => 'Rescue Team 1',
            'email' => 'rescuer@example.com',
            'phone' => '1234567890',
            'role' => 'rescuer',
            'status' => 'active',
        ]);

        // 2. Test Rate Limiting (API)
        $this->actingAs($reporter);

        for ($i = 1; $i <= 3; $i++) {
            $this->withoutExceptionHandling();
            $response = $this->postJson('/api/v1/rescues', [
                'latitude' => 27.7172,
                'longitude' => 85.3240,
                'title' => "Injured Dog $i",
                'description' => "Found an injured dog on the street.",
                'urgency' => 'high',
                'address' => 'Kathmandu, Nepal',
            ]);
            $response->assertStatus(201);
        }

        // 4th request should hit rate limit
        $response = $this->actingAs($reporter)->postJson('/api/v1/rescues', [
            'latitude' => 27.7172,
            'longitude' => 85.3240,
            'title' => "Injured Dog 4",
            'description' => "Found another injured dog.",
            'urgency' => 'high',
            'address' => 'Kathmandu, Nepal',
        ]);
        
        $response->assertStatus(429);

        // Verify the 1st request was saved
        $this->assertDatabaseHas('rescue_reports', [
            'title' => 'Injured Dog 1',
            'user_id' => $reporter->id,
        ]);
        
        $rescueReport = RescueReport::where('title', 'Injured Dog 1')->first();
        
        // Org accepts the case first (this assigns organization_id)
        $this->actingAs($orgOwner);
        $this->postJson(route('organization.rescues.accept', $rescueReport->id))->assertStatus(200);

        // 3. Organization Assigns Team (Web)
        $response = $this->postJson(route('organization.rescues.assign-team', $rescueReport->id), [
            'team_members' => [
                [
                    'team_member_id' => $teamMember->id,
                    'role' => 'rescuer',
                ]
            ],
        ]);

        $response->assertStatus(200);
        
        $this->assertDatabaseHas('rescue_reports', [
            'id' => $rescueReport->id,
            'status' => 'in_progress',
        ]);

        // Check TeamDispatchNotification
        Notification::assertSentTo(
            $teamMember,
            TeamDispatchNotification::class,
            function ($notification) use ($rescueReport) {
                return $notification->rescue->id === $rescueReport->id;
            }
        );

        // 4. Case Marked as Completed (Web)
        $response = $this->postJson(route('organization.rescues.update', $rescueReport->id), [
            'status' => 'resolved',
            'notes' => 'Dog was rescued and brought to shelter.',
        ]);

        $response->assertStatus(200);
        
        $this->assertDatabaseHas('rescue_reports', [
            'id' => $rescueReport->id,
            'status' => 'resolved',
        ]);

        // Check RescueCompletedEvent was broadcasted
        Event::assertDispatched(RescueCompletedEvent::class, function ($event) use ($rescueReport) {
            return $event->rescue->id === $rescueReport->id;
        });

        // Check RescueCompletedNotification sent to reporter
        Notification::assertSentTo(
            $reporter,
            RescueCompletedNotification::class,
            function ($notification) use ($rescueReport) {
                return $notification->rescue->id === $rescueReport->id;
            }
        );
    }
}
