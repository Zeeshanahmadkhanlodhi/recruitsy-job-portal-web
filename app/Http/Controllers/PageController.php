<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Company;
use App\Models\SavedJob;

class PageController extends Controller
{
    public function home()
    {
        return view('pages.home');
    }

    public function about()
    {
        return view('pages.about');
    }

    public function jobs(Request $request)
    {
        // Start with base query
        $query = Job::with('company');
        
        // Apply keyword search
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%")
                  ->orWhere('requirements', 'like', "%{$keyword}%")
                  ->orWhereHas('company', function($companyQuery) use ($keyword) {
                      $companyQuery->where('name', 'like', "%{$keyword}%");
                  });
            });
        }
        
        // Apply location filter
        if ($request->filled('location')) {
            $location = $request->location;
            $query->where('location', 'like', "%{$location}%");
        }
        
        // Apply category filter (using requirements field)
        if ($request->filled('category') && $request->category !== '') {
            $category = $request->category;
            $query->where('requirements', 'like', "%{$category}%");
        }
        
        // Apply job type filter
        if ($request->filled('job_type') && is_array($request->job_type)) {
            $query->whereIn('employment_type', $request->job_type);
        }
        
        // Apply experience level filter
        if ($request->filled('experience_level') && is_array($request->experience_level)) {
            $query->whereIn('experience_level', $request->experience_level);
        }
        
        // Apply salary range filter
        if ($request->filled('salary_range') && is_array($request->salary_range)) {
            $query->where(function($q) use ($request) {
                foreach ($request->salary_range as $range) {
                    switch ($range) {
                        case '0-50k':
                            $q->orWhere(function($subQ) {
                                $subQ->where('salary_max', '<=', 50000);
                            });
                            break;
                        case '50k-100k':
                            $q->orWhere(function($subQ) {
                                $subQ->where('salary_min', '>=', 50000)
                                     ->where('salary_max', '<=', 100000);
                            });
                            break;
                        case '100k-150k':
                            $q->orWhere(function($subQ) {
                                $subQ->where('salary_min', '>=', 100000)
                                     ->where('salary_max', '<=', 150000);
                            });
                            break;
                        case '150k+':
                            $q->orWhere(function($subQ) {
                                $subQ->where('salary_min', '>=', 150000);
                            });
                            break;
                    }
                }
            });
        }
        
        // Apply remote filter
        if ($request->filled('remote') && is_array($request->remote)) {
            if (in_array('remote', $request->remote)) {
                $query->where('is_remote', true);
            }
        }
        
        // Apply sorting
        $sortBy = $request->get('sort_by', 'posted_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        switch ($sortBy) {
            case 'salary_high_low':
                $query->orderBy('salary_max', 'desc');
                break;
            case 'salary_low_high':
                $query->orderBy('salary_min', 'asc');
                break;
            case 'experience_level':
                $query->orderBy('experience_level', 'asc');
                break;
            case 'posted_at':
            default:
                $query->orderBy('posted_at', 'desc');
                break;
        }
        
        // Get paginated results
        $jobs = $query->paginate(20)->withQueryString();
        
        // Get filter options for the sidebar - only show options that actually exist in the database
        $filterOptions = [
            'jobTypes' => Job::whereNotNull('employment_type')
                ->where('employment_type', '!=', '')
                ->distinct()
                ->pluck('employment_type')
                ->filter()
                ->values(),
            'experienceLevels' => Job::whereNotNull('experience_level')
                ->where('experience_level', '!=', '')
                ->distinct()
                ->pluck('experience_level')
                ->filter()
                ->values(),
            'categories' => [
                'technology' => 'Technology',
                'marketing' => 'Marketing',
                'sales' => 'Sales',
                'design' => 'Design',
                'finance' => 'Finance',
                'healthcare' => 'Healthcare',
                'engineering' => 'Engineering',
                'operations' => 'Operations'
            ]
        ];
        
        // Debug: Log the query and results
        \Log::info('Jobs query:', [
            'sql' => $query->toSql(),
            'bindings' => $query->getBindings(),
            'filters_applied' => $request->all(),
            'total_results' => $jobs->total()
        ]);
        
        return view('pages.jobs', compact('jobs', 'filterOptions'));
    }

    public function jobDetail($id)
    {
        $job = Job::with('company')->findOrFail($id);
        
        // Get related jobs from the same company
        $relatedJobs = Job::with('company')
            ->where('company_id', $job->company_id)
            ->where('id', '!=', $job->id)
            ->orderBy('posted_at', 'desc')
            ->limit(3)
            ->get();
            
        return view('pages.job-detail', compact('job', 'relatedJobs'));
    }

    public function signin()
    {
        return view('pages.signin');
    }

    public function signup()
    {
        return view('pages.signup');
    }

    public function dashboard()
    {
        $user = auth()->user();
        
        // Get real data for dashboard
        $applications = $user->applications()
            ->with(['job.company'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        $savedJobs = $user->savedJobs()
            ->orderBy('saved_at', 'desc')
            ->limit(5)
            ->get();
            
        // Get recommended jobs (recent jobs from the same field/company as user's applications)
        $recommendedJobs = Job::with('company')
            ->whereHas('company')
            ->orderBy('posted_at', 'desc')
            ->limit(4)
            ->get();
            
        // Check which jobs are already saved by the user
        $savedJobIds = $user->savedJobs()
            ->where('source', 'local')
            ->pluck('external_id')
            ->toArray();
            
        // Calculate profile completion
        $profileCompletion = $user->profile_completion;
        
        return view('pages.dashboard', compact(
            'applications', 
            'savedJobs', 
            'recommendedJobs', 
            'profileCompletion',
            'savedJobIds'
        ));
    }

    public function applications()
    {
        // Get user's applications from local database
        $applications = auth()->user()->applications()
            ->with(['job.company'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('pages.applications', compact('applications'));
    }

    public function savedJobs()
    {
        return view('pages.saved-jobs');
    }

    public function profile()
    {
        return view('pages.profile');
    }

    public function jobAlerts()
    {
        return view('pages.job-alerts');
    }

    public function settings()
    {
        return view('pages.settings');
    }

    public function dashboardJobs()
    {
        // Get jobs from our local database for dashboard
        $jobs = Job::with('company')
            ->orderBy('posted_at', 'desc')
            ->paginate(20);
            
        return view('pages.dashboard-jobs', compact('jobs'));
    }

    public function dashboardJobDetail($id)
    {
        $job = Job::with('company')->findOrFail($id);
        
        // Get related jobs from the same company
        $relatedJobs = Job::with('company')
            ->where('company_id', $job->company_id)
            ->where('id', '!=', $job->id)
            ->orderBy('posted_at', 'desc')
            ->limit(3)
            ->get();
            
        return view('pages.dashboard-job-detail', compact('job', 'relatedJobs'));
    }

    public function messages()
    {
        return view('pages.messages');
    }
    
    // Profile Edit Methods
    public function editPersonal()
    {
        return view('pages.profile-edit-personal');
    }
    

    
    public function editSkills()
    {
        return view('pages.profile-edit-skills');
    }
    
    public function editExperience()
    {
        return view('pages.profile-edit-experience');
    }
    
    public function editEducation()
    {
        return view('pages.profile-edit-education');
    }
    
    public function editResume()
    {
        return view('pages.profile-edit-resume');
    }
} 