@extends('admin.layouts.app')

@section('title', 'Applications Management')
@section('page-title', 'Applications Management')

@section('content')
<div class="applications-container">
    <div class="header-actions">
        <h2>Applications</h2>
        <div class="search-filter">
            <input type="text" id="searchInput" placeholder="Search applications..." class="search-input">
            <select id="statusFilter" class="status-filter">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="success">Success</option>
                <option value="failed">Failed</option>
            </select>
            <select id="jobFilter" class="job-filter">
                <option value="">All Jobs</option>
                @foreach($jobs as $job)
                    <option value="{{ $job->id }}">{{ $job->title }}</option>
                @endforeach
            </select>
            <select id="companyFilter" class="company-filter">
                <option value="">All Companies</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="applications-table">
        <table>
            <thead>
                <tr>
                    <th>Candidate</th>
                    <th>Job</th>
                    <th>Company</th>
                    <th>Status</th>
                    <th>Applied</th>
                    <th>HR Platform</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $application)
                <tr>
                    <td>
                        <div class="candidate-info">
                            <div class="candidate-avatar">
                                {{ substr($application->candidate_name, 0, 1) }}
                            </div>
                            <div>
                                <div class="candidate-name">{{ $application->candidate_name }}</div>
                                <div class="candidate-email">{{ $application->candidate_email }}</div>
                                @if($application->candidate_phone)
                                    <div class="candidate-phone">{{ $application->candidate_phone }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="job-info">
                            <div class="job-title">{{ $application->job->title }}</div>
                            <div class="job-location">{{ $application->job->location ?? 'Remote' }}</div>
                        </div>
                    </td>
                    <td>
                        <div class="company-info">
                            <div class="company-name">{{ $application->job->company->name }}</div>
                            <div class="company-location">{{ $application->job->company->location ?? 'Location not specified' }}</div>
                        </div>
                    </td>
                    <td>
                        <span class="status-badge status-{{ $application->status }}">
                            {{ ucfirst($application->status) }}
                        </span>
                        @if($application->error_message)
                            <div class="error-tooltip" title="{{ $application->error_message }}">
                                <i class="fas fa-exclamation-triangle text-warning"></i>
                            </div>
                        @endif
                    </td>
                    <td>{{ $application->created_at->format('M j, Y g:i A') }}</td>
                    <td>
                        @if($application->status === 'success')
                            <span class="hr-platform-badge hr-platform-success">
                                <i class="fas fa-check"></i> Forwarded
                            </span>
                        @elseif($application->status === 'failed')
                            <span class="hr-platform-badge hr-platform-failed">
                                <i class="fas fa-times"></i> Failed
                            </span>
                        @else
                            <span class="hr-platform-badge hr-platform-pending">
                                <i class="fas fa-clock"></i> Pending
                            </span>
                        @endif
                    </td>
                    <td class="actions">
                        <a href="{{ route('admin.applications.show', $application) }}" class="btn btn-sm btn-info" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if($application->status === 'failed')
                            <form method="POST" action="{{ route('admin.applications.retry', $application) }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success" title="Retry">
                                    <i class="fas fa-redo"></i>
                                </button>
                            </form>
                        @endif
                        <form method="POST" action="{{ route('admin.applications.destroy', $application) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this application?')">
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
                    <td colspan="7" class="empty-state">
                        <i class="fas fa-file-alt"></i>
                        <p>No applications found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $applications->links() }}
    </div>
</div>

<style>
.applications-container {
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

.search-input, .status-filter, .job-filter, .company-filter {
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

.search-input:focus, .status-filter:focus, .job-filter:focus, .company-filter:focus {
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

.applications-table {
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

.candidate-info, .job-info, .company-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.candidate-avatar {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 0.875rem;
    flex-shrink: 0;
}

.candidate-name, .job-title, .company-name {
    font-weight: 600;
    color: #1f2937;
    font-size: 0.875rem;
}

.candidate-email, .candidate-phone, .job-location, .company-location {
    font-size: 0.75rem;
    color: #6b7280;
    margin-top: 0.25rem;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
}

.status-success {
    background: #d1fae5;
    color: #065f46;
}

.status-failed {
    background: #fee2e2;
    color: #991b1b;
}

.error-tooltip {
    display: inline-block;
    margin-left: 0.5rem;
    cursor: help;
}

.text-warning {
    color: #f59e0b;
}

.hr-platform-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.hr-platform-success {
    background: #d1fae5;
    color: #065f46;
}

.hr-platform-failed {
    background: #fee2e2;
    color: #991b1b;
}

.hr-platform-pending {
    background: #fef3c7;
    color: #92400e;
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
    const jobFilter = document.getElementById('jobFilter');
    const companyFilter = document.getElementById('companyFilter');
    
    // Add search and filter functionality here
    searchInput.addEventListener('input', function() {
        // Implement search logic
    });
    
    statusFilter.addEventListener('change', function() {
        // Implement filter logic
    });
    
    jobFilter.addEventListener('change', function() {
        // Implement filter logic
    });
    
    companyFilter.addEventListener('change', function() {
        // Implement filter logic
    });
});
</script>
@endsection
