@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="dashboard">
    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon users">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ number_format($stats['total_users']) }}</h3>
                <p class="stat-label">Total Users</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon jobs">
                <i class="fas fa-briefcase"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ number_format($stats['total_jobs']) }}</h3>
                <p class="stat-label">Total Jobs</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon companies">
                <i class="fas fa-building"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ number_format($stats['total_companies']) }}</h3>
                <p class="stat-label">Total Companies</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon applications">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ number_format($stats['total_applications']) }}</h3>
                <p class="stat-label">Total Applications</p>
            </div>
        </div>
    </div>

    <!-- Charts and Analytics -->
    <div class="charts-section">
        <div class="chart-container">
            <h3 class="chart-title">Application Status Distribution</h3>
            <div class="chart-content">
                <div class="pie-chart">
                    @foreach($applicationStatuses as $status => $count)
                    <div class="chart-item">
                        <div class="chart-color status-{{ $status }}"></div>
                        <span class="chart-label">{{ ucfirst($status) }}</span>
                        <span class="chart-value">{{ $count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="chart-container">
            <h3 class="chart-title">Monthly Applications (Last 6 Months)</h3>
            <div class="chart-content">
                <div class="bar-chart">
                    @foreach($monthlyApplications as $month)
                    <div class="bar-item">
                        <div class="bar" style="height: {{ ($month->count / $maxMonthlyCount) * 100 }}%"></div>
                        <span class="bar-label">{{ \Carbon\Carbon::createFromFormat('Y-m', $month->month)->format('M Y') }}</span>
                        <span class="bar-value">{{ $month->count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="recent-activity">
        <div class="activity-section">
            <h3 class="section-title">Recent Applications</h3>
            <div class="activity-list">
                @forelse($recentApplications as $application)
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-header">
                            <span class="activity-title">{{ $application->candidate_name }}</span>
                            <span class="activity-status status-{{ $application->status }}">{{ ucfirst($application->status) }}</span>
                        </div>
                        <p class="activity-description">
                            Applied for <strong>{{ $application->job->title }}</strong> at {{ $application->job->company->name }}
                        </p>
                        <span class="activity-time">{{ $application->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>No applications yet</p>
                </div>
                @endforelse
            </div>
        </div>

        <div class="activity-section">
            <h3 class="section-title">Recent Jobs</h3>
            <div class="activity-list">
                @forelse($recentJobs as $job)
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-header">
                            <span class="activity-title">{{ $job->title }}</span>
                            <span class="activity-company">{{ $job->company->name }}</span>
                        </div>
                        <p class="activity-description">{{ Str::limit($job->description, 100) }}</p>
                        <span class="activity-time">{{ $job->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <i class="fas fa-briefcase"></i>
                    <p>No jobs posted yet</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <h3 class="section-title">Quick Actions</h3>
        <div class="actions-grid">
            <a href="{{ route('admin.users.index') }}" class="action-card">
                <i class="fas fa-users"></i>
                <span>Manage Users</span>
            </a>
            <a href="{{ route('admin.jobs.index') }}" class="action-card">
                <i class="fas fa-briefcase"></i>
                <span>Manage Jobs</span>
            </a>
            <a href="{{ route('admin.companies.index') }}" class="action-card">
                <i class="fas fa-building"></i>
                <span>Manage Companies</span>
            </a>
            <a href="{{ route('admin.applications.index') }}" class="action-card">
                <i class="fas fa-file-alt"></i>
                <span>View Applications</span>
            </a>
            <a href="{{ route('admin.import.index') }}" class="action-card">
                <i class="fas fa-upload"></i>
                <span>Import Data</span>
            </a>
            <a href="{{ route('admin.admins.index') }}" class="action-card">
                <i class="fas fa-user-shield"></i>
                <span>Manage Admins</span>
            </a>
        </div>
    </div>
</div>

<style>
.dashboard {
    max-width: 1400px;
    margin: 0 auto;
}

/* Statistics Cards */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.stat-icon.users { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
.stat-icon.jobs { background: linear-gradient(135deg, #10b981, #059669); }
.stat-icon.companies { background: linear-gradient(135deg, #f59e0b, #d97706); }
.stat-icon.applications { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }

.stat-content h3 {
    font-size: 2rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.stat-label {
    color: #6b7280;
    font-size: 0.9rem;
    font-weight: 500;
}

/* Charts Section */
.charts-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.chart-container {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.chart-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 1.5rem;
}

.pie-chart {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.chart-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem;
    background: #f8fafc;
    border-radius: 10px;
}

.chart-color {
    width: 20px;
    height: 20px;
    border-radius: 50%;
}

.status-pending { background: #f59e0b; }
.status-success { background: #10b981; }
.status-failed { background: #ef4444; }

.chart-label {
    flex: 1;
    font-weight: 500;
    color: #374151;
}

.chart-value {
    font-weight: 700;
    color: #1f2937;
    background: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
}

.bar-chart {
    display: flex;
    align-items: end;
    gap: 1rem;
    height: 200px;
    padding: 1rem 0;
}

.bar-item {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
}

.bar {
    width: 100%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 5px 5px 0 0;
    min-height: 20px;
    transition: all 0.3s ease;
}

.bar:hover {
    background: linear-gradient(135deg, #5a67d8, #6b46c1);
}

.bar-label {
    font-size: 0.75rem;
    color: #6b7280;
    text-align: center;
    transform: rotate(-45deg);
    white-space: nowrap;
}

.bar-value {
    font-size: 0.875rem;
    font-weight: 600;
    color: #1f2937;
}

/* Recent Activity */
.recent-activity {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.activity-section {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.section-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 1.5rem;
}

.activity-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.activity-item {
    display: flex;
    gap: 1rem;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 10px;
    transition: background 0.3s ease;
}

.activity-item:hover {
    background: #f1f5f9;
}

.activity-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
}

.activity-content {
    flex: 1;
}

.activity-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.activity-title {
    font-weight: 600;
    color: #1f2937;
}

.activity-status {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-pending { background: #fef3c7; color: #92400e; }
.status-success { background: #d1fae5; color: #065f46; }
.status-failed { background: #fee2e2; color: #991b1b; }

.activity-company {
    color: #6b7280;
    font-size: 0.875rem;
}

.activity-description {
    color: #6b7280;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

.activity-time {
    font-size: 0.75rem;
    color: #9ca3af;
}

.empty-state {
    text-align: center;
    padding: 2rem;
    color: #9ca3af;
}

.empty-state i {
    font-size: 2rem;
    margin-bottom: 1rem;
}

/* Quick Actions */
.quick-actions {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.action-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
    padding: 2rem 1rem;
    background: #f8fafc;
    border-radius: 15px;
    text-decoration: none;
    color: #374151;
    transition: all 0.3s ease;
}

.action-card:hover {
    background: #f1f5f9;
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.action-card i {
    font-size: 2rem;
    color: #667eea;
}

.action-card span {
    font-weight: 600;
    text-align: center;
}

/* Responsive Design */
@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .charts-section {
        grid-template-columns: 1fr;
    }
    
    .recent-activity {
        grid-template-columns: 1fr;
    }
    
    .actions-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>
@endsection
