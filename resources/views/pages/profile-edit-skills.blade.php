@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto px-6 py-10">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit Skills</h1>
                    <p class="text-gray-600 mt-2">Manage your technical and soft skills</p>
                </div>
                <a href="{{ route('profile') }}" class="btn btn-outline">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Profile
                </a>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="bg-white rounded-lg shadow-md p-8">
            <form id="skillsForm" class="settings-form">
                @csrf
                
                <!-- Technical Skills -->
                <div class="settings-section">
                    <h3 class="section-title">Technical Skills</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="skill_name" class="form-label">Skill Name</label>
                            <input type="text" id="skill_name" placeholder="e.g., JavaScript, Python" 
                                   class="form-input">
                        </div>
                        <div class="form-group">
                            <label for="skill_level" class="form-label">Skill Level</label>
                            <select id="skill_level" class="form-select">
                                <option value="beginner">Beginner</option>
                                <option value="intermediate">Intermediate</option>
                                <option value="advanced">Advanced</option>
                                <option value="expert">Expert</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="button" onclick="addSkill()" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus mr-2"></i>Add Skill
                        </button>
                    </div>
                    
                    <div id="technicalSkillsList" class="skills-list">
                        @foreach(auth()->user()->skills as $skill)
                        <div class="skill-item" data-skill-id="{{ $skill->id }}">
                            <div class="skill-info">
                                <span class="skill-name">{{ $skill->skill_name }}</span>
                                <span class="skill-level {{ $skill->proficiency_level }}">
                                    {{ ucfirst($skill->proficiency_level) }}
                                </span>
                            </div>
                            <button type="button" onclick="removeSkill({{ $skill->id }})" class="btn btn-outline btn-sm danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Soft Skills -->
                <div class="settings-section">
                    <h3 class="section-title">Soft Skills</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="soft_skill_name" class="form-label">Skill Name</label>
                            <input type="text" id="soft_skill_name" placeholder="e.g., Leadership, Communication" 
                                   class="form-input">
                        </div>
                        <div class="form-group">
                            <label for="soft_skill_level" class="form-label">Skill Level</label>
                            <select id="soft_skill_level" class="form-select">
                                <option value="beginner">Beginner</option>
                                <option value="intermediate">Intermediate</option>
                                <option value="advanced">Advanced</option>
                                <option value="expert">Expert</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="button" onclick="addSoftSkill()" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus mr-2"></i>Add Skill
                        </button>
                    </div>
                    
                    <div id="softSkillsList" class="skills-list">
                        @foreach(auth()->user()->skills->where('category', 'soft') as $skill)
                        <div class="skill-item" data-skill-id="{{ $skill->id }}">
                            <div class="skill-info">
                                <span class="skill-name">{{ $skill->skill_name }}</span>
                                <span class="skill-level {{ $skill->proficiency_level }}">
                                    {{ ucfirst($skill->proficiency_level) }}
                                </span>
                            </div>
                            <button type="button" onclick="removeSkill({{ $skill->id }})" class="btn btn-outline btn-sm danger">
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

/* Skills List */
.skills-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    padding: 1.5rem;
    background: #f8fafc;
    border-radius: 0.75rem;
    border: 1px solid #e2e8f0;
    margin-top: 1rem;
}

.skill-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    background: #f9fafb;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    width: fit-content;
    min-width: 200px;
}

.skill-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex: 1;
    min-width: 0;
}

.skill-name {
    font-weight: 500;
    color: #1f2937;
}

.skill-level {
    padding: 0.25rem 0.5rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
    white-space: nowrap;
}

.skill-level.beginner {
    background: #dcfce7;
    color: #16a34a;
}

.skill-level.intermediate {
    background: #fef3c7;
    color: #d97706;
}

.skill-level.advanced {
    background: #dbeafe;
    color: #2563eb;
}

.skill-level.expert {
    background: #f3e8ff;
    color: #7c3aed;
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
    
    .skills-list {
        gap: 0.5rem;
    }
    
    .skill-item {
        flex-direction: column;
        gap: 0.5rem;
        align-items: stretch;
        min-width: 150px;
        width: 100%;
    }
    
    .skill-info {
        flex-direction: column;
        gap: 0.5rem;
        align-items: stretch;
    }
}
</style>

<script>
let skillsToAdd = [];
let skillsToRemove = [];

function addSkill() {
    const skillName = document.getElementById('skill_name').value.trim();
    const skillLevel = document.getElementById('skill_level').value;
    
    if (!skillName) {
        showAlert('Please enter a skill name', 'error');
        return;
    }
    
    const skillData = {
        skill_name: skillName,
        skill_level: skillLevel,
        skill_type: 'technical'
    };
    
    skillsToAdd.push(skillData);
    displaySkill(skillData, 'technicalSkillsList');
    
    // Clear inputs
    document.getElementById('skill_name').value = '';
    document.getElementById('skill_level').value = 'beginner';
}

function addSoftSkill() {
    const skillName = document.getElementById('soft_skill_name').value.trim();
    const skillLevel = document.getElementById('soft_skill_level').value;
    
    if (!skillName) {
        showAlert('Please enter a skill name', 'error');
        return;
    }
    
    const skillData = {
        skill_name: skillName,
        skill_level: skillLevel,
        skill_type: 'soft'
    };
    
    skillsToAdd.push(skillData);
    displaySkill(skillData, 'softSkillsList');
    
    // Clear inputs
    document.getElementById('soft_skill_name').value = '';
    document.getElementById('soft_skill_level').value = 'beginner';
}

function displaySkill(skillData, containerId) {
    const container = document.getElementById(containerId);
    const skillDiv = document.createElement('div');
    skillDiv.className = 'skill-item flex items-center justify-between p-3 bg-blue-50 rounded-lg border-2 border-blue-200';
    skillDiv.innerHTML = `
        <div class="flex items-center space-x-3">
            <span class="font-medium">${skillData.skill_name}</span>
            <span class="px-2 py-1 text-xs font-medium rounded-full 
                ${skillData.skill_level === 'expert' ? 'bg-purple-100 text-purple-800' : 
                  (skillData.skill_level === 'advanced' ? 'bg-blue-100 text-blue-800' : 
                  (skillData.skill_level === 'intermediate' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800'))}">
                ${skillData.skill_level.charAt(0).toUpperCase() + skillData.skill_level.slice(1)}
            </span>
            <span class="text-blue-600 text-xs">(New)</span>
        </div>
        <button type="button" onclick="removeNewSkill(this)" class="text-red-500 hover:text-red-700">
            <i class="fas fa-trash"></i>
        </button>
    `;
    container.appendChild(skillDiv);
}

function removeNewSkill(button) {
    const skillItem = button.closest('.skill-item');
    const skillName = skillItem.querySelector('.font-medium').textContent;
    
    // Remove from skillsToAdd array
    skillsToAdd = skillsToAdd.filter(skill => skill.skill_name !== skillName);
    skillItem.remove();
}

function removeSkill(skillId) {
    if (confirm('Are you sure you want to remove this skill?')) {
        skillsToRemove.push(skillId);
        const skillItem = document.querySelector(`[data-skill-id="${skillId}"]`);
        skillItem.style.opacity = '0.5';
        skillItem.style.backgroundColor = '#fef2f2';
    }
}

document.getElementById('skillsForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    try {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
        
        // Collect all skills: existing skills (not marked for removal) + new skills
        const allSkills = [];
        
        // Add existing skills that are not marked for removal
        document.querySelectorAll('.skill-item[data-skill-id]').forEach(item => {
            if (!item.style.opacity || item.style.opacity !== '0.5') {
                const skillName = item.querySelector('.skill-name').textContent;
                const skillLevel = item.querySelector('.skill-level').textContent.toLowerCase();
                const skillType = item.closest('#technicalSkillsList') ? 'technical' : 'soft';
                
                allSkills.push({
                    category: skillType,
                    skill_name: skillName,
                    proficiency_level: skillLevel,
                    years_of_experience: null
                });
            }
        });
        
        // Add new skills
        allSkills.push(...skillsToAdd.map(skill => ({
            category: skill.skill_type,
            skill_name: skill.skill_name,
            proficiency_level: skill.skill_level,
            years_of_experience: null
        })));
        
        console.log('Sending skills data:', allSkills);
        
        const response = await fetch('/api/profile/skills', {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                skills: allSkills
            })
        });
        
        console.log('Response status:', response.status);
        
        if (response.ok) {
            showAlert('Skills updated successfully!', 'success');
            
            // Redirect back to profile page after a short delay
            setTimeout(() => {
                window.location.href = '{{ route("profile") }}';
            }, 1500);
        } else {
            const error = await response.json();
            showAlert(error.message || 'Failed to update skills', 'error');
        }
    } catch (error) {
        showAlert('An error occurred while updating your skills', 'error');
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
