@extends('admin.layouts.app')

@section('title', 'Companies Management')
@section('page-title', 'Companies Management')

@section('content')
<div class="companies-container">
    <div class="header-actions">
        <h2>Companies</h2>
        <div class="search-filter">
            <input type="text" id="searchInput" placeholder="Search companies..." class="search-input">
            <select id="statusFilter" class="status-filter">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            <select id="locationFilter" class="location-filter">
                <option value="">All Locations</option>
                @foreach($locations as $location)
                    <option value="{{ $location }}">{{ $location }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="companies-table">
        <table>
            <thead>
                <tr>
                    <th>Company</th>
                    <th>Location</th>
                    <th>Industry</th>
                    <th>Jobs Posted</th>
                    <th>Status</th>
                    <th>HR Platform</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($companies as $company)
                <tr>
                    <td>
                        <div class="company-info">
                            <div class="company-logo">
                                @if($company->logo)
                                    <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }}" class="logo-img">
                                @else
                                    <div class="logo-placeholder">
                                        {{ substr($company->name, 0, 2) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <div class="company-name">{{ $company->name }}</div>
                                @if($company->website)
                                    <div class="company-website">
                                        <a href="{{ $company->website }}" target="_blank" class="website-link">
                                            <i class="fas fa-external-link-alt"></i> {{ $company->website }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>{{ $company->location ?? 'Not specified' }}</td>
                    <td>{{ $company->industry ?? 'Not specified' }}</td>
                    <td>
                        <span class="jobs-count">
                            {{ $company->jobs_count ?? 0 }} jobs
                        </span>
                    </td>
                    <td>
                        <span class="status-badge status-{{ $company->is_active ? 'active' : 'inactive' }}">
                            {{ $company->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        @if($company->hr_portal_url)
                            <span class="hr-platform-badge hr-platform-active">
                                <i class="fas fa-check"></i> Connected
                            </span>
                        @else
                            <span class="hr-platform-badge hr-platform-inactive">
                                <i class="fas fa-times"></i> Not Connected
                            </span>
                        @endif
                    </td>
                    <td>{{ $company->created_at->format('M j, Y') }}</td>
                    <td class="actions">
                        <a href="{{ route('admin.companies.show', $company) }}" class="btn btn-sm btn-info" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.companies.edit', $company) }}" class="btn btn-sm btn-warning" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.companies.toggle-status', $company) }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-{{ $company->is_active ? 'warning' : 'success' }}" title="{{ $company->is_active ? 'Deactivate' : 'Activate' }}">
                                <i class="fas fa-{{ $company->is_active ? 'ban' : 'check' }}"></i>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.companies.destroy', $company) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this company?')">
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
                        <i class="fas fa-building"></i>
                        <p>No companies found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $companies->links() }}
    </div>
</div>

<style>
.companies-container {
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

.search-input, .status-filter, .location-filter {
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

.search-input:focus, .status-filter:focus, .location-filter:focus {
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

.companies-table {
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

.company-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.company-logo {
    width: 50px;
    height: 50px;
    flex-shrink: 0;
}

.logo-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 8px;
}

.logo-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 0.875rem;
}

.company-name {
    font-weight: 600;
    color: #1f2937;
    font-size: 0.875rem;
}

.company-website {
    margin-top: 0.25rem;
}

.website-link {
    color: #667eea;
    text-decoration: none;
    font-size: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.website-link:hover {
    text-decoration: underline;
}

.jobs-count {
    font-size: 0.875rem;
    color: #6b7280;
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

.hr-platform-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.hr-platform-active {
    background: #d1fae5;
    color: #065f46;
}

.hr-platform-inactive {
    background: #fee2e2;
    color: #991b1b;
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
    const locationFilter = document.getElementById('locationFilter');
    
    // Add search and filter functionality here
    searchInput.addEventListener('input', function() {
        // Implement search logic
    });
    
    statusFilter.addEventListener('change', function() {
        // Implement filter logic
    });
    
    locationFilter.addEventListener('change', function() {
        // Implement filter logic
    });
});
</script>
@endsection
