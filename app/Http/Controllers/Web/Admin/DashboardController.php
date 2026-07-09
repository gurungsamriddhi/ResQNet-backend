<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Organization;
use App\Models\Pet;
use App\Models\RescueReport;
use App\Models\RescueCaseHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'pets_this_month' => Pet::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'active_orgs' => Organization::where('status', 'active')->count(),
            'pending_orgs' => Organization::where('status', 'pending')->count(),
            'total_donations' => Donation::where('status', 'completed')
                ->sum('amount'),
        ];

        $liveRescues = RescueReport::with('organization')
            ->latest()
            ->take(10)
            ->get();

        $pendingOrganizations = Organization::where('status', 'pending')
            ->latest()
            ->take(10)
            ->get();

        $dailyDonations = Donation::where('status', 'completed')
            ->selectRaw("strftime('%Y-%m-%d', created_at) as date, SUM(amount) as total")
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(fn($d) => [
                'date' => $d->date,
                'total' => (float) $d->total,
            ])
            ->values();

        $activities = collect();

        RescueCaseHistory::with('rescueReport.organization')
            ->latest()
            ->take(10)
            ->get()
            ->each(function ($history) use ($activities) {
                $report = $history->rescueReport;
                if (! $report) return;
                $orgName = $report->organization?->name ?? 'System';
                $caseCode = $report->case_code ?? 'Unknown';
                $activities->push((object) [
                    'description' => "Rescue Case #{$caseCode} shifted state to {$history->to_status} by {$orgName}",
                    'time' => $history->created_at->diffForHumans(),
                    'created_at' => $history->created_at,
                ]);
            });

        RescueReport::with('organization')
            ->latest()
            ->take(5)
            ->get()
            ->each(function ($report) use ($activities) {
                $orgName = $report->organization?->name ?? 'Unassigned';
                $caseCode = $report->case_code ?? 'Unknown';
                $activities->push((object) [
                    'description' => "New rescue case #{$caseCode} reported — {$report->title} via {$orgName}",
                    'time' => $report->created_at->diffForHumans(),
                    'created_at' => $report->created_at,
                ]);
            });

        $recentActivities = $activities->sortByDesc('created_at')->take(15)->values();

        return view('admin.dashboard', compact(
            'stats',
            'liveRescues',
            'pendingOrganizations',
            'dailyDonations',
            'recentActivities'
        ));
    }

    public function donationStats(Request $request)
    {
        // Handle range-based requests (pills)
        if ($request->has('range')) {
            $range = max(1, (int) $request->query('range'));

            $endDate   = now()->endOfDay();
            $startDate = now()->subDays($range - 1)->startOfDay();

            $daily = Donation::where('status', 'completed')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw("DATE(created_at) as date, SUM(amount) as total")
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->keyBy('date')
                ->map(fn($d) => (float) $d->total);

            $labels = [];
            $data   = [];
            $total  = 0;
            $count  = 0;

            $cursor = $startDate->copy();
            while ($cursor->lte($endDate)) {
                $dateStr = $cursor->format('Y-m-d');
                $amount  = $daily[$dateStr] ?? 0;
                $labels[] = $cursor->format('M j');
                $data[]   = $amount;
                $total   += $amount;
                if ($amount > 0) $count++;
                $cursor->addDay();
            }

            return response()->json([
                'labels'      => $labels,
                'data'        => $data,
                'total'       => $total,
                'count'       => $count,
                'periodLabel' => 'Last ' . $range . ' days',
            ]);
        }

        // Custom date range (date inputs)
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::parse($request->query('start_date'))->startOfDay();
            $endDate   = Carbon::parse($request->query('end_date'))->endOfDay();

            $daily = Donation::where('status', 'completed')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw("DATE(created_at) as date, SUM(amount) as total")
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->keyBy('date')
                ->map(fn($d) => (float) $d->total);

            $labels = [];
            $data   = [];
            $total  = 0;
            $count  = 0;

            $cursor = $startDate->copy();
            while ($cursor->lte($endDate)) {
                $dateStr = $cursor->format('Y-m-d');
                $amount  = $daily[$dateStr] ?? 0;
                $labels[] = $cursor->format('M j');
                $data[]   = $amount;
                $total   += $amount;
                if ($amount > 0) $count++;
                $cursor->addDay();
            }

            return response()->json([
                'labels'      => $labels,
                'data'        => $data,
                'total'       => $total,
                'count'       => $count,
                'periodLabel' => $startDate->format('M j') . ' — ' . $endDate->format('M j, Y'),
            ]);
        }

        // Month / year calendar view (dropdowns)
        $month = $request->query('month', now()->format('m'));
        $year  = $request->query('year', now()->format('Y'));

        if ($month === 'all' || $month === null) $month = now()->format('m');
        if ($year  === 'all' || $year  === null) $year  = now()->format('Y');

        $month  = str_pad($month, 2, '0', STR_PAD_LEFT);
        $year   = (int) $year;

        $now  = now();
        $isCurrent = ((int) $month === (int) $now->format('m') && $year === (int) $now->format('Y'));
        $daysIn = $isCurrent ? (int) $now->day : Carbon::createFromDate($year, (int) $month, 1)->daysInMonth;

        $daily = Donation::where('status', 'completed')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->selectRaw("DATE(created_at) as date, SUM(amount) as total")
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date')
            ->map(fn($d) => (float) $d->total);

        $labels = [];
        $data   = [];
        $total  = 0;
        $count  = 0;

        for ($day = 1; $day <= $daysIn; $day++) {
            $date   = sprintf('%04d-%02d-%02d', $year, $month, $day);
            $amount = $daily[$date] ?? 0;
            $labels[] = Carbon::parse($date)->format('M j');
            $data[]   = $amount;
            $total   += $amount;
            if ($amount > 0) $count++;
        }

        return response()->json([
            'labels'      => $labels,
            'data'        => $data,
            'total'       => $total,
            'count'       => $count,
            'periodLabel' => Carbon::createFromDate($year, $month, 1)->format('F Y') . ' total',
        ]);
    }
}
