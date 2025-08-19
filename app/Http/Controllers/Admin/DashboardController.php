<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Job;
use App\Models\Company;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get basic statistics
        $stats = [
            'total_users' => User::count(),
            'total_jobs' => Job::count(),
            'total_companies' => Company::count(),
            'total_applications' => Application::count(),
        ];

        // Get recent applications
        $recentApplications = Application::with(['user', 'job.company'])
            ->latest()
            ->take(10)
            ->get();

        // Get application status distribution (ensure all keys exist)
        $rawStatuses = Application::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        $applicationStatuses = array_merge([
            'pending' => 0,
            'success' => 0,
            'failed' => 0,
        ], $rawStatuses);

        // Get recent jobs
        $recentJobs = Job::with('company')
            ->latest()
            ->take(5)
            ->get();

        // Get recent users
        $recentUsers = User::latest()
            ->take(5)
            ->get();

        // Get monthly application trends (last 6 months, zero-filled)
        $rawMonthly = Application::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('count(*) as count')
            )
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month');

        $monthKeys = collect(range(0, 5))
            ->map(fn($i) => now()->subMonths(5 - $i)->format('Y-m'));

        $monthlyApplications = $monthKeys->map(function ($month) use ($rawMonthly) {
            return (object) [
                'month' => $month,
                'count' => (int) ($rawMonthly[$month] ?? 0),
            ];
        });

        $maxMonthlyCount = max(1, $monthlyApplications->max(fn($m) => $m->count));

        return view('admin.dashboard', compact(
            'stats',
            'recentApplications',
            'applicationStatuses',
            'recentJobs',
            'recentUsers',
            'monthlyApplications',
            'maxMonthlyCount'
        ));
    }

    public function users(Request $request)
    {
        $query = User::with(['skills', 'experience', 'education', 'resumes']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function jobs(Request $request)
    {
        $query = Job::with('company');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Filter by company
        if ($request->filled('company')) {
            $query->where('company_id', $request->company);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $jobs = $query->latest()->paginate(20);
        $companies = Company::orderBy('name')->get();

        return view('admin.jobs.index', compact('jobs', 'companies'));
    }

    public function companies(Request $request)
    {
        $query = Company::withCount(['jobs', 'applications']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $companies = $query->latest()->paginate(20);
        
        // Get unique locations for filter
        $locations = Company::whereNotNull('location')
            ->distinct()
            ->pluck('location')
            ->filter()
            ->sort()
            ->values();

        return view('admin.companies.index', compact('companies', 'locations'));
    }

    public function applications(Request $request)
    {
        $query = Application::with(['user', 'job.company']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('candidate_name', 'like', "%{$search}%")
                  ->orWhere('candidate_email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by company
        if ($request->filled('company')) {
            $query->whereHas('job', function ($q) use ($request) {
                $q->where('company_id', $request->company);
            });
        }

        $applications = $query->latest()->paginate(20);
        $companies = Company::orderBy('name')->get();
        $jobs = Job::with('company')->orderBy('title')->get();

        return view('admin.applications.index', compact('applications', 'companies', 'jobs'));
    }
}
