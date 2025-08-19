@extends('admin.layouts.app')

@section('title', 'Admin Details')
@section('page-title', 'Admin Details')

@section('content')
<div class="admin-details-container">
    <div class="admin-details-card">
        <div class="card-header">
            <h3 class="card-title">Admin User Details</h3>
            <div class="header-actions">
                <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <div class="admin-info">
            <div class="info-row">
                <div class="info-label">Name:</div>
                <div class="info-value">{{ $admin->name }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value">{{ $admin->email }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Role:</div>
                <div class="info-value">
                    <span class="role-badge role-{{ $admin->role }}">
                        {{ ucfirst(str_replace('_', ' ', $admin->role)) }}
                    </span>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Status:</div>
                <div class="info-value">
                    <span class="status-badge status-{{ $admin->is_active ? 'active' : 'inactive' }}">
                        {{ $admin->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Email Verified:</div>
                <div class="info-value">
                    @if($admin->email_verified_at)
                        <span class="verified-badge">Verified</span>
                    @else
                        <span class="unverified-badge">Not Verified</span>
                    @endif
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Last Login:</div>
                <div class="info-value">
                    {{ $admin->last_login_at ? $admin->last_login_at->diffForHumans() : 'Never' }}
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Created:</div>
                <div class="info-value">{{ $admin->created_at->format('F j, Y \a\t g:i A') }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Last Updated:</div>
                <div class="info-value">{{ $admin->updated_at->format('F j, Y \a\t g:i A') }}</div>
            </div>
        </div>

        @if($admin->id !== auth()->guard('admin')->id())
        <div class="danger-zone">
            <h4>Danger Zone</h4>
            <div class="danger-actions">
                <form method="POST" action="{{ route('admin.admins.toggle-status', $admin) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-{{ $admin->is_active ? 'warning' : 'success' }}" onclick="return confirm('Are you sure you want to {{ $admin->is_active ? 'deactivate' : 'activate' }} this admin?')">
                        <i class="fas fa-{{ $admin->is_active ? 'ban' : 'check' }}"></i>
                        {{ $admin->is_active ? 'Deactivate' : 'Activate' }} Admin
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.admins.destroy', $admin) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this admin? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete Admin
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
.admin-details-container {
    max-width: 800px;
    margin: 0 auto;
}

.admin-details-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e5e7eb;
}

.card-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}

.header-actions {
    display: flex;
    gap: 1rem;
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

.btn-warning {
    background: #f59e0b;
    color: white;
}

.btn-warning:hover {
    background: #d97706;
    transform: translateY(-2px);
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

.btn-secondary:hover {
    background: #4b5563;
    transform: translateY(-2px);
}

.btn-success {
    background: #10b981;
    color: white;
}

.btn-success:hover {
    background: #059669;
    transform: translateY(-2px);
}

.btn-danger {
    background: #ef4444;
    color: white;
}

.btn-danger:hover {
    background: #dc2626;
    transform: translateY(-2px);
}

.admin-info {
    margin-bottom: 2rem;
}

.info-row {
    display: flex;
    padding: 1rem 0;
    border-bottom: 1px solid #f3f4f6;
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    width: 150px;
    font-weight: 600;
    color: #374151;
}

.info-value {
    flex: 1;
    color: #6b7280;
}

.role-badge, .status-badge, .verified-badge, .unverified-badge {
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

.verified-badge {
    background: #d1fae5;
    color: #065f46;
}

.unverified-badge {
    background: #fee2e2;
    color: #991b1b;
}

.danger-zone {
    margin-top: 2rem;
    padding: 1.5rem;
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 10px;
}

.danger-zone h4 {
    color: #991b1b;
    margin-bottom: 1rem;
    font-size: 1.125rem;
}

.danger-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.danger-actions .btn {
    margin: 0;
}

@media (max-width: 768px) {
    .card-header {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }

    .header-actions {
        justify-content: center;
    }

    .info-row {
        flex-direction: column;
        gap: 0.5rem;
    }

    .info-label {
        width: auto;
    }

    .danger-actions {
        flex-direction: column;
    }
}
</style>
@endsection
