<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Company;
use Illuminate\Http\Request;

class JobManagementController extends Controller
{
    public function index(Request $request)
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
            if ($request->status === 'expired') {
                $query->where('expires_at', '<', now());
            } else {
                $query->where('is_active', $request->status === 'active');
            }
        }

        $jobs = $query->latest()->paginate(20);
        $companies = Company::orderBy('name')->get();

        return view('admin.jobs.index', compact('jobs', 'companies'));
    }

    public function show(Job $job)
    {
        $job->load(['company', 'applications.user']);
        return view('admin.jobs.show', compact('job'));
    }

    public function edit(Job $job)
    {
        $companies = Company::orderBy('name')->get();
        return view('admin.jobs.edit', compact('job', 'companies'));
    }

    public function update(Request $request, Job $job)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'employment_type' => 'nullable|string|max:50',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'company_id' => 'required|exists:companies,id',
            'is_active' => 'boolean',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $job->update($request->all());

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Job updated successfully!');
    }

    public function toggleStatus(Job $job)
    {
        $job->update(['is_active' => !$job->is_active]);

        $status = $job->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Job {$status} successfully!");
    }

    public function destroy(Job $job)
    {
        $job->delete();

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Job deleted successfully!');
    }
}
