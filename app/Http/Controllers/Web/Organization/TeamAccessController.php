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
        session([
            'team_member_id' => $teamMember->id,
            'team_access_report_id' => $report->id,
        ]);

        // Load the report with all necessary relationships
        $report->load(['user', 'organization', 'assignments.teamMember', 'histories']);

        return view('organization.rescue.show', [
            'report' => $report,
            'isTeamAccess' => true,
        ]);
    }

    public function markArrived(RescueReport $report, OrganizationTeam $teamMember)
    {
        // 1. Ensure the user is the one linked to this session, for this case
        if (session('team_member_id') != $teamMember->id || (int) session('team_access_report_id') !== $report->id) {
            abort(403);
        }

        // 2. Arrival only moves an assigned case forward; never reopen or rewind one
        if ($report->status !== 'assigned') {
            return back()->with('success', 'This case is already in progress or resolved.');
        }

        $previousStatus = $report->status;
        $report->update(['status' => 'in_progress', 'in_progress_at' => now()]);

        // 3. Log the history
        RescueCaseHistory::create([
            'rescue_report_id' => $report->id,
            'from_status' => $previousStatus,
            'to_status' => 'in_progress',
            'changed_by' => null, // No admin, it was the rescuer
            'notes' => 'Rescuer ' . $teamMember->name . ' has arrived at the location.',
        ]);

        // 4. Broadcast to the Organization Dashboard
        broadcast(new \App\Events\CaseStatusUpdatedEvent($report, $previousStatus));

        return back()->with('success', 'Status updated: You have been marked as arrived.');
    }
}
