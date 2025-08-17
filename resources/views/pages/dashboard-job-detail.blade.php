@extends('layouts.dashboard')

@section('title', $job->title . ' - Job Details - RecruitSy')
@section('page-title', 'Job Details')

@section('content')
<div class="dashboard-job-detail">
    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <a href="{{ route('dashboard-jobs') }}" class="breadcrumb-link">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Jobs
        </a>
    </div>

    <!-- Job Header -->
    <div class="job-header-section">
        <div class="job-header-content">
            <div class="job-title-section">
                <h1 class="job-title">{{ $job->title }}</h1>
                <div class="company-info">
                    @if($job->company)
                        <div class="company-logo">
                            @if($job->company->logo_path)
                                <img src="{{ asset('storage/' . $job->company->logo_path) }}" alt="{{ $job->company->name }} Logo">
                            @else
                                <div class="company-logo-placeholder">
                                    {{ substr($job->company->name, 0, 2) }}
                                </div>
                            @endif
                        </div>
                        <div class="company-details">
                            <h3 class="company-name">{{ $job->company->name }}</h3>
                            @if($job->company->website)
                                <a href="{{ $job->company->website }}" target="_blank" class="company-website">
                                    {{ parse_url($job->company->website, PHP_URL_HOST) }}
                                </a>
                            @endif
                        </div>
                    @else
                        <div class="company-details">
                            <h3 class="company-name">Company Not Specified</h3>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="job-actions">
                <button class="btn btn-outline btn-sm btn-save" onclick="toggleSaveJob({{ $job->id }})" data-job-id="{{ $job->id }}">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                    </svg>
                    <span class="save-text">Save Job</span>
                </button>
                <button class="btn btn-primary btn-sm btn-apply" onclick="applyToJob({{ $job->id }})" id="applyBtn">
                    <span id="applyBtnText">Apply Now</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Job Details Grid -->
    <div class="job-details-grid">
        <!-- Main Content -->
        <div class="job-main-content">
            <!-- Job Description -->
            <div class="content-section">
                <h2 class="section-title">Job Description</h2>
                <div class="job-description">
                    {!! nl2br(e($job->description ?? 'No description available.')) !!}
                </div>
            </div>

            <!-- Requirements -->
            @if($job->requirements)
            <div class="content-section">
                <h2 class="section-title">Requirements</h2>
                <div class="requirements">
                    {!! nl2br(e($job->requirements)) !!}
                </div>
            </div>
            @endif

            <!-- Benefits -->
            @if($job->benefits)
            <div class="content-section">
                <h2 class="section-title">Benefits</h2>
                <div class="benefits">
                    {!! nl2br(e($job->benefits)) !!}
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="job-sidebar">
            <!-- Job Overview -->
            <div class="sidebar-section">
                <h3 class="sidebar-title">Job Overview</h3>
                <div class="overview-list">
                    @if($job->location)
                    <div class="overview-item">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <div class="overview-content">
                            <span class="overview-label">Location</span>
                            <span class="overview-value">{{ $job->location }}</span>
                        </div>
                    </div>
                    @endif

                    @if($job->employment_type)
                    <div class="overview-item">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                        </svg>
                        <div class="overview-content">
                            <span class="overview-label">Employment Type</span>
                            <span class="overview-value">{{ $job->employment_type }}</span>
                        </div>
                    </div>
                    @endif

                    @if($job->is_remote)
                    <div class="overview-item">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <div class="overview-content">
                            <span class="overview-label">Remote Work</span>
                            <span class="overview-value">Available</span>
                        </div>
                    </div>
                    @endif

                    @if($job->salary_min || $job->salary_max)
                    <div class="overview-item">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        <div class="overview-content">
                            <span class="overview-label">Salary</span>
                            <span class="overview-value">
                                @if($job->salary_min && $job->salary_max)
                                    {{ $job->currency ?? '$' }}{{ number_format($job->salary_min) }} - {{ number_format($job->salary_max) }}
                                @elseif($job->salary_min)
                                    {{ $job->currency ?? '$' }}{{ number_format($job->salary_min) }}+
                                @elseif($job->salary_max)
                                    Up to {{ $job->currency ?? '$' }}{{ number_format($job->salary_max) }}
                                @endif
                            </span>
                        </div>
                    </div>
                    @endif

                    @if($job->posted_at)
                    <div class="overview-item">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <div class="overview-content">
                            <span class="overview-label">Posted</span>
                            <span class="overview-value">{{ \Carbon\Carbon::parse($job->posted_at)->diffForHumans() }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Company Information -->
            @if($job->company)
            <div class="sidebar-section">
                <h3 class="sidebar-title">Company Information</h3>
                <div class="company-overview">
                    @if($job->company->description)
                    <p class="company-description">{{ Str::limit($job->company->description, 200) }}</p>
                    @endif
                    
                    @if($job->company->industry)
                    <div class="company-detail">
                        <span class="detail-label">Industry:</span>
                        <span class="detail-value">{{ $job->company->industry }}</span>
                    </div>
                    @endif
                    
                    @if($job->company->size)
                    <div class="company-detail">
                        <span class="detail-label">Company Size:</span>
                        <span class="detail-value">{{ $job->company->size }}</span>
                    </div>
                    @endif
                    
                    @if($job->company->founded_year)
                    <div class="company-detail">
                        <span class="detail-label">Founded:</span>
                        <span class="detail-value">{{ $job->company->founded_year }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="sidebar-section">
                <h3 class="sidebar-title">Quick Actions</h3>
                <div class="quick-actions">
                    <button class="btn btn-outline btn-sm btn-full" onclick="shareJob()">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                        </svg>
                        Share Job
                    </button>
                    
                    @if($job->apply_url)
                    <a href="{{ $job->apply_url }}" target="_blank" class="btn btn-primary btn-sm btn-full">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                        Apply on Company Site
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Related Jobs -->
    @if($relatedJobs->count() > 0)
    <div class="related-jobs-section">
        <h2 class="section-title">Related Jobs at {{ $job->company->name ?? 'This Company' }}</h2>
        <div class="related-jobs-grid">
            @foreach($relatedJobs as $relatedJob)
            <div class="related-job-card">
                <div class="related-job-header">
                    <h3 class="related-job-title">
                        <a href="{{ route('dashboard-jobs.detail', $relatedJob->id) }}">{{ $relatedJob->title }}</a>
                    </h3>
                    <p class="related-job-company">{{ $relatedJob->company->name ?? 'Company Not Specified' }}</p>
                </div>
                <div class="related-job-meta">
                    @if($relatedJob->location)
                    <span class="meta-item">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        </svg>
                        {{ $relatedJob->location }}
                    </span>
                    @endif
                    @if($relatedJob->employment_type)
                    <span class="meta-item">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                        </svg>
                        {{ $relatedJob->employment_type }}
                    </span>
                    @endif
                </div>
                <div class="related-job-actions">
                    <a href="{{ route('dashboard-jobs.detail', $relatedJob->id) }}" class="btn btn-outline btn-sm">View Details</a>
                    <button class="btn btn-primary btn-sm" onclick="applyToJob({{ $relatedJob->id }})">Apply</button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>



<style>
.dashboard-job-detail {
    max-width: 1200px;
    margin: 0 auto;
}

/* Breadcrumb */
.breadcrumb {
    margin-bottom: 2rem;
}

.breadcrumb-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: #6b7280;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s;
}

.breadcrumb-link:hover {
    color: #374151;
}

/* Job Header Section */
.job-header-section {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border: 1px solid #e2e8f0;
}

.job-header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 2rem;
}

.job-title-section {
    flex: 1;
}

.job-title {
    font-size: 2rem;
    font-weight: 700;
    color: #1f2937;
    margin: 0 0 1rem 0;
    line-height: 1.2;
}

.company-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.company-logo {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    overflow: hidden;
    flex-shrink: 0;
}

.company-logo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.company-logo-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: 700;
}

.company-details {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.company-name {
    font-size: 1.25rem;
    font-weight: 600;
    color: #374151;
    margin: 0;
}

.company-website {
    color: #667eea;
    text-decoration: none;
    font-size: 0.9rem;
}

.company-website:hover {
    text-decoration: underline;
}

.job-actions {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    align-items: flex-end;
}

/* Job Details Grid */
.job-details-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
    margin-bottom: 3rem;
}

/* Main Content */
.job-main-content {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.content-section {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border: 1px solid #e2e8f0;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 1rem 0;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #e2e8f0;
}

.job-description,
.requirements,
.benefits {
    line-height: 1.6;
    color: #374151;
}

/* Sidebar */
.job-sidebar {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.sidebar-section {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border: 1px solid #e2e8f0;
}

.sidebar-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 1rem 0;
}

.overview-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.overview-item {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}

.overview-item svg {
    color: #6b7280;
    flex-shrink: 0;
    margin-top: 0.125rem;
}

.overview-content {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.overview-label {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
}

.overview-value {
    color: #374151;
    font-weight: 600;
}

.company-overview {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.company-description {
    color: #6b7280;
    line-height: 1.5;
    margin: 0;
}

.company-detail {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.detail-label {
    color: #6b7280;
    font-weight: 500;
}

.detail-value {
    color: #374151;
    font-weight: 600;
}

.quick-actions {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.btn-full {
    width: 100%;
    justify-content: center;
}

/* Related Jobs */
.related-jobs-section {
    margin-top: 3rem;
}

.related-jobs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 1.5rem;
    margin-top: 1.5rem;
}

.related-job-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
}

.related-job-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    border-color: #667eea;
}

.related-job-header {
    margin-bottom: 1rem;
}

.related-job-title {
    margin: 0 0 0.5rem 0;
}

.related-job-title a {
    color: #1f2937;
    text-decoration: none;
    font-size: 1.125rem;
    font-weight: 600;
    transition: color 0.2s;
}

.related-job-title a:hover {
    color: #667eea;
}

.related-job-company {
    color: #6b7280;
    margin: 0;
    font-size: 0.9rem;
}

.related-job-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1rem;
    font-size: 0.875rem;
    color: #6b7280;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.related-job-actions {
    display: flex;
    gap: 0.75rem;
}

/* Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.9rem;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    justify-content: center;
}

.btn-primary {
    background: #667eea;
    color: white;
}

.btn-primary:hover {
    background: #5a67d8;
    transform: translateY(-1px);
}

.btn-outline {
    background: transparent;
    color: #667eea;
    border: 2px solid #667eea;
}

.btn-outline:hover {
    background: #667eea;
    color: white;
    transform: translateY(-1px);
}

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
}

.btn-save {
    background: transparent;
    color: #6b7280;
    border: 2px solid #e2e8f0;
}

.btn-save:hover {
    border-color: #667eea;
    color: #667eea;
}

.btn-save.active {
    border-color: #2563eb;
    color: #2563eb;
}



/* Notification Styles */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1000;
    max-width: 400px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    animation: slideInRight 0.3s ease-out;
}

.notification-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem;
}

.notification-message {
    flex: 1;
    margin-right: 1rem;
}

.notification-close {
    background: none;
    border: none;
    font-size: 1.25rem;
    cursor: pointer;
    color: inherit;
    padding: 0;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
}

.notification-close:hover {
    background: rgba(255, 255, 255, 0.2);
}

.notification-success {
    background: #10b981;
    color: white;
}

.notification-error {
    background: #ef4444;
    color: white;
}

.notification-info {
    background: #3b82f6;
    color: white;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Button States */
.btn-success {
    background: #10b981;
    border-color: #10b981;
    color: white;
}

.btn-success:hover {
    background: #059669;
    border-color: #059669;
}

.btn-success:disabled {
    background: #10b981;
    border-color: #10b981;
    opacity: 0.7;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .job-details-grid {
        grid-template-columns: 1fr;
    }
    
    .job-header-content {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .job-actions {
        align-items: stretch;
        width: 100%;
    }
    
    .btn {
        width: 100%;
    }
}

@media (max-width: 768px) {
    .dashboard-job-detail {
        padding: 0 1rem;
    }
    
    .job-header-section {
        padding: 1.5rem;
    }
    
    .job-title {
        font-size: 1.5rem;
    }
    
    .related-jobs-grid {
        grid-template-columns: 1fr;
    }
    

}
</style>

<script>
function applyToJob(jobId) {
    const applyBtn = document.getElementById('applyBtn');
    const applyBtnText = document.getElementById('applyBtnText');
    
    // Show loading state
    applyBtn.disabled = true;
    applyBtnText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Applying...';
    
    // Prepare application data with user's profile information
    const applicationData = {
        job_id: jobId,
        cover_letter: '', // Optional cover letter - can be enhanced later
        user_profile: {
            // This will be populated from the user's profile data
            skills: {{ auth()->user()->skills->count() }},
            experience: {{ auth()->user()->experience->count() }},
            education: {{ auth()->user()->education->count() }},
            resumes: {{ auth()->user()->resumes->count() }}
        }
    };
    
    // Send application directly to HR platform
    fetch('/api/jobs/' + jobId + '/apply', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(applicationData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showNotification('Application submitted successfully! Your profile has been sent to the HR team.', 'success');
            
            // Update button to show applied state
            applyBtn.classList.remove('btn-primary');
            applyBtn.classList.add('btn-success');
            applyBtnText.innerHTML = '<i class="fas fa-check mr-2"></i>Applied';
            applyBtn.disabled = true;
        } else {
            showNotification('Failed to submit application: ' + (data.message || 'Unknown error'), 'error');
            
            // Reset button
            applyBtn.disabled = false;
            applyBtnText.innerHTML = 'Apply Now';
        }
    })
    .catch(error => {
        console.error('Application error:', error);
        showNotification('An error occurred while submitting your application', 'error');
        
        // Reset button
        applyBtn.disabled = false;
        applyBtnText.innerHTML = 'Apply Now';
    });
}

function toggleSaveJob(jobId) {
    const saveBtn = document.querySelector(`[data-job-id="${jobId}"]`);
    const saveText = saveBtn.querySelector('.save-text');
    
    if (saveBtn.classList.contains('active')) {
        saveBtn.classList.remove('active');
        saveText.textContent = 'Save Job';
        // Remove from saved jobs
        fetch(`/api/saved-jobs/${jobId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
    } else {
        saveBtn.classList.add('active');
        saveText.textContent = 'Saved';
        // Add to saved jobs
        fetch('/api/saved-jobs', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ job_id: jobId })
        });
    }
}

function shareJob() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $job->title }}',
            text: 'Check out this job opportunity at {{ $job->company->name ?? "this company" }}',
            url: window.location.href
        });
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Job link copied to clipboard!');
        });
    }
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <span class="notification-message">${message}</span>
            <button class="notification-close" onclick="this.parentElement.parentElement.remove()">&times;</button>
        </div>
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}



// Check if job is already saved on page load
document.addEventListener('DOMContentLoaded', function() {
    const saveBtn = document.querySelector(`[data-job-id="{{ $job->id }}"]`);
    if (saveBtn) {
        // Check if job is in saved jobs (you might want to fetch this from an API)
        // For now, we'll assume it's not saved
    }
});
</script>
@endsection
