@extends('admin.layouts.app')

@section('title', 'Jobs Management')
@section('page-title', 'Jobs Management')

@section('content')
<div class="jobs-container">
    <div class="header-actions">
        <h2>Jobs</h2>
        <div class="search-filter">
            <input type="text" id="searchInput" placeholder="Search jobs..." class="search-input">
            <select id="statusFilter" class="status-filter">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="expired">Expired</option>
            </select>
            <select id="companyFilter" class="company-filter">
                <option value="">All Companies</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="jobs-table">
        <table>
            <thead>
                <tr>
                    <th>Job Title</th>
                    <th>Company</th>
                    <th>Location</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Applications</th>
                    <th>Posted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jobs as $job)
                <tr>
                    <td>
                        <div class="job-info">
                            <div class="job-title">{{ $job->title }}</div>
                            <div class="job-salary">
                                @if($job->salary_min && $job->salary_max)
                                    ${{ number_format($job->salary_min) }} - ${{ number_format($job->salary_max) }}
                                @elseif($job->salary_min)
                                    From ${{ number_format($job->salary_min) }}
                                @else
                                    Salary not specified
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="company-info">
                            <div class="company-name">{{ $job->company->name }}</div>
                            <div class="company-location">{{ $job->company->location ?? 'Location not specified' }}</div>
                        </div>
                    </td>
                    <td>{{ $job->location ?? 'Remote' }}</td>
                    <td>
                        <span class="job-type-badge job-type-{{ strtolower($job->employment_type ?? 'full-time') }}">
                            {{ ucfirst($job->employment_type ?? 'Full-time') }}
                        </span>
                    </td>
                    <td>
                        <span class="status-badge status-{{ $job->is_active ? 'active' : 'inactive' }}">
                            {{ $job->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <span class="applications-count">
                            {{ $job->applications_count ?? 0 }} applications
                        </span>
                    </td>
                    <td>{{ $job->created_at->format('M j, Y') }}</td>
                    <td class="actions">
                        <a href="{{ route('admin.jobs.show', $job) }}" class="btn btn-sm btn-info" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.jobs.edit', $job) }}" class="btn btn-sm btn-warning" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.jobs.toggle-status', $job) }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-{{ $job->is_active ? 'warning' : 'success' }}" title="{{ $job->is_active ? 'Deactivate' : 'Activate' }}">
                                <i class="fas fa-{{ $job->is_active ? 'ban' : 'check' }}"></i>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.jobs.destroy', $job) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this job?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="empty-state">
                        <i class="fas fa-briefcase"></i>
                        <p>No jobs found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $jobs->links() }}
    </div>
</div>

<style>
.jobs-container {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.header-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.header-actions h2 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1f2937;
}

.search-filter {
    display: flex;
    gap: 1rem;
    align-items: center;
    flex-wrap: wrap;
}

.search-input, .status-filter, .company-filter {
    padding: 0.75rem 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    font-size: 0.875rem;
    background: white;
    transition: all 0.3s ease;
}

.search-input {
    min-width: 250px;
}

.search-input:focus, .status-filter:focus, .company-filter:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 10px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.btn-sm {
    padding: 0.5rem 0.75rem;
    font-size: 0.75rem;
}

.btn-info {
    background: #3b82f6;
    color: white;
}

.btn-warning {
    background: #f59e0b;
    color: white;
}

.btn-success {
    background: #10b981;
    color: white;
}

.btn-danger {
    background: #ef4444;
    color: white;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.jobs-table {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 2rem;
}

th, td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
}

th {
    background: #f8fafc;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

td {
    color: #6b7280;
}

.job-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.job-title {
    font-weight: 600;
    color: #1f2937;
    font-size: 0.875rem;
}

.job-salary {
    font-size: 0.75rem;
    color: #6b7280;
}

.company-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.company-name {
    font-weight: 600;
    color: #1f2937;
    font-size: 0.875rem;
}

.company-location {
    font-size: 0.75rem;
    color: #6b7280;
}

.job-type-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.job-type-full-time {
    background: #dbeafe;
    color: #1e40af;
}

.job-type-part-time {
    background: #fef3c7;
    color: #92400e;
}

.job-type-contract {
    background: #d1fae5;
    color: #065f46;
}

.job-type-internship {
    background: #f3e8ff;
    color: #7c3aed;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-active {
    background: #d1fae5;
    color: #065f46;
}

.status-inactive {
    background: #fee2e2;
    color: #991b1b;
}

.applications-count {
    font-size: 0.875rem;
    color: #6b7280;
}

.actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.empty-state {
    text-align: center;
    padding: 3rem;
    color: #9ca3af;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.pagination {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}

@media (max-width: 768px) {
    .header-actions {
        flex-direction: column;
        align-items: stretch;
    }
    
    .search-filter {
        flex-direction: column;
        align-items: stretch;
    }
    
    .search-input {
        min-width: auto;
    }
    
    .actions {
        flex-direction: column;
        gap: 0.25rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const companyFilter = document.getElementById('companyFilter');
    
    // Add search and filter functionality here
    searchInput.addEventListener('input', function() {
        // Implement search logic
    });
    
    statusFilter.addEventListener('change', function() {
        // Implement filter logic
    });
    
    companyFilter.addEventListener('change', function() {
        // Implement filter logic
    });
});
</script>
@endsection
