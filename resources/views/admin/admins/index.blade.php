@extends('admin.layouts.app')

@section('title', 'Admin Users')
@section('page-title', 'Admin Users')

@section('content')
<div class="admins-container">
    <div class="header-actions">
        <h2>Admin Users</h2>
        <a href="{{ route('admin.admins.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Admin
        </a>
    </div>

    <div class="admins-table">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Last Login</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($admins as $admin)
                <tr>
                    <td>{{ $admin->name }}</td>
                    <td>{{ $admin->email }}</td>
                    <td>
                        <span class="role-badge role-{{ $admin->role }}">
                            {{ ucfirst(str_replace('_', ' ', $admin->role)) }}
                        </span>
                    </td>
                    <td>
                        <span class="status-badge status-{{ $admin->is_active ? 'active' : 'inactive' }}">
                            {{ $admin->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        {{ $admin->last_login_at ? $admin->last_login_at->diffForHumans() : 'Never' }}
                    </td>
                    <td class="actions">
                        <a href="{{ route('admin.admins.show', $admin) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        @if($admin->id !== auth()->guard('admin')->id())
                        <form method="POST" action="{{ route('admin.admins.toggle-status', $admin) }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-{{ $admin->is_active ? 'warning' : 'success' }}">
                                <i class="fas fa-{{ $admin->is_active ? 'ban' : 'check' }}"></i>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.admins.destroy', $admin) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this admin?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $admins->links() }}
    </div>
</div>

<style>
.admins-container {
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
}

.header-actions h2 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1f2937;
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

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
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

.admins-table {
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

.role-badge, .status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.role-super_admin {
    background: #fef3c7;
    color: #92400e;
}

.role-admin {
    background: #dbeafe;
    color: #1e40af;
}

.role-moderator {
    background: #d1fae5;
    color: #065f46;
}

.status-active {
    background: #d1fae5;
    color: #065f46;
}

.status-inactive {
    background: #fee2e2;
    color: #991b1b;
}

.actions {
    display: flex;
    gap: 0.5rem;
}

.pagination {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}

.pagination .pagination-links {
    display: flex;
    gap: 0.5rem;
}

.pagination .pagination-links a,
.pagination .pagination-links span {
    padding: 0.5rem 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    text-decoration: none;
    color: #6b7280;
    transition: all 0.3s ease;
}

.pagination .pagination-links a:hover {
    background: #f8fafc;
    border-color: #d1d5db;
}

.pagination .pagination-links .current {
    background: #667eea;
    color: white;
    border-color: #667eea;
}
</style>
@endsection
