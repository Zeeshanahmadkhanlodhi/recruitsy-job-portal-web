<!-- Education Modal -->
<div id="educationModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit Education</h3>
            <span class="close" onclick="closeModal('educationModal')">&times;</span>
        </div>
        
        <div class="modal-body">
            <div class="education-header">
                <h4>Education</h4>
                <button type="button" class="btn btn-sm btn-primary" onclick="addEducationRow()">Add Education</button>
            </div>
            
            <form id="educationForm" onsubmit="updateEducation(event)">
                <div id="educationList">
                    @if(auth()->user()->education->count() > 0)
                        @foreach(auth()->user()->education as $index => $edu)
                        <div class="education-row" data-index="{{ $index }}">
                            <div class="form-group">
                                <label for="degree_{{ $index }}">Degree *</label>
                                <input type="text" id="degree_{{ $index }}" name="education[{{ $index }}][degree]" value="{{ $edu->degree }}" placeholder="Degree" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="institution_{{ $index }}">Institution *</label>
                                <input type="text" id="institution_{{ $index }}" name="education[{{ $index }}][institution]" value="{{ $edu->institution }}" placeholder="Institution" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="field_of_study_{{ $index }}">Field of Study</label>
                                <input type="text" id="field_of_study_{{ $index }}" name="education[{{ $index }}][field_of_study]" value="{{ $edu->field_of_study ?? '' }}" placeholder="Field of Study (optional)">
                            </div>
                            
                            <div class="form-group">
                                <label for="location_{{ $index }}">Location</label>
                                <input type="text" id="location_{{ $index }}" name="education[{{ $index }}][location]" value="{{ $edu->location ?? '' }}" placeholder="Location (optional)">
                            </div>
                            
                            <div class="form-group">
                                <label for="start_date_{{ $index }}">Start Date</label>
                                <input type="date" id="start_date_{{ $index }}" name="education[{{ $index }}][start_date]" value="{{ $edu->start_date ? $edu->start_date->format('Y-m-d') : '' }}" class="start-date">
                            </div>
                            
                            <div class="form-group">
                                <label for="end_date_{{ $index }}">End Date</label>
                                <input type="date" id="end_date_{{ $index }}" name="education[{{ $index }}][end_date]" value="{{ $edu->end_date ? $edu->end_date->format('Y-m-d') : '' }}" class="end-date">
                            </div>
                            
                            <div class="form-group">
                                <label for="graduation_year_{{ $index }}">Graduation Year</label>
                                <input type="number" id="graduation_year_{{ $index }}" name="education[{{ $index }}][graduation_year]" value="{{ $edu->graduation_year ?? '' }}" placeholder="Graduation Year" min="1900" max="2030" class="graduation-year">
                            </div>
                            
                            <div class="form-group">
                                <label for="gpa_{{ $index }}">GPA</label>
                                <input type="number" id="gpa_{{ $index }}" name="education[{{ $index }}][gpa]" value="{{ $edu->gpa ?? '' }}" placeholder="GPA" min="0" max="4" step="0.01" class="gpa-input">
                            </div>
                            
                            <div class="form-group">
                                <label for="gpa_scale_{{ $index }}">GPA Scale</label>
                                <select id="gpa_scale_{{ $index }}" name="education[{{ $index }}][gpa_scale]" class="gpa-scale">
                                    <option value="4.0" {{ ($edu->gpa_scale ?? '4.0') === '4.0' ? 'selected' : '' }}>4.0 Scale</option>
                                    <option value="5.0" {{ ($edu->gpa_scale ?? '4.0') === '5.0' ? 'selected' : '' }}>5.0 Scale</option>
                                    <option value="10.0" {{ ($edu->gpa_scale ?? '4.0') === '10.0' ? 'selected' : '' }}>10.0 Scale</option>
                                </select>
                            </div>
                            
                            <div class="form-group full-width">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="education[{{ $index }}][is_current]" value="1" {{ $edu->is_current ? 'checked' : '' }} onchange="toggleEducationEndDate(this)">
                                    Currently Studying
                                </label>
                            </div>
                            
                            <div class="form-group full-width">
                                <label for="description_{{ $index }}">Description</label>
                                <textarea id="description_{{ $index }}" name="education[{{ $index }}][description]" placeholder="Description (optional)" rows="2">{{ $edu->description ?? '' }}</textarea>
                            </div>
                            
                            <div class="form-group full-width">
                                <button type="button" class="btn btn-sm btn-danger" onclick="removeEducationRow(this)">Remove Education</button>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="education-row" data-index="0">
                            <div class="form-group">
                                <label for="degree_0">Degree *</label>
                                <input type="text" id="degree_0" name="education[0][degree]" placeholder="Degree" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="institution_0">Institution *</label>
                                <input type="text" id="institution_0" name="education[0][institution]" placeholder="Institution" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="field_of_study_0">Field of Study</label>
                                <input type="text" id="field_of_study_0" name="education[0][field_of_study]" placeholder="Field of Study (optional)">
                            </div>
                            
                            <div class="form-group">
                                <label for="location_0">Location</label>
                                <input type="text" id="location_0" name="education[0][location]" placeholder="Location (optional)">
                            </div>
                            
                            <div class="form-group">
                                <label for="start_date_0">Start Date</label>
                                <input type="date" id="start_date_0" name="education[0][start_date]" class="start-date">
                            </div>
                            
                            <div class="form-group">
                                <label for="end_date_0">End Date</label>
                                <input type="date" id="end_date_0" name="education[0][end_date]" class="end-date">
                            </div>
                            
                            <div class="form-group">
                                <label for="graduation_year_0">Graduation Year</label>
                                <input type="number" id="graduation_year_0" name="education[0][graduation_year]" placeholder="Graduation Year" min="1900" max="2030" class="graduation-year">
                            </div>
                            
                            <div class="form-group">
                                <label for="gpa_0">GPA</label>
                                <input type="number" id="gpa_0" name="education[0][gpa]" placeholder="GPA" min="0" max="4" step="0.01" class="gpa-input">
                            </div>
                            
                            <div class="form-group">
                                <label for="gpa_scale_0">GPA Scale</label>
                                <select id="gpa_scale_0" name="education[0][gpa_scale]" class="gpa-scale">
                                    <option value="4.0" selected>4.0 Scale</option>
                                    <option value="5.0">5.0 Scale</option>
                                    <option value="10.0">10.0 Scale</option>
                                </select>
                            </div>
                            
                            <div class="form-group full-width">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="education[0][is_current]" value="1" onchange="toggleEducationEndDate(this)">
                                    Currently Studying
                                </label>
                            </div>
                            
                            <div class="form-group full-width">
                                <label for="description_0">Description</label>
                                <textarea id="description_0" name="education[0][description]" placeholder="Description (optional)" rows="2"></textarea>
                            </div>
                            
                            <div class="form-group full-width">
                                <button type="button" class="btn btn-sm btn-danger" onclick="removeEducationRow(this)">Remove Education</button>
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="closeModal('educationModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let educationIndex = {{ auth()->user()->education->count() > 0 ? auth()->user()->education->count() : 1 }};

function addEducationRow() {
    const educationList = document.getElementById('educationList');
    const newRow = document.createElement('div');
    newRow.className = 'education-row';
    newRow.setAttribute('data-index', educationIndex);
    
    newRow.innerHTML = `
        <div class="form-group">
            <label for="degree_${educationIndex}">Degree *</label>
            <input type="text" id="degree_${educationIndex}" name="education[${educationIndex}][degree]" placeholder="Degree" required>
        </div>
        
        <div class="form-group">
            <label for="institution_${educationIndex}">Institution *</label>
            <input type="text" id="institution_${educationIndex}" name="education[${educationIndex}][institution]" placeholder="Institution" required>
        </div>
        
        <div class="form-group">
            <label for="field_of_study_${educationIndex}">Field of Study</label>
            <input type="text" id="field_of_study_${educationIndex}" name="education[${educationIndex}][field_of_study]" placeholder="Field of Study (optional)">
        </div>
        
        <div class="form-group">
            <label for="location_${educationIndex}">Location</label>
            <input type="text" id="location_${educationIndex}" name="education[${educationIndex}][location]" placeholder="Location (optional)">
        </div>
        
        <div class="form-group">
            <label for="start_date_${educationIndex}">Start Date</label>
            <input type="date" id="start_date_${educationIndex}" name="education[${educationIndex}][start_date]" class="start-date">
        </div>
        
        <div class="form-group">
            <label for="end_date_${educationIndex}">End Date</label>
            <input type="date" id="end_date_${educationIndex}" name="education[${educationIndex}][end_date]" class="end-date">
        </div>
        
        <div class="form-group">
            <label for="graduation_year_${educationIndex}">Graduation Year</label>
            <input type="number" id="graduation_year_${educationIndex}" name="education[${educationIndex}][graduation_year]" placeholder="Graduation Year" min="1900" max="2030" class="graduation-year">
        </div>
        
        <div class="form-group">
            <label for="gpa_${educationIndex}">GPA</label>
            <input type="number" id="gpa_${educationIndex}" name="education[${educationIndex}][gpa]" placeholder="GPA" min="0" max="4" step="0.01" class="gpa-input">
        </div>
        
        <div class="form-group">
            <label for="gpa_scale_${educationIndex}">GPA Scale</label>
            <select id="gpa_scale_${educationIndex}" name="education[${educationIndex}][gpa_scale]" class="gpa-scale">
                <option value="4.0" selected>4.0 Scale</option>
                <option value="5.0">5.0 Scale</option>
                <option value="10.0">10.0 Scale</option>
            </select>
        </div>
        
        <div class="form-group full-width">
            <label class="checkbox-label">
                <input type="checkbox" name="education[${educationIndex}][is_current]" value="1" onchange="toggleEducationEndDate(this)">
                Currently Studying
            </label>
        </div>
        
        <div class="form-group full-width">
            <label for="description_${educationIndex}">Description</label>
            <textarea id="description_${educationIndex}" name="education[${educationIndex}][description]" placeholder="Description (optional)" rows="2"></textarea>
        </div>
        
        <div class="form-group full-width">
            <button type="button" class="btn btn-sm btn-danger" onclick="removeEducationRow(this)">Remove Education</button>
        </div>
    `;
    
    educationList.appendChild(newRow);
    educationIndex++;
}

function removeEducationRow(button) {
    const educationRow = button.closest('.education-row');
    if (educationRow) {
        educationRow.remove();
    }
}

function toggleEducationEndDate(checkbox) {
    const educationRow = checkbox.closest('.education-row');
    const endDateInput = educationRow.querySelector('.end-date');
    const graduationYearInput = educationRow.querySelector('.graduation-year');
    
    if (checkbox.checked) {
        endDateInput.disabled = true;
        endDateInput.value = '';
        graduationYearInput.disabled = true;
        graduationYearInput.value = '';
    } else {
        endDateInput.disabled = false;
        graduationYearInput.disabled = false;
    }
}

function updateEducation(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData.entries());
    
    // Convert form data to the expected format
    const education = [];
    const eduData = {};
    
    for (const [key, value] of Object.entries(data)) {
        if (key.startsWith('education[')) {
            const matches = key.match(/education\[(\d+)\]\[(\w+)\]/);
            if (matches) {
                const index = matches[1];
                const field = matches[2];
                
                if (!eduData[index]) {
                    eduData[index] = {};
                }
                eduData[index][field] = value;
            }
        }
    }
    
    // Convert to array format
    Object.values(eduData).forEach(edu => {
        if (edu.degree && edu.institution) {
            education.push(edu);
        }
    });
    
    fetch('/api/profile/education', {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ education: education }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeModal('educationModal');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating education');
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
