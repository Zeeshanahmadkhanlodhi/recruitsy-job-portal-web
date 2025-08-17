@extends('layouts.dashboard')

@section('title', 'Dashboard - RecruitSy')
@section('page-title', 'Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon applications">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-number">{{ $applications->count() }}</div>
            <div class="stat-label">Applications</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon success">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-number">{{ $applications->where('status', 'success')->count() }}</div>
            <div class="stat-label">Successful Applications</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon saved">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-number">{{ $savedJobs->count() }}</div>
            <div class="stat-label">Saved Jobs</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon profile">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-number">{{ $profileCompletion }}%</div>
            <div class="stat-label">Profile Complete</div>
        </div>
    </div>
</div>

<div class="dashboard-sections">
    <!-- Recent Applications -->
    <div class="dashboard-section">
        <div class="section-header">
            <h2 class="section-title">Recent Applications</h2>
            <a href="{{ route('applications') }}" class="view-all">View All</a>
        </div>
        <div class="applications-list">
            @if($applications->count() > 0)
                @foreach($applications as $application)
                <div class="application-item">
                                         <div class="application-info">
                         <h3 class="job-title">
                             <a href="{{ route('applications') }}" class="job-title-link">{{ $application->job->title ?? 'Job Title Not Available' }}</a>
                         </h3>
                         <p class="company-name">{{ $application->job->company->name ?? 'Company Not Available' }}</p>
                        <div class="application-meta">
                            <span class="meta-item">Applied {{ $application->created_at->diffForHumans() }}</span>
                            <span class="status {{ $application->status }}">
                                @switch($application->status)
                                    @case('pending')
                                        Pending Review
                                        @break
                                    @case('success')
                                        Successfully Forwarded
                                        @break
                                    @case('failed')
                                        Failed to Forward
                                        @break
                                    @default
                                        {{ ucfirst($application->status) }}
                                @endswitch
                            </span>
                        </div>
                    </div>
                    <div class="application-actions">
                        <a href="{{ route('applications') }}" class="btn btn-outline btn-sm">View Details</a>
                    </div>
                </div>
                @endforeach
            @else
                <div class="empty-state">
                    <p class="text-gray-500 text-center py-4">No applications yet. Start applying to jobs to see them here!</p>
                    <div class="text-center">
                        <a href="{{ route('jobs') }}" class="btn btn-primary btn-sm">Browse Jobs</a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Recommended Jobs -->
    <div class="dashboard-section">
        <div class="section-header">
            <h2 class="section-title">Recommended for You</h2>
            <a href="{{ route('jobs') }}" class="view-all">Browse More</a>
        </div>
        <div class="jobs-grid">
            @if($recommendedJobs->count() > 0)
                @foreach($recommendedJobs as $job)
                                 <div class="job-card">
                     <div class="job-content">
                         <div class="job-header">
                             <h3 class="job-title">
                                 <a href="{{ route('jobs.detail', $job->id) }}" class="job-title-link">{{ $job->title }}</a>
                             </h3>
                             <p class="job-company">{{ $job->company->name ?? 'Company Not Available' }}</p>
                         </div>
                         <div class="job-meta">
                             @if($job->location)
                                 <span class="location">{{ $job->is_remote ? 'Remote' : $job->location }}</span>
                             @endif
                             @if($job->salary_min && $job->salary_max)
                                 <span class="salary">${{ number_format($job->salary_min) }}k - ${{ number_format($job->salary_max) }}k</span>
                             @endif
                         </div>
                         @if($job->requirements)
                             <div class="job-tags">
                                 @php
                                     $tags = explode(',', $job->requirements);
                                     $tags = array_slice($tags, 0, 3); // Show only first 3 tags
                                 @endphp
                                 @foreach($tags as $tag)
                                     <span class="tag">{{ trim($tag) }}</span>
                                 @endforeach
                             </div>
                         @endif
                     </div>
                     <div class="job-actions">
                         <div class="primary-actions">
                             <a href="{{ route('jobs.detail', $job->id) }}" class="btn btn-primary btn-sm">Apply Now</a>
                         </div>
                         <div class="save-action">
                             @if(in_array($job->id, $savedJobIds))
                                 <button class="btn btn-success btn-sm saved-btn" disabled>
                                     <i class="fas fa-check"></i> Saved
                                 </button>
                             @else
                                 <button class="btn btn-outline btn-sm save-job-btn" data-job-id="{{ $job->id }}" data-job-title="{{ $job->title }}" data-company-name="{{ $job->company->name ?? 'Company Not Available' }}" data-location="{{ $job->location ?? '' }}" data-employment-type="{{ $job->employment_type ?? '' }}">
                                     <i class="fas fa-bookmark"></i> Save
                                 </button>
                             @endif
                         </div>
                     </div>
                 </div>
                @endforeach
            @else
                <div class="empty-state">
                    <p class="text-gray-500 text-center py-4">No recommended jobs available at the moment.</p>
                    <div class="text-center">
                        <a href="{{ route('jobs') }}" class="btn btn-primary btn-sm">Browse All Jobs</a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Saved Jobs -->
    <div class="dashboard-section">
        <div class="section-header">
            <h2 class="section-title">Recently Saved Jobs</h2>
            <a href="{{ route('saved-jobs') }}" class="view-all">View All</a>
        </div>
        <div class="saved-jobs-list">
            @if($savedJobs->count() > 0)
                @foreach($savedJobs as $savedJob)
                <div class="saved-job-item">
                                         <div class="saved-job-info">
                         <h3 class="job-title">
                             <a href="{{ $savedJob->apply_url ?? route('saved-jobs') }}" class="job-title-link" {{ $savedJob->apply_url ? 'target="_blank"' : '' }}>{{ $savedJob->title }}</a>
                         </h3>
                         <p class="company-name">{{ $savedJob->company_name }}</p>
                        <div class="job-meta">
                            @if($savedJob->location)
                                <span class="location">{{ $savedJob->location }}</span>
                            @endif
                            @if($savedJob->employment_type)
                                <span class="employment-type">{{ $savedJob->employment_type }}</span>
                            @endif
                        </div>
                        <p class="saved-date">Saved {{ $savedJob->saved_at->diffForHumans() }}</p>
                    </div>
                    <div class="saved-job-actions">
                        @if($savedJob->apply_url)
                            <a href="{{ $savedJob->apply_url }}" target="_blank" class="btn btn-primary btn-sm">Apply Now</a>
                        @endif
                        <a href="{{ route('saved-jobs') }}" class="btn btn-outline btn-sm">View Details</a>
                    </div>
                </div>
                @endforeach
            @else
                <div class="empty-state">
                    <p class="text-gray-500 text-center py-4">No saved jobs yet. Start saving jobs you're interested in!</p>
                    <div class="text-center">
                        <a href="{{ route('jobs') }}" class="btn btn-primary btn-sm">Browse Jobs</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: 1rem;
    padding: 1.75rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    display: flex;
    align-items: center;
    gap: 1.25rem;
    transition: all 0.3s ease;
    border: 1px solid #f1f5f9;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #3b82f6, #8b5cf6);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.15);
    border-color: #e2e8f0;
}

.stat-card:hover::before {
    opacity: 1;
}

.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    position: relative;
    z-index: 1;
}

.stat-card:hover .stat-icon {
    transform: scale(1.1);
}

.stat-icon.applications {
    background: #dbeafe;
    color: #2563eb;
}

.stat-icon.success {
    background: #dcfce7;
    color: #16a34a;
}

.stat-icon.saved {
    background: #fef3c7;
    color: #d97706;
}

.stat-icon.profile {
    background: #f3e8ff;
    color: #9333ea;
}

.stat-number {
    font-size: 1.875rem;
    font-weight: bold;
    color: #1f2937;
}

.stat-label {
    color: #6b7280;
    font-size: 0.875rem;
}

/* Dashboard Sections */
.dashboard-sections {
    display: grid;
    gap: 2rem;
}

.dashboard-section {
    background: white;
    border-radius: 0.75rem;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.section-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1f2937;
}

.view-all {
    color: #2563eb;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.875rem;
}

.view-all:hover {
    text-decoration: underline;
}

/* Applications List */
.applications-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.application-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    transition: all 0.3s;
}

.application-item:hover {
    border-color: #d1d5db;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.job-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.company-name {
    color: #2563eb;
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.application-meta {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.meta-item {
    color: #6b7280;
    font-size: 0.875rem;
}

.status {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status.pending {
    background: #fef3c7;
    color: #d97706;
}

.status.interview {
    background: #dcfce7;
    color: #16a34a;
}

.status.rejected {
    background: #fee2e2;
    color: #dc2626;
}

.status.success {
    background: #dcfce7;
    color: #16a34a;
}

.status.failed {
    background: #fee2e2;
    color: #dc2626;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 2rem;
    color: #6b7280;
}

.empty-state p {
    margin-bottom: 1rem;
}

.empty-state .btn {
    margin: 0 auto;
}

/* Saved Jobs List */
.saved-jobs-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.saved-job-item {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    transition: all 0.3s;
}

.saved-job-item:hover {
    border-color: #d1d5db;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.saved-job-info {
    flex: 1;
}

.saved-job-info .job-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.saved-job-info .company-name {
    color: #2563eb;
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.saved-job-info .job-meta {
    display: flex;
    gap: 1rem;
    margin-bottom: 0.5rem;
}

.saved-job-info .location,
.saved-job-info .employment-type {
    color: #6b7280;
    font-size: 0.875rem;
}

.saved-job-info .saved-date {
    color: #9ca3af;
    font-size: 0.75rem;
}

.saved-job-actions {
    display: flex;
    gap: 0.5rem;
    flex-shrink: 0;
}

/* Jobs Grid */
.jobs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.job-card {
    border: 1px solid #e5e7eb;
    border-radius: 0.75rem;
    padding: 1.5rem;
    transition: all 0.3s ease;
    background: white;
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.job-card:hover {
    border-color: #d1d5db;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.job-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #2563eb, #3b82f6);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.job-card:hover::before {
    opacity: 1;
}

.job-header {
    margin-bottom: 1rem;
}

.job-header .job-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.5rem;
    line-height: 1.3;
}

.job-title-link {
    color: #1f2937;
    text-decoration: none;
    transition: all 0.2s ease;
    cursor: pointer;
    display: block;
    width: 100%;
}

.job-title-link:hover {
    color: #2563eb;
    text-decoration: underline;
}

/* Ensure job titles are clickable in all sections */
.application-item .job-title-link,
.saved-job-item .job-title-link {
    color: #1f2937;
}

.application-item .job-title-link:hover,
.saved-job-item .job-title-link:hover {
    color: #2563eb;
}

.job-header .job-company {
    color: #2563eb;
    font-weight: 600;
    font-size: 1rem;
    margin-bottom: 0.5rem;
}

.job-meta {
    display: flex;
    gap: 1rem;
    margin: 1rem 0;
    color: #6b7280;
    font-size: 0.875rem;
    flex-wrap: wrap;
}

.job-meta .location,
.job-meta .salary {
    background: #f3f4f6;
    padding: 0.25rem 0.75rem;
    border-radius: 0.375rem;
    font-weight: 500;
}

.job-tags {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 1rem;
}

.job-tags {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin: 1rem 0;
}

.tag {
    background: #e0e7ff;
    color: #3730a3;
    padding: 0.375rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    border: 1px solid #c7d2fe;
    transition: all 0.2s ease;
}

.tag:hover {
    background: #c7d2fe;
    transform: translateY(-1px);
}

.job-actions {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-top: 1.5rem;
    gap: 1rem;
    flex-wrap: wrap;
}

.primary-actions {
    display: flex;
    gap: 0.75rem;
    flex: 1;
    min-width: 0;
}

.save-action {
    display: flex;
    justify-content: flex-end;
    flex-shrink: 0;
    min-width: fit-content;
}

.save-btn {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
}



/* Button Styles */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    font-weight: 500;
    font-size: 0.875rem;
    text-decoration: none;
    transition: all 0.2s ease-in-out;
    border: 1px solid transparent;
    cursor: pointer;
    min-height: 36px;
    white-space: nowrap;
    flex-shrink: 0;
}

.btn-primary {
    background: #2563eb;
    color: white;
    border-color: #2563eb;
}

.btn-primary:hover {
    background: #1d4ed8;
    border-color: #1d4ed8;
    transform: translateY(-1px);
}

.btn-outline {
    background: white;
    color: #6b7280;
    border-color: #d1d5db;
}

.btn-outline:hover {
    background: #f9fafb;
    border-color: #9ca3af;
    color: #374151;
}

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    min-height: 36px;
    min-width: 100px;
}

.btn-success {
    background: #16a34a;
    color: white;
    border-color: #16a34a;
    cursor: default;
}

.btn-success:hover {
    background: #16a34a;
    border-color: #16a34a;
    transform: none;
}

.saved-btn {
    background: #16a34a !important;
    color: white !important;
    border-color: #16a34a !important;
    opacity: 0.9;
}

.save-job-btn {
    transition: all 0.2s ease;
    min-width: 100px;
}

.save-job-btn:hover {
    background: #f3f4f6;
    border-color: #9ca3af;
    color: #374151;
    transform: translateY(-1px);
}

.saved-btn {
    min-width: 100px;
    opacity: 0.9;
}

/* Ensure consistent button heights */
.job-actions .btn {
    height: 36px;
    align-items: center;
}

/* Fix button alignment in job cards */
.job-card .job-actions {
    margin-top: auto;
    padding-top: 1rem;
    border-top: 1px solid #f1f5f9;
}

/* Ensure job content takes available space */
.job-card .job-header,
.job-card .job-meta,
.job-card .job-tags {
    flex-shrink: 0;
}

.job-card .job-content {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.save-job-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

.save-job-btn .fa-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Responsive Design */
@media (max-width: 1024px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .jobs-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .application-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .job-actions {
        width: 100%;
        flex-direction: column;
        align-items: stretch;
        gap: 1rem;
    }
    
    .primary-actions {
        justify-content: center;
        gap: 1rem;
    }
    
    .save-action {
        justify-content: center;
    }
    
    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .saved-job-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .saved-job-actions {
        width: 100%;
        justify-content: flex-start;
    }
    
    .btn-sm {
        min-width: 120px;
        padding: 0.75rem 1rem;
    }
}
 </style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle save job functionality
    const saveJobBtns = document.querySelectorAll('.save-job-btn');
    
    saveJobBtns.forEach(btn => {
        btn.addEventListener('click', async function() {
            // Check if button is already in saved state
            if (this.disabled || this.classList.contains('btn-success')) {
                return;
            }
            
            const jobId = this.getAttribute('data-job-id');
            const jobTitle = this.getAttribute('data-job-title');
            const companyName = this.getAttribute('data-company-name');
            const location = this.getAttribute('data-location');
            const employmentType = this.getAttribute('data-employment-type');
            
            try {
                // Show loading state
                const originalText = this.innerHTML;
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
                
                // Prepare job data for saving
                const jobData = {
                    source: 'local',
                    external_id: jobId,
                    title: jobTitle,
                    company_name: companyName,
                    location: location,
                    employment_type: employmentType,
                    apply_url: `{{ route('jobs.detail', '') }}/${jobId}`,
                    short_description: '',
                    tags: [],
                    saved_at: new Date().toISOString()
                };
                
                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (!csrfToken) {
                    throw new Error('CSRF token not found. Please refresh the page and try again.');
                }
                
                // Save the job
                const response = await fetch('/api/saved-jobs', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(jobData)
                });
                
                if (response.ok) {
                    // Job saved successfully
                    this.innerHTML = '<i class="fas fa-check"></i> Saved!';
                    this.classList.remove('btn-outline');
                    this.classList.add('btn-success');
                    this.disabled = true;
                    
                    // Show success message
                    showAlert('Job saved successfully!', 'success');
                    
                    // Update saved jobs count
                    updateSavedJobsCount();
                    
                    // Refresh the page after a short delay to show updated saved jobs list
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                    
                } else {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Failed to save job');
                }
                
            } catch (error) {
                console.error('Error saving job:', error);
                this.innerHTML = originalText;
                this.disabled = false;
                showAlert('Failed to save job: ' + error.message, 'error');
            }
        });
    });
    
    // Function to show alerts
    function showAlert(message, type) {
        const alert = document.createElement('div');
        alert.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        alert.textContent = message;
        
        document.body.appendChild(alert);
        
        // Remove alert after 3 seconds
        setTimeout(() => {
            alert.remove();
        }, 3000);
    }
    
    // Function to update saved jobs count
    function updateSavedJobsCount() {
        const savedJobsCountElement = document.querySelector('.stat-icon.saved + .stat-content .stat-number');
        if (savedJobsCountElement) {
            const currentCount = parseInt(savedJobsCountElement.textContent) || 0;
            savedJobsCountElement.textContent = currentCount + 1;
        }
    }
});
</script>

@endsection