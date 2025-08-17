@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto px-6 py-10">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit Education</h1>
                    <p class="text-gray-600 mt-2">Manage your educational background</p>
                </div>
                <a href="{{ route('profile') }}" class="btn btn-outline">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Profile
                </a>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="bg-white rounded-lg shadow-md p-8">
            <form id="educationForm" class="settings-form">
                @csrf
                
                <!-- Add New Education -->
                <div class="settings-section">
                    <h3 class="section-title">Add New Education</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="degree" class="form-label">Degree</label>
                            <input type="text" id="degree" class="form-input" placeholder="e.g., Bachelor of Science">
                        </div>
                        <div class="form-group">
                            <label for="field_of_study" class="form-label">Field of Study</label>
                            <input type="text" id="field_of_study" class="form-input" placeholder="e.g., Computer Science">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="institution" class="form-label">Institution</label>
                            <input type="text" id="institution" class="form-input" placeholder="e.g., University of Technology">
                        </div>
                        <div class="form-group">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" id="location" class="form-input" placeholder="e.g., New York, NY">
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
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="gpa" class="form-label">GPA</label>
                            <input type="number" id="gpa" class="form-input" step="0.01" min="0" max="4" placeholder="e.g., 3.8">
                        </div>
                        <div class="form-group">
                            <label for="scale" class="form-label">GPA Scale</label>
                            <select id="scale" class="form-select">
                                <option value="4.0">4.0 Scale</option>
                                <option value="5.0">5.0 Scale</option>
                                <option value="10.0">10.0 Scale</option>
                                <option value="100">100 Scale</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" rows="3" class="form-input" 
                                  placeholder="Describe your studies, achievements, or relevant coursework..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" id="current_education" class="form-checkbox">
                            <span class="checkbox-text">Currently studying here</span>
                        </label>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" onclick="addEducation()" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus mr-2"></i>Add Education
                        </button>
                    </div>
                </div>

                <!-- Existing Education -->
                <div class="settings-section">
                    <h3 class="section-title">Current Education</h3>
                    <div id="educationList" class="education-list">
                        @foreach(auth()->user()->education as $edu)
                        <div class="education-item" data-education-id="{{ $edu->id }}">
                            <div class="education-info">
                                <h4 class="education-degree">{{ $edu->degree }}</h4>
                                <p class="education-field">{{ $edu->field_of_study }}</p>
                                <p class="education-institution">{{ $edu->institution }}</p>
                                <p class="education-duration">{{ $edu->formatted_duration }}</p>
                                @if($edu->gpa)
                                <p class="education-gpa">GPA: {{ $edu->formatted_gpa }}</p>
                                @endif
                            </div>
                            <button type="button" onclick="removeEducation({{ $edu->id }})" class="btn btn-outline btn-sm danger">
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

.form-input,
.form-select {
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    background: white;
    width: 100%;
    transition: all 0.2s ease-in-out;
}

.form-input:focus,
.form-select:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.form-input:hover,
.form-select:hover {
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

/* Education List */
.education-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    padding: 1.5rem;
    background: #f8fafc;
    border-radius: 0.75rem;
    border: 1px solid #e2e8f0;
    margin-top: 1rem;
}

.education-item {
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

.education-info {
    flex: 1;
}

.education-degree {
    font-size: 1rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.education-field {
    color: #6b7280;
    margin-bottom: 0.25rem;
}

.education-institution {
    color: #6b7280;
    margin-bottom: 0.25rem;
}

.education-duration {
    font-size: 0.875rem;
    color: #9ca3af;
    margin-bottom: 0.25rem;
}

.education-gpa {
    font-size: 0.875rem;
    color: #9ca3af;
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
    
    .education-item {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
}
</style>

<script>
let educationToAdd = [];
let educationToRemove = [];

// Handle current education checkbox
document.getElementById('current_education').addEventListener('change', function() {
    const endDateInput = document.getElementById('end_date');
    if (this.checked) {
        endDateInput.disabled = true;
        endDateInput.value = '';
    } else {
        endDateInput.disabled = false;
    }
});

function addEducation() {
    const degree = document.getElementById('degree').value.trim();
    const fieldOfStudy = document.getElementById('field_of_study').value.trim();
    const institution = document.getElementById('institution').value.trim();
    const location = document.getElementById('location').value.trim();
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    const gpa = document.getElementById('gpa').value;
    const scale = document.getElementById('scale').value;
    const description = document.getElementById('description').value.trim();
    const isCurrentEducation = document.getElementById('current_education').checked;
    
    if (!degree || !fieldOfStudy || !institution || !startDate || (!isCurrentEducation && !endDate)) {
        showAlert('Please fill in all required fields', 'error');
        return;
    }
    
    const educationData = {
        degree: degree,
        field_of_study: fieldOfStudy,
        institution: institution,
        location: location,
        start_date: startDate,
        end_date: isCurrentEducation ? null : endDate,
        gpa: gpa || null,
        scale: scale,
        description: description
    };
    
    console.log('Education data to add:', educationData);
    
    educationToAdd.push(educationData);
    console.log('Added to educationToAdd array. Current array:', educationToAdd);
    
    displayEducation(educationData);
    
    // Clear form
    clearEducationForm();
}

function displayEducation(educationData) {
    const container = document.getElementById('educationList');
    const eduDiv = document.createElement('div');
    eduDiv.className = 'education-item border-2 border-blue-200 bg-blue-50 rounded-lg p-4';
    eduDiv.innerHTML = `
        <div class="flex justify-between items-start mb-3">
            <div>
                <h4 class="font-medium text-gray-900">${educationData.degree}</h4>
                <p class="text-gray-600">${educationData.field_of_study}</p>
                <p class="text-gray-600">${educationData.institution}</p>
                <p class="text-sm text-gray-500">${educationData.start_date} - ${educationData.end_date || 'Present'}</p>
                ${educationData.gpa ? `<p class="text-sm text-gray-500">GPA: ${educationData.gpa}/${educationData.scale}</p>` : ''}
                <span class="text-blue-600 text-xs">(New)</span>
            </div>
            <button type="button" onclick="removeNewEducation(this)" class="text-red-500 hover:text-red-700">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        ${educationData.description ? `<p class="text-gray-700">${educationData.description}</p>` : ''}
    `;
    container.appendChild(eduDiv);
}

function removeNewEducation(button) {
    const eduItem = button.closest('.education-item');
    const degree = eduItem.querySelector('h4').textContent;
    const fieldOfStudy = eduItem.querySelector('p').textContent;
    
    // Remove from educationToAdd array
    educationToAdd = educationToAdd.filter(edu => 
        edu.degree !== degree || edu.field_of_study !== fieldOfStudy
    );
    eduItem.remove();
}

function removeEducation(educationId) {
    if (confirm('Are you sure you want to remove this education?')) {
        educationToRemove.push(educationId);
        const eduItem = document.querySelector(`[data-education-id="${educationId}"]`);
        eduItem.style.opacity = '0.5';
        eduItem.style.backgroundColor = '#fef2f2';
    }
}

function clearEducationForm() {
    document.getElementById('degree').value = '';
    document.getElementById('field_of_study').value = '';
    document.getElementById('institution').value = '';
    document.getElementById('location').value = '';
    document.getElementById('start_date').value = '';
    document.getElementById('end_date').value = '';
    document.getElementById('gpa').value = '';
    document.getElementById('scale').value = '4.0';
    document.getElementById('description').value = '';
    document.getElementById('current_education').checked = false;
    document.getElementById('end_date').disabled = false;
}

document.getElementById('educationForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    console.log('=== EDUCATION FORM SUBMISSION DEBUG ===');
    console.log('Form submitted');
    console.log('educationToAdd array length:', educationToAdd.length);
    console.log('educationToAdd content:', educationToAdd);
    console.log('educationToRemove array length:', educationToRemove.length);
    console.log('educationToRemove content:', educationToRemove);
    
    // Check if there's anything to save
    if (educationToAdd.length === 0 && educationToRemove.length === 0) {
        console.log('No changes to save!');
        showAlert('No changes to save. Please add some education first.', 'error');
        return;
    }
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    try {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
        
        const requestData = {
            education_to_add: educationToAdd,
            education_to_remove: educationToRemove
        };
        
        console.log('Final request data being sent:', requestData);
        console.log('Request data JSON stringified:', JSON.stringify(requestData));
        
        const response = await fetch('/api/profile/education', {
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
            showAlert('Education updated successfully!', 'success');
            
            // Redirect back to profile page after a short delay
            setTimeout(() => {
                window.location.href = '{{ route("profile") }}';
            }, 1500);
        } else {
            const error = await response.json();
            console.error('Error response:', error);
            showAlert(error.message || 'Failed to update education', 'error');
        }
    } catch (error) {
        console.error('Exception occurred:', error);
        showAlert('An error occurred while updating your education', 'error');
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
