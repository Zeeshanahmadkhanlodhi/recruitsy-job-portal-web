@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto px-6 py-10">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit Personal Information</h1>
                    <p class="text-gray-600 mt-2">Update your personal details and contact information</p>
                </div>
                <a href="{{ route('profile') }}" class="btn btn-outline">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Profile
                </a>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="bg-white rounded-lg shadow-md p-8">
            <form id="personalInfoForm" class="settings-form">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" id="first_name" name="first_name" value="{{ auth()->user()->first_name }}" 
                               class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" id="last_name" name="last_name" value="{{ auth()->user()->last_name }}" 
                               class="form-input" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ auth()->user()->email }}" 
                           class="form-input" required>
                </div>

                <div class="form-group">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" id="phone" name="phone" value="{{ auth()->user()->phone }}" 
                           class="form-input">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" 
                               value="{{ auth()->user()->date_of_birth }}" class="form-input">
                    </div>

                    <div class="form-group">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" id="location" name="location" value="{{ auth()->user()->location }}" 
                               class="form-input" placeholder="City, Country">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="linkedin_url" class="form-label">LinkedIn URL</label>
                        <input type="url" id="linkedin_url" name="linkedin_url" value="{{ auth()->user()->linkedin_url }}" 
                               class="form-input" placeholder="https://linkedin.com/in/username">
                    </div>

                    <div class="form-group">
                        <label for="github_url" class="form-label">GitHub URL</label>
                        <input type="url" id="github_url" name="github_url" value="{{ auth()->user()->github_url }}" 
                               class="form-input" placeholder="https://github.com/username">
                    </div>
                </div>

                <div class="form-group">
                    <label for="portfolio_url" class="form-label">Portfolio URL</label>
                    <input type="url" id="portfolio_url" name="portfolio_url" value="{{ auth()->user()->portfolio_url }}" 
                           class="form-input" placeholder="https://yourportfolio.com">
                </div>

                <div class="form-group">
                    <label for="bio" class="form-label">Bio</label>
                    <textarea id="bio" name="bio" rows="4" 
                              class="form-input" placeholder="Tell us about yourself, your background, skills, and what makes you unique...">{{ auth()->user()->bio }}</textarea>
                    <p class="text-sm text-gray-500 mt-2">Share your story, experience, and what drives you professionally.</p>
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
    margin-top: 1rem;
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
}
</style>

<script>
document.getElementById('personalInfoForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    try {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
        
        const response = await fetch('/api/profile/personal-info', {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                first_name: formData.get('first_name'),
                last_name: formData.get('last_name'),
                email: formData.get('email'),
                phone: formData.get('phone'),
                date_of_birth: formData.get('date_of_birth'),
                location: formData.get('location'),
                linkedin_url: formData.get('linkedin_url'),
                github_url: formData.get('github_url'),
                portfolio_url: formData.get('portfolio_url'),
                bio: formData.get('bio')
            })
        });
        
        if (response.ok) {
            // Show success message
            showAlert('Personal information updated successfully!', 'success');
            
            // Redirect back to profile page after a short delay
            setTimeout(() => {
                window.location.href = '{{ route("profile") }}';
            }, 1500);
        } else {
            const error = await response.json();
            showAlert(error.message || 'Failed to update personal information', 'error');
        }
    } catch (error) {
        showAlert('An error occurred while updating your information', 'error');
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
