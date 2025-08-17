<!-- Experience Modal -->
<div id="experienceModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit Professional Experience</h3>
            <span class="close" onclick="closeModal('experienceModal')">&times;</span>
        </div>
        
        <div class="modal-body">
            <div class="experience-header">
                <h4>Professional Experience</h4>
                <button type="button" class="btn btn-sm btn-primary" onclick="addExperienceRow()">Add Experience</button>
            </div>
            
            <form id="experienceForm" onsubmit="updateExperience(event)">
                <div id="experienceList">
                    @if(auth()->user()->experience->count() > 0)
                        @foreach(auth()->user()->experience as $index => $exp)
                        <div class="experience-row" data-index="{{ $index }}">
                            <div class="form-group">
                                <label for="job_title_{{ $index }}">Job Title *</label>
                                <input type="text" id="job_title_{{ $index }}" name="experience[{{ $index }}][job_title]" value="{{ $exp->job_title }}" placeholder="Job Title" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="company_name_{{ $index }}">Company Name *</label>
                                <input type="text" id="company_name_{{ $index }}" name="experience[{{ $index }}][company_name]" value="{{ $exp->company_name }}" placeholder="Company Name" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="location_{{ $index }}">Location</label>
                                <input type="text" id="location_{{ $index }}" name="experience[{{ $index }}][location]" value="{{ $exp->location ?? '' }}" placeholder="Location (optional)">
                            </div>
                            
                            <div class="form-group">
                                <label for="employment_type_{{ $index }}">Employment Type</label>
                                <select id="employment_type_{{ $index }}" name="experience[{{ $index }}][employment_type]">
                                    <option value="">Employment Type</option>
                                    <option value="full-time" {{ $exp->employment_type === 'full-time' ? 'selected' : '' }}>Full-time</option>
                                    <option value="part-time" {{ $exp->employment_type === 'part-time' ? 'selected' : '' }}>Part-time</option>
                                    <option value="contract" {{ $exp->employment_type === 'contract' ? 'selected' : '' }}>Contract</option>
                                    <option value="freelance" {{ $exp->employment_type === 'freelance' ? 'selected' : '' }}>Freelance</option>
                                    <option value="internship" {{ $exp->employment_type === 'internship' ? 'selected' : '' }}>Internship</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="start_date_{{ $index }}">Start Date *</label>
                                <input type="date" id="start_date_{{ $index }}" name="experience[{{ $index }}][start_date]" value="{{ $exp->start_date->format('Y-m-d') }}" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="end_date_{{ $index }}">End Date</label>
                                <input type="date" id="end_date_{{ $index }}" name="experience[{{ $index }}][end_date]" value="{{ $exp->end_date ? $exp->end_date->format('Y-m-d') : '' }}" class="end-date">
                            </div>
                            
                            <div class="form-group full-width">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="experience[{{ $index }}][is_current]" value="1" {{ $exp->is_current ? 'checked' : '' }} onchange="toggleEndDate(this)">
                                    Current Position
                                </label>
                            </div>
                            
                            <div class="form-group full-width">
                                <label for="description_{{ $index }}">Job Description *</label>
                                <textarea id="description_{{ $index }}" name="experience[{{ $index }}][description]" placeholder="Job Description" rows="3" required>{{ $exp->description }}</textarea>
                            </div>
                            
                            <div class="form-group full-width">
                                <label for="achievements_{{ $index }}">Key Achievements</label>
                                <textarea id="achievements_{{ $index }}" name="experience[{{ $index }}][achievements]" placeholder="Key Achievements (optional)" rows="2">{{ $exp->achievements ?? '' }}</textarea>
                            </div>
                            
                            <div class="form-group full-width">
                                <button type="button" class="btn btn-sm btn-danger" onclick="removeExperienceRow(this)">Remove Experience</button>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="experience-row" data-index="0">
                            <div class="form-group">
                                <label for="job_title_0">Job Title *</label>
                                <input type="text" id="job_title_0" name="experience[0][job_title]" placeholder="Job Title" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="company_name_0">Company Name *</label>
                                <input type="text" id="company_name_0" name="experience[0][company_name]" placeholder="Company Name" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="location_0">Location</label>
                                <input type="text" id="location_0" name="experience[0][location]" placeholder="Location (optional)">
                            </div>
                            
                            <div class="form-group">
                                <label for="employment_type_0">Employment Type</label>
                                <select id="employment_type_0" name="experience[0][employment_type]">
                                    <option value="">Employment Type</option>
                                    <option value="full-time">Full-time</option>
                                    <option value="part-time">Part-time</option>
                                    <option value="contract">Contract</option>
                                    <option value="freelance">Freelance</option>
                                    <option value="internship">Internship</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="start_date_0">Start Date *</label>
                                <input type="date" id="start_date_0" name="experience[0][start_date]" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="end_date_0">End Date</label>
                                <input type="date" id="end_date_0" name="experience[0][end_date]" class="end-date">
                            </div>
                            
                            <div class="form-group full-width">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="experience[0][is_current]" value="1" onchange="toggleEndDate(this)">
                                    Current Position
                                </label>
                            </div>
                            
                            <div class="form-group full-width">
                                <label for="description_0">Job Description *</label>
                                <textarea id="description_0" name="experience[0][description]" placeholder="Job Description" rows="3" required></textarea>
                            </div>
                            
                            <div class="form-group full-width">
                                <label for="achievements_0">Key Achievements</label>
                                <textarea id="achievements_0" name="experience[0][achievements]" placeholder="Key Achievements (optional)" rows="2"></textarea>
                            </div>
                            
                            <div class="form-group full-width">
                                <button type="button" class="btn btn-sm btn-danger" onclick="removeExperienceRow(this)">Remove Experience</button>
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="closeModal('experienceModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let experienceIndex = {{ auth()->user()->experience->count() > 0 ? auth()->user()->experience->count() : 1 }};

function addExperienceRow() {
    const experienceList = document.getElementById('experienceList');
    const newRow = document.createElement('div');
    newRow.className = 'experience-row';
    newRow.setAttribute('data-index', experienceIndex);
    
    newRow.innerHTML = `
        <div class="form-group">
            <label for="job_title_${experienceIndex}">Job Title *</label>
            <input type="text" id="job_title_${experienceIndex}" name="experience[${experienceIndex}][job_title]" placeholder="Job Title" required>
        </div>
        
        <div class="form-group">
            <label for="company_name_${experienceIndex}">Company Name *</label>
            <input type="text" id="company_name_${experienceIndex}" name="experience[${experienceIndex}][company_name]" placeholder="Company Name" required>
        </div>
        
        <div class="form-group">
            <label for="location_${experienceIndex}">Location</label>
            <input type="text" id="location_${experienceIndex}" name="experience[${experienceIndex}][location]" placeholder="Location (optional)">
        </div>
        
        <div class="form-group">
            <label for="employment_type_${experienceIndex}">Employment Type</label>
            <select id="employment_type_${experienceIndex}" name="experience[${experienceIndex}][employment_type]">
                <option value="">Employment Type</option>
                <option value="full-time">Full-time</option>
                <option value="part-time">Part-time</option>
                <option value="contract">Contract</option>
                <option value="freelance">Freelance</option>
                <option value="internship">Internship</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="start_date_${experienceIndex}">Start Date *</label>
            <input type="date" id="start_date_${experienceIndex}" name="experience[${experienceIndex}][start_date]" required>
        </div>
        
        <div class="form-group">
            <label for="end_date_${experienceIndex}">End Date</label>
            <input type="date" id="end_date_${experienceIndex}" name="experience[${experienceIndex}][end_date]" class="end-date">
        </div>
        
        <div class="form-group full-width">
            <label class="checkbox-label">
                <input type="checkbox" name="experience[${experienceIndex}][is_current]" value="1" onchange="toggleEndDate(this)">
                Current Position
            </label>
        </div>
        
        <div class="form-group full-width">
            <label for="description_${experienceIndex}">Job Description *</label>
            <textarea id="description_${experienceIndex}" name="experience[${experienceIndex}][description]" placeholder="Job Description" rows="3" required></textarea>
        </div>
        
        <div class="form-group full-width">
            <label for="achievements_${experienceIndex}">Key Achievements</label>
            <textarea id="achievements_${experienceIndex}" name="experience[${experienceIndex}][achievements]" placeholder="Key Achievements (optional)" rows="2"></textarea>
        </div>
        
        <div class="form-group full-width">
            <button type="button" class="btn btn-sm btn-danger" onclick="removeExperienceRow(this)">Remove Experience</button>
        </div>
    `;
    
    experienceList.appendChild(newRow);
    experienceIndex++;
}

function removeExperienceRow(button) {
    const experienceRow = button.closest('.experience-row');
    if (experienceRow) {
        experienceRow.remove();
    }
}

function toggleEndDate(checkbox) {
    const experienceRow = checkbox.closest('.experience-row');
    const endDateInput = experienceRow.querySelector('.end-date');
    
    if (checkbox.checked) {
        endDateInput.disabled = true;
        endDateInput.value = '';
    } else {
        endDateInput.disabled = false;
    }
}

function updateExperience(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData.entries());
    
    // Convert form data to the expected format
    const experience = [];
    const expData = {};
    
    for (const [key, value] of Object.entries(data)) {
        if (key.startsWith('experience[')) {
            const matches = key.match(/experience\[(\d+)\]\[(\w+)\]/);
            if (matches) {
                const index = matches[1];
                const field = matches[2];
                
                if (!expData[index]) {
                    expData[index] = {};
                }
                expData[index][field] = value;
            }
        }
    }
    
    // Convert to array format
    Object.values(expData).forEach(exp => {
        if (exp.job_title && exp.company_name && exp.start_date) {
            experience.push(exp);
        }
    });
    
    fetch('/api/profile/experience', {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ experience: experience }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeModal('experienceModal');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating experience');
    });
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
        // Reset form if it exists
        const form = modal.querySelector('form');
        if (form) {
            form.reset();
        }
    }
}
</script>
