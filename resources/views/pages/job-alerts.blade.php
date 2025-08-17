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
                <span class="overview-number">5</span>
            </div>
        </div>
        
        <div class="overview-card">
            <div class="overview-icon">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="overview-content">
                <h3 class="overview-title">Last 7 Days</h3>
                <span class="overview-number">12</span>
            </div>
        </div>
        
        <div class="overview-card">
            <div class="overview-icon">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="overview-content">
                <h3 class="overview-title">Applied</h3>
                <span class="overview-number">3</span>
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
    <div class="active-alerts-section">
        <div class="section-header">
            <h3 class="section-title">Active Job Alerts</h3>
        </div>
        
        <div class="alerts-list" id="alerts-list">
            <div class="alert-card" id="alerts-empty" style="display:none;">
                <div class="alert-header">
                    <div class="alert-info"><h4 class="alert-title">No alerts yet</h4><p class="alert-location">Create your first alert above</p></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Settings -->
    <div class="notification-settings-section">
        <div class="section-header">
            <h3 class="section-title">Notification Settings</h3>
        </div>
        
        <div class="settings-content">
            <div class="setting-group">
                <div class="setting-item">
                    <div class="setting-info">
                        <h4 class="setting-title">Email Notifications</h4>
                        <p class="setting-description">Receive job alerts via email</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" checked>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="setting-item">
                    <div class="setting-info">
                        <h4 class="setting-title">Push Notifications</h4>
                        <p class="setting-description">Receive notifications in your browser</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" checked>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="setting-item">
                    <div class="setting-info">
                        <h4 class="setting-title">Application Updates</h4>
                        <p class="setting-description">Get notified about application status changes</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" checked>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="setting-item">
                    <div class="setting-info">
                        <h4 class="setting-title">Weekly Summary</h4>
                        <p class="setting-description">Receive a weekly summary of job opportunities</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>
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
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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

/* Notification Settings */
.notification-settings-section {
    background: white;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    overflow: hidden;
}

.settings-content {
    padding: 1.5rem;
}

.setting-group {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.setting-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
}

.setting-info {
    flex: 1;
}

.setting-title {
    font-size: 1rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.setting-description {
    font-size: 0.875rem;
    color: #6b7280;
}

/* Toggle Switch */
.toggle-switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 24px;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

input:checked + .toggle-slider {
    background-color: #2563eb;
}

input:checked + .toggle-slider:before {
    transform: translateX(26px);
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
    
    .setting-item {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
    
    .toggle-switch {
        align-self: flex-end;
    }
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('create-alert-form');
    const alertsList = document.getElementById('alerts-list');
    const empty = document.getElementById('alerts-empty');

    function renderAlertItem(a) {
        const activeClass = a.is_active ? 'active' : 'paused';
        const activeText = a.is_active ? 'Active' : 'Paused';
        return `
        <div class="alert-card" data-id="${a.id}">
            <div class="alert-header">
                <div class="alert-info">
                    <h4 class="alert-title">${a.title || 'Any role'}</h4>
                    <p class="alert-location">${a.location || ''}</p>
                    <div class="alert-meta">
                        ${a.job_type ? `<span class="meta-item">${a.job_type}</span>` : ''}
                        ${a.experience_level ? `<span class="meta-item">${a.experience_level}</span>` : ''}
                        ${a.salary_range ? `<span class="meta-item">${a.salary_range}</span>` : ''}
                    </div>
                </div>
                <div class="alert-status ${activeClass}">
                    <span class="status-dot"></span>
                    <span class="status-text">${activeText}</span>
                </div>
            </div>
            <div class="alert-details">
                <div class="detail-item"><span class="detail-label">Frequency:</span><span class="detail-value">${a.frequency}</span></div>
                ${a.last_sent_at ? `<div class="detail-item"><span class="detail-label">Last Sent:</span><span class="detail-value">${new Date(a.last_sent_at).toLocaleString()}</span></div>` : ''}
            </div>
            <div class="alert-actions">
                <button class="btn btn-sm btn-outline btn-edit">Edit</button>
                <button class="btn btn-sm btn-outline btn-toggle">${a.is_active ? 'Pause' : 'Resume'}</button>
                <button class="btn btn-sm btn-outline btn-delete">Delete</button>
            </div>
        </div>`;
    }

    async function loadAlerts() {
        const res = await window.axios.get('/api/job-alerts');
        const data = res.data || {};
        const items = Array.isArray(data.data) ? data.data : [];
        alertsList.innerHTML = '';
        if (items.length === 0) {
            empty.style.display = '';
            alertsList.appendChild(empty);
            return;
        }
        empty.style.display = 'none';
        alertsList.insertAdjacentHTML('beforeend', items.map(renderAlertItem).join(''));
    }

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const payload = {
            title: document.getElementById('job-title').value,
            location: document.getElementById('location').value,
            job_type: document.getElementById('job-type').value,
            experience_level: document.getElementById('experience-level').value,
            salary_range: document.getElementById('salary-range').value,
            frequency: document.getElementById('frequency').value || 'daily',
        };
        try {
            await window.axios.post('/api/job-alerts', payload);
            await loadAlerts();
            form.reset();
        } catch (err) {
            alert('Failed to create alert');
        }
    });

    document.addEventListener('click', async function(e) {
        const card = e.target.closest('.alert-card');
        if (!card) return;
        const id = card.getAttribute('data-id');
        if (e.target.closest('.btn-delete')) {
            await window.axios.delete(`/api/job-alerts/${id}`);
            await loadAlerts();
        } else if (e.target.closest('.btn-toggle')) {
            const isActive = card.querySelector('.status-text')?.textContent === 'Active';
            await window.axios.put(`/api/job-alerts/${id}`, { is_active: !isActive });
            await loadAlerts();
        } else if (e.target.closest('.btn-edit')) {
            // Simple inline edit (toggle active and change frequency for demo)
            const newFreq = prompt('Frequency (daily, weekly, monthly):', 'daily');
            if (!newFreq) return;
            await window.axios.put(`/api/job-alerts/${id}`, { frequency: newFreq });
            await loadAlerts();
        }
    });

    loadAlerts();
});
</script>
@endsection 