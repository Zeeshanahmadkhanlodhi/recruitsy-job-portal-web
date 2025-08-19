@extends('layouts.dashboard')

@section('title', 'Settings - RecruitSy')
@section('page-title', 'Settings')

@section('content')
<div class="settings-container">
    <!-- Settings Navigation -->
    <div class="settings-nav">
        <button class="nav-tab active" data-tab="account">Account</button>
        <button class="nav-tab" data-tab="privacy">Privacy</button>
        <button class="nav-tab" data-tab="notifications">Notifications</button>
        <button class="nav-tab" data-tab="security">Security</button>
        <button class="nav-tab" data-tab="preferences">Preferences</button>
    </div>

    <!-- Settings Content -->
    <div class="settings-content">
        <!-- Account Settings -->
        <div class="settings-tab active" id="account">
            <div class="settings-section">
                <h3 class="section-title">Account Information</h3>
                
                <form class="settings-form" action="{{ route('settings.account') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first-name" class="form-label">First Name</label>
                            <input type="text" id="first-name" name="first_name" class="form-input" value="{{ old('first_name', auth()->user()->first_name) }}">
                        </div>
                        <div class="form-group">
                            <label for="last-name" class="form-label">Last Name</label>
                            <input type="text" id="last-name" name="last_name" class="form-input" value="{{ old('last_name', auth()->user()->last_name) }}">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email" class="form-input" value="{{ old('email', auth()->user()->email) }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" id="phone" name="phone" class="form-input" value="{{ old('phone', auth()->user()->phone) }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" id="location" name="location" class="form-input" value="{{ old('location', auth()->user()->location) }}">
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                        <button type="button" class="btn btn-outline btn-sm">Cancel</button>
                    </div>
                </form>
            </div>

            <div class="settings-section">
                <h3 class="section-title">Profile Picture</h3>
                
                <div class="profile-picture-section">
                    <div class="current-picture">
                        <div class="profile-avatar">
                            @if (auth()->user()->avatar_path)
                                <img src="{{ asset('storage/'.auth()->user()->avatar_path) }}" alt="Profile" style="width:100%; height:100%; object-fit:cover; border-radius:50%;" />
                            @else
                                <svg width="80" height="80" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8c0 2.208-1.79 4-3.998 4-2.208 0-3.998-1.792-3.998-4s1.79-4 3.998-4c2.208 0 3.998 1.792 3.998 4z"/>
                                </svg>
                            @endif
                        </div>
                        <p class="picture-text">{{ auth()->user()->avatar_path ? 'Profile picture uploaded' : 'No profile picture uploaded' }}</p>
                    </div>
                    
                    <div class="picture-actions">
                        <form action="{{ route('settings.account') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="avatar" accept="image/*" class="form-input" />
                            <button class="btn btn-outline btn-sm" style="margin-top:0.5rem;">Upload Photo</button>
                        </form>
                        @if(auth()->user()->avatar_path)
                        <form action="{{ route('settings.account') }}" method="POST">
                            @csrf
                            <input type="hidden" name="avatar_remove" value="1" />
                            <input type="hidden" name="first_name" value="{{ auth()->user()->first_name }}" />
                            <input type="hidden" name="last_name" value="{{ auth()->user()->last_name }}" />
                            <input type="hidden" name="email" value="{{ auth()->user()->email }}" />
                            <button class="btn btn-outline btn-sm">Remove</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Privacy Settings -->
        <div class="settings-tab" id="privacy">
            <div class="settings-section">
                <h3 class="section-title">Profile Visibility</h3>
                
                <div class="privacy-options">
                    <div class="privacy-option">
                        <div class="option-info">
                            <h4 class="option-title">Public Profile</h4>
                            <p class="option-description">Your profile is visible to all employers and recruiters</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    
                    <div class="privacy-option">
                        <div class="option-info">
                            <h4 class="option-title">Show Contact Information</h4>
                            <p class="option-description">Allow employers to see your email and phone number</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    
                    <div class="privacy-option">
                        <div class="option-info">
                            <h4 class="option-title">Show Current Employer</h4>
                            <p class="option-description">Display your current company on your profile</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox">
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    
                    <div class="privacy-option">
                        <div class="option-info">
                            <h4 class="option-title">Show Salary Expectations</h4>
                            <p class="option-description">Display your expected salary range</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="settings-section">
                <h3 class="section-title">Data Usage</h3>
                
                <div class="data-options">
                    <div class="data-option">
                        <div class="option-info">
                            <h4 class="option-title">Analytics & Research</h4>
                            <p class="option-description">Help us improve our services by sharing anonymous usage data</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    
                    <div class="data-option">
                        <div class="option-info">
                            <h4 class="option-title">Personalized Recommendations</h4>
                            <p class="option-description">Receive job recommendations based on your profile and activity</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification Settings -->
        <div class="settings-tab" id="notifications">
            <div class="settings-section">
                <h3 class="section-title">Email Notifications</h3>
                
                <div class="notification-options">
                    <div class="notification-option">
                        <div class="option-info">
                            <h4 class="option-title">Job Alerts</h4>
                            <p class="option-description">Receive notifications about new job opportunities</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    
                    <div class="notification-option">
                        <div class="option-info">
                            <h4 class="option-title">Application Updates</h4>
                            <p class="option-description">Get notified about changes to your job applications</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    
                    <div class="notification-option">
                        <div class="option-info">
                            <h4 class="option-title">Profile Views</h4>
                            <p class="option-description">Know when employers view your profile</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    
                    <div class="notification-option">
                        <div class="option-info">
                            <h4 class="option-title">Weekly Summary</h4>
                            <p class="option-description">Receive a weekly digest of your job search activity</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox">
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="settings-section">
                <h3 class="section-title">Notification Frequency</h3>
                
                <div class="frequency-options">
                    <label class="radio-option">
                        <input type="radio" name="frequency" value="immediate" checked>
                        <span class="radio-custom"></span>
                        <div class="radio-content">
                            <span class="radio-title">Immediate</span>
                            <span class="radio-description">Receive notifications as soon as they happen</span>
                        </div>
                    </label>
                    
                    <label class="radio-option">
                        <input type="radio" name="frequency" value="daily">
                        <span class="radio-custom"></span>
                        <div class="radio-content">
                            <span class="radio-title">Daily Digest</span>
                            <span class="radio-description">Receive a daily summary of all notifications</span>
                        </div>
                    </label>
                    
                    <label class="radio-option">
                        <input type="radio" name="frequency" value="weekly">
                        <span class="radio-custom"></span>
                        <div class="radio-content">
                            <span class="radio-title">Weekly Digest</span>
                            <span class="radio-description">Receive a weekly summary of all notifications</span>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Security Settings -->
        <div class="settings-tab" id="security">
            <div class="settings-section">
                <h3 class="section-title">Change Password</h3>
                
                <form class="settings-form" action="{{ route('settings.password') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="current-password" class="form-label">Current Password</label>
                        <input type="password" id="current-password" name="current_password" class="form-input" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="new-password" class="form-label">New Password</label>
                        <input type="password" id="new-password" name="new_password" class="form-input" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm-password" class="form-label">Confirm New Password</label>
                        <input type="password" id="confirm-password" name="new_password_confirmation" class="form-input" required>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-sm">Update Password</button>
                    </div>
                </form>
            </div>

            <div class="settings-section">
                <h3 class="section-title">Active Sessions</h3>
                <div id="sessions-list" class="sessions-list">
                    <p>Loading sessions…</p>
                </div>
            </div>

            <div class="settings-section">
                <h3 class="section-title">Two-Factor Authentication</h3>
                
                <div class="two-factor-section">
                    <div class="two-factor-info">
                        <h4 class="two-factor-title">Enhanced Security</h4>
                        <p class="two-factor-description">Add an extra layer of security to your account with two-factor authentication.</p>
                    </div>
                    
                    <div class="two-factor-status">
                        <span class="status-badge disabled">Disabled</span>
                        <button class="btn btn-outline btn-sm">Enable 2FA</button>
                    </div>
                </div>
            </div>

            
        </div>

        <!-- Preferences Settings -->
        <div class="settings-tab" id="preferences">
            <div class="settings-section">
                <h3 class="section-title">Language & Region</h3>
                
                <div class="preferences-form">
                    <div class="form-group">
                        <label for="language" class="form-label">Language</label>
                        <select id="language" class="form-select">
                            <option value="en">English</option>
                            <option value="es">Spanish</option>
                            <option value="fr">French</option>
                            <option value="de">German</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="timezone" class="form-label">Timezone</label>
                        <select id="timezone" class="form-select">
                            <option value="pst">Pacific Time (PT)</option>
                            <option value="mst">Mountain Time (MT)</option>
                            <option value="cst">Central Time (CT)</option>
                            <option value="est">Eastern Time (ET)</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="currency" class="form-label">Currency</label>
                        <select id="currency" class="form-select">
                            <option value="usd">USD ($)</option>
                            <option value="eur">EUR (€)</option>
                            <option value="gbp">GBP (£)</option>
                            <option value="cad">CAD ($)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="settings-section">
                <h3 class="section-title">Display Preferences</h3>
                
                <div class="display-options">
                    <div class="display-option">
                        <div class="option-info">
                            <h4 class="option-title">Dark Mode</h4>
                            <p class="option-description">Use dark theme for the dashboard</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox">
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    

                </div>
            </div>

            <div class="settings-section">
                <h3 class="section-title">Account Actions</h3>
                
                <div class="account-actions">
                    <button class="btn btn-outline btn-sm danger">Delete Account</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Settings Container */
.settings-container {
    max-width: 800px;
}

/* Settings Navigation */
.settings-nav {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 2rem;
    border-bottom: 1px solid #e5e7eb;
    overflow-x: auto;
}

.nav-tab {
    padding: 1rem 1.5rem;
    background: none;
    border: none;
    border-bottom: 2px solid transparent;
    color: #6b7280;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s;
    white-space: nowrap;
}

.nav-tab:hover {
    color: #374151;
}

.nav-tab.active {
    color: #2563eb;
    border-bottom-color: #2563eb;
}

/* Settings Content */
.settings-content {
    background: white;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    overflow: hidden;
}

.settings-tab {
    display: none;
    padding: 2rem;
}

.settings-tab.active {
    display: block;
}

.settings-section {
    margin-bottom: 2rem;
}

.settings-section:last-child {
    margin-bottom: 0;
}

.section-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 1.5rem;
}

/* Settings Form */
.settings-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
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

/* Profile Picture Section */
.profile-picture-section {
    display: flex;
    align-items: center;
    gap: 2rem;
}

.current-picture {
    text-align: center;
}

.profile-avatar {
    width: 100px;
    height: 100px;
    background: #e5e7eb;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #9ca3af;
    margin-bottom: 1rem;
}

.picture-text {
    color: #6b7280;
    font-size: 0.875rem;
}

.picture-actions {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

/* Privacy Options */
.privacy-options,
.notification-options,
.data-options,
.display-options {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.privacy-option,
.notification-option,
.data-option,
.display-option {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
}

.option-info {
    flex: 1;
}

.option-title {
    font-size: 1rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.option-description {
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

/* Frequency Options */
.frequency-options {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.radio-option {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    cursor: pointer;
    transition: all 0.3s;
}

.radio-option:hover {
    border-color: #d1d5db;
    background: #f9fafb;
}

.radio-option input[type="radio"] {
    display: none;
}

.radio-custom {
    width: 20px;
    height: 20px;
    border: 2px solid #d1d5db;
    border-radius: 50%;
    position: relative;
    flex-shrink: 0;
}

.radio-option input[type="radio"]:checked + .radio-custom {
    border-color: #2563eb;
}

.radio-option input[type="radio"]:checked + .radio-custom::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 8px;
    height: 8px;
    background: #2563eb;
    border-radius: 50%;
}

.radio-content {
    flex: 1;
}

.radio-title {
    display: block;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.radio-description {
    font-size: 0.875rem;
    color: #6b7280;
}

/* Two-Factor Section */
.two-factor-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
}

.two-factor-info {
    flex: 1;
}

.two-factor-title {
    font-size: 1rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.two-factor-description {
    font-size: 0.875rem;
    color: #6b7280;
}

.two-factor-status {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-badge.disabled {
    background: #fee2e2;
    color: #dc2626;
}

/* Sessions List */
.sessions-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.session-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
}

.session-info {
    flex: 1;
}

.session-device {
    font-size: 1rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.session-location {
    color: #6b7280;
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.session-time {
    color: #9ca3af;
    font-size: 0.75rem;
}

.current-badge {
    background: #dcfce7;
    color: #16a34a;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

/* Preferences Form */
.preferences-form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

/* Account Actions */
.account-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.btn.danger {
    color: #dc2626;
    border-color: #dc2626;
}

.btn.danger:hover {
    background: #dc2626;
    color: white;
}

/* Responsive Design */
@media (max-width: 768px) {
    .settings-nav {
        flex-wrap: wrap;
    }
    
    .nav-tab {
        flex: 1;
        min-width: 120px;
        text-align: center;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .profile-picture-section {
        flex-direction: column;
        text-align: center;
    }
    
    .privacy-option,
    .notification-option,
    .data-option,
    .display-option {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
    
    .two-factor-section {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .session-item {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
    
    .account-actions {
        flex-direction: column;
    }
    
    .account-actions .btn {
        width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const navTabs = document.querySelectorAll('.nav-tab');
    const settingsTabs = document.querySelectorAll('.settings-tab');
    
    navTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Remove active class from all tabs
            navTabs.forEach(t => t.classList.remove('active'));
            settingsTabs.forEach(t => t.classList.remove('active'));
            
            // Add active class to clicked tab
            this.classList.add('active');
            document.getElementById(targetTab).classList.add('active');
        });
    });

    // Load active sessions for Security tab
    async function loadSessions() {
        try {
            const res = await window.axios.get('/api/sessions');
            const list = Array.isArray(res.data) ? res.data : [];
            const container = document.getElementById('sessions-list');
            if (!container) return;
            if (list.length === 0) {
                container.innerHTML = '<p>No active sessions.</p>';
                return;
            }
            container.innerHTML = list.map(s => `
                <div class="session-item">
                    <div class="session-info">
                        <h4 class="session-device">${(s.browser || 'Browser')} on ${(s.platform || 'Device')}</h4>
                        <p class="session-location">${s.location || s.ip_address || ''}</p>
                        <span class="session-time">Last active: ${new Date(s.last_activity || s.updated_at).toLocaleString()}</span>
                    </div>
                    <div class="session-actions">
                        ${s.session_id === (window.LaravelSessionId || '') ? '<span class="current-badge">Current</span>' : ''}
                    </div>
                </div>
            `).join('');
        } catch (e) {
            console.error('Failed to load sessions', e);
        }
    }

    loadSessions();
});
</script>
@endsection 