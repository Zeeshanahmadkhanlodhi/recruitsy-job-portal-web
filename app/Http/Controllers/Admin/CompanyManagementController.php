<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyManagementController extends Controller
{
    public function index(Request $request)
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

        // Filter by location
        if ($request->filled('location')) {
            $query->where('location', $request->location);
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

    public function show(Company $company)
    {
        $company->load(['jobs.applications', 'jobs.applications.user']);
        return view('admin.companies.show', compact('company'));
    }

    public function edit(Company $company)
    {
        return view('admin.companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'hr_portal_url' => 'nullable|url|max:255',
            'is_active' => 'boolean',
        ]);

        $company->update($request->all());

        return redirect()->route('admin.companies.index')
            ->with('success', 'Company updated successfully!');
    }

    public function toggleStatus(Company $company)
    {
        $company->update(['is_active' => !$company->is_active]);

        $status = $company->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Company {$status} successfully!");
    }

    public function destroy(Company $company)
    {
        $company->delete();

        return redirect()->route('admin.companies.index')
            ->with('success', 'Company deleted successfully!');
    }
}
