@extends('admin.layouts.app')

@section('title', 'User Details')
@section('page-title', 'User Details')

@section('content')
<div class="user-details-container">
    <div class="user-details-card">
        <div class="card-header">
            <h3 class="card-title">User Details</h3>
            <div class="header-actions">
                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <div class="user-info">
            <div class="user-avatar-large">
                {{ substr($user->name, 0, 1) }}
            </div>
            <div class="user-basic-info">
                <h4 class="user-name">{{ $user->name }}</h4>
                <p class="user-email">{{ $user->email }}</p>
                @if($user->phone)
                    <p class="user-phone">{{ $user->phone }}</p>
                @endif
                <div class="user-status">
                    <span class="status-badge status-{{ $user->is_active ? 'active' : 'inactive' }}">
                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="user-details-grid">
            <div class="detail-section">
                <h5>Personal Information</h5>
                <div class="detail-row">
                    <span class="detail-label">First Name:</span>
                    <span class="detail-value">{{ $user->first_name ?? 'Not specified' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Last Name:</span>
                    <span class="detail-value">{{ $user->last_name ?? 'Not specified' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Email Verified:</span>
                    <span class="detail-value">
                        @if($user->email_verified_at)
                            <span class="verified-badge">Verified</span>
                        @else
                            <span class="unverified-badge">Not Verified</span>
                        @endif
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Joined:</span>
                    <span class="detail-value">{{ $user->created_at->format('F j, Y \a\t g:i A') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Last Login:</span>
                    <span class="detail-value">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</span>
                </div>
            </div>

            @if($user->skills->count() > 0)
            <div class="detail-section">
                <h5>Skills</h5>
                <div class="skills-grid">
                    @foreach($user->skills as $skill)
                        <span class="skill-badge">{{ $skill->name }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            @if($user->experience->count() > 0)
            <div class="detail-section">
                <h5>Experience</h5>
                @foreach($user->experience as $exp)
                    <div class="experience-item">
                        <div class="exp-title">{{ $exp->title }}</div>
                        <div class="exp-company">{{ $exp->company_name }}</div>
                        <div class="exp-duration">{{ $exp->start_date->format('M Y') }} - {{ $exp->end_date ? $exp->end_date->format('M Y') : 'Present' }}</div>
                    </div>
                @endforeach
            </div>
            @endif

            @if($user->education->count() > 0)
            <div class="detail-section">
                <h5>Education</h5>
                @foreach($user->education as $edu)
                    <div class="education-item">
                        <div class="edu-degree">{{ $edu->degree }}</div>
                        <div class="edu-institution">{{ $edu->institution }}</div>
                        <div class="edu-duration">{{ $edu->start_date->format('Y') }} - {{ $edu->end_date ? $edu->end_date->format('Y') : 'Present' }}</div>
                    </div>
                @endforeach
            </div>
            @endif
        </div>

        @if($user->applications->count() > 0)
        <div class="applications-section">
            <h5>Job Applications</h5>
            <div class="applications-list">
                @foreach($user->applications as $application)
                    <div class="application-item">
                        <div class="app-job">{{ $application->job->title }}</div>
                        <div class="app-company">{{ $application->job->company->name }}</div>
                        <div class="app-status">
                            <span class="status-badge status-{{ $application->status }}">
                                {{ ucfirst($application->status) }}
                            </span>
                        </div>
                        <div class="app-date">{{ $application->created_at->format('M j, Y') }}</div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="danger-zone">
            <h5>Danger Zone</h5>
            <div class="danger-actions">
                <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-{{ $user->is_active ? 'warning' : 'success' }}" onclick="return confirm('Are you sure you want to {{ $user->is_active ? 'deactivate' : 'activate' }} this user?')">
                        <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i>
                        {{ $user->is_active ? 'Deactivate' : 'Activate' }} User
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete User
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.user-details-container {
    max-width: 1000px;
    margin: 0 auto;
}

.user-details-card {
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

.btn-secondary {
    background: #6b7280;
    color: white;
}

.btn-danger {
    background: #ef4444;
    color: white;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.user-info {
    display: flex;
    align-items: center;
    gap: 2rem;
    margin-bottom: 2rem;
    padding: 2rem;
    background: #f8fafc;
    border-radius: 15px;
}

.user-avatar-large {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 2.5rem;
    flex-shrink: 0;
}

.user-basic-info h4 {
    margin: 0 0 0.5rem 0;
    font-size: 1.5rem;
    color: #1f2937;
}

.user-email, .user-phone {
    margin: 0.25rem 0;
    color: #6b7280;
}

.user-details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.detail-section {
    background: #f8fafc;
    padding: 1.5rem;
    border-radius: 10px;
}

.detail-section h5 {
    margin: 0 0 1rem 0;
    font-size: 1.125rem;
    color: #1f2937;
    border-bottom: 1px solid #e5e7eb;
    padding-bottom: 0.5rem;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f3f4f6;
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-label {
    font-weight: 600;
    color: #374151;
}

.detail-value {
    color: #6b7280;
}

.status-badge, .verified-badge, .unverified-badge {
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

.verified-badge {
    background: #d1fae5;
    color: #065f46;
}

.unverified-badge {
    background: #fee2e2;
    color: #991b1b;
}

.skills-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.skill-badge {
    background: #dbeafe;
    color: #1e40af;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.experience-item, .education-item {
    padding: 1rem;
    background: white;
    border-radius: 8px;
    margin-bottom: 1rem;
}

.exp-title, .edu-degree {
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.exp-company, .edu-institution {
    color: #6b7280;
    margin-bottom: 0.25rem;
}

.exp-duration, .edu-duration {
    font-size: 0.875rem;
    color: #9ca3af;
}

.applications-section {
    background: #f8fafc;
    padding: 1.5rem;
    border-radius: 10px;
    margin-bottom: 2rem;
}

.applications-section h5 {
    margin: 0 0 1rem 0;
    font-size: 1.125rem;
    color: #1f2937;
}

.applications-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.application-item {
    display: grid;
    grid-template-columns: 2fr 2fr 1fr 1fr;
    gap: 1rem;
    align-items: center;
    padding: 1rem;
    background: white;
    border-radius: 8px;
}

.app-job, .app-company {
    font-weight: 600;
    color: #1f2937;
}

.app-date {
    color: #6b7280;
    font-size: 0.875rem;
}

.danger-zone {
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 10px;
    padding: 1.5rem;
}

.danger-zone h5 {
    color: #991b1b;
    margin-bottom: 1rem;
    font-size: 1.125rem;
}

.danger-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

@media (max-width: 768px) {
    .user-info {
        flex-direction: column;
        text-align: center;
    }
    
    .user-details-grid {
        grid-template-columns: 1fr;
    }
    
    .application-item {
        grid-template-columns: 1fr;
        text-align: center;
    }
    
    .danger-actions {
        flex-direction: column;
    }
}
</style>
@endsection
