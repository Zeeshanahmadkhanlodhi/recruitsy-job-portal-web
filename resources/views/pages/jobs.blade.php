@extends('layouts.app')

@section('title', 'Jobs - RecruitSy')

@section('content')
<!-- Debug Section (Temporary) -->
<div class="bg-yellow-100 border-b p-4">
    <div class="container">
        <button type="button" onclick="testFilter()" class="bg-blue-500 text-white px-4 py-2 rounded">
            Test Filter Function
        </button>
        <span class="ml-4 text-sm">Check console for debug info</span>
    </div>
</div>

<!-- Hero Section -->
<div class="hero">
    <div class="container">
        <h1>Find Your Next Opportunity</h1>
        <p>Browse thousands of job opportunities and find the perfect match for your career</p>
    </div>
</div>

<!-- Search and Filters -->
<div class="bg-white border-b">
    <div class="container py-8">
        <form class="grid grid-2" method="GET" action="{{ route('jobs') }}" id="searchForm">
            <div class="form-group">
                <label for="keyword" class="form-label">Job Title or Keyword</label>
                <input type="text" id="keyword" name="keyword" placeholder="e.g. Software Engineer" class="form-input" value="{{ request('keyword') }}">
            </div>
            <div class="form-group">
                <label for="location" class="form-label">Location</label>
                <input type="text" id="location" name="location" placeholder="e.g. New York, NY" class="form-input" value="{{ request('location') }}">
            </div>
            <div class="form-group">
                <label for="category" class="form-label">Category</label>
                <select id="category" name="category" class="form-input">
                    <option value="">All Categories</option>
                    @foreach($filterOptions['categories'] as $key => $value)
                        <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;" id="searchBtn">
                    <span class="btn-text">Search Jobs</span>
                    <span class="btn-loading" style="display: none;">
                        <svg class="animate-spin" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Searching...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Jobs List -->
<div class="bg-gray-50 py-12">
    <div class="container">
        <div class="jobs-header">
            <div class="jobs-info">
                <h2 class="jobs-title">Showing {{ $jobs->total() }} jobs</h2>
                @if(request('keyword') || request('location') || request('category') || request('job_type') || request('experience_level') || request('salary_range') || request('remote'))
                    <p class="jobs-subtitle">Filtered results</p>
                @endif
            </div>
            <div class="jobs-sort">
                <span class="sort-label">Sort by:</span>
                <select class="sort-select" id="sortSelect" onchange="updateSort()">
                    <option value="posted_at" {{ request('sort_by') == 'posted_at' ? 'selected' : '' }}>Most Recent</option>
                    <option value="salary_high_low" {{ request('sort_by') == 'salary_high_low' ? 'selected' : '' }}>Salary: High to Low</option>
                    <option value="salary_low_high" {{ request('sort_by') == 'salary_low_high' ? 'selected' : '' }}>Salary: Low to High</option>
                    <option value="experience_level" {{ request('sort_by') == 'experience_level' ? 'selected' : '' }}>Experience Level</option>
                </select>
            </div>
        </div>
        
        @if(request('keyword') || request('location') || request('category') || request('job_type') || request('experience_level') || request('salary_range') || request('remote'))
            <div class="active-filters">
                <span class="active-filters-label">Active filters:</span>
                @if(request('keyword'))
                    <span class="filter-tag">
                        Keyword: "{{ request('keyword') }}"
                        <button type="button" class="filter-tag-remove" onclick="removeFilter('keyword')">&times;</button>
                    </span>
                @endif
                @if(request('location'))
                    <span class="filter-tag">
                        Location: "{{ request('location') }}"
                        <button type="button" class="filter-tag-remove" onclick="removeFilter('location')">&times;</button>
                    </span>
                @endif
                @if(request('category'))
                    <span class="filter-tag">
                        Category: "{{ $filterOptions['categories'][request('category')] }}"
                        <button type="button" class="filter-tag-remove" onclick="removeFilter('category')">&times;</button>
                    </span>
                @endif
                @foreach(request('job_type', []) as $jobType)
                    <span class="filter-tag">
                        Job Type: {{ $jobType }}
                        <button type="button" class="filter-tag-remove" onclick="removeFilterValue('job_type', '{{ $jobType }}')">&times;</button>
                    </span>
                @endforeach
                @foreach(request('experience_level', []) as $level)
                    <span class="filter-tag">
                        Experience: {{ $level }}
                        <button type="button" class="filter-tag-remove" onclick="removeFilterValue('experience_level', '{{ $level }}')">&times;</button>
                    </span>
                @endforeach
                @foreach(request('salary_range', []) as $range)
                    <span class="filter-tag">
                        Salary: {{ $range == '0-50k' ? '$0 - $50k' : ($range == '50k-100k' ? '$50k - $100k' : ($range == '100k-150k' ? '$100k - $150k' : '$150k+')) }}
                        <button type="button" class="filter-tag-remove" onclick="removeFilterValue('salary_range', '{{ $range }}')">&times;</button>
                    </span>
                @endforeach
                @if(request('remote'))
                    <span class="filter-tag">
                        Remote: Yes
                        <button type="button" class="filter-tag-remove" onclick="removeFilterValue('remote', 'remote')">&times;</button>
                    </span>
                @endif
                <button type="button" class="clear-filters-btn" onclick="clearFilters()">Clear All</button>
            </div>
        @endif

        <div class="jobs-layout">
            <!-- Job Listings -->
            <div class="jobs-main">
                <div id="jobs-list" class="jobs-list" aria-live="polite">
                    @if($jobs->count() > 0)
                        @foreach($jobs as $job)
                            <div class="job-card">
                                <div class="job-header">
                                    <div>
                                        <h3 class="job-title">
                                            <a href="{{ route('jobs.detail', $job->id) }}" class="job-title-link">{{ $job->title }}</a>
                                        </h3>
                                        <p class="job-company">{{ $job->company->name ?? 'Company Not Specified' }}</p>
                                    </div>
                                    @if($job->employment_type)
                                        <div class="job-type {{ strtolower($job->employment_type) === 'full-time' ? 'full-time' : (strtolower($job->employment_type) === 'contract' ? 'contract' : '') }}">
                                            {{ $job->employment_type }}
                                        </div>
                                    @endif
                                </div>
                                <div class="job-meta">
                                    @if($job->location)
                                        <div class="meta-item">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            {{ $job->is_remote ? 'Remote' : $job->location }}
                                        </div>
                                    @endif
                                    @if($job->posted_at)
                                        <div class="meta-item">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ \Carbon\Carbon::parse($job->posted_at)->diffForHumans() }}
                                        </div>
                                    @endif
                                </div>
                                @if($job->description)
                                    <p class="job-description">{{ Str::limit($job->description, 220) }}</p>
                                @endif
                                <div class="job-footer">
                                    @if($job->salary_min || $job->salary_max)
                                        <div class="job-salary">
                                            <div class="salary-amount">
                                                @if($job->salary_min && $job->salary_max)
                                                    ${{ number_format($job->salary_min) }} - ${{ number_format($job->salary_max) }} {{ $job->currency ?? 'USD' }}
                                                @elseif($job->salary_min)
                                                    ${{ number_format($job->salary_min) }}+ {{ $job->currency ?? 'USD' }}
                                                @elseif($job->salary_max)
                                                    Up to ${{ number_format($job->salary_max) }} {{ $job->currency ?? 'USD' }}
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="job-actions">
                                    <div class="job-action-buttons">
                                        <a href="{{ route('jobs.detail', $job->id) }}" class="btn btn-outline btn-sm">View Details</a>
                                        <button class="btn btn-primary btn-sm" onclick="openApplicationModal({{ $job->id }}, '{{ $job->title }}', '{{ $job->company->name ?? 'Company Not Specified' }}')">Apply Now</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="no-results">
                            <div class="no-results-icon">
                                <svg width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <h3 class="no-results-title">No jobs found</h3>
                            <p class="no-results-description">
                                @if(request('keyword') || request('location') || request('category') || request('job_type') || request('experience_level') || request('salary_range') || request('remote'))
                                    No jobs match your current filters. Try adjusting your search criteria.
                                @else
                                    We don't have any job openings at the moment. Please check back later for new opportunities.
                                @endif
                            </p>
                            @if(request('keyword') || request('location') || request('category') || request('job_type') || request('experience_level') || request('salary_range') || request('remote'))
                                <button class="btn btn-primary btn-sm" onclick="clearFilters()">Clear All Filters</button>
                            @endif
                        </div>
                    @endif
                </div>
                
                <!-- Pagination -->
                @if($jobs->hasPages())
                    <div id="jobs-pagination" class="pagination">
                        @if($jobs->onFirstPage())
                            <span class="pagination-link disabled">Previous</span>
                        @else
                            <a href="{{ $jobs->previousPageUrl() }}" class="pagination-link">Previous</a>
                        @endif
                        
                        <span class="pagination-link active">{{ $jobs->currentPage() }} / {{ $jobs->lastPage() }}</span>
                        
                        @if($jobs->hasMorePages())
                            <a href="{{ $jobs->nextPageUrl() }}" class="pagination-link">Next</a>
                        @else
                            <span class="pagination-link disabled">Next</span>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="jobs-sidebar">
                <div class="filters-card">
                    <h3 class="filters-title">Filters</h3>
                    
                    <div class="filter-section">
                        <h4 class="filter-label">Job Type</h4>
                        <div class="filter-options">
                            @foreach($filterOptions['jobTypes'] as $jobType)
                                <label class="filter-option">
                                    <input type="checkbox" class="filter-checkbox" name="job_type[]" value="{{ $jobType }}" 
                                           {{ in_array($jobType, request('job_type', [])) ? 'checked' : '' }}>
                                    <span class="filter-text">{{ $jobType }}</span>
                                </label>
                            @endforeach
                            @if($filterOptions['jobTypes']->isEmpty())
                                <p class="text-gray-500 text-sm">No job types available</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="filter-section">
                        <h4 class="filter-label">Experience Level</h4>
                        <div class="filter-options">
                            @foreach($filterOptions['experienceLevels'] as $level)
                                <label class="filter-option">
                                    <input type="checkbox" class="filter-checkbox" name="experience_level[]" value="{{ $level }}" 
                                           {{ in_array($level, request('experience_level', [])) ? 'checked' : '' }}>
                                    <span class="filter-text">{{ $level }}</span>
                                </label>
                            @endforeach
                            @if($filterOptions['experienceLevels']->isEmpty())
                                <p class="text-gray-500 text-sm">No experience levels available</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="filter-section">
                        <h4 class="filter-label">Salary Range</h4>
                        <div class="filter-options">
                            <label class="filter-option">
                                <input type="checkbox" class="filter-checkbox" name="salary_range[]" value="0-50k" 
                                       {{ in_array('0-50k', request('salary_range', [])) ? 'checked' : '' }}>
                                <span class="filter-text">$0 - $50k</span>
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" class="filter-checkbox" name="salary_range[]" value="50k-100k" 
                                       {{ in_array('50k-100k', request('salary_range', [])) ? 'checked' : '' }}>
                                <span class="filter-text">$50k - $100k</span>
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" class="filter-checkbox" name="salary_range[]" value="100k-150k" 
                                       {{ in_array('100k-150k', request('salary_range', [])) ? 'checked' : '' }}>
                                <span class="filter-text">$100k - $150k</span>
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" class="filter-checkbox" name="salary_range[]" value="150k+" 
                                       {{ in_array('150k+', request('salary_range', [])) ? 'checked' : '' }}>
                                <span class="filter-text">$150k+</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="filter-section">
                        <h4 class="filter-label">Remote Work</h4>
                        <div class="filter-options">
                            <label class="filter-option">
                                <input type="checkbox" class="filter-checkbox" name="remote[]" value="remote" 
                                       {{ in_array('remote', request('remote', [])) ? 'checked' : '' }}>
                                <span class="filter-text">Remote Only</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="filter-section">
                        <button type="button" class="btn btn-outline btn-sm" onclick="clearFilters()" style="width: 100%;">
                            Clear All Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.jobs-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.jobs-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.jobs-title {
    font-size: 1.5rem;
    font-weight: bold;
    color: #1f2937;
    margin: 0;
}

.jobs-subtitle {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0;
}

/* Active Filters Styles */
.active-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    align-items: center;
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 0.5rem;
    border: 1px solid #e2e8f0;
}

.active-filters-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #64748b;
    margin-right: 0.5rem;
}

.filter-tag {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.375rem 0.75rem;
    background: #dbeafe;
    color: #1e40af;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
    border: 1px solid #bfdbfe;
}

.filter-tag-remove {
    background: none;
    border: none;
    color: #1e40af;
    cursor: pointer;
    font-size: 1rem;
    font-weight: bold;
    padding: 0;
    width: 16px;
    height: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.2s;
}

.filter-tag-remove:hover {
    background: #1e40af;
    color: white;
}

.clear-filters-btn {
    background: none;
    border: 1px solid #d1d5db;
    color: #6b7280;
    padding: 0.375rem 0.75rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    cursor: pointer;
    transition: all 0.2s;
}

.clear-filters-btn:hover {
    background: #f3f4f6;
    border-color: #9ca3af;
    color: #374151;
}

.jobs-sort {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.sort-label {
    font-size: 0.875rem;
    color: #6b7280;
}

.sort-select {
    padding: 0.5rem 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 0.875rem;
}

.jobs-layout {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
}

.jobs-main {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.job-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 0.75rem;
    padding: 1.5rem;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    transition: all 0.2s;
    position: relative;
}

.job-card:hover {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    border-color: #d1d5db;
}

.job-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.job-title {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
    line-height: 1.4;
}

.job-title-link {
    color: #111827;
    text-decoration: none;
    transition: color 0.2s;
    cursor: pointer;
    display: inline-block;
    padding: 0.25rem 0;
}

.job-title-link:hover {
    color: #3b82f6;
    text-decoration: underline;
}

.job-type {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

.job-type.full-time {
    background: #dcfce7;
    color: #166534;
}

.job-type.remote {
    background: #dbeafe;
    color: #1e40af;
}

.job-type.contract {
    background: #f3e8ff;
    color: #7c3aed;
}

.job-meta {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
    color: #6b7280;
    font-size: 0.875rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.job-description {
    color: #6b7280;
    margin-bottom: 1rem;
    line-height: 1.6;
}

.job-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.job-salary {
    text-align: right;
}

.salary-amount {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
}

.salary-label {
    font-size: 0.75rem;
    color: #6b7280;
}

.job-actions {
    padding-top: 1rem;
    border-top: 1px solid #e5e7eb;
}

.job-action-buttons {
    display: flex;
    gap: 0.75rem;
    justify-content: flex-end;
}

.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.5rem;
    margin-top: 2rem;
}

.pagination-link {
    padding: 0.5rem 0.75rem;
    border-radius: 0.375rem;
    text-decoration: none;
    color: #6b7280;
    transition: all 0.3s;
}

.pagination-link:hover {
    color: #374151;
}

.pagination-link.active {
    background: #2563eb;
    color: white;
}

.pagination-ellipsis {
    padding: 0.5rem 0.75rem;
    color: #6b7280;
}

.jobs-sidebar {
    position: sticky;
    top: 1rem;
}

.filters-card {
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    padding: 1.5rem;
}

.filters-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 1.5rem;
}

.filter-section {
    margin-bottom: 1.5rem;
}

.filter-label {
    font-weight: 500;
    color: #1f2937;
    margin-bottom: 0.75rem;
    display: block;
}

.filter-options {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.filter-option {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
}

.filter-checkbox {
    width: 1rem;
    height: 1rem;
    border-radius: 0.25rem;
    border: 1px solid #d1d5db;
}

.filter-text {
    color: #374151;
    font-size: 0.875rem;
}

.filter-checkbox:checked {
    background-color: #2563eb;
    border-color: #2563eb;
}

.filter-checkbox:checked + .filter-text {
    color: #2563eb;
    font-weight: 500;
}

/* Loading state for filters */
.filter-loading {
    opacity: 0.6;
    pointer-events: none;
}

.filter-loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid #2563eb;
    border-top: 2px solid transparent;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

@media (max-width: 1024px) {
    .jobs-layout {
        grid-template-columns: 1fr;
    }
    
    .jobs-sidebar {
        position: static;
    }
}

/* No Results Styles */
.no-results {
    text-align: center;
    padding: 3rem 2rem;
    background: white;
    border-radius: 0.75rem;
    border: 1px solid #e5e7eb;
}

/* Button Loading Styles */
.btn-loading {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.animate-spin {
    animation: spin 1s linear infinite;
}

.no-results-icon {
    color: #9ca3af;
    margin-bottom: 1rem;
}

.no-results-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}

.no-results-description {
    color: #6b7280;
    margin-bottom: 1.5rem;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

/* Application Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
    background-color: white;
    margin: 5% auto;
    padding: 2rem;
    border-radius: 0.75rem;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e5e7eb;
}

.modal-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1f2937;
}

.close {
    color: #9ca3af;
    font-size: 1.5rem;
    font-weight: bold;
    cursor: pointer;
    background: none;
    border: none;
}

.close:hover {
    color: #374151;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #374151;
}

.form-input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 0.875rem;
}

.form-input:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.form-textarea {
    min-height: 120px;
    resize: vertical;
}

.modal-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e5e7eb;
}

.alert {
    padding: 1rem;
    border-radius: 0.375rem;
    margin-bottom: 1rem;
}

.alert-success {
    background-color: #dcfce7;
    color: #16a34a;
    border: 1px solid #bbf7d0;
}

.alert-error {
    background-color: #fee2e2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.alert-info {
    background-color: #dbeafe;
    color: #2563eb;
    border: 1px solid #bfdbfe;
}
</style>

<!-- Application Modal -->
<div id="applicationModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title" id="modalJobTitle">Apply for Job</h2>
            <button class="close" onclick="closeApplicationModal()">&times;</button>
        </div>
        
        <div id="modalBody">
            <div id="applicationForm">
                <div class="form-group">
                    <label for="candidate_name" class="form-label">Full Name *</label>
                    <input type="text" id="candidate_name" name="candidate_name" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label for="candidate_email" class="form-label">Email Address *</label>
                    <input type="email" id="candidate_email" name="candidate_email" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label for="candidate_phone" class="form-label">Phone Number</label>
                    <input type="tel" id="candidate_phone" name="candidate_phone" class="form-input">
                </div>
                
                <div class="form-group">
                    <label for="resume_url" class="form-label">Resume/CV URL</label>
                    <input type="url" id="resume_url" name="resume_url" class="form-input" placeholder="https://example.com/resume.pdf">
                </div>
                
                <div class="form-group">
                    <label for="cover_letter" class="form-label">Cover Letter</label>
                    <textarea id="cover_letter" name="cover_letter" class="form-textarea form-input" placeholder="Tell us why you're interested in this position..."></textarea>
                </div>
                
                <div class="modal-actions">
                    <button type="button" class="btn btn-outline btn-sm" onclick="closeApplicationModal()">Cancel</button>
                    <button type="button" class="btn btn-primary btn-sm" onclick="submitApplication()">Submit Application</button>
                </div>
            </div>
            
            <div id="applicationSuccess" style="display: none;">
                <div class="alert alert-success">
                    <h3>Application Submitted Successfully!</h3>
                    <p>Thank you for your interest. We've received your application and will forward it to the company. You'll receive a confirmation email shortly.</p>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-primary btn-sm" onclick="closeApplicationModal()">Close</button>
                </div>
            </div>
            
            <div id="applicationError" style="display: none;">
                <div class="alert alert-error">
                    <h3>Application Submission Failed</h3>
                    <p id="errorMessage">An error occurred while submitting your application. Please try again.</p>
                </div>
                <div="modal-actions">
                    <button type="button" class="btn btn-outline btn-sm" onclick="closeApplicationModal()">Close</button>
                    <button type="button" class="btn btn-primary btn-sm" onclick="showApplicationForm()">Try Again</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Test function for debugging
function testFilter() {
    console.log('testFilter called');
    alert('Test function working!');
    
    // Check if checkboxes exist
    const checkboxes = document.querySelectorAll('.filter-checkbox');
    console.log('Found checkboxes:', checkboxes.length);
    
    // Check if applyFilters function exists
    if (typeof applyFilters === 'function') {
        console.log('applyFilters function exists');
    } else {
        console.log('applyFilters function NOT found');
    }
}

// Filter functionality
function applyFilters() {
    console.log('applyFilters called');
    
    // Add loading state
    const filtersCard = document.querySelector('.filters-card');
    filtersCard.classList.add('filter-loading');
    
    const form = document.getElementById('searchForm');
    const formData = new FormData(form);
    
    // Get all checked checkboxes
    const jobTypeCheckboxes = document.querySelectorAll('input[name="job_type[]"]:checked');
    const experienceLevelCheckboxes = document.querySelectorAll('input[name="experience_level[]"]:checked');
    const salaryRangeCheckboxes = document.querySelectorAll('input[name="salary_range[]"]:checked');
    const remoteCheckboxes = document.querySelectorAll('input[name="remote[]"]:checked');
    
    console.log('Checked checkboxes:', {
        jobType: Array.from(jobTypeCheckboxes).map(cb => cb.value),
        experienceLevel: Array.from(experienceLevelCheckboxes).map(cb => cb.value),
        salaryRange: Array.from(salaryRangeCheckboxes).map(cb => cb.value),
        remote: Array.from(remoteCheckboxes).map(cb => cb.value)
    });
    
    // Clear existing values
    formData.delete('job_type[]');
    formData.delete('experience_level[]');
    formData.delete('salary_range[]');
    formData.delete('remote[]');
    
    // Add checked values
    jobTypeCheckboxes.forEach(checkbox => {
        formData.append('job_type[]', checkbox.value);
    });
    
    experienceLevelCheckboxes.forEach(checkbox => {
        formData.append('experience_level[]', checkbox.value);
    });
    
    salaryRangeCheckboxes.forEach(checkbox => {
        formData.append('salary_range[]', checkbox.value);
    });
    
    remoteCheckboxes.forEach(checkbox => {
        formData.append('remote[]', checkbox.value);
    });
    
    // Build query string
    const params = new URLSearchParams();
    for (let [key, value] of formData.entries()) {
        if (value) {
            params.append(key, value);
        }
    }
    
    console.log('Final query string:', params.toString());
    console.log('Redirecting to:', '{{ route("jobs") }}?' + params.toString());
    
    // Redirect with filters
    window.location.href = '{{ route("jobs") }}?' + params.toString();
}

function updateSort() {
    const sortSelect = document.getElementById('sortSelect');
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('sort_by', sortSelect.value);
    window.location.href = currentUrl.toString();
}

function clearFilters() {
    window.location.href = '{{ route("jobs") }}';
}

function removeFilter(filterName) {
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.delete(filterName);
    window.location.href = currentUrl.toString();
}

function removeFilterValue(filterName, value) {
    const currentUrl = new URL(window.location);
    const currentValues = currentUrl.searchParams.getAll(filterName);
    const newValues = currentValues.filter(v => v !== value);
    
    currentUrl.searchParams.delete(filterName);
    newValues.forEach(v => currentUrl.searchParams.append(filterName, v));
    
    window.location.href = currentUrl.toString();
}

// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, setting up event listeners');
    
    // Search form submission with loading state
    const searchForm = document.getElementById('searchForm');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const searchBtn = document.getElementById('searchBtn');
            const btnText = searchBtn.querySelector('.btn-text');
            const btnLoading = searchBtn.querySelector('.btn-loading');
            
            // Show loading state
            btnText.style.display = 'none';
            btnLoading.style.display = 'inline-flex';
            searchBtn.disabled = true;
        });
        console.log('Search form event listener added');
    } else {
        console.error('Search form not found');
    }
    
    // Add click event listeners to all filter checkboxes
    const filterCheckboxes = document.querySelectorAll('.filter-checkbox');
    filterCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            console.log('Checkbox changed:', this.name, this.value, this.checked);
            applyFilters();
        });
    });
    console.log('Added event listeners to', filterCheckboxes.length, 'filter checkboxes');
});

let currentJobId = null;

function openApplicationModal(jobId, jobTitle, companyName) {
    currentJobId = jobId;
    document.getElementById('modalJobTitle').textContent = `Apply for ${jobTitle} at ${companyName}`;
    document.getElementById('applicationModal').style.display = 'block';
    showApplicationForm();
}

function closeApplicationModal() {
    document.getElementById('applicationModal').style.display = 'none';
    currentJobId = null;
}

function showApplicationForm() {
    document.getElementById('applicationForm').style.display = 'block';
    document.getElementById('applicationSuccess').style.display = 'none';
    document.getElementById('applicationError').style.display = 'none';
}

function submitApplication() {
    const formData = {
        candidate_name: document.getElementById('candidate_name').value,
        candidate_email: document.getElementById('candidate_email').value,
        candidate_phone: document.getElementById('candidate_phone').value,
        resume_url: document.getElementById('resume_url').value,
        cover_letter: document.getElementById('cover_letter').value,
    };

    // Basic validation
    if (!formData.candidate_name || !formData.candidate_email) {
        alert('Please fill in all required fields.');
        return;
    }

    // Show loading state
    const submitBtn = document.querySelector('#applicationForm .btn-primary');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Submitting...';
    submitBtn.disabled = true;

    fetch(`/api/jobs/${currentJobId}/apply`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('applicationForm').style.display = 'none';
            document.getElementById('applicationSuccess').style.display = 'block';
        } else {
            throw new Error(data.message || 'Application submission failed');
        }
    })
    .catch(error => {
        document.getElementById('errorMessage').textContent = error.message;
        document.getElementById('applicationForm').style.display = 'none';
        document.getElementById('applicationError').style.display = 'block';
    })
    .finally(() => {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('applicationModal');
    if (event.target === modal) {
        closeApplicationModal();
    }
}
</script>
@endsection 