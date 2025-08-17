@extends('layouts.dashboard')

@section('title', 'Saved Jobs - RecruitSy')
@section('page-title', 'Saved Jobs')

@section('content')
<!-- Saved Jobs Header -->
<div class="saved-jobs-header">
    <div class="header-info">
        <h2 class="header-title">Your Saved Jobs</h2>
        <p class="header-subtitle">Keep track of jobs you're interested in</p>
    </div>
    
    <div class="header-actions">
        <div class="filter-group">
            <label for="sort-filter" class="filter-label">Sort by:</label>
            <select id="sort-filter" class="filter-select">
                <option value="recent">Recently Saved</option>
                <option value="title">Job Title</option>
                <option value="company">Company</option>
                <option value="location">Location</option>
                <option value="salary">Salary</option>
            </select>
        </div>
        <div class="search-group">
            <input type="text" placeholder="Search saved jobs..." class="search-input">
            <button class="search-btn">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </button>
        </div>
    </div>
</div>

<!-- Saved Jobs Grid -->
<div id="saved-jobs-grid" class="saved-jobs-grid"></div>

<!-- Empty State (hidden by default) -->
<div id="empty-state" class="empty-state" style="display: none;">
    <div class="empty-icon">
        <svg width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
        </svg>
    </div>
    <h3 class="empty-title">No saved jobs yet</h3>
    <p class="empty-description">Start saving jobs you're interested in to keep track of them here.</p>
                    <a href="{{ route('jobs') }}" class="btn btn-primary btn-sm">Browse Jobs</a>
</div>

<style>
/* Saved Jobs Header */
.saved-jobs-header {
    background: white;
    border-radius: 0.75rem;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.header-info {
    margin-bottom: 1.5rem;
}

.header-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.header-subtitle {
    color: #6b7280;
    font-size: 1rem;
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

/* Saved Jobs Grid */
.saved-jobs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 1.5rem;
}

.job-card {
    background: white;
    border-radius: 0.75rem;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    border: 1px solid #e5e7eb;
    transition: all 0.3s;
    position: relative;
}

.job-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    border-color: #d1d5db;
}

.job-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.job-title-section {
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
}

.job-actions {
    display: flex;
    gap: 0.5rem;
}

.action-btn {
    padding: 0.5rem;
    background: none;
    border: none;
    border-radius: 0.375rem;
    cursor: pointer;
    color: #6b7280;
    transition: all 0.3s;
}

.action-btn:hover {
    background: #f3f4f6;
    color: #dc2626;
}

.unsave-btn {
    color: #f59e0b;
}

.unsave-btn:hover {
    color: #dc2626;
}

/* Job Meta */
.job-meta {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #6b7280;
    font-size: 0.875rem;
}

.meta-item svg {
    flex-shrink: 0;
}

/* Job Tags */
.job-tags {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 1rem;
}

.tag {
    background: #e5e7eb;
    color: #374151;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

/* Job Description */
.job-description {
    color: #6b7280;
    font-size: 0.875rem;
    line-height: 1.5;
    margin-bottom: 1rem;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Job Footer */
.job-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.saved-date {
    font-size: 0.75rem;
    color: #9ca3af;
}

.job-buttons {
    display: flex;
    gap: 0.5rem;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.empty-icon {
    color: #9ca3af;
    margin-bottom: 1rem;
}

.empty-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.empty-description {
    color: #6b7280;
    margin-bottom: 1.5rem;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .saved-jobs-grid {
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    }
}

@media (max-width: 768px) {
    .header-actions {
        flex-direction: column;
        align-items: stretch;
    }
    
    .search-input {
        width: 100%;
    }
    
    .saved-jobs-grid {
        grid-template-columns: 1fr;
    }
    
    .job-footer {
        flex-direction: column;
        align-items: stretch;
    }
    
    .job-buttons {
        flex-direction: column;
    }
    
    .job-buttons .btn {
        width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', async function() {
    const grid = document.getElementById('saved-jobs-grid');
    const empty = document.getElementById('empty-state');

    function sanitize(text) { return (text ?? '').toString(); }

    function card(j) {
        const tags = Array.isArray(j.tags) ? j.tags : [];
        return `
        <div class="job-card" data-id="${j.id}">
            <div class="job-header">
                <div class="job-title-section">
                    <h3 class="job-title">${sanitize(j.title)}</h3>
                    <p class="company-name">${sanitize(j.company_name)}</p>
                </div>
                <div class="job-actions">
                    <button class="action-btn unsave-btn" title="Remove from saved">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="job-meta">
                ${j.location ? `<div class="meta-item"><span>${sanitize(j.location)}</span></div>` : ''}
                ${j.employment_type ? `<div class="meta-item"><span>${sanitize(j.employment_type)}</span></div>` : ''}
            </div>
            <div class="job-tags">${tags.slice(0,5).map(t => `<span class="tag">${sanitize(t)}</span>`).join('')}</div>
            ${j.short_description ? `<p class="job-description">${sanitize(j.short_description).slice(0,220)}${sanitize(j.short_description).length>220?'…':''}</p>` : ''}
            <div class="job-footer">
                <span class="saved-date">Saved ${new Date(j.saved_at || j.created_at).toLocaleDateString()}</span>
                <div class="job-buttons">
                    ${j.apply_url ? `<a class="btn btn-primary btn-sm" href="${sanitize(j.apply_url)}" target="_blank" rel="noopener">Apply Now</a>` : ''}
                </div>
            </div>
        </div>`;
    }

    async function loadSaved() {
        try {
            const res = await window.axios.get('/api/saved-jobs');
            const data = res.data || {};
            const items = Array.isArray(data.data) ? data.data : [];
            grid.innerHTML = '';
            if (items.length === 0) {
                empty.style.display = '';
                return;
            }
            empty.style.display = 'none';
            grid.insertAdjacentHTML('beforeend', items.map(card).join(''));
        } catch (err) {
            console.error('Failed to load saved jobs', err);
            empty.style.display = '';
        }
    }

    document.addEventListener('click', async function(e) {
        const btn = e.target.closest('.unsave-btn');
        if (!btn) return;
        const cardEl = btn.closest('.job-card');
        const id = cardEl?.getAttribute('data-id');
        if (!id) return;
        try {
            await window.axios.delete(`/api/saved-jobs/${id}`);
            cardEl.remove();
            if (!grid.children.length) empty.style.display = '';
        } catch (err) {
            console.error('Failed to remove saved job', err);
            alert('Failed to remove.');
        }
    });

    await loadSaved();
});
</script>
@endsection 