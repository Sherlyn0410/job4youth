<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $employer = Auth::guard('employer')->user();
        
        // Get employer statistics (placeholder data for now)
        $stats = [
            'total_jobs' => 0, // $employer->jobs()->count(),
            'active_jobs' => 0, // $employer->jobs()->where('status', 'active')->count(),
            'total_applications' => 0,
            'pending_applications' => 0,
        ];

        // Get recent jobs (empty for now)
        $recentJobs = collect(); // $employer->jobs()->latest()->take(5)->get();

        return view('employer.dashboard', compact('employer', 'stats', 'recentJobs'));
    }
}