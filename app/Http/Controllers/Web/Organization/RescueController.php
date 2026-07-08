<?php

namespace App\Http\Controllers\Web\Organization;

use App\Events\CaseClaimedEvent;
use App\Events\CaseStatusUpdatedEvent;
use App\Events\CaseTeamAssignedEvent;
use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Pet;
use App\Models\RescueAssignment;
use App\Models\RescueCaseHistory;
use App\Models\RescueReport;
use App\Notifications\RescueTeamAssignedNotification;
use App\Notifications\TeamDispatchNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RescueController extends Controller
{
    public function index()
    {
        $orgId = auth()->user()->organization_id;

        $rescues = RescueReport::with(['user', 'organization', 'assignments.teamMember', 'histories'])
            ->where(function ($q) use ($orgId) {
                $q->where('organization_id', $orgId)
                  ->orWhereNull('organization_id');
            })
            ->latest()
            ->get();

        $teamMembers = \App\Models\OrganizationTeam::where('organization_id', $orgId)
            ->get(['id', 'name', 'email']);

        return view('organization.rescue.index', compact('rescues', 'teamMembers'));
    }

    //for the magic link
    public function show($id)
    {
        // 1. Find the report with all necessary data
        $report = RescueReport::with(['user', 'organization', 'assignments.teamMember', 'histories'])
            ->findOrFail($id);

        // 2. Authorization: Allow if admin OR if Magic Link session is active
        $isAuthorized = (auth()->check() && auth()->user()->organization_id === $report->organization_id)
            || session()->has('team_member_id');

        if (!$isAuthorized) {
            abort(403, 'Unauthorized access.');
        }

        // 3. Return the view (you'll need to create this blade file)
        return view('organization.rescue.show', compact('report'));
    }

    public function acceptCase(Request $request, RescueReport $report)
    {
        $this->authorize('accept', $report);

        $orgId = auth()->user()->organization_id;

        $updated = RescueReport::where('id', $report->id)
            ->whereNull('organization_id')
            ->where('status', 'reported')
            ->update([
                'organization_id' => $orgId,
                'status' => 'assigned',
                'assigned_at' => now(),
            ]);

        if ($updated === 0) {
            return response()->json([
                'message' => 'This case has already been claimed by another organization.',
            ], 409);
        }

        $report->refresh();

        RescueCaseHistory::create([
            'rescue_report_id' => $report->id,
            'from_status' => 'reported',
            'to_status' => 'assigned',
            'changed_by' => auth()->id(),
            'notes' => 'Case accepted by organization',
        ]);

        broadcast(new CaseClaimedEvent($report));

        return response()->json([
            'message' => 'Case accepted successfully. Assign a team to proceed.',
            'data' => $report->load('organization'),
        ]);
    }

    public function assignTeam(Request $request, RescueReport $report)
    {
        $this->authorize('update', $report);

        $validated = $request->validate([
            'team_members' => 'required|array|min:1',
            'team_members.*.team_member_id' => 'required|exists:organization_teams,id',
            'team_members.*.role' => 'nullable|string|max:100',
        ]);

        $orgId = auth()->user()->organization_id;

        if ($report->organization_id !== $orgId) {
            abort(403, 'You can only assign team members to your own cases.');
        }

        DB::transaction(function () use ($report, $validated, $orgId) {
            RescueAssignment::where('rescue_report_id', $report->id)->delete();

            foreach ($validated['team_members'] as $member) {
                RescueAssignment::create([
                    'rescue_report_id' => $report->id,
                    'organization_team_id' => $member['team_member_id'],
                    'role' => $member['role'] ?? 'rescuer',
                    'assigned_at' => now(),
                ]);
            }

            if ($report->status === 'assigned') {
                $oldStatus = $report->status;
                $report->update([
                    'status' => 'in_progress',
                    'in_progress_at' => now(),
                ]);

                RescueCaseHistory::create([
                    'rescue_report_id' => $report->id,
                    'from_status' => $oldStatus,
                    'to_status' => 'in_progress',
                    'changed_by' => auth()->id(),
                    'notes' => 'Team assigned and dispatched',
                ]);
            }
        });

        $report->load('assignments.teamMember');
        $teamData = $report->assignments->map(fn ($a) => [
            'id' => $a->organization_team_id,
            'name' => $a->teamMember?->name,
            'role' => $a->role,
        ])->toArray();

        broadcast(new CaseTeamAssignedEvent($report, $teamData));

        foreach ($report->assignments as $assignment) {
            if ($assignment->teamMember) {
                $assignment->teamMember->notify(new TeamDispatchNotification($report, $assignment->role));
            }
        }

        // Notify the citizen who submitted the report
        if ($report->user && $report->user->fcm_token) {
            $report->user->notify(new RescueTeamAssignedNotification($report));
        }

        return response()->json([
            'message' => 'Team assigned successfully. Team members have been notified.',
            'data' => $report->load('assignments.teamMember', 'organization'),
        ]);
    }

    public function updateStatus(Request $request, RescueReport $report)
    {
        $this->authorize('update', $report);

        $validated = $request->validate([
            'status' => 'required|string|in:assigned,in_progress,resolved',
            'notes' => 'nullable|string|max:2000',
        ]);

        $allowed = [
            'reported' => ['assigned'],
            'assigned' => ['in_progress', 'resolved'],
            'in_progress' => ['resolved'],
        ];

        $current = $report->status;

        if (!isset($allowed[$current]) || !in_array($validated['status'], $allowed[$current])) {
            return response()->json([
                'message' => "Cannot transition from '{$current}' to '{$validated['status']}'.",
            ], 422);
        }

        $orgId = auth()->user()->organization_id;

        $pet = null;

        DB::transaction(function () use ($report, $validated, $current, $orgId, &$pet) {
            $updateData = ['status' => $validated['status']];

            if ($validated['status'] === 'resolved') {
                $updateData['resolved_at'] = now();

                $pet = Pet::create([
                    'organization_id' => $orgId,
                    'created_by' => auth()->id(),
                    'name' => 'Rescued ' . $report->title,
                    'species' => 'other',
                    'breed' => 'Unknown',
                    'age_months' => null,
                    'gender' => 'unknown',
                    'size' => 'medium',
                    'color' => null,
                    'description' => 'Rescued via ' . ($report->case_code ?? 'rescue') . ': ' . $report->description,
                    'health_status' => 'TBD — intake pending',
                    'is_vaccinated' => false,
                    'is_neutered' => false,
                    'status' => 'in_care',
                ]);

                MedicalRecord::create([
                    'pet_id' => $pet->id,
                    'vet_id' => null,
                    'type' => 'checkup',
                    'title' => 'Intake examination',
                    'description' => 'Initial intake recorded upon rescue case resolution.',
                    'notes' => null,
                    'record_date' => today(),
                ]);
            }

            $report->update($updateData);

            if ($pet) {
                $report->pet_id = $pet->id;
                $report->save();
            }

            RescueCaseHistory::create([
                'rescue_report_id' => $report->id,
                'from_status' => $current,
                'to_status' => $validated['status'],
                'changed_by' => auth()->id(),
                'notes' => $validated['notes'] ?? ($pet ? 'Resolved — pet profile created (#'.$pet->id.')' : null),
            ]);
        });

        broadcast(new CaseStatusUpdatedEvent($report, $current));

        $message = match ($validated['status']) {
            'resolved' => 'Case marked as resolved. Pet profile created and moved to In Care.',
            'in_progress' => 'Case is now in progress.',
            'assigned' => 'Case assigned successfully.',
            default => 'Status updated.',
        };

        return response()->json([
            'message' => $message,
            'data' => $report->fresh()->load('assignments.teamMember', 'histories', 'pet'),
            'pet' => $pet ? $pet->fresh() : null,
        ]);
    }

    public function linkToPet(Request $request, RescueReport $report)
    {
        $this->authorize('update', $report);

        $validated = $request->validate([
            'pet_id' => 'required|exists:pets,id',
        ]);

        $report->update([
            'pet_id' => $validated['pet_id'],
        ]);

        RescueCaseHistory::create([
            'rescue_report_id' => $report->id,
            'from_status' => $report->status,
            'to_status' => $report->status,
            'changed_by' => auth()->id(),
            'notes' => 'Rescued animal linked to pet record',
        ]);

        return response()->json([
            'message' => 'Pet linked to rescue case successfully.',
            'data' => $report->fresh()->load('pet'),
        ]);
    }
}
