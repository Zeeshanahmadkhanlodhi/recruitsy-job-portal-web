@extends('layouts.dashboard')

@section('title', 'Applications - RecruitSy')
@section('page-title', 'My Applications')

@section('content')
<!-- Applications Header -->
<div class="applications-header">
    <div class="header-stats">
        <div class="stat-item">
            <span class="stat-number">{{ $applications->total() }}</span>
            <span class="stat-label">Total Applications</span>
        </div>
        <div class="stat-item">
            <span class="stat-number">{{ $applications->where('status', 'pending')->count() }}</span>
            <span class="stat-label">Pending Review</span>
        </div>
        <div class="stat-item">
            <span class="stat-number">{{ $applications->where('status', 'success')->count() }}</span>
            <span class="stat-label">Successfully Forwarded</span>
        </div>
        <div class="stat-item">
            <span class="stat-number">{{ $applications->where('status', 'failed')->count() }}</span>
            <span class="stat-label">Failed to Forward</span>
        </div>
    </div>
    
    <div class="header-actions">
        <div class="filter-group">
            <label for="status-filter" class="filter-label">Filter by Status:</label>
            <select id="status-filter" class="filter-select" onchange="filterApplications(this.value)">
                <option value="">All Applications</option>
                <option value="pending">Pending Review</option>
                <option value="success">Successfully Forwarded</option>
                <option value="failed">Failed to Forward</option>
            </select>
        </div>
        <div class="search-group">
            <input type="text" id="search-input" placeholder="Search applications..." class="search-input" onkeyup="searchApplications(this.value)">
            <button class="search-btn">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </button>
        </div>
    </div>
</div>

<!-- Applications List -->
<div class="applications-container">
    @if($applications->count() > 0)
        @foreach($applications as $application)
            <div class="application-card" data-status="{{ $application->status }}" data-search="{{ strtolower($application->job->title . ' ' . $application->job->company->name) }}">
                <div class="application-header">
                    <div class="job-info">
                        <h3 class="job-title">{{ $application->job->title }}</h3>
                        <p class="company-name">{{ $application->job->company->name ?? 'Company Not Specified' }}</p>
                        <div class="job-meta">
                            @if($application->job->location)
                                <span class="location">{{ $application->job->is_remote ? 'Remote' : $application->job->location }}</span>
                            @endif
                            @if($application->job->employment_type)
                                <span class="job-type">{{ $application->job->employment_type }}</span>
                            @endif
                            @if($application->job->salary_min && $application->job->salary_max)
                                <span class="salary">{{ $application->job->currency }}{{ $application->job->salary_min }}k - {{ $application->job->salary_max }}k</span>
                            @endif
                        </div>
                    </div>
                    <div class="application-status {{ $application->status }}">
                        <span class="status-badge">
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
                        <span class="applied-date">Applied {{ $application->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                
                <div class="application-details">
                    <div class="detail-row">
                        <div class="detail-item">
                            <span class="detail-label">Application ID:</span>
                            <span class="detail-value">#APP-{{ $application->id }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Candidate:</span>
                            <span class="detail-value">{{ $application->candidate_name }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Email:</span>
                            <span class="detail-value">{{ $application->candidate_email }}</span>
                        </div>
                        @if($application->candidate_phone)
                            <div class="detail-item">
                                <span class="detail-label">Phone:</span>
                                <span class="detail-value">{{ $application->candidate_phone }}</span>
                            </div>
                        @endif
                        @if($application->resume_url)
                            <div class="detail-item">
                                <span class="detail-label">Resume:</span>
                                <span class="detail-value">
                                    <a href="{{ $application->resume_url }}" target="_blank" rel="noopener">View Resume</a>
                                </span>
                            </div>
                        @endif
                        @if($application->cover_letter)
                            <div class="detail-item">
                                <span class="detail-label">Cover Letter:</span>
                                <span class="detail-value">{{ Str::limit($application->cover_letter, 100) }}</span>
                            </div>
                        @endif
                        @if($application->hr_response)
                            <div class="detail-item">
                                <span class="detail-label">HR Platform Response:</span>
                                <span class="detail-value">
                                    <button class="btn btn-sm btn-outline" onclick="showHrResponse({{ $application->id }})">View Response</button>
                                </span>
                            </div>
                        @endif
                        @if($application->error_message)
                            <div class="detail-item">
                                <span class="detail-label">Error:</span>
                                <span class="detail-value text-red-600">{{ $application->error_message }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="application-actions">
                    <a href="{{ route('jobs.detail', $application->job->id) }}" class="btn btn-outline btn-sm">View Job Details</a>
                    @if($application->status === 'failed')
                        <button class="btn btn-primary btn-sm" onclick="retryApplication({{ $application->id }})">Retry Forwarding</button>
                    @endif
                </div>
            </div>
        @endforeach
    @else
        <div class="application-card">
            <div class="application-header">
                <div class="job-info">
                    <h3 class="job-title">No Applications Yet</h3>
                    <p class="company-name">Start applying to jobs to see your applications here</p>
                </div>
            </div>
            <div class="application-actions">
                <a href="{{ route('jobs') }}" class="btn btn-primary btn-sm">Browse Jobs</a>
            </div>
        </div>
    @endif
</div>

<!-- Pagination -->
@if($applications->hasPages())
    <div class="pagination">
        @if($applications->onFirstPage())
            <button class="pagination-btn" disabled>Previous</button>
        @else
            <a href="{{ $applications->previousPageUrl() }}" class="pagination-btn">Previous</a>
        @endif
        
        <div class="pagination-pages">
            @foreach(range(1, $applications->lastPage()) as $page)
                @if($page == $applications->currentPage())
                    <span class="page-btn active">{{ $page }}</span>
                @else
                    <a href="{{ $applications->url($page) }}" class="page-btn">{{ $page }}</a>
                @endif
            @endforeach
        </div>
        
        @if($applications->hasMorePages())
            <a href="{{ $applications->nextPageUrl() }}" class="pagination-btn">Next</a>
        @else
            <button class="pagination-btn" disabled>Next</button>
        @endif
    </div>
@endif

<!-- HR Response Modal -->
<div id="hrResponseModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>HR Platform Response</h3>
            <span class="close" onclick="closeHrResponseModal()">&times;</span>
        </div>
        <div class="modal-body">
            <div id="hrResponseContent"></div>
        </div>
        <div class="modal-actions">
            <button type="button" class="btn btn-primary btn-sm" onclick="closeHrResponseModal()">Close</button>
        </div>
    </div>
</div>

<script>
function filterApplications(status) {
    const applications = document.querySelectorAll('.application-card');
    applications.forEach(card => {
        if (status === '' || card.dataset.status === status) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

function searchApplications(query) {
    const applications = document.querySelectorAll('.application-card');
    const searchTerm = query.toLowerCase();
    
    applications.forEach(card => {
        const searchText = card.dataset.search;
        if (searchText.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

function showHrResponse(applicationId) {
    // This would typically fetch the HR response from the server
    // For now, we'll show a placeholder
    document.getElementById('hrResponseContent').innerHTML = `
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <h4 class="font-semibold text-green-800 mb-2">Application Successfully Processed</h4>
            <p class="text-green-700">Your application has been received and is under review by the HR team.</p>
        </div>
    `;
    document.getElementById('hrResponseModal').style.display = 'block';
}

function closeHrResponseModal() {
    document.getElementById('hrResponseModal').style.display = 'none';
}

function retryApplication(applicationId) {
    if (confirm('Are you sure you want to retry forwarding this application?')) {
        // This would typically make an API call to retry the application
        alert('Retry functionality will be implemented soon.');
    }
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('hrResponseModal');
    if (event.target === modal) {
        closeHrResponseModal();
    }
}
</script>

<style>
/* Applications Header */
.applications-header {
    background: white;
    border-radius: 0.75rem;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.header-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.stat-item {
    text-align: center;
    padding: 1rem;
    background: #f9fafb;
    border-radius: 0.5rem;
}

.stat-number {
    display: block;
    font-size: 1.5rem;
    font-weight: bold;
    color: #2563eb;
}

.stat-label {
    font-size: 0.875rem;
    color: #6b7280;
}

.header-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
    flex-wrap: wrap;
}

.filter-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.filter-label {
    font-size: 0.875rem;
    color: #374151;
    font-weight: 500;
}

.filter-select {
    padding: 0.5rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    background: white;
}

.search-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.search-input {
    padding: 0.5rem 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    width: 250px;
}

.search-btn {
    padding: 0.5rem;
    background: #2563eb;
    color: white;
    border: none;
    border-radius: 0.375rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.search-btn:hover {
    background: #1d4ed8;
}

/* Applications Container */
.applications-container {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.application-card {
    background: white;
    border-radius: 0.75rem;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    border: 1px solid #e5e7eb;
    transition: all 0.3s;
}

.application-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    border-color: #d1d5db;
}

.application-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.job-info {
    flex: 1;
}

.job-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.company-name {
    color: #2563eb;
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.job-meta {
    display: flex;
    gap: 1rem;
    color: #6b7280;
    font-size: 0.875rem;
}

.application-status {
    text-align: right;
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
    margin-bottom: 0.25rem;
}

.status-badge.pending {
    background: #fef3c7;
    color: #d97706;
}

.status-badge.interview {
    background: #dcfce7;
    color: #16a34a;
}

.status-badge.rejected {
    background: #fee2e2;
    color: #dc2626;
}

.applied-date {
    display: block;
    font-size: 0.75rem;
    color: #9ca3af;
}

/* Application Details */
.application-details {
    background: #f9fafb;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 1rem;
}

.detail-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.detail-label {
    font-size: 0.75rem;
    color: #6b7280;
    font-weight: 500;
}

.detail-value {
    font-size: 0.875rem;
    color: #374151;
}

/* Application Actions */
.application-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

/* Pagination */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.5rem;
    margin-top: 2rem;
}

.pagination-btn {
    padding: 0.5rem 1rem;
    border: 1px solid #d1d5db;
    background: white;
    color: #374151;
    border-radius: 0.375rem;
    cursor: pointer;
    font-size: 0.875rem;
}

.pagination-btn:hover:not(:disabled) {
    background: #f9fafb;
}

.pagination-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.pagination-pages {
    display: flex;
    gap: 0.25rem;
}

.page-btn {
    width: 2.5rem;
    height: 2.5rem;
    border: 1px solid #d1d5db;
    background: white;
    color: #374151;
    border-radius: 0.375rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
}

.page-btn:hover {
    background: #f9fafb;
}

.page-btn.active {
    background: #2563eb;
    color: white;
    border-color: #2563eb;
}

/* Responsive Design */
@media (max-width: 768px) {
    .header-actions {
        flex-direction: column;
        align-items: stretch;
    }
    
    .search-input {
        width: 100%;
    }
    
    .application-header {
        flex-direction: column;
        gap: 1rem;
    }
    
    .application-status {
        text-align: left;
    }
    
    .detail-row {
        grid-template-columns: 1fr;
    }
    
    .application-actions {
        flex-direction: column;
    }
    
    .application-actions .btn {
        width: 100%;
    }
}
</style>
@endsection 