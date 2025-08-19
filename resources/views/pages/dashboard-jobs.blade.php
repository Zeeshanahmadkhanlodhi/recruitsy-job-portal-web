@extends('layouts.dashboard')

@section('content')
<div class="dashboard-jobs">
    <!-- Header Section -->
    <div class="jobs-header">
        <div class="header-content">
            <h1>Find Your Next Job</h1>
            <p>Browse through thousands of job opportunities and find the perfect match for your skills</p>
        </div>
        <div class="header-actions">
            <!-- Post a Job button removed -->
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="search-filters">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search jobs, companies, or keywords...">
            <button class="search-btn">Search</button>
        </div>
        
        <div class="filters">
            <div class="filter-group">
                <label>Location</label>
                <select>
                    <option>All Locations</option>
                    <option>Remote</option>
                    <option>New York</option>
                    <option>San Francisco</option>
                    <option>London</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label>Job Type</label>
                <select>
                    <option>All Types</option>
                    <option>Full-time</option>
                    <option>Part-time</option>
                    <option>Contract</option>
                    <option>Internship</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label>Experience Level</label>
                <select>
                    <option>All Levels</option>
                    <option>Entry Level</option>
                    <option>Mid Level</option>
                    <option>Senior Level</option>
                    <option>Executive</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label>Salary Range</label>
                <select>
                    <option>All Ranges</option>
                    <option>$0 - $50k</option>
                    <option>$50k - $100k</option>
                    <option>$100k - $150k</option>
                    <option>$150k+</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Results Summary -->
    <div class="results-summary">
        <div class="results-info">
            <span class="results-count" id="results-count">Showing {{ $jobs->total() }} jobs</span>
            <span class="results-sort">
                Sort by: 
                <select id="sort-by">
                    <option value="date">Most Recent</option>
                    <option value="relevance">Relevance</option>
                </select>
            </span>
        </div>
        <div class="view-toggle">
            <button class="view-btn active" data-view="grid">
                <i class="fas fa-th"></i>
            </button>
            <button class="view-btn" data-view="list">
                <i class="fas fa-list"></i>
            </button>
        </div>
    </div>

    <!-- Job Listings -->
    <div class="job-listings" id="job-listings">
        @if($jobs->count() > 0)
            @foreach($jobs as $job)
                <div class="job-card">
                    <div class="job-header">
                        <div class="job-info">
                                                    <h3 class="job-title">
                            <a href="{{ route('dashboard-jobs.detail', $job->id) }}" class="job-title-link">{{ $job->title }}</a>
                        </h3>
                            <p class="company-name">{{ $job->company->name ?? 'Company Not Specified' }}</p>
                        </div>
                        @if($job->employment_type)
                            <div class="job-type {{ strtolower($job->employment_type) === 'full-time' ? 'full-time' : (strtolower($job->employment_type) === 'contract' ? 'contract' : '') }}">
                                {{ $job->employment_type }}
                            </div>
                        @endif
                    </div>
                    <div class="job-description">
                        @if($job->description)
                            <p>{{ Str::limit($job->description, 200) }}</p>
                        @else
                            <p>No description available</p>
                        @endif
                    </div>
                    <div class="job-meta">
                        @if($job->location)
                            <div class="meta-item">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $job->is_remote ? 'Remote' : $job->location }}
                            </div>
                        @endif
                        @if($job->posted_at)
                            <div class="meta-item">
                                <i class="fas fa-clock"></i>
                                {{ \Carbon\Carbon::parse($job->posted_at)->diffForHumans() }}
                            </div>
                        @endif
                        @if($job->salary_min || $job->salary_max)
                            <div class="meta-item">
                                <i class="fas fa-dollar-sign"></i>
                                @if($job->salary_min && $job->salary_max)
                                    ${{ number_format($job->salary_min) }} - ${{ number_format($job->salary_max) }} {{ $job->currency ?? 'USD' }}
                                @elseif($job->salary_min)
                                    ${{ number_format($job->salary_min) }}+ {{ $job->currency ?? 'USD' }}
                                @elseif($job->salary_max)
                                    Up to ${{ number_format($job->salary_max) }} {{ $job->currency ?? 'USD' }}
                                @endif
                            </div>
                        @endif
                    </div>
                                                    <div class="job-actions">
                                    <a href="{{ route('dashboard-jobs.detail', $job->id) }}" class="btn btn-outline btn-sm">View Details</a>
                                    <button class="btn btn-primary btn-sm" onclick="applyToJob({{ $job->id }}, this)" id="applyBtn{{ $job->id }}">Apply Now</button>
                                </div>
                </div>
            @endforeach
        @else
            <div class="job-card">
                <div class="job-header">
                    <div class="job-info">
                        <h3 class="job-title">No jobs found</h3>
                        <p class="company-name">Please check back later</p>
                    </div>
                </div>
                <div class="job-description">
                    <p>We don't have any job openings at the moment. Please check back later for new opportunities.</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($jobs->hasPages())
        <div class="pagination">
            @if($jobs->onFirstPage())
                <button class="page-btn prev" disabled>
                    <i class="fas fa-chevron-left"></i>
                    Previous
                </button>
            @else
                <a href="{{ $jobs->previousPageUrl() }}" class="page-btn prev">
                    <i class="fas fa-chevron-left"></i>
                    Previous
                </a>
            @endif
            
            <div class="page-numbers">
                <span class="page-btn active">{{ $jobs->currentPage() }} / {{ $jobs->lastPage() }}</span>
            </div>
            
            @if($jobs->hasMorePages())
                <a href="{{ $jobs->nextPageUrl() }}" class="page-btn next">
                    Next
                    <i class="fas fa-chevron-right"></i>
                </a>
            @else
                <button class="page-btn next" disabled>
                    Next
                    <i class="fas fa-chevron-right"></i>
                </button>
            @endif
        </div>
    @endif
</div>

<style>
.dashboard-jobs {
    padding: 0;
    background: #f8fafc;
    min-height: 100vh;
}

/* Header Section */
.jobs-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.header-content h1 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.header-content p {
    font-size: 1.1rem;
    opacity: 0.9;
    margin: 0;
}

.header-actions .btn {
    background: rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.header-actions .btn:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
}

/* Search and Filters */
.search-filters {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
}

.search-box {
    display: flex;
    align-items: center;
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 0.75rem 1rem;
    margin-bottom: 1.5rem;
    transition: border-color 0.3s ease;
}

.search-box:focus-within {
    border-color: #667eea;
}

.search-box i {
    color: #64748b;
    margin-right: 0.75rem;
    font-size: 1.1rem;
}

.search-box input {
    flex: 1;
    border: none;
    background: none;
    font-size: 1rem;
    outline: none;
}

.search-btn {
    background: #667eea;
    color: white;
    border: none;
    padding: 0.5rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s ease;
}

.search-btn:hover {
    background: #5a67d8;
}

.filters {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.filter-group label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.filter-group select {
    padding: 0.75rem;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    background: white;
    font-size: 0.9rem;
    transition: border-color 0.3s ease;
}

.filter-group select:focus {
    outline: none;
    border-color: #667eea;
}

/* Results Summary */
.results-summary {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding: 0 0.5rem;
}

.results-count {
    font-weight: 600;
    color: #374151;
}

.results-sort {
    color: #6b7280;
    font-size: 0.9rem;
}

.results-sort select {
    border: none;
    background: none;
    color: #667eea;
    font-weight: 600;
    cursor: pointer;
    margin-left: 0.5rem;
}

.view-toggle {
    display: flex;
    gap: 0.5rem;
}

.view-btn {
    background: white;
    border: 2px solid #e2e8f0;
    padding: 0.5rem;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    color: #6b7280;
}

.view-btn.active,
.view-btn:hover {
    border-color: #667eea;
    color: #667eea;
}

/* Job Listings */
.job-listings {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.job-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    border: 1px solid #e2e8f0;
    display: flex;
    flex-direction: column;
}

.job-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    border-color: #667eea;
}

.job-header {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1rem;
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

.job-info {
    flex: 1;
    min-width: 0;
}

.job-title {
    margin: 0 0 0.25rem 0;
}

.job-title-link {
    color: #1f2937;
    text-decoration: none;
    transition: color 0.2s;
    cursor: pointer;
    display: inline-block;
    padding: 0.25rem 0;
    font-size: 1.25rem;
    font-weight: 700;
}

.job-title-link:hover {
    color: #3b82f6;
    text-decoration: underline;
}

.company-name {
    color: #6b7280;
    font-weight: 600;
    margin: 0 0 0.75rem 0;
}

.job-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    font-size: 0.9rem;
    color: #6b7280;
    margin-bottom: 1rem;
}

.job-meta span {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.job-actions {
    display: flex;
    flex-direction: row;
    gap: 1rem;
    align-items: center;
    flex-wrap: wrap;
    margin-top: auto;
    padding-top: 1rem;
    border-top: 1px solid #e2e8f0;
}

.btn-save { background: none; border: none; color: #6b7280; font-size: 1.2rem; cursor: pointer; transition: color 0.2s ease; padding: 0.25rem; }
.btn-save:hover { color: #374151; }
.btn-save.active, .btn-save.active i { color: #2563eb; }

/* Button Success State */
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

/* Button Warning State */
.btn-warning {
    background: #f59e0b;
    border-color: #f59e0b;
    color: white;
}

.btn-warning:hover {
    background: #d97706;
    border-color: #d97706;
}

.btn-warning:disabled {
    background: #f59e0b;
    border-color: #f59e0b;
    opacity: 0.7;
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

.notification-warning {
    background: #f59e0b;
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

/* Button styles are now handled by the main CSS file */

.job-description {
    margin-bottom: 1rem;
}

.job-description p {
    color: #6b7280;
    line-height: 1.6;
    margin-bottom: 1rem;
}

.job-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.tag {
    background: #f3f4f6;
    color: #374151;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.job-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1rem;
    border-top: 1px solid #e2e8f0;
    font-size: 0.85rem;
    color: #6b7280;
}

/* Pagination */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1rem;
    margin-top: 2rem;
}

.page-btn {
    background: white;
    border: 2px solid #e2e8f0;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
    color: #374151;
}

.page-btn:hover:not(:disabled) {
    border-color: #667eea;
    color: #667eea;
}

.page-btn.active {
    background: #667eea;
    border-color: #667eea;
    color: white;
}

.page-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.page-numbers {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.page-dots {
    color: #6b7280;
    padding: 0 0.5rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .jobs-header {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .header-content h1 {
        font-size: 2rem;
    }
    
    .filters {
        grid-template-columns: 1fr;
    }
    
    .job-listings {
        grid-template-columns: 1fr;
    }
    
    .job-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .job-actions {
        flex-direction: row;
        width: 100%;
        justify-content: space-between;
        margin-top: 1rem;
    }
    
    .results-summary {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .pagination {
        flex-wrap: wrap;
    }
}
</style>

<!-- JavaScript removed - now using server-side rendering with local database -->
<!-- <script>
document.addEventListener('DOMContentLoaded', function() {
    const listingsEl = document.getElementById('job-listings');
    const resultsCountEl = document.getElementById('results-count');
    const btnPrev = document.getElementById('btn-prev');
    const btnNext = document.getElementById('btn-next');
    const pageIndicator = document.getElementById('page-indicator');
    const qs = new URLSearchParams(window.location.search);
    const sortSelect = document.getElementById('sort-by');

    const apiUrl = '/external-jobs'; // proxy to Findwork

    function timeAgo(iso) {
        try {
            const date = new Date(iso);
            const diffMs = Date.now() - date.getTime();
            const sec = Math.floor(diffMs / 1000);
            const min = Math.floor(sec / 60);
            const hr = Math.floor(min / 60);
            const day = Math.floor(hr / 24);
            if (day > 0) return `${day} day${day>1?'s':''} ago`;
            if (hr > 0) return `${hr} hour${hr>1?'s':''} ago`;
            if (min > 0) return `${min} minute${min>1?'s':''} ago`;
            return 'just now';
        } catch (_) { return ''; }
    }

    function sanitize(text) { return (text ?? '').toString(); }

    function cardTemplate(job) {
        const title = sanitize(job.title);
        const company = sanitize(job.company_name);
        const location = sanitize(job.location);
        const type = sanitize(job.employment_type);
        const desc = sanitize(job.short_description).slice(0, 220);
        const posted = timeAgo(job.created_at);
        const tags = Array.isArray(job.tags) ? job.tags : [];
        const applyUrl = job.apply_url || '#';
        return `
        <div class="job-card">
            <div class="job-header">
                <div class="job-info">
                    <h3 class="job-title">${title}</h3>
                    <p class="company-name">${company}</p>
                    <div class="job-meta">
                        ${location ? `<span class="location"><i class="fas fa-map-marker-alt"></i> ${location}</span>` : ''}
                        ${type ? `<span class="type"><i class="fas fa-clock"></i> ${type}</span>` : ''}
                    </div>
                </div>
                <div class="job-actions">
                    <button class="btn-save" data-payload='${JSON.stringify({
                        source: 'findwork',
                        external_id: job.id,
                        title,
                        company_name: company,
                        location,
                        employment_type: type,
                        apply_url: applyUrl,
                        short_description: desc,
                        tags
                    }).replace(/'/g, '&apos;')}' title="Save Job">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16l-7-3.5L5 21V5z"></path>
                        </svg>
                    </button>
                    <a href="${applyUrl}" target="_blank" rel="noopener" class="btn-apply">Apply Now</a>
                </div>
            </div>
            ${desc ? `<div class="job-description"><p>${desc}${desc.length >= 220 ? '…' : ''}</p></div>` : ''}
            <div class="job-tags">${tags.slice(0,5).map(t => `<span class="tag">${sanitize(t)}</span>`).join('')}</div>
            <div class="job-footer">
                <span class="posted-date">${posted}</span>
            </div>
        </div>`;
    }

    async function load(pageUrl = null) {
        listingsEl.innerHTML = document.getElementById('jobs-loading').outerHTML;
        const params = new URLSearchParams();
        const search = qs.get('search'); if (search) params.set('search', search);
        const location = qs.get('location'); if (location) params.set('location', location);
        const remote = qs.get('remote'); if (remote) params.set('remote', remote);
        const employment = qs.get('employment_type'); if (employment) params.set('employment_type', employment);
        const sortBy = sortSelect?.value || 'date'; if (sortBy) params.set('sort_by', sortBy);
        const url = pageUrl || `${apiUrl}?${params.toString()}`;
        try {
            const res = await window.axios.get(url);
            const data = res.data || {};
            const list = Array.isArray(data.data) ? data.data : (Array.isArray(data.results) ? data.results : []);
            const total = data.total ?? data.count ?? list.length;
            resultsCountEl.textContent = `Showing ${total} jobs`;
            listingsEl.innerHTML = '';
            if (list.length === 0) {
                listingsEl.innerHTML = '<p>No jobs found.</p>';
            } else {
                listingsEl.insertAdjacentHTML('beforeend', list.map(cardTemplate).join(''));
            }
            // Pagination
            btnPrev.disabled = !data.prev_page_url;
            btnNext.disabled = !data.next_page_url;
            pageIndicator.textContent = data.current_page ? data.current_page : (btnNext.disabled && btnPrev.disabled ? '1' : '');
            btnPrev.onclick = (e) => { e.preventDefault(); if (data.prev_page_url) load(data.prev_page_url); };
            btnNext.onclick = (e) => { e.preventDefault(); if (data.next_page_url) load(data.next_page_url); };
        } catch (err) {
            console.error(err);
            listingsEl.innerHTML = '<p>Failed to load jobs. Please try again.</p>';
        }
    }

    // Initial load
    load();

    // Existing view toggle functionality
    const viewBtns = document.querySelectorAll('.view-btn');
    const jobListings = document.querySelector('.job-listings');
    
    viewBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            viewBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const view = this.dataset.view;
            if (view === 'list') {
                jobListings.style.gridTemplateColumns = '1fr';
            } else {
                jobListings.style.gridTemplateColumns = 'repeat(auto-fill, minmax(400px, 1fr))';
            }
        });
    });
    
    // Save job functionality (delegated)
    document.addEventListener('click', async function(e) {
        const btn = e.target.closest('.btn-save');
        if (!btn) return;
        const payloadAttr = btn.getAttribute('data-payload');
        if (!payloadAttr) return;
        try {
            const payload = JSON.parse(payloadAttr.replaceAll('&apos;', '\''));
            const res = await window.axios.post('/api/saved-jobs', payload);
            btn.classList.add('active');
            const icon = btn.querySelector('i');
            if (icon) icon.className = 'fas fa-bookmark';
        } catch (err) {
            console.error('Failed to save job', err);
            alert('Failed to save job. Please try again.');
        }
    });
    
    // Apply button functionality
    const applyBtns = document.querySelectorAll('.btn-apply');
    applyBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            this.textContent = 'Applied';
            this.style.background = '#10b981';
            this.disabled = true;
        });
    });
});
</script> -->



<style>

</style>

<script>
function applyToJob(jobId, buttonElement) {
    // Show loading state
    buttonElement.disabled = true;
    buttonElement.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Applying...';
    
    // Prepare application data with user's profile information
    const applicationData = {
        job_id: jobId,
        // Provide required schema fields so backend can save application
        candidate_name: @json(auth()->user()->full_name ?? auth()->user()->name ?? ''),
        candidate_email: @json(auth()->user()->email ?? ''),
        candidate_phone: @json(auth()->user()->phone ?? ''),
        resume_url: @json(optional(auth()->user()->primaryResume)->file_path ?? ''),
        cover_letter: '', // Optional cover letter - can be enhanced later
        user_profile: {
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
            buttonElement.classList.remove('btn-primary');
            buttonElement.classList.add('btn-success');
            buttonElement.innerHTML = '<i class="fas fa-check mr-2"></i>Applied';
            buttonElement.disabled = true;
        } else {
            // Check if this is a forwarding failure that can be retried
            if (data.application_id && data.message && data.message.includes('HR Platform')) {
                showNotification('Application saved but failed to reach HR team. You can retry later.', 'warning');
                
                // Update button to show retry option
                buttonElement.classList.remove('btn-primary');
                buttonElement.classList.add('btn-warning');
                buttonElement.innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i>Retry';
                buttonElement.onclick = () => retryApplication(data.application_id, buttonElement);
            } else {
                showNotification('Failed to submit application: ' + (data.message || 'Unknown error'), 'error');
                
                // Reset button
                buttonElement.disabled = false;
                buttonElement.innerHTML = 'Apply Now';
            }
        }
    })
    .catch(error => {
        console.error('Application error:', error);
        showNotification('An error occurred while submitting your application', 'error');
        
        // Reset button
        buttonElement.disabled = false;
        buttonElement.innerHTML = 'Apply Now';
    });
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

function retryApplication(applicationId, buttonElement) {
    // Show loading state
    buttonElement.disabled = true;
    buttonElement.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Retrying...';
    
    // Send retry request
    fetch('/api/applications/' + applicationId + '/retry', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Application retried successfully!', 'success');
            
            // Update button to show applied state
            buttonElement.classList.remove('btn-warning');
            buttonElement.classList.add('btn-success');
            buttonElement.innerHTML = '<i class="fas fa-check mr-2"></i>Applied';
            buttonElement.disabled = true;
        } else {
            showNotification('Retry failed: ' + (data.message || 'Unknown error'), 'error');
            
            // Reset button to retry again
            buttonElement.disabled = false;
            buttonElement.innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i>Retry';
        }
    })
    .catch(error => {
        console.error('Retry error:', error);
        showNotification('An error occurred while retrying the application', 'error');
        
        // Reset button
        buttonElement.disabled = false;
        buttonElement.innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i>Retry';
    });
}
</script>
@endsection 