@extends('layouts.dashboard')

@section('title', 'Job Alerts - RecruitSy')
@section('page-title', 'Job Alerts')

@section('content')
<div class="job-alerts-container">
    <!-- Alerts Overview -->
    <div class="alerts-overview">
        <div class="overview-card">
            <div class="overview-icon">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-2H4v2zM4 15h6v-2H4v2zM4 11h6V9H4v2zM4 7h6V5H4v2z"></path>
                </svg>
            </div>
            <div class="overview-content">
                <h3 class="overview-title">Active Alerts</h3>
                <span class="overview-number">{{ $activeAlerts }}</span>
            </div>
        </div>
        
        <div class="overview-card">
            <div class="overview-icon">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
            <div class="overview-content">
                <h3 class="overview-title">Total Alerts</h3>
                <span class="overview-number">{{ $totalAlerts }}</span>
            </div>
        </div>
    </div>



    <!-- Create New Alert -->
    <div class="create-alert-section">
        <div class="section-header">
            <h3 class="section-title">Create New Job Alert</h3>
        </div>
        
        <form class="alert-form" id="create-alert-form">
            <div class="form-grid">
                <div class="form-group">
                    <label for="job-title" class="form-label">Job Title</label>
                    <input type="text" id="job-title" class="form-input" placeholder="e.g., Software Engineer">
                </div>
                
                <div class="form-group">
                    <label for="location" class="form-label">Location</label>
                    <input type="text" id="location" class="form-input" placeholder="e.g., San Francisco, CA">
                </div>
                
                <div class="form-group">
                    <label for="job-type" class="form-label">Job Type</label>
                    <select id="job-type" class="form-select">
                        <option value="">All Types</option>
                        <option value="full-time">Full-time</option>
                        <option value="part-time">Part-time</option>
                        <option value="contract">Contract</option>
                        <option value="internship">Internship</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="experience-level" class="form-label">Experience Level</label>
                    <select id="experience-level" class="form-select">
                        <option value="">All Levels</option>
                        <option value="entry">Entry Level</option>
                        <option value="mid">Mid Level</option>
                        <option value="senior">Senior Level</option>
                        <option value="executive">Executive</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="salary-range" class="form-label">Salary Range</label>
                    <select id="salary-range" class="form-select">
                        <option value="">Any Salary</option>
                        <option value="0-50000">$0 - $50,000</option>
                        <option value="50000-75000">$50,000 - $75,000</option>
                        <option value="75000-100000">$75,000 - $100,000</option>
                        <option value="100000-150000">$100,000 - $150,000</option>
                        <option value="150000+">$150,000+</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="frequency" class="form-label">Alert Frequency</label>
                    <select id="frequency" class="form-select">
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-sm">Create Alert</button>
                <button type="button" id="btn-draft" class="btn btn-outline">Save as Draft</button>
            </div>
        </form>
    </div>



    <!-- Active Alerts -->
        <div class="section-header">
            <h3 class="section-title">Active Job Alerts</h3>
        </div>
        
        <div class="alerts-list" id="alerts-list">
            @if($activeJobAlerts->count() > 0)
                @foreach($activeJobAlerts as $alert)
                    <div class="alert-card">
                        <div class="alert-header">
                            <div class="alert-info">
                                <h4 class="alert-title">{{ $alert->title ?: 'All Jobs' }}</h4>
                                <p class="alert-location">
                                    @if($alert->location)
                                        {{ $alert->location }}
                                    @else
                                        Any Location
                                    @endif
                                </p>
                            </div>
                            <div class="alert-actions">
                                <button class="btn btn-outline btn-sm" onclick="editAlert({{ $alert->id }})">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteAlert({{ $alert->id }})">Delete</button>
                            </div>
                        </div>
                        <div class="alert-details">
                            <div class="alert-meta">
                                @if($alert->job_type)
                                    <span class="meta-tag">{{ ucfirst($alert->job_type) }}</span>
                                @endif
                                @if($alert->experience_level)
                                    <span class="meta-tag">{{ ucfirst($alert->experience_level) }}</span>
                                @endif
                                @if($alert->salary_range)
                                    <span class="meta-tag">{{ $alert->salary_range }}</span>
                                @endif
                                <span class="meta-tag">{{ ucfirst($alert->frequency) }}</span>
                            </div>
                            <div class="alert-status">
                                <span class="status-badge status-active">Active</span>
                                @if($alert->last_sent_at)
                                    <small class="last-sent">Last sent: {{ $alert->last_sent_at->diffForHumans() }}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="alert-card" id="alerts-empty">
                    <div class="alert-header">
                        <div class="alert-info">
                            <h4 class="alert-title">No alerts yet</h4>
                            <p class="alert-location">Create your first alert above</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>


</div>

<style>
/* Job Alerts Container */
.job-alerts-container {
    max-width: 1000px;
}

/* Alerts Overview */
.alerts-overview {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
    margin-bottom: 2rem;
}







.overview-card {
    background: white;
    border-radius: 0.75rem;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
}

.overview-icon {
    width: 48px;
    height: 48px;
    background: #dbeafe;
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #2563eb;
}

.overview-content {
    flex: 1;
}

.overview-title {
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 0.25rem;
}

.overview-number {
    font-size: 1.5rem;
    font-weight: bold;
    color: #1f2937;
}

/* Create Alert Section */
.create-alert-section {
    background: white;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
    overflow: hidden;
}

.section-header {
    background: #f9fafb;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #e5e7eb;
}

.section-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}

.alert-form {
    padding: 1.5rem;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-label {
    font-size: 0.875rem;
    color: #374151;
    font-weight: 500;
}

.form-input,
.form-select {
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    background: white;
}

.form-input:focus,
.form-select:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.form-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

/* Active Alerts Section */
.active-alerts-section {
    background: white;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
    overflow: hidden;
}

.alerts-list {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.alert-card {
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    padding: 1.5rem;
    transition: all 0.3s;
}

.alert-card:hover {
    border-color: #d1d5db;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.alert-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.alert-info {
    flex: 1;
}

.alert-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.alert-location {
    color: #6b7280;
    margin-bottom: 0.5rem;
}

.alert-meta {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.meta-item {
    background: #f3f4f6;
    color: #374151;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

.alert-status {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
}

.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}

.alert-status.active .status-dot {
    background: #10b981;
}

.alert-status.active .status-text {
    color: #10b981;
}

.alert-status.paused .status-dot {
    background: #f59e0b;
}

.alert-status.paused .status-text {
    color: #f59e0b;
}

.alert-details {
    background: #f9fafb;
    border-radius: 0.375rem;
    padding: 1rem;
    margin-bottom: 1rem;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.detail-item:last-child {
    margin-bottom: 0;
}

.detail-label {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
}

.detail-value {
    font-size: 0.875rem;
    color: #374151;
}

.alert-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}



/* Alert Card Styles */
.alert-card {
    background: white;
    border-radius: 0.75rem;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    border: 1px solid #e5e7eb;
    margin-bottom: 1rem;
}

.alert-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.alert-info h4 {
    margin: 0 0 0.5rem 0;
    font-size: 1.125rem;
    font-weight: 600;
    color: #111827;
}

.alert-info p {
    margin: 0;
    color: #6b7280;
    font-size: 0.875rem;
}

.alert-actions {
    display: flex;
    gap: 0.5rem;
}

.alert-details {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.alert-meta {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.meta-tag {
    background: #f3f4f6;
    color: #374151;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

.alert-status {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.25rem;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-active {
    background: #dcfce7;
    color: #166534;
}

.last-sent {
    color: #6b7280;
    font-size: 0.75rem;
}

.btn-danger {
    background: #ef4444;
    border-color: #ef4444;
    color: white;
}

.btn-danger:hover {
    background: #dc2626;
    border-color: #dc2626;
}

/* Responsive Design */
@media (max-width: 768px) {
    .alerts-overview {
        grid-template-columns: 1fr;
    }
    

    

    
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .alert-header {
        flex-direction: column;
        gap: 1rem;
    }
    
    .alert-status {
        align-self: flex-start;
    }
    
    .detail-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
    
    .alert-actions {
        flex-direction: column;
    }
    
    .alert-actions .btn {
        width: 100%;
    }
    

}

@media (max-width: 480px) {
    .overview-card {
        padding: 1rem;
    }
}
</style>
<script>
    // Global functions for alert actions
    function editAlert(alertId) {
        // Simple inline edit for frequency
        const newFreq = prompt('Enter new frequency (daily, weekly, monthly):', 'daily');
        if (!newFreq || !['daily', 'weekly', 'monthly'].includes(newFreq)) {
            return;
        }
        
        fetch(`/api/job-alerts/${alertId}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                frequency: newFreq
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success || data.status === 'updated') {
                alert('Alert updated successfully!');
                window.location.reload();
            } else {
                alert('Failed to update alert: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Update error:', error);
            alert('An error occurred while updating the alert');
        });
    }

    function deleteAlert(alertId) {
        if (confirm('Are you sure you want to delete this job alert?')) {
            fetch(`/api/job-alerts/${alertId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success || data.status === 'deleted') {
                    // Reload the page to refresh the data
                    window.location.reload();
                } else {
                    alert('Failed to delete alert: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Delete error:', error);
                alert('An error occurred while deleting the alert');
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('create-alert-form');
    const alertsList = document.getElementById('alerts-list');
    const empty = document.getElementById('alerts-empty');

    // For demo purposes, we'll just show the existing alerts from the database
    // The loadAlerts function is not needed since we're using server-side rendering

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Get form values
        const title = document.getElementById('job-title').value;
        const location = document.getElementById('location').value;
        const jobType = document.getElementById('job-type').value;
        const experienceLevel = document.getElementById('experience-level').value;
        const salaryRange = document.getElementById('salary-range').value;
        const frequency = document.getElementById('frequency').value || 'daily';
        
        // Basic validation
        if (!title && !location && !jobType && !experienceLevel && !salaryRange) {
            alert('Please fill in at least one field to create a job alert.');
            return;
        }
        
        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Creating...';
        submitBtn.disabled = true;
        
        try {
            // Send data to backend
            const response = await fetch('/api/job-alerts', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({
                    title: title || null,
                    location: location || null,
                    job_type: jobType || null,
                    experience_level: experienceLevel || null,
                    salary_range: salaryRange || null,
                    frequency: frequency
                })
            });
            
            const data = await response.json();
            
            if (response.ok && data.success) {
                // Success - show message and reload page to display new alert
                alert('Job alert created successfully!');
                window.location.reload();
            } else {
                // Error from backend
                alert('Failed to create job alert: ' + (data.message || 'Unknown error'));
            }
        } catch (error) {
            // Network or other error
            console.error('Error creating job alert:', error);
            alert('An error occurred while creating the job alert. Please try again.');
        } finally {
            // Reset button state
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        }
    });

    // TODO: Implement edit/delete functionality when backend is ready
    // For now, these buttons will show "coming soon" messages
});
</script>
@endsection 