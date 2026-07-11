<?php

namespace App\Http\Controllers\Web\Organization;

use App\Models\RescueReport;
use App\Models\OrganizationTeam;
use App\Models\RescueCaseHistory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TeamAccessController extends Controller
{
    public function accessCase(Request $request, RescueReport $report, OrganizationTeam $teamMember)
    {
        // Validate team member belongs to the org that owns this rescue
        if ($report->organization_id !== $teamMember->organization_id) {
            abort(403, 'Unauthorized: Team member does not belong to this rescue\'s organization.');
        }

        // Store team member ID in session for subsequent actions
        session(['team_member_id' => $teamMember->id]);

        // Load the report with all necessary relationships
        $report->load(['user', 'organization', 'assignments.teamMember', 'histories']);

        return view('organization.rescue.show', [
            'report' => $report,
            'isTeamAccess' => true,
        ]);
    }

    public function markArrived(RescueReport $report, OrganizationTeam $teamMember)
    {
        // 1. Ensure the user is the one linked to this session
        if (session('team_member_id') != $teamMember->id) {
            abort(403);
        }

        // 2. Update status to 'in_progress' (or a new 'arrived' status if you have one)
        $report->update(['status' => 'in_progress']); // Or your specific status

        // 3. Log the history
        RescueCaseHistory::create([
            'rescue_report_id' => $report->id,
            'from_status' => 'assigned',
            'to_status' => 'in_progress',
            'changed_by' => null, // No admin, it was the rescuer
            'notes' => 'Rescuer ' . $teamMember->name . ' has arrived at the location.',
        ]);

        // 4. Broadcast to the Organization Dashboard
        broadcast(new \App\Events\CaseStatusUpdatedEvent($report, 'assigned'));

        return back()->with('success', 'Status updated: You have been marked as arrived.');
    }
}
