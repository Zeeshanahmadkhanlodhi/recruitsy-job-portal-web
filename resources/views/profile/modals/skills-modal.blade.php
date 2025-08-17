<!-- Skills Modal -->
<div id="skillsModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit Skills</h3>
            <span class="close" onclick="closeModal('skillsModal')">&times;</span>
        </div>
        
        <div class="modal-body">
            <div class="skills-header">
                <h4>Current Skills</h4>
                <button type="button" class="btn btn-sm btn-primary" onclick="addSkillRow()">Add Skill</button>
            </div>
            
            <form id="skillsForm" onsubmit="updateSkills(event)">
                <div id="skillsList">
                    @if(auth()->user()->skills->count() > 0)
                        @foreach(auth()->user()->skills as $index => $skill)
                        <div class="skill-row" data-index="{{ $index }}">
                            <div class="form-group">
                                <label for="skill_category_{{ $index }}">Category *</label>
                                <select id="skill_category_{{ $index }}" name="skills[{{ $index }}][category]" required>
                                    <option value="">Category</option>
                                    <option value="programming-languages" {{ $skill->category === 'programming-languages' ? 'selected' : '' }}>Programming Languages</option>
                                    <option value="frameworks-libraries" {{ $skill->category === 'frameworks-libraries' ? 'selected' : '' }}>Frameworks & Libraries</option>
                                    <option value="databases" {{ $skill->category === 'databases' ? 'selected' : '' }}>Databases</option>
                                    <option value="tools-technologies" {{ $skill->category === 'tools-technologies' ? 'selected' : '' }}>Tools & Technologies</option>
                                    <option value="soft-skills" {{ $skill->category === 'soft-skills' ? 'selected' : '' }}>Soft Skills</option>
                                    <option value="languages" {{ $skill->category === 'languages' ? 'selected' : '' }}>Languages</option>
                                    <option value="other" {{ $skill->category === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="skill_name_{{ $index }}">Skill Name *</label>
                                <input type="text" id="skill_name_{{ $index }}" name="skills[{{ $index }}][skill_name]" value="{{ $skill->skill_name }}" placeholder="Skill name" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="skill_proficiency_{{ $index }}">Proficiency Level</label>
                                <select id="skill_proficiency_{{ $index }}" name="skills[{{ $index }}][proficiency_level]">
                                    <option value="beginner" {{ $skill->proficiency_level === 'beginner' ? 'selected' : '' }}>Beginner</option>
                                    <option value="intermediate" {{ $skill->proficiency_level === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                    <option value="advanced" {{ $skill->proficiency_level === 'advanced' ? 'selected' : '' }}>Advanced</option>
                                    <option value="expert" {{ $skill->proficiency_level === 'expert' ? 'selected' : '' }}>Expert</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="skill_years_{{ $index }}">Years of Experience</label>
                                <input type="number" id="skill_years_{{ $index }}" name="skills[{{ $index }}][years_of_experience]" value="{{ $skill->years_of_experience ?? '' }}" placeholder="Years" min="0" max="50">
                            </div>
                            
                            <div class="form-group full-width">
                                <button type="button" class="btn btn-sm btn-danger" onclick="removeSkillRow(this)">Remove Skill</button>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="skill-row" data-index="0">
                            <div class="form-group">
                                <label for="skill_category_0">Category *</label>
                                <select id="skill_category_0" name="skills[0][category]" required>
                                    <option value="">Category</option>
                                    <option value="programming-languages">Programming Languages</option>
                                    <option value="frameworks-libraries">Frameworks & Libraries</option>
                                    <option value="databases">Databases</option>
                                    <option value="tools-technologies">Tools & Technologies</option>
                                    <option value="soft-skills">Soft Skills</option>
                                    <option value="languages">Languages</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="skill_name_0">Skill Name *</label>
                                <input type="text" id="skill_name_0" name="skills[0][skill_name]" placeholder="Skill name" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="skill_proficiency_0">Proficiency Level</label>
                                <select id="skill_proficiency_0" name="skills[0][proficiency_level]">
                                    <option value="beginner">Beginner</option>
                                    <option value="intermediate" selected>Intermediate</option>
                                    <option value="advanced">Advanced</option>
                                    <option value="expert">Expert</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="skill_years_0">Years of Experience</label>
                                <input type="number" id="skill_years_0" name="skills[0][years_of_experience]" placeholder="Years" min="0" max="50">
                            </div>
                            
                            <div class="form-group full-width">
                                <button type="button" class="btn btn-sm btn-danger" onclick="removeSkillRow(this)">Remove Skill</button>
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="closeModal('skillsModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let skillIndex = {{ auth()->user()->skills->count() > 0 ? auth()->user()->skills->count() : 1 }};

function addSkillRow() {
    const skillsList = document.getElementById('skillsList');
    const newRow = document.createElement('div');
    newRow.className = 'skill-row';
    newRow.setAttribute('data-index', skillIndex);
    
    newRow.innerHTML = `
        <div class="form-group">
            <label for="skill_category_${skillIndex}">Category *</label>
            <select id="skill_category_${skillIndex}" name="skills[${skillIndex}][category]" required>
                <option value="">Category</option>
                <option value="programming-languages">Programming Languages</option>
                <option value="frameworks-libraries">Frameworks & Libraries</option>
                <option value="databases">Databases</option>
                <option value="tools-technologies">Tools & Technologies</option>
                <option value="soft-skills">Soft Skills</option>
                <option value="languages">Languages</option>
                <option value="other">Other</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="skill_name_${skillIndex}">Skill Name *</label>
            <input type="text" id="skill_name_${skillIndex}" name="skills[${skillIndex}][skill_name]" placeholder="Skill name" required>
        </div>
        
        <div class="form-group">
            <label for="skill_proficiency_${skillIndex}">Proficiency Level</label>
            <select id="skill_proficiency_${skillIndex}" name="skills[${skillIndex}][proficiency_level]">
                <option value="beginner">Beginner</option>
                <option value="intermediate" selected>Intermediate</option>
                <option value="advanced">Advanced</option>
                <option value="expert">Expert</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="skill_years_${skillIndex}">Years of Experience</label>
            <input type="number" id="skill_years_${skillIndex}" name="skills[${skillIndex}][years_of_experience]" placeholder="Years" min="0" max="50">
        </div>
        
        <div class="form-group full-width">
            <button type="button" class="btn btn-sm btn-danger" onclick="removeSkillRow(this)">Remove Skill</button>
        </div>
    `;
    
    skillsList.appendChild(newRow);
    skillIndex++;
}

function removeSkillRow(button) {
    const skillRow = button.closest('.skill-row');
    if (skillRow) {
        skillRow.remove();
    }
}

function updateSkills(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData.entries());
    
    // Convert form data to the expected format
    const skills = [];
    const skillData = {};
    
    for (const [key, value] of Object.entries(data)) {
        if (key.startsWith('skills[')) {
            const matches = key.match(/skills\[(\d+)\]\[(\w+)\]/);
            if (matches) {
                const index = matches[1];
                const field = matches[2];
                
                if (!skillData[index]) {
                    skillData[index] = {};
                }
                skillData[index][field] = value;
            }
        }
    }
    
    // Convert to array format
    Object.values(skillData).forEach(skill => {
        if (skill.category && skill.skill_name) {
            skills.push(skill);
        }
    });
    
    fetch('/api/profile/skills', {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ skills: skills }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeModal('skillsModal');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating skills');
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
