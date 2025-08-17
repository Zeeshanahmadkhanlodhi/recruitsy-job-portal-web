@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto px-6 py-10">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit Professional Experience</h1>
                    <p class="text-gray-600 mt-2">Manage your professional work history and career progression</p>
                </div>
                <a href="{{ route('profile') }}" class="btn btn-outline">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Profile
                </a>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="bg-white rounded-lg shadow-md p-8">
            <form id="experienceForm" class="settings-form">
                @csrf
                
                <!-- Add New Professional Experience -->
                <div class="settings-section">
                    <h3 class="section-title">Add New Professional Experience</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="job_title" class="form-label">Job Title</label>
                            <input type="text" id="job_title" class="form-input" placeholder="e.g., Senior Developer">
                        </div>
                        <div class="form-group">
                            <label for="company_name" class="form-label">Company</label>
                            <input type="text" id="company_name" class="form-input" placeholder="e.g., Tech Corp">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" id="start_date" class="form-input">
                        </div>
                        <div class="form-group">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" id="end_date" class="form-input">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="job_description" class="form-label">Job Description</label>
                        <textarea id="job_description" rows="3" class="form-input" 
                                  placeholder="Describe your responsibilities and achievements..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" id="current_job" class="form-checkbox">
                            <span class="checkbox-text">I currently work here</span>
                        </label>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" onclick="addExperience()" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus mr-2"></i>Add Professional Experience
                        </button>
                    </div>
                </div>

                <!-- Existing Professional Experience -->
                <div class="settings-section">
                    <h3 class="section-title">Your Professional Experience</h3>
                    <div id="experienceList" class="experience-list">
                        @foreach(auth()->user()->experience as $exp)
                        <div class="experience-item" data-experience-id="{{ $exp->id }}">
                            <div class="experience-info">
                                <h4 class="experience-title">{{ $exp->job_title }}</h4>
                                <p class="experience-company">{{ $exp->company_name }}</p>
                                <p class="experience-duration">{{ $exp->formatted_duration }}</p>
                                <p class="experience-description">{{ $exp->job_description }}</p>
                            </div>
                            <button type="button" onclick="removeExperience({{ $exp->id }})" class="btn btn-outline btn-sm danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="form-actions">
                    <a href="{{ route('profile') }}" class="btn btn-outline btn-sm">Cancel</a>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-save mr-2"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Settings Form Styles - Following the exact settings UI pattern */
.settings-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.settings-section {
    margin-bottom: 3rem;
    padding: 2rem;
    background: #ffffff;
    border-radius: 0.75rem;
    border: 1px solid #e5e7eb;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
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

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    padding: 0.5rem 0;
}

.form-label {
    font-size: 0.875rem;
    color: #374151;
    font-weight: 500;
}

.form-input {
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    background: white;
    width: 100%;
    transition: all 0.2s ease-in-out;
}

.form-input:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.form-input:hover {
    border-color: #9ca3af;
}

.form-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    padding: 1.5rem 0;
    border-top: 1px solid #e5e7eb;
    margin-bottom: 2rem;
}

/* Checkbox Styles */
.checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
}

.form-checkbox {
    width: 1rem;
    height: 1rem;
    border: 1px solid #d1d5db;
    border-radius: 0.25rem;
    cursor: pointer;
}

.checkbox-text {
    font-size: 0.875rem;
    color: #6b7280;
}

/* Experience List */
.experience-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    padding: 1.5rem;
    background: #f8fafc;
    border-radius: 0.75rem;
    border: 1px solid #e2e8f0;
    margin-top: 1rem;
}

.experience-item {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 1.5rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    background: #f9fafb;
    margin: 0.75rem 0;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.experience-info {
    flex: 1;
}

.experience-title {
    font-size: 1rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.experience-company {
    color: #6b7280;
    margin-bottom: 0.25rem;
}

.experience-duration {
    font-size: 0.875rem;
    color: #9ca3af;
    margin-bottom: 0.5rem;
}

.experience-description {
    color: #374151;
    line-height: 1.5;
}

/* Button Styles - Following settings UI */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    font-weight: 500;
    font-size: 0.875rem;
    text-decoration: none;
    transition: all 0.2s ease-in-out;
    border: 1px solid transparent;
    cursor: pointer;
    min-height: 36px;
}

.btn-primary {
    background: #2563eb;
    color: white;
    border-color: #2563eb;
}

.btn-primary:hover {
    background: #1d4ed8;
    border-color: #1d4ed8;
    transform: translateY(-1px);
}

.btn-outline {
    background: white;
    color: #6b7280;
    border-color: #d1d5db;
}

.btn-outline:hover {
    background: #f9fafb;
    border-color: #9ca3af;
    color: #374151;
}

.btn-secondary {
    background: #6b7280;
    color: white;
    border-color: #6b7280;
}

.btn-secondary:hover {
    background: #4b5563;
    border-color: #4b5563;
}

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    min-height: 36px;
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
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .form-actions .btn {
        width: 100%;
    }
    
    .experience-item {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
}
</style>

<script>
let experiencesToAdd = [];
let experiencesToRemove = [];

// Handle current job checkbox
document.getElementById('current_job').addEventListener('change', function() {
    const endDateInput = document.getElementById('end_date');
    if (this.checked) {
        endDateInput.disabled = true;
        endDateInput.value = '';
    } else {
        endDateInput.disabled = false;
    }
});

function addExperience() {
    const jobTitle = document.getElementById('job_title').value.trim();
    const companyName = document.getElementById('company_name').value.trim();
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    const jobDescription = document.getElementById('job_description').value.trim();
    const isCurrentJob = document.getElementById('current_job').checked;
    
    console.log('Adding experience with data:', {
        jobTitle, companyName, startDate, endDate, jobDescription, isCurrentJob
    });
    
    if (!jobTitle || !companyName || !startDate || (!isCurrentJob && !endDate) || !jobDescription) {
        showAlert('Please fill in all required fields', 'error');
        return;
    }
    
    const experienceData = {
        job_title: jobTitle,
        company_name: companyName,
        start_date: startDate,
        end_date: isCurrentJob ? null : endDate,
        job_description: jobDescription
    };
    
    experiencesToAdd.push(experienceData);
    console.log('Added to experiencesToAdd array. Current array:', experiencesToAdd);
    
    displayExperience(experienceData);
    
    // Clear form
    clearExperienceForm();
}

function displayExperience(experienceData) {
    const container = document.getElementById('experienceList');
    const expDiv = document.createElement('div');
    expDiv.className = 'experience-item border-2 border-blue-200 bg-blue-50 rounded-lg p-4';
    expDiv.innerHTML = `
        <div class="flex justify-between items-start mb-3">
            <div>
                <h4 class="font-medium text-gray-900">${experienceData.job_title}</h4>
                <p class="text-gray-600">${experienceData.company_name}</p>
                <p class="text-sm text-gray-500">${experienceData.start_date} - ${experienceData.end_date || 'Present'}</p>
                <span class="text-blue-600 text-xs">(New)</span>
            </div>
            <button type="button" onclick="removeNewExperience(this)" class="text-red-500 hover:text-red-700">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <p class="text-gray-700">${experienceData.job_description}</p>
    `;
    container.appendChild(expDiv);
}

function removeNewExperience(button) {
    const expItem = button.closest('.experience-item');
    const jobTitle = expItem.querySelector('h4').textContent;
    const companyName = expItem.querySelector('p').textContent;
    
    // Remove from experiencesToAdd array
    experiencesToAdd = experiencesToAdd.filter(exp => 
        exp.job_title !== jobTitle || exp.company_name !== companyName
    );
    expItem.remove();
}

function removeExperience(experienceId) {
    if (confirm('Are you sure you want to remove this experience?')) {
        experiencesToRemove.push(experienceId);
        const expItem = document.querySelector(`[data-experience-id="${experienceId}"]`);
        expItem.style.opacity = '0.5';
        expItem.style.backgroundColor = '#fef2f2';
    }
}

function clearExperienceForm() {
    document.getElementById('job_title').value = '';
    document.getElementById('company_name').value = '';
    document.getElementById('start_date').value = '';
    document.getElementById('end_date').value = '';
    document.getElementById('job_description').value = '';
    document.getElementById('current_job').checked = false;
    document.getElementById('end_date').disabled = false;
}

document.getElementById('experienceForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    console.log('=== FORM SUBMISSION DEBUG ===');
    console.log('Form submitted');
    console.log('experiencesToAdd array length:', experiencesToAdd.length);
    console.log('experiencesToAdd content:', experiencesToAdd);
    console.log('experiencesToRemove array length:', experiencesToRemove.length);
    console.log('experiencesToRemove content:', experiencesToRemove);
    
    // Check if there's anything to save
    if (experiencesToAdd.length === 0 && experiencesToRemove.length === 0) {
        console.log('No changes to save!');
        showAlert('No changes to save. Please add some experience first.', 'error');
        return;
    }
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    try {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
        
        const requestData = {
            experiences_to_add: experiencesToAdd,
            experiences_to_remove: experiencesToRemove
        };
        
        console.log('Final request data being sent:', requestData);
        console.log('Request data JSON stringified:', JSON.stringify(requestData));
        
        const response = await fetch('/api/profile/experience', {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(requestData)
        });
        
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        if (response.ok) {
            const responseData = await response.json();
            console.log('Success response:', responseData);
            showAlert('Professional experience updated successfully!', 'success');
            
            // Redirect back to profile page after a short delay
            setTimeout(() => {
                window.location.href = '{{ route("profile") }}';
            }, 1500);
        } else {
            const error = await response.json();
            console.error('Error response:', error);
            showAlert(error.message || 'Failed to update professional experience', 'error');
        }
    } catch (error) {
        console.error('Exception occurred:', error);
        showAlert('An error occurred while updating your experience', 'error');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});



function showAlert(message, type) {
    // Create alert element
    const alert = document.createElement('div');
    alert.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    alert.textContent = message;
    
    document.body.appendChild(alert);
    
    // Remove alert after 3 seconds
    setTimeout(() => {
        alert.remove();
    }, 3000);
}
</script>
@endsection
