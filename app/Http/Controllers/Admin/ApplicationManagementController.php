<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Company;
use App\Models\Job;
use Illuminate\Http\Request;

class ApplicationManagementController extends Controller
{
    public function index(Request $request)
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

        // Filter by job
        if ($request->filled('job')) {
            $query->where('job_id', $request->job);
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

    public function show(Application $application)
    {
        $application->load(['user', 'job.company']);
        return view('admin.applications.show', compact('application'));
    }

    public function retry(Application $application)
    {
        // Reset application status for retry
        $application->update([
            'status' => 'pending',
            'error_message' => null,
        ]);

        // Here you would typically trigger the forwarding service again
        // For now, we'll just update the status

        return back()->with('success', 'Application marked for retry!');
    }

    public function destroy(Application $application)
    {
        $application->delete();

        return redirect()->route('admin.applications.index')
            ->with('success', 'Application deleted successfully!');
    }
}
