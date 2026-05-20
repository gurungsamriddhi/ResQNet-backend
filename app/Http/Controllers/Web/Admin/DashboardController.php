<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // For now, we use dummy data. We'll wire this to the DB later.
        $stats = [
            'total_orgs' => \App\Models\Organization::count(),
            'pending_orgs' => \App\Models\Organization::where('status', 'pending')->count(),
            'total_users' => \App\Models\User::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
