@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto px-6 py-10">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit Resumes</h1>
                    <p class="text-gray-600 mt-2">Manage your resume files and documents</p>
                </div>
                <a href="{{ route('profile') }}" class="btn btn-outline">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Profile
                </a>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="bg-white rounded-lg shadow-md p-8">
            <form id="resumeForm" class="space-y-6">
                @csrf
                
                                 <!-- Upload New Resume -->
                 <div class="settings-section">
                     <h3 class="section-title">Upload New Resume</h3>
                                          <div class="space-y-6">
                         <div class="form-group">
                             <label for="resume_file" class="form-label">Resume File</label>
                             <input type="file" id="resume_file" accept=".pdf,.doc,.docx" class="form-input w-full" required>
                             <p class="text-sm text-gray-500 mt-1">Accepted formats: PDF, DOC, DOCX (Max 5MB)</p>
                         </div>
                         <div class="form-group">
                             <label for="resume_title" class="form-label">Resume Title</label>
                             <input type="text" id="resume_title" class="form-input w-full" placeholder="e.g., Software Developer Resume" required>
                         </div>
                         <div class="form-group">
                             <label for="resume_description" class="form-label">Description (Optional)</label>
                             <textarea id="resume_description" rows="3" class="form-textarea w-full" 
                                       placeholder="Brief description of this resume version..."></textarea>
                         </div>
                         <div class="form-group">
                             <button type="button" onclick="uploadResume()" class="btn btn-primary">
                                 <i class="fas fa-upload mr-2"></i>Upload Resume
                             </button>
                         </div>
                     </div>
                </div>

                                 <!-- Current Resumes -->
                 <div class="settings-section">
                     <h3 class="section-title">Current Resumes</h3>
                                          <div id="resumeList" class="resume-list">
                         @foreach(auth()->user()->resumes as $resume)
                         <div class="resume-item" data-resume-id="{{ $resume->id }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-file-pdf text-red-500 text-2xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $resume->title }}</h4>
                                        <p class="text-sm text-gray-500">{{ $resume->formatted_file_size }}</p>
                                        <p class="text-sm text-gray-500">Uploaded: {{ $resume->created_at->format('M d, Y') }}</p>
                                        @if($resume->description)
                                        <p class="text-sm text-gray-600 mt-1">{{ $resume->description }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($resume->is_primary)
                                    <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Primary</span>
                                    @else
                                    <button type="button" onclick="setPrimaryResume({{ $resume->id }})" class="btn btn-sm btn-outline">
                                        Set as Primary
                                    </button>
                                    @endif
                                    <a href="{{ Storage::url($resume->file_path) }}" target="_blank" class="btn btn-sm btn-outline">
                                        <i class="fas fa-eye mr-1"></i>View
                                    </a>
                                    <button type="button" onclick="removeResume({{ $resume->id }})" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                                 <!-- Submit Button -->
                 <div class="form-actions">
                     <a href="{{ route('profile') }}" class="btn btn-outline">Cancel</a>
                     <button type="submit" class="btn btn-primary" disabled>
                         <i class="fas fa-save mr-2"></i>Save Changes
                     </button>
                 </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Settings Form Styles - Following the exact settings UI pattern */
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

.form-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    padding: 1.5rem 0;
    border-top: 1px solid #e5e7eb;
    margin-top: 1rem;
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

/* Resume List */
.resume-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    padding: 1.5rem;
    background: #f8fafc;
    border-radius: 0.75rem;
    border: 1px solid #e2e8f0;
    margin-top: 1rem;
}

/* Enhanced Resume Form Styles */
.resume-item {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    padding: 1.5rem;
    margin: 0.75rem 0;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    transition: all 0.2s ease-in-out;
}

.resume-item:hover {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transform: translateY(-1px);
}

.form-input, .form-textarea {
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    background: white;
    width: 100%;
    transition: all 0.2s ease-in-out;
}

.form-input:focus, .form-textarea:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.form-input:hover, .form-textarea:hover {
    border-color: #9ca3af;
}

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

.btn-danger {
    background: #dc2626;
    color: white;
    border-color: #dc2626;
}

.btn-danger:hover {
    background: #b91c1c;
    border-color: #b91c1c;
}

/* Responsive Design */
@media (max-width: 768px) {
    .form-actions {
        flex-direction: column;
    }
    
    .form-actions .btn {
        width: 100%;
    }
    
    .resume-item {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
}
</style>

<script>
let resumesToAdd = [];
let resumesToRemove = [];

function uploadResume() {
    const fileInput = document.getElementById('resume_file');
    const titleInput = document.getElementById('resume_title');
    const descriptionInput = document.getElementById('resume_description');
    
    const file = fileInput.files[0];
    const title = titleInput.value.trim();
    const description = descriptionInput.value.trim();
    
    console.log('=== UPLOAD RESUME DEBUG ===');
    console.log('File:', file);
    console.log('Title:', title);
    console.log('Description:', description);
    
    if (!file) {
        showAlert('Please select a file to upload', 'error');
        return;
    }
    
    if (!title) {
        showAlert('Please enter a resume title', 'error');
        return;
    }
    
    // Check file size (5MB limit)
    if (file.size > 5 * 1024 * 1024) {
        showAlert('File size must be less than 5MB', 'error');
        return;
    }
    
    // Check file type
    const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    if (!allowedTypes.includes(file.type)) {
        showAlert('Please select a valid file type (PDF, DOC, or DOCX)', 'error');
        return;
    }
    
    const formData = new FormData();
    formData.append('resume_file', file);
    formData.append('title', title);
    formData.append('description', description);
    formData.append('_token', document.querySelector('input[name="_token"]').value);
    
    console.log('FormData contents:');
    for (let [key, value] of formData.entries()) {
        console.log(key, ':', value);
    }
    
    // Show loading state
    const uploadBtn = document.querySelector('button[onclick="uploadResume()"]');
    const originalText = uploadBtn.innerHTML;
    uploadBtn.disabled = true;
    uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Uploading...';
    
    console.log('Sending request to /api/profile/resume...');
    
    fetch('/api/profile/resume', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        
        if (data.success) {
            showAlert('Resume uploaded successfully!', 'success');
            
            // Add to resumesToAdd array
            const resumeData = {
                id: data.resume.id,
                title: title,
                description: description,
                file_path: data.resume.file_path,
                file_size: file.size,
                created_at: new Date().toISOString()
            };
            
            resumesToAdd.push(resumeData);
            displayResume(resumeData);
            
            // Clear form
            clearResumeForm();
            
            // Enable submit button
            document.querySelector('button[type="submit"]').disabled = false;
        } else {
            showAlert(data.message || 'Failed to upload resume', 'error');
        }
    })
    .catch(error => {
        console.error('Upload error:', error);
        showAlert('An error occurred while uploading the resume', 'error');
    })
    .finally(() => {
        uploadBtn.disabled = false;
        uploadBtn.innerHTML = originalText;
    });
}

function displayResume(resumeData) {
    const container = document.getElementById('resumeList');
    const resumeDiv = document.createElement('div');
    resumeDiv.className = 'resume-item border-2 border-blue-200 bg-blue-50 rounded-lg p-4';
    resumeDiv.innerHTML = `
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <i class="fas fa-file-pdf text-red-500 text-2xl"></i>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">${resumeData.title}</h4>
                    <p class="text-sm text-gray-500">${formatFileSize(resumeData.file_size)}</p>
                    <p class="text-sm text-gray-500">Uploaded: ${new Date(resumeData.created_at).toLocaleDateString()}</p>
                    ${resumeData.description ? `<p class="text-sm text-gray-600 mt-1">${resumeData.description}</p>` : ''}
                    <span class="text-blue-600 text-xs">(New)</span>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <button type="button" onclick="removeNewResume(this)" class="btn btn-sm btn-danger">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    container.appendChild(resumeDiv);
}

function removeNewResume(button) {
    const resumeItem = button.closest('.resume-item');
    const title = resumeItem.querySelector('h4').textContent;
    
    // Remove from resumesToAdd array
    resumesToAdd = resumesToAdd.filter(resume => resume.title !== title);
    resumeItem.remove();
    
    // Disable submit button if no changes
    if (resumesToAdd.length === 0 && resumesToRemove.length === 0) {
        document.querySelector('button[type="submit"]').disabled = true;
    }
}

function removeResume(resumeId) {
    if (confirm('Are you sure you want to remove this resume?')) {
        resumesToRemove.push(resumeId);
        const resumeItem = document.querySelector(`[data-resume-id="${resumeId}"]`);
        resumeItem.style.opacity = '0.5';
        resumeItem.style.backgroundColor = '#fef2f2';
        
        // Enable submit button
        document.querySelector('button[type="submit"]').disabled = false;
    }
}

function setPrimaryResume(resumeId) {
    fetch(`/api/profile/resume/${resumeId}/primary`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Primary resume updated successfully!', 'success');
            // Refresh the page to show updated primary status
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showAlert(data.message || 'Failed to update primary resume', 'error');
        }
    })
    .catch(error => {
        showAlert('An error occurred while updating the primary resume', 'error');
    });
}

function clearResumeForm() {
    document.getElementById('resume_file').value = '';
    document.getElementById('resume_title').value = '';
    document.getElementById('resume_description').value = '';
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

document.getElementById('resumeForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    console.log('=== RESUME FORM SUBMISSION DEBUG ===');
    console.log('Form submitted');
    console.log('resumesToAdd array length:', resumesToAdd.length);
    console.log('resumesToAdd content:', resumesToAdd);
    console.log('resumesToRemove array length:', resumesToRemove.length);
    console.log('resumesToRemove content:', resumesToRemove);
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    try {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
        
        // Handle resume deletions
        for (const resumeId of resumesToRemove) {
            console.log('Deleting resume ID:', resumeId);
            const response = await fetch(`/api/profile/resume/${resumeId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                }
            });
            
            console.log('Delete response status:', response.status);
            
            if (!response.ok) {
                throw new Error('Failed to delete resume');
            }
        }
        
        showAlert('Resume changes saved successfully!', 'success');
        
        // Redirect back to profile page after a short delay
        setTimeout(() => {
            window.location.href = '{{ route("profile") }}';
        }, 1500);
        
    } catch (error) {
        console.error('Form submission error:', error);
        showAlert('An error occurred while saving your changes', 'error');
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
