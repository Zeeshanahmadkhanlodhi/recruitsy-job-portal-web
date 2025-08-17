@extends('layouts.dashboard')

@section('title', 'Profile - RecruitSy')
@section('page-title', 'My Profile')

@section('content')
<div class="profile-container">
    <!-- Profile Header -->
    <div class="profile-header">
        <div class="profile-avatar-section">
            <div class="profile-avatar">
                @if(auth()->user()->avatar_path)
                    <img src="{{ asset('storage/' . auth()->user()->avatar_path) }}" alt="Profile Avatar" class="avatar-image">
                @else
                    <svg width="80" height="80" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8c0 2.208-1.79 4-3.998 4-2.208 0-3.998-1.792-3.998-4s1.79-4 3.998-4c2.208 0 3.998 1.792 3.998 4z"/>
                    </svg>
                @endif
            </div>
            <div class="avatar-actions">
                <button class="btn btn-sm btn-outline">Change Photo</button>
                @if(auth()->user()->avatar_path)
                    <button class="btn btn-sm btn-outline">Remove</button>
                @endif
            </div>
        </div>
        
        <div class="profile-info">
            <h2 class="profile-name">{{ auth()->user()->fullName }}</h2>
            <p class="profile-title">Professional</p>
            <p class="profile-location">{{ auth()->user()->location ?? 'No location set' }}</p>
            <div class="profile-completion">
                <div class="completion-bar">
                    <div class="completion-fill" style="width: {{ auth()->user()->profileCompletion }}%"></div>
                </div>
                <span class="completion-text">{{ auth()->user()->profileCompletion }}% Profile Complete</span>
            </div>
        </div>
    </div>

    <!-- Profile Actions & Settings -->
    <div class="profile-actions">
        <div class="action-buttons">
            <button class="btn btn-primary" onclick="exportProfile()">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="mr-2">
                    <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                    <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                </svg>
                Export Profile
            </button>
            <button class="btn btn-outline" onclick="toggleProfileVisibility()">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="mr-2">
                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                </svg>
                {{ auth()->user()->profile_visibility === 'public' ? 'Public Profile' : 'Private Profile' }}
            </button>
        </div>
        
        <div class="profile-stats">
            <div class="stat-item">
                <span class="stat-number">{{ auth()->user()->profile_views ?? 0 }}</span>
                <span class="stat-label">Profile Views</span>
            </div>
                             <div class="stat-item">
                     <span class="stat-number">{{ optional(auth()->user()->applications)->count() ?? 0 }}</span>
                     <span class="stat-label">Applications</span>
                 </div>
                 <div class="stat-item">
                     <span class="stat-number">{{ optional(auth()->user()->saved_jobs)->count() ?? 0 }}</span>
                     <span class="stat-label">Saved Jobs</span>
                 </div>
        </div>
    </div>



    <!-- Profile Sections -->
    <div class="profile-sections">
        <!-- Personal Information -->
        <div class="profile-section">
            <div class="section-header">
                <h3 class="section-title">Personal Information</h3>
                <a href="{{ route('profile.edit.personal') }}" class="btn btn-sm btn-outline">Edit</a>
            </div>
            
            <div class="section-content">
                <div class="info-grid">
                    <div class="info-item">
                        <label class="info-label">Full Name</label>
                        <span class="info-value">{{ auth()->user()->fullName }}</span>
                    </div>
                    <div class="info-item">
                        <label class="info-label">Email</label>
                        <span class="info-value">{{ auth()->user()->email }}</span>
                    </div>
                    <div class="info-item">
                        <label class="info-label">Phone</label>
                        <span class="info-value">{{ auth()->user()->phone ?? 'Not provided' }}</span>
                    </div>
                    <div class="info-item">
                        <label class="info-label">Location</label>
                        <span class="info-value">{{ auth()->user()->location ?? 'Not provided' }}</span>
                    </div>
                    <div class="info-item">
                        <label class="info-label">Date of Birth</label>
                        <span class="info-value">{{ auth()->user()->date_of_birth ? auth()->user()->date_of_birth->format('F j, Y') : 'Not provided' }}</span>
                    </div>
                    <div class="info-item">
                        <label class="info-label">LinkedIn</label>
                        <span class="info-value">
                            @if(auth()->user()->linkedin_url)
                                <a href="{{ auth()->user()->linkedin_url }}" target="_blank" class="text-blue-600 hover:underline">{{ str_replace(['https://', 'http://'], '', auth()->user()->linkedin_url) }}</a>
                            @else
                                Not provided
                            @endif
                        </span>
                    </div>
                    <div class="info-item">
                        <label class="info-label">GitHub</label>
                        <span class="info-value">
                            @if(auth()->user()->github_url)
                                <a href="{{ auth()->user()->github_url }}" target="_blank" class="text-blue-600 hover:underline">{{ str_replace(['https://', 'http://'], '', auth()->user()->github_url) }}</a>
                            @else
                                Not provided
                            @endif
                        </span>
                    </div>
                    <div class="info-item">
                        <label class="info-label">Portfolio</label>
                        <span class="info-value">
                            @if(auth()->user()->portfolio_url)
                                <a href="{{ auth()->user()->portfolio_url }}" target="_blank" class="text-blue-600 hover:underline">{{ str_replace(['https://', 'http://'], '', auth()->user()->portfolio_url) }}</a>
                            @else
                                Not provided
                            @endif
                        </span>
                    </div>
                    @if(auth()->user()->bio)
                    <div class="info-item full-width">
                        <label class="info-label">Bio</label>
                        <span class="info-value">{{ auth()->user()->bio }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>



        <!-- Skills -->
        <div class="profile-section">
            <div class="section-header">
                <h3 class="section-title">Skills</h3>
                <a href="{{ route('profile.edit.skills') }}" class="btn btn-sm btn-outline">Edit</a>
            </div>
            
            <div class="section-content">
                @if(auth()->user()->skills->count() > 0)
                <div class="skills-categories">
                    @php
                        $skillsByCategory = auth()->user()->skills->groupBy('category');
                    @endphp
                    
                    @foreach($skillsByCategory as $category => $skills)
                    <div class="skill-category">
                        <h4 class="category-title">{{ ucfirst($category) }}</h4>
                        <div class="skills-list">
                            @foreach($skills as $skill)
                            <span class="skill-tag" title="{{ ucfirst($skill->proficiency_level) }} - {{ $skill->years_of_experience ?? 'Unknown' }} years">
                                {{ $skill->skill_name }}
                                @if($skill->proficiency_level !== 'intermediate')
                                    <span class="proficiency-badge {{ $skill->proficiency_level }}">{{ ucfirst($skill->proficiency_level) }}</span>
                                @endif
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state">
                    <p>No skills added yet.</p>
                    <a href="{{ route('profile.edit.skills') }}" class="btn btn-primary">Add Skills</a>
                </div>
                @endif
            </div>
        </div>

        <!-- Experience -->
        <div class="profile-section">
            <div class="section-header">
                <h3 class="section-title">Professional Experience</h3>
                <button class="btn btn-sm btn-outline" onclick="editSection('experience')">Add Experience</button>
            </div>
            
            <div class="section-content">
                @if(auth()->user()->experience->count() > 0)
                <div class="experience-list">
                    @foreach(auth()->user()->experience->sortByDesc('start_date') as $exp)
                    <div class="experience-item">
                        <div class="experience-header">
                            <h4 class="job-title">{{ $exp->job_title }}</h4>
                            <span class="company-name">{{ $exp->company_name }}</span>
                        </div>
                        <div class="experience-meta">
                            <span class="duration">
                                {{ $exp->start_date->format('M Y') }} - 
                                @if($exp->is_current)
                                    Present
                                @else
                                    {{ $exp->end_date ? $exp->end_date->format('M Y') : 'Not specified' }}
                                @endif
                                @if($exp->formattedDuration)
                                    ({{ $exp->formattedDuration }})
                                @endif
                            </span>
                            @if($exp->location)
                            <span class="location">{{ $exp->location }}</span>
                            @endif
                            @if($exp->employment_type)
                            <span class="employment-type">{{ ucfirst($exp->employment_type) }}</span>
                            @endif
                        </div>
                        <p class="experience-description">{{ $exp->description }}</p>
                        @if($exp->achievements)
                        <div class="achievements">
                            <strong>Key Achievements:</strong>
                            <p>{{ $exp->achievements }}</p>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state">
                    <p>No professional experience added yet.</p>
                    <a href="{{ route('profile.edit.experience') }}" class="btn btn-primary">Add Professional Experience</a>
                </div>
                @endif
            </div>
        </div>

        <!-- Education -->
        <div class="profile-section">
            <div class="section-header">
                <h3 class="section-title">Education</h3>
                <button class="btn btn-sm btn-outline" onclick="editSection('education')">Add Education</button>
            </div>
            
            <div class="section-content">
                @if(auth()->user()->education->count() > 0)
                <div class="education-list">
                    @foreach(auth()->user()->education->sortByDesc('graduation_year') as $edu)
                    <div class="education-item">
                        <div class="education-header">
                            <h4 class="degree">{{ $edu->degree }}</h4>
                            <span class="institution">{{ $edu->institution }}</span>
                        </div>
                        <div class="education-meta">
                            @if($edu->graduation_year)
                            <span class="graduation-year">{{ $edu->graduation_year }}</span>
                            @endif
                            @if($edu->gpa)
                            <span class="gpa">GPA: {{ $edu->formattedGpa }}</span>
                            @endif
                            @if($edu->field_of_study)
                            <span class="field-of-study">{{ $edu->field_of_study }}</span>
                            @endif
                            @if($edu->location)
                            <span class="location">{{ $edu->location }}</span>
                            @endif
                        </div>
                        @if($edu->description)
                        <p class="education-description">{{ $edu->description }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state">
                    <p>No education added yet.</p>
                    <button class="btn btn-primary" onclick="editSection('education')">Add Education</button>
                </div>
                @endif
            </div>
        </div>

        <!-- Resume -->
        <div class="profile-section">
            <div class="section-header">
                <h3 class="section-title">Resume</h3>
                <button class="btn btn-sm btn-outline" onclick="editSection('resume')">Upload New</button>
            </div>
            
            <div class="section-content">
                @if(auth()->user()->resumes->count() > 0)
                <div class="resume-list">
                    @foreach(auth()->user()->resumes as $resume)
                    <div class="resume-item {{ $resume->is_primary ? 'primary' : '' }}">
                        <div class="resume-file">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <div class="file-info">
                                <span class="file-name">{{ $resume->file_name }}</span>
                                <span class="file-size">{{ $resume->formattedFileSize }}</span>
                                @if($resume->is_primary)
                                <span class="primary-badge">Primary</span>
                                @endif
                            </div>
                            <div class="file-actions">
                                <button class="btn btn-sm btn-outline">Download</button>
                                @if(!$resume->is_primary)
                                <button class="btn btn-sm btn-outline" onclick="setPrimaryResume({{ $resume->id }})">Set Primary</button>
                                @endif
                                <button class="btn btn-sm btn-outline btn-danger" onclick="deleteResume({{ $resume->id }})">Delete</button>
                            </div>
                        </div>
                        @if($resume->title || $resume->description)
                        <div class="resume-details">
                            @if($resume->title)
                            <p><strong>Title:</strong> {{ $resume->title }}</p>
                            @endif
                            @if($resume->description)
                            <p><strong>Description:</strong> {{ $resume->description }}</p>
                            @endif
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state">
                    <p>No resumes uploaded yet.</p>
                    <button class="btn btn-primary" onclick="editSection('resume')">Upload Resume</button>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Edit Modals -->
@include('profile.modals.personal-info-modal')
@include('profile.modals.skills-modal')
@include('profile.modals.experience-modal')
@include('profile.modals.education-modal')
@include('profile.modals.resume-modal')

<script>
function editSection(section) {
    // Hide all modals first
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => modal.style.display = 'none');
    
    // Show the appropriate modal based on section
    switch(section) {
        case 'personal':
            document.getElementById('personalInfoModal').style.display = 'block';
            break;

        case 'skills':
            document.getElementById('skillsModal').style.display = 'block';
            break;
        case 'experience':
            document.getElementById('experienceModal').style.display = 'block';
            break;
        case 'education':
            document.getElementById('educationModal').style.display = 'block';
            break;
        case 'resume':
            document.getElementById('resumeModal').style.display = 'block';
            break;
        default:
            console.log('Unknown section:', section);
    }
}

function setPrimaryResume(resumeId) {
    if (confirm('Set this resume as primary?')) {
        fetch(`/api/profile/resume/${resumeId}/primary`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while setting primary resume');
        });
    }
}

function deleteResume(resumeId) {
    if (confirm('Are you sure you want to delete this resume?')) {
        fetch(`/api/profile/resume/${resumeId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the resume');
        });
    }
}
</script>

<style>
/* Profile Container */
.profile-container {
    max-width: 800px;
}

/* Avatar Image */
.avatar-image {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 2rem;
    color: #6b7280;
}

.empty-state p {
    margin-bottom: 1rem;
}

/* Proficiency Badges */
.proficiency-badge {
    font-size: 0.75rem;
    padding: 0.125rem 0.5rem;
    border-radius: 0.375rem;
    margin-left: 0.5rem;
    font-weight: 500;
}

.proficiency-badge.beginner {
    background-color: #e0f2fe;
    color: #0369a1;
    border: 1px solid #bae6fd;
}

.proficiency-badge.advanced {
    background-color: #fef3c7;
    color: #92400e;
    border: 1px solid #fde68a;
}

.proficiency-badge.expert {
    background-color: #dcfce7;
    color: #166534;
    border: 1px solid #bbf7d0;
}

/* Resume Items */
.resume-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.resume-item {
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    padding: 1rem;
}

.resume-item.primary {
    border-color: #2563eb;
    background-color: #f0f9ff;
}

.primary-badge {
    background-color: #2563eb;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 500;
    margin-left: 0.5rem;
}

.resume-details {
    margin-top: 0.75rem;
    padding-top: 0.75rem;
    border-top: 1px solid #e5e7eb;
}

.resume-details p {
    margin: 0.25rem 0;
    font-size: 0.875rem;
    color: #6b7280;
}

/* Full Width Info Items */
.info-item.full-width {
    grid-column: 1 / -1;
}

/* Employment Type and Location Tags */
.employment-type, .location {
    background-color: #e2e8f0;
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    color: #1e293b;
}

/* Achievements */
.achievements {
    margin-top: 0.75rem;
    padding: 0.75rem;
    background-color: #f8fafc;
    border-radius: 0.375rem;
}

.achievements p {
    margin: 0.25rem 0;
}

/* Field of Study */
.field-of-study {
    background-color: #e2e8f0;
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    color: #1e293b;
}

/* Profile Header */
.profile-header {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 0.75rem;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    display: flex;
    gap: 2rem;
    align-items: flex-start;
}

.profile-avatar-section {
    text-align: center;
}

.profile-avatar {
    width: 120px;
    height: 120px;
    background: #e5e7eb;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #9ca3af;
    margin-bottom: 1rem;
}

.avatar-actions {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.profile-info {
    flex: 1;
}

.profile-name {
    font-size: 2rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.profile-title {
    font-size: 1.125rem;
    color: #2563eb;
    font-weight: 500;
    margin-bottom: 0.25rem;
}

.profile-location {
    color: #6b7280;
    margin-bottom: 1rem;
}

.profile-completion {
    margin-top: 1rem;
}

.completion-bar {
    width: 100%;
    height: 8px;
    background: #e5e7eb;
    border-radius: 4px;
    overflow: hidden;
    margin-bottom: 0.5rem;
}

.completion-fill {
    height: 100%;
    background: #2563eb;
    border-radius: 4px;
}

.completion-text {
    font-size: 0.875rem;
    color: #6b7280;
}

/* Profile Actions & Settings */
.profile-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 0.75rem;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.action-buttons {
    display: flex;
    gap: 1rem;
}

.profile-stats {
    display: flex;
    gap: 2rem;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
}

.stat-label {
    font-size: 0.875rem;
    color: #6b7280;
}

/* Profile Completion Suggestions */
.profile-suggestions {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 0.75rem;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.suggestions-header {
    text-align: center;
    margin-bottom: 1.5rem;
}

.suggestions-header h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.suggestions-header p {
    font-size: 0.9375rem;
    color: #6b7280;
    margin-bottom: 1.5rem;
}

.suggestions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
}

.suggestion-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.suggestion-icon {
    font-size: 2rem;
    color: #2563eb;
}

.suggestion-content h4 {
    font-size: 1rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.suggestion-content p {
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 1.5rem;
}

.suggestion-content .btn {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    border-radius: 6px;
}

/* Profile Sections */
.profile-sections {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.profile-section {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    overflow: hidden;
}

.section-header {
    background: #f8fafc;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.section-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}

.section-content {
    padding: 1.5rem;
}

/* Information Grid */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.info-label {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
}

.info-value {
    font-size: 1rem;
    color: #1f2937;
}

/* Skills */
.skills-categories {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.skill-category {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.category-title {
    font-size: 1rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}

.skills-list {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.skill-tag {
    background: #e0f2fe;
    color: #0369a1;
    padding: 0.5rem 1rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 500;
    border: 1px solid #bae6fd;
}

/* Experience */
.experience-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.experience-item {
    padding: 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
}

.experience-header {
    margin-bottom: 0.5rem;
}

.job-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.company-name {
    color: #2563eb;
    font-weight: 500;
}

.experience-meta {
    display: flex;
    gap: 1rem;
    margin-bottom: 0.75rem;
    color: #6b7280;
    font-size: 0.875rem;
}

.experience-description {
    color: #6b7280;
    line-height: 1.5;
    margin: 0;
}

/* Education */
.education-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.education-item {
    padding: 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
}

.education-header {
    margin-bottom: 0.5rem;
}

.degree {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.institution {
    color: #2563eb;
    font-weight: 500;
}

.education-meta {
    display: flex;
    gap: 1rem;
    color: #6b7280;
    font-size: 0.875rem;
}

/* Resume */
.resume-info {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.resume-file {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    background: #f8fafc;
}

.file-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.file-name {
    font-weight: 500;
    color: #1f2937;
}

.file-size {
    font-size: 0.875rem;
    color: #6b7280;
}

.file-actions {
    display: flex;
    gap: 0.5rem;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    background-color: #fefefe;
    margin: 2% auto;
    padding: 0;
    border: 1px solid #888;
    width: 90%;
    max-width: 800px;
    max-height: 96vh;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.modal-header {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
    background-color: #fefefe;
}

.modal-header h3 {
    margin: 0;
    color: #111827;
    font-size: 1.125rem;
    font-weight: 600;
}

.close {
    color: #aaa;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    line-height: 1;
}

.close:hover,
.close:focus {
    color: #000;
}

.modal-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid #e5e7eb;
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
    flex-shrink: 0;
    background-color: #fefefe;
}

/* Form Styles */
.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #374151;
}

.form-group input[type="text"],
.form-group input[type="tel"],
.form-group input[type="url"],
.form-group input[type="date"],
.form-group input[type="number"],
.form-group input[type="file"],
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 0.5rem 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    line-height: 1.25rem;
}

.form-group input[type="file"] {
    padding: 0.375rem 0.75rem;
}

.form-group textarea {
    resize: vertical;
    min-height: 80px;
}

.form-group select {
    background-color: white;
}

/* Radio and Checkbox Styles */
.radio-group {
    display: flex;
    gap: 1rem;
}

.radio-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
}

/* Modal Form Two-Column Layout */
.modal-body {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    padding: 1.5rem;
    overflow-y: auto;
    flex: 1;
    min-height: 0;
}

.modal-body .form-group {
    margin-bottom: 1rem;
}

/* Full-width form groups for textareas and longer inputs */
.modal-body .form-group.full-width {
    grid-column: 1 / -1;
}

/* Fallback for browsers that support :has() */
@supports (selector(:has(*))) {
    .modal-body .form-group:has(textarea),
    .modal-body .form-group:has(input[type="url"]),
    .modal-body .form-group:has(input[type="date"]) {
        grid-column: 1 / -1;
    }
    
    .modal-body .form-group:has(.radio-group) {
        grid-column: 1 / -1;
    }
}

/* Fallback for browsers that don't support :has() */
@supports not (selector(:has(*))) {
    .modal-body .form-group textarea,
    .modal-body .form-group input[type="url"],
    .modal-body .form-group input[type="date"] {
        grid-column: 1 / -1;
    }
    
    .modal-body .form-group .radio-group {
        grid-column: 1 / -1;
    }
}

/* Ensure form inputs look good in two-column layout */
.modal-body .form-group input,
.modal-body .form-group select,
.modal-body .form-group textarea {
    width: 100%;
    box-sizing: border-box;
}

/* Add some spacing between form groups */
.modal-body .form-group + .form-group {
    margin-top: 0.5rem;
}

/* Improve the visual hierarchy in two-column layout */
.modal-body .form-group label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}

/* Add subtle borders to form inputs for better definition */
.modal-body .form-group input:focus,
.modal-body .form-group select:focus,
.modal-body .form-group textarea:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

/* Ensure modal content is properly scrollable */
.modal {
    overflow-y: auto;
    padding: 20px 0;
}

/* Ensure the modal body can scroll when content is long */
.modal-body::-webkit-scrollbar {
    width: 6px;
}

.modal-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.modal-body::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.modal-body::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Responsive Design */
@media (max-width: 768px) {
    .profile-header {
        flex-direction: column;
        text-align: center;
        gap: 1.5rem;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .resume-file {
        flex-direction: column;
        text-align: center;
    }
    
    .file-actions {
        justify-content: center;
    }
    
    .section-header {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
    
    .section-header .btn {
        align-self: flex-end;
    }
    
    /* Mobile: revert to single column for forms */
    .modal-body {
        grid-template-columns: 1fr;
    }
    
    /* Ensure modal is properly sized on mobile */
    .modal-content {
        margin: 1% auto;
        max-height: 98vh;
        width: 95%;
    }
}

/* Styles for multi-entry forms (skills, experience, education) */
.skill-row,
.experience-row,
.education-row {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    background-color: #f8fafc;
}

.skill-row:last-child,
.experience-row:last-child,
.education-row:last-child {
    margin-bottom: 0;
}

.skills-header,
.experience-header,
.education-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #e5e7eb;
}

.skills-header h4,
.experience-header h4,
.education-header h4 {
    margin: 0;
    color: #374151;
    font-size: 1.125rem;
    font-weight: 600;
}

/* Ensure proper spacing in multi-entry forms */
.skill-row .form-group,
.experience-row .form-group,
.education-row .form-group {
    margin-bottom: 1rem;
}

.skill-row .form-group:last-child,
.experience-row .form-group:last-child,
.education-row .form-group:last-child {
    margin-bottom: 0;
}

/* Style for the add button in headers */
.skills-header .btn,
.experience-header .btn,
.education-header .btn {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    border-radius: 6px;
}

/* Enhanced Mobile Responsiveness */
@media (max-width: 768px) {
    .profile-actions {
        flex-direction: column;
        gap: 1.5rem;
        text-align: center;
    }
    
    .action-buttons {
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .profile-stats {
        justify-content: center;
        gap: 1.5rem;
    }
    
    .suggestions-grid {
        grid-template-columns: 1fr;
    }
    
    .suggestion-item {
        flex-direction: column;
        text-align: center;
    }
    
    .suggestion-icon {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
    }
}

@media (max-width: 480px) {
    .profile-header {
        padding: 1.5rem;
        gap: 1rem;
    }
    
    .profile-avatar {
        width: 100px;
        height: 100px;
    }
    
    .profile-actions {
        padding: 1rem;
    }
    
    .action-buttons {
        flex-direction: column;
        width: 100%;
    }
    
    .action-buttons .btn {
        width: 100%;
    }
    
    .profile-stats {
        flex-direction: column;
        gap: 1rem;
    }
    
    .stat-item {
    padding: 0.75rem;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}
}

/* Light theme - consistent styling */
.profile-actions,
.profile-suggestions {
    background: white;
    color: #1f2937;
}

.suggestion-item {
    background: #f8fafc;
    color: #1f2937;
    border: 1px solid #e2e8f0;
}

.stat-item {
    background: #f8fafc;
    color: #1f2937;
    border: 1px solid #e2e8f0;
}

.suggestions-header h3,
.suggestion-content h4 {
    color: #1f2937;
}

.suggestions-header p,
.suggestion-content p {
    color: #6b7280;
}

/* Notification styles */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1000;
    max-width: 400px;
    animation: slideIn 0.3s ease-out;
}

.notification-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.5rem;
    border-radius: 0.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    color: white;
}

.notification-close {
    background: none;
    border: none;
    color: white;
    font-size: 1.25rem;
    cursor: pointer;
    margin-left: 1rem;
    opacity: 0.8;
    transition: opacity 0.2s;
}

.notification-close:hover {
    opacity: 1;
}

@keyframes slideIn {
    from { 
        transform: translateX(100%); 
        opacity: 0; 
    }
    to { 
        transform: translateX(0); 
        opacity: 1; 
    }
}

/* Loading animation for buttons */
.btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>

<script>
// Profile export functionality
function exportProfile() {
    // Show loading state
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Exporting...';
    btn.disabled = true;
    
    // Simulate export process (replace with actual API call)
    setTimeout(() => {
        // Create a simple HTML export
        const profileData = {
            name: '{{ auth()->user()->fullName }}',
            email: '{{ auth()->user()->email }}',
            title: 'Professional',
            location: '{{ auth()->user()->location ?? "No location set" }}',
            completion: '{{ auth()->user()->profileCompletion }}%'
        };
        
        // Generate HTML content
        const htmlContent = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>${profileData.name} - Profile</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 40px; }
                    .header { border-bottom: 2px solid #2563eb; padding-bottom: 20px; margin-bottom: 30px; }
                    .section { margin-bottom: 25px; }
                    .section h2 { color: #2563eb; border-bottom: 1px solid #e5e7eb; padding-bottom: 10px; }
                    .info-item { margin-bottom: 15px; }
                    .label { font-weight: bold; color: #374151; }
                    .value { margin-left: 10px; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>${profileData.name}</h1>
                    <p><strong>${profileData.title}</strong></p>
                    <p>${profileData.location} • ${profileData.email}</p>
                    <p>Profile Completion: ${profileData.completion}</p>
                </div>
                
                <div class="section">
                    <h2>Personal Information</h2>
                    <div class="info-item">
                        <span class="label">Full Name:</span>
                        <span class="value">${profileData.name}</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Email:</span>
                        <span class="value">${profileData.email}</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Current Title:</span>
                        <span class="value">${profileData.title}</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Location:</span>
                        <span class="value">${profileData.location}</span>
                    </div>
                </div>
                
                <div class="section">
                    <h2>Professional Experience</h2>
                    <p>Experience details would be included here...</p>
                </div>
                
                <div class="section">
                    <h2>Education</h2>
                    <p>Education details would be included here...</p>
                </div>
                
                <div class="section">
                    <h2>Skills</h2>
                    <p>Skills would be listed here...</p>
                </div>
                
                <p><em>Generated on ${new Date().toLocaleDateString()}</em></p>
            </body>
            </html>
        `;
        
        // Create and download file
        const blob = new Blob([htmlContent], { type: 'text/html' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `${profileData.name.replace(/\s+/g, '_')}_Profile.html`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
        
        // Reset button
        btn.innerHTML = originalText;
        btn.disabled = false;
        
        // Show success message
        showNotification('Profile exported successfully!', 'success');
    }, 2000);
}

// Toggle profile visibility
function toggleProfileVisibility() {
    const btn = event.target;
    const currentVisibility = btn.textContent.includes('Public') ? 'public' : 'private';
    const newVisibility = currentVisibility === 'public' ? 'private' : 'public';
    
    // Show loading state
    const originalText = btn.innerHTML;
    btn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Updating...';
    btn.disabled = true;
    
    // Simulate API call (replace with actual endpoint)
    setTimeout(() => {
        // Update button text
        if (newVisibility === 'public') {
            btn.innerHTML = '<svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="mr-2"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/></svg> Public Profile';
        } else {
            btn.innerHTML = '<svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="mr-2"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z"/></svg> Private Profile';
        }
        
        btn.disabled = false;
        
        // Show success message
        const visibilityText = newVisibility === 'public' ? 'public' : 'private';
        showNotification(`Profile visibility changed to ${visibilityText}!`, 'success');
    }, 1000);
}

// Show notification
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    
    // Set background color based on type
    const bgColor = type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6';
    notification.style.background = bgColor;
    
    notification.innerHTML = `
        <div class="notification-content">
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="notification-close">&times;</button>
        </div>
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}

// Enhanced edit section function
function editSection(section) {
    const routes = {
        'personal': '{{ route("profile.edit.personal") }}',
        'experience': '{{ route("profile.edit.experience") }}',
        'education': '{{ route("profile.edit.education") }}',
        'skills': '{{ route("profile.edit.skills") }}',
        'resume': '{{ route("profile.edit.resume") }}'
    };
    
    if (routes[section]) {
        window.location.href = routes[section];
    } else {
        showNotification('Edit section not found!', 'error');
    }
}

// Initialize profile page
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Add loading states for buttons
    document.querySelectorAll('.btn').forEach(btn => {
        if (btn.onclick) {
            btn.addEventListener('click', function() {
                if (!this.disabled) {
                    this.style.opacity = '0.7';
                    setTimeout(() => {
                        this.style.opacity = '1';
                    }, 200);
                }
            });
        }
    });
    
    // Profile completion animation
    const completionBar = document.querySelector('.completion-fill');
    if (completionBar) {
        const targetWidth = completionBar.style.width;
        completionBar.style.width = '0%';
        setTimeout(() => {
            completionBar.style.transition = 'width 1s ease-in-out';
            completionBar.style.width = targetWidth;
        }, 500);
    }
});
</script>
@endsection 