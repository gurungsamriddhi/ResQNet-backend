<?php

namespace App\Http\Controllers\Web\Organization;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\OrganizationTeam;
use App\Models\Pet;
use App\Models\RescueReport;
use App\Models\Vaccination;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    private const SHELTER_CAPACITY = 30;

    public function index()
    {
        $user = auth()->user();

        if ($user && $user->organization) {
            $organization = $user->organization;
            $orgId = $organization->id;

            $activeRescues = RescueReport::where('organization_id', $orgId)
                ->whereIn('status', ['assigned', 'in_progress'])
                ->count();

            $pendingRescues = RescueReport::whereNull('organization_id')
                ->where('status', 'reported')
                ->count();

            $petsInCare = $organization->pets()->where('status', 'available')->count();
            $activePets = $organization->pets()->whereNotIn('status', ['suspended', 'inactive'])->count();

            $stats = [
                'active_pets' => $activePets,
                'pending_adoptions' => $organization->pets()->where('status', 'available')->count(),
                'active_rescues' => $activeRescues,
                'pending_rescues' => $pendingRescues,
                'pets_in_care' => $petsInCare,
                'total_donations' => 0,
            ];

            $shelterCapacity = [
                'occupied' => $activePets,
                'capacity' => self::SHELTER_CAPACITY,
            ];

            $avgResponseMinutes = $this->computeAvgResponseVelocity($orgId);

            $chartData = $this->computeChartData($orgId);

            $medicalWing = $this->computeMedicalWing($orgId);

            $priorityRescues = RescueReport::with('user', 'organization', 'assignments.teamMember')
                ->where(function ($q) use ($orgId) {
                    $q->where('organization_id', $orgId)
                      ->orWhereNull('organization_id');
                })
                ->whereIn('status', ['reported', 'assigned', 'in_progress'])
                ->orderByRaw("CASE urgency WHEN 'critical' THEN 0 WHEN 'high' THEN 1 WHEN 'medium' THEN 2 ELSE 3 END")
                ->latest()
                ->take(3)
                ->get();

            $teamMembers = OrganizationTeam::where('organization_id', $orgId)
                ->get(['id', 'name', 'email']);
        } else {
            $stats = [
                'active_pets' => 24,
                'pending_adoptions' => 7,
                'active_rescues' => 3,
                'pending_rescues' => 2,
                'pets_in_care' => 28,
                'total_donations' => 124500,
            ];

            $shelterCapacity = ['occupied' => 18, 'capacity' => self::SHELTER_CAPACITY];
            $avgResponseMinutes = 12.5;
            $chartData = $this->computeChartData(null);
            $medicalWing = $this->computeMedicalWing(null);
            $priorityRescues = collect();
            $teamMembers = collect();
        }

        $orgId = $user && $user->organization ? $user->organization->id : null;

        return view('organization.dashboard', compact(
            'stats', 'orgId', 'shelterCapacity', 'avgResponseMinutes', 'chartData', 'medicalWing', 'priorityRescues', 'teamMembers'
        ));
    }

    private function computeAvgResponseVelocity(?int $orgId): float
    {
        $rescues = RescueReport::where('organization_id', $orgId)
            ->whereNotNull('assigned_at')
            ->whereNotNull('in_progress_at')
            ->get(['assigned_at', 'in_progress_at']);

        if ($rescues->isEmpty()) {
            return 0.0;
        }

        $totalMinutes = $rescues->sum(fn ($r) => $r->assigned_at->diffInSeconds($r->in_progress_at) / 60);

        return round($totalMinutes / $rescues->count(), 1);
    }

    public function chartStats(Request $request)
    {
        $user = auth()->user();
        $orgId = $user->organization->id ?? null;

        if ($request->has('range')) {
            $range = max(1, (int) $request->query('range'));
            $endDate = Carbon::today();
            $startDate = Carbon::today()->subDays($range - 1);
        } elseif ($request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::parse($request->query('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->query('end_date'))->endOfDay();
        } else {
            $range = 14;
            $endDate = Carbon::today();
            $startDate = Carbon::today()->subDays($range - 1);
        }

        $labels = [];
        $intakes = [];
        $adoptions = [];
        $cursor = $startDate->copy();

        while ($cursor->lte($endDate)) {
            $labels[] = $cursor->format('M d');

            $intakes[] = Pet::where('organization_id', $orgId)
                ->whereDate('created_at', $cursor)
                ->count();

            $adoptions[] = Pet::where('organization_id', $orgId)
                ->whereNotNull('adopted_at')
                ->whereDate('adopted_at', $cursor)
                ->count();

            $cursor->addDay();
        }

        return response()->json([
            'labels' => $labels,
            'intakes' => $intakes,
            'adoptions' => $adoptions,
        ]);
    }

    private function computeChartData(?int $orgId): array
    {
        $days = 14;
        $labels = [];
        $intakes = [];
        $adoptions = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateStr = $date->format('M d');
            $labels[] = $dateStr;

            $intakes[] = Pet::where('organization_id', $orgId)
                ->whereDate('created_at', $date)
                ->count();

            $adoptions[] = Pet::where('organization_id', $orgId)
                ->whereNotNull('adopted_at')
                ->whereDate('adopted_at', $date)
                ->count();
        }

        return compact('labels', 'intakes', 'adoptions');
    }

    private function computeMedicalWing(?int $orgId): array
    {
        $treatments = MedicalRecord::where('type', 'treatment')
            ->whereHas('pet', fn ($q) => $q->where('organization_id', $orgId))
            ->count();

        $vaccinations = Vaccination::whereHas('pet', fn ($q) => $q->where('organization_id', $orgId))
            ->count();

        $checkups = MedicalRecord::whereIn('type', ['checkup', 'observation'])
            ->whereHas('pet', fn ($q) => $q->where('organization_id', $orgId))
            ->count();

        $total = $treatments + $vaccinations + $checkups;

        if ($total === 0) {
            return [
                ['label' => 'Treatment & Recovery', 'percent' => 0, 'color' => 'admin-btnRed'],
                ['label' => 'Vaccination & Preventive', 'percent' => 0, 'color' => 'admin-primary'],
                ['label' => 'Checkups & Monitoring', 'percent' => 0, 'color' => 'admin-navActive'],
            ];
        }

        return [
            [
                'label' => 'Treatment & Recovery',
                'percent' => round(($treatments / $total) * 100),
                'color' => 'admin-btnRed',
            ],
            [
                'label' => 'Vaccination & Preventive',
                'percent' => round(($vaccinations / $total) * 100),
                'color' => 'admin-primary',
            ],
            [
                'label' => 'Checkups & Monitoring',
                'percent' => round(($checkups / $total) * 100),
                'color' => 'admin-navActive',
            ],
        ];
    }
}
