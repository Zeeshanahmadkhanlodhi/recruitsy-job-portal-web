@extends('layouts.app')

@section('title', $job->title . ' - ' . ($job->company->name ?? 'Company') . ' - RecruitSy')

@section('content')
<!-- Breadcrumb -->
<div class="bg-gray-50 border-b">
    <div class="container py-4">
        <nav class="breadcrumb">
            <a href="{{ route('home') }}" class="breadcrumb-link">Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('jobs') }}" class="breadcrumb-link">Jobs</a>
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-current">{{ Str::limit($job->title, 50) }}</span>
        </nav>
    </div>
</div>

<!-- Job Header -->
<div class="bg-white border-b">
    <div class="container py-8">
        <div class="job-header-detail">
            <div class="job-header-main">
                <div class="job-title-section">
                    <h1 class="job-title-main">{{ $job->title }}</h1>
                    <div class="job-company-info">
                        @if($job->company)
                            <div class="company-logo">
                                <div class="company-logo-placeholder">
                                    {{ strtoupper(substr($job->company->name, 0, 2)) }}
                                </div>
                            </div>
                            <div class="company-details">
                                <h2 class="company-name">{{ $job->company->name }}</h2>
                                @if($job->company->hr_portal_url)
                                    <a href="{{ $job->company->hr_portal_url }}" target="_blank" class="company-website">
                                        Visit Company Website
                                    </a>
                                @endif
                            </div>
                        @else
                            <div class="company-details">
                                <h2 class="company-name">Company Not Specified</h2>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="job-actions-main">
                    <button class="btn btn-primary btn-sm" onclick="openApplicationModal({{ $job->id }}, '{{ $job->title }}', '{{ $job->company->name ?? 'Company Not Specified' }}')">
                        Apply Now
                    </button>
                    <button class="btn btn-outline btn-sm" onclick="saveJob({{ $job->id }})">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                        </svg>
                        Save Job
                    </button>
                </div>
            </div>
            
            <div class="job-meta-detail">
                @if($job->location || $job->is_remote)
                    <div class="meta-item-detail">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>
                            @if($job->is_remote && $job->location)
                                {{ $job->location }} (Remote Available)
                            @elseif($job->is_remote)
                                Remote
                            @else
                                {{ $job->location }}
                            @endif
                        </span>
                    </div>
                @endif
                
                @if($job->employment_type)
                    <div class="meta-item-detail">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0H8m8 0v2a2 2 0 01-2 2H10a2 2 0 01-2-2V6m8 0v2a2 2 0 01-2 2H10a2 2 0 01-2-2V6"></path>
                        </svg>
                        <span>{{ $job->employment_type }}</span>
                    </div>
                @endif
                
                @if($job->posted_at)
                    <div class="meta-item-detail">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Posted {{ \Carbon\Carbon::parse($job->posted_at)->diffForHumans() }}</span>
                    </div>
                @endif
                
                @if($job->salary_min || $job->salary_max)
                    <div class="meta-item-detail">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        <span>
                            @if($job->salary_min && $job->salary_max)
                                ${{ number_format($job->salary_min) }} - ${{ number_format($job->salary_max) }} {{ $job->currency ?? 'USD' }}
                            @elseif($job->salary_min)
                                ${{ number_format($job->salary_min) }}+ {{ $job->currency ?? 'USD' }}
                            @elseif($job->salary_max)
                                Up to ${{ number_format($job->salary_max) }} {{ $job->currency ?? 'USD' }}
                            @endif
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Job Content -->
<div class="bg-white py-12">
    <div class="container">
        <div class="job-content-layout">
            <!-- Main Content -->
            <div class="job-content-main">
                @if($job->description)
                    <div class="content-section">
                        <h3 class="section-title">Job Description</h3>
                        <div class="job-description-content">
                            {!! nl2br(e($job->description)) !!}
                        </div>
                    </div>
                @endif
                
                @if($job->company)
                    <div class="content-section">
                        <h3 class="section-title">About {{ $job->company->name }}</h3>
                        <div class="company-description">
                            <p>We're looking for talented individuals to join our team and help us grow. If you're passionate about what you do and want to make a difference, we'd love to hear from you.</p>
                        </div>
                    </div>
                @endif
                
                @if($job->apply_url)
                    <div class="content-section">
                        <h3 class="section-title">How to Apply</h3>
                        <div class="apply-section">
                            <p>Click the "Apply Now" button above to submit your application, or visit our external application portal:</p>
                            <a href="{{ $job->apply_url }}" target="_blank" class="btn btn-outline">
                                Apply on External Portal
                            </a>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Sidebar -->
            <div class="job-content-sidebar">
                <!-- Company Card -->
                @if($job->company)
                    <div class="sidebar-card">
                        <h4 class="sidebar-card-title">Company Information</h4>
                        <div class="company-card">
                            <div class="company-logo-sidebar">
                                <div class="company-logo-placeholder-sidebar">
                                    {{ strtoupper(substr($job->company->name, 0, 2)) }}
                                </div>
                            </div>
                            <div class="company-info-sidebar">
                                <h5 class="company-name-sidebar">{{ $job->company->name }}</h5>
                                @if($job->company->hr_portal_url)
                                    <a href="{{ $job->company->hr_portal_url }}" target="_blank" class="company-link-sidebar">
                                        Visit Website
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Job Summary Card -->
                <div class="sidebar-card">
                    <h4 class="sidebar-card-title">Job Summary</h4>
                    <div class="summary-list">
                        @if($job->employment_type)
                            <div class="summary-item">
                                <span class="summary-label">Employment Type:</span>
                                <span class="summary-value">{{ $job->employment_type }}</span>
                            </div>
                        @endif
                        
                        @if($job->location)
                            <div class="summary-item">
                                <span class="summary-label">Location:</span>
                                <span class="summary-value">
                                    @if($job->is_remote)
                                        {{ $job->location }} (Remote Available)
                                    @else
                                        {{ $job->location }}
                                    @endif
                                </span>
                            </div>
                        @endif
                        
                        @if($job->posted_at)
                            <div class="summary-item">
                                <span class="summary-label">Posted:</span>
                                <span class="summary-value">{{ \Carbon\Carbon::parse($job->posted_at)->format('M d, Y') }}</span>
                            </div>
                        @endif
                        
                        @if($job->salary_min || $job->salary_max)
                            <div class="summary-item">
                                <span class="summary-label">Salary:</span>
                                <span class="summary-value">
                                    @if($job->salary_min && $job->salary_max)
                                        ${{ number_format($job->salary_min) }} - ${{ number_format($job->salary_max) }} {{ $job->currency ?? 'USD' }}
                                    @elseif($job->salary_min)
                                        ${{ number_format($job->salary_min) }}+ {{ $job->currency ?? 'USD' }}
                                    @elseif($job->salary_max)
                                        Up to ${{ number_format($job->salary_max) }} {{ $job->currency ?? 'USD' }}
                                    @endif
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Share Job Card -->
                <div class="sidebar-card">
                    <h4 class="sidebar-card-title">Share This Job</h4>
                    <div class="share-buttons">
                        <button class="share-btn share-linkedin" onclick="shareJob('linkedin')">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                            LinkedIn
                        </button>
                        <button class="share-btn share-twitter" onclick="shareJob('twitter')">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.665 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                            Twitter
                        </button>
                        <button class="share-btn share-email" onclick="shareJob('email')">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Email
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Related Jobs -->
@if($relatedJobs->count() > 0)
    <div class="bg-gray-50 py-12">
        <div class="container">
            <div class="related-jobs-section">
                <h3 class="section-title">More Jobs at {{ $job->company->name ?? 'This Company' }}</h3>
                <div class="related-jobs-grid">
                    @foreach($relatedJobs as $relatedJob)
                        <div class="related-job-card">
                            <div class="related-job-header">
                                <h4 class="related-job-title">
                                    <a href="{{ route('jobs.detail', $relatedJob->id) }}" class="related-job-link">
                                        {{ $relatedJob->title }}
                                    </a>
                                </h4>
                                <div class="related-job-type {{ strtolower($relatedJob->employment_type) === 'full-time' ? 'full-time' : (strtolower($relatedJob->employment_type) === 'contract' ? 'contract' : '') }}">
                                    {{ $relatedJob->employment_type }}
                                </div>
                            </div>
                            <div class="related-job-meta">
                                @if($relatedJob->location)
                                    <div class="related-meta-item">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $relatedJob->is_remote ? 'Remote' : $relatedJob->location }}
                                    </div>
                                @endif
                                @if($relatedJob->posted_at)
                                    <div class="related-meta-item">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ \Carbon\Carbon::parse($relatedJob->posted_at)->diffForHumans() }}
                                    </div>
                                @endif
                            </div>
                            <div class="related-job-actions">
                                <a href="{{ route('jobs.detail', $relatedJob->id) }}" class="btn btn-outline btn-sm">View Details</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Application Modal -->
<div id="applicationModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Apply for Position</h3>
            <button class="modal-close" onclick="closeApplicationModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="applicationForm" class="application-form">
                <div class="form-group">
                    <label for="fullName" class="form-label">Full Name *</label>
                    <input type="text" id="fullName" name="fullName" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="email" class="form-label">Email *</label>
                    <input type="email" id="email" name="email" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="tel" id="phone" name="phone" class="form-input">
                </div>
                <div class="form-group">
                    <label for="coverLetter" class="form-label">Cover Letter</label>
                    <textarea id="coverLetter" name="coverLetter" rows="4" class="form-textarea" placeholder="Tell us why you're interested in this position..."></textarea>
                </div>
                <div class="form-group">
                    <label for="resume" class="form-label">Resume/CV *</label>
                    <input type="file" id="resume" name="resume" class="form-input" accept=".pdf,.doc,.docx" required>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-outline" onclick="closeApplicationModal()">Cancel</button>
                                            <button type="submit" class="btn btn-primary btn-lg">Submit Application</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Breadcrumb Styles */
.breadcrumb {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
}

.breadcrumb-link {
    color: #6b7280;
    text-decoration: none;
}

.breadcrumb-link:hover {
    color: #374151;
}

.breadcrumb-separator {
    color: #9ca3af;
}

.breadcrumb-current {
    color: #374151;
    font-weight: 500;
}

/* Job Header Detail Styles */
.job-header-detail {
    max-width: 100%;
}

.job-header-main {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 2rem;
    gap: 2rem;
}

.job-title-section {
    flex: 1;
}

.job-title-main {
    font-size: 2.5rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 1rem;
    line-height: 1.2;
}

.job-company-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.company-logo {
    flex-shrink: 0;
}

.company-logo-placeholder {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1.25rem;
}

.company-details h2 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
    color: #374151;
}

.company-website {
    color: #3b82f6;
    text-decoration: none;
    font-size: 0.875rem;
}

.company-website:hover {
    text-decoration: underline;
}

.job-actions-main {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    flex-shrink: 0;
}

.job-meta-detail {
    display: flex;
    flex-wrap: wrap;
    gap: 2rem;
}

.meta-item-detail {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #6b7280;
    font-size: 0.875rem;
}

.meta-item-detail svg {
    color: #9ca3af;
}

/* Job Content Layout */
.job-content-layout {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 3rem;
}

.job-content-main {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.content-section {
    background: white;
    border-radius: 8px;
    padding: 2rem;
    border: 1px solid #e5e7eb;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #111827;
    margin-bottom: 1rem;
}

.job-description-content {
    line-height: 1.7;
    color: #374151;
}

.company-description {
    line-height: 1.6;
    color: #6b7280;
}

.apply-section {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.apply-section p {
    color: #6b7280;
    margin: 0;
}

/* Sidebar Styles */
.job-content-sidebar {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.sidebar-card {
    background: white;
    border-radius: 8px;
    padding: 1.5rem;
    border: 1px solid #e5e7eb;
}

.sidebar-card-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #111827;
    margin-bottom: 1rem;
}

.company-card {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.company-logo-placeholder-sidebar {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1rem;
}

.company-name-sidebar {
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
    color: #374151;
}

.company-link-sidebar {
    color: #3b82f6;
    text-decoration: none;
    font-size: 0.875rem;
}

.company-link-sidebar:hover {
    text-decoration: underline;
}

.summary-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f3f4f6;
}

.summary-item:last-child {
    border-bottom: none;
}

.summary-label {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
}

.summary-value {
    font-size: 0.875rem;
    color: #374151;
    font-weight: 600;
    text-align: right;
}

/* Share Buttons */
.share-buttons {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.share-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    border: none;
    border-radius: 6px;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    justify-content: center;
}

.share-linkedin {
    background: #0077b5;
    color: white;
}

.share-linkedin:hover {
    background: #005885;
}

.share-twitter {
    background: #1da1f2;
    color: white;
}

.share-twitter:hover {
    background: #0c7abf;
}

.share-email {
    background: #6b7280;
    color: white;
}

.share-email:hover {
    background: #4b5563;
}

/* Related Jobs */
.related-jobs-section {
    margin-top: 2rem;
}

.related-jobs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-top: 1.5rem;
}

.related-job-card {
    background: white;
    border-radius: 8px;
    padding: 1.5rem;
    border: 1px solid #e5e7eb;
    transition: all 0.2s;
}

.related-job-card:hover {
    border-color: #d1d5db;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.related-job-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.related-job-title {
    margin: 0;
    font-size: 1.125rem;
    font-weight: 600;
    line-height: 1.4;
}

.related-job-link {
    color: #111827;
    text-decoration: none;
}

.related-job-link:hover {
    color: #3b82f6;
}

.related-job-type {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
    flex-shrink: 0;
}

.related-job-type.full-time {
    background: #dcfce7;
    color: #166534;
}

.related-job-type.contract {
    background: #f3e8ff;
    color: #7c3aed;
}

.related-job-meta {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.related-meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #6b7280;
    font-size: 0.875rem;
}

.related-meta-item svg {
    color: #9ca3af;
}

.related-job-actions {
    display: flex;
    justify-content: flex-end;
}

/* Modal Styles */
.modal {
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background-color: white;
    border-radius: 8px;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
}

.modal-header h3 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #6b7280;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
}

.modal-close:hover {
    background: #f3f4f6;
    color: #374151;
}

.modal-body {
    padding: 1.5rem;
}

.application-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-label {
    font-weight: 500;
    color: #374151;
}

.form-input, .form-textarea {
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 0.875rem;
    transition: border-color 0.2s;
}

.form-input:focus, .form-textarea:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-textarea {
    resize: vertical;
    min-height: 100px;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 1rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .job-header-main {
        flex-direction: column;
        align-items: stretch;
    }
    
    .job-actions-main {
        flex-direction: row;
        justify-content: stretch;
    }
    
    .job-content-layout {
        grid-template-columns: 1fr;
    }
    
    .job-meta-detail {
        flex-direction: column;
        gap: 1rem;
    }
    
    .related-jobs-grid {
        grid-template-columns: 1fr;
    }
    
    .modal-content {
        width: 95%;
        margin: 1rem;
    }
    
    .form-actions {
        flex-direction: column;
    }
}

@media (max-width: 480px) {
    .job-title-main {
        font-size: 2rem;
    }
    
    .job-company-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
    }
    
    .job-actions-main {
        flex-direction: column;
    }
}
</style>

<script>
let currentJobId = null;
let currentJobTitle = '';
let currentCompanyName = '';

function openApplicationModal(jobId, jobTitle, companyName) {
    currentJobId = jobId;
    currentJobTitle = jobTitle;
    currentCompanyName = companyName;
    
    document.getElementById('modalTitle').textContent = `Apply for ${jobTitle} at ${companyName}`;
    document.getElementById('applicationModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeApplicationModal() {
    document.getElementById('applicationModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    
    // Reset form
    document.getElementById('applicationForm').reset();
}

function saveJob(jobId) {
    // Implementation for saving job
    console.log('Saving job:', jobId);
    // You can implement AJAX call here to save the job
    alert('Job saved successfully!');
}

function shareJob(platform) {
    const jobUrl = window.location.href;
    const jobTitle = '{{ $job->title }}';
    const companyName = '{{ $job->company->name ?? "Company" }}';
    
    let shareUrl = '';
    
    switch(platform) {
        case 'linkedin':
            shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(jobUrl)}`;
            break;
        case 'twitter':
            shareUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(`Check out this job: ${jobTitle} at ${companyName}`)}&url=${encodeURIComponent(jobUrl)}`;
            break;
        case 'email':
            shareUrl = `mailto:?subject=${encodeURIComponent(`Job Opportunity: ${jobTitle} at ${companyName}`)}&body=${encodeURIComponent(`I found this interesting job opportunity that might interest you:\n\n${jobTitle} at ${companyName}\n\nView the job: ${jobUrl}`)}`;
            break;
    }
    
    if (shareUrl) {
        window.open(shareUrl, '_blank');
    }
}

// Close modal when clicking outside
document.getElementById('applicationModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeApplicationModal();
    }
});

// Handle form submission
document.getElementById('applicationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Here you would typically submit the form data via AJAX
    console.log('Form submitted for job:', currentJobId);
    
    // For now, just show a success message
    alert('Application submitted successfully! We will review your application and get back to you soon.');
    closeApplicationModal();
});
</script>
@endsection
