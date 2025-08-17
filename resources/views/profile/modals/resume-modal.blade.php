<!-- Resume Modal -->
<div id="resumeModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Upload Resume</h3>
            <span class="close" onclick="closeModal('resumeModal')">&times;</span>
        </div>
        
        <form id="resumeForm" onsubmit="uploadResume(event)" enctype="multipart/form-data">
            <div class="modal-body">
                <div class="form-group">
                    <label for="resume_title">Resume Title *</label>
                    <input type="text" id="resume_title" name="title" required placeholder="e.g., Software Engineer Resume 2024">
                </div>
                
                <div class="form-group full-width">
                    <label for="resume_description">Description</label>
                    <textarea id="resume_description" name="description" rows="3" placeholder="Brief description of this resume version..."></textarea>
                </div>
                
                <div class="form-group">
                    <label for="resume_file">Resume File *</label>
                    <input type="file" id="resume_file" name="resume" accept=".pdf,.doc,.docx" required>
                    <small class="form-text">Accepted formats: PDF, DOC, DOCX (Max size: 10MB)</small>
                </div>
                
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" id="is_primary" name="is_primary" value="1">
                        Set as primary resume
                    </label>
                    <small class="form-text">This resume will be used as your default when applying to jobs</small>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('resumeModal')">Cancel</button>
                <button type="submit" class="btn btn-primary" id="uploadBtn">
                    <span class="upload-text">Upload Resume</span>
                    <span class="uploading-text" style="display: none;">Uploading...</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function uploadResume(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const uploadBtn = document.getElementById('uploadBtn');
    const uploadText = uploadBtn.querySelector('.upload-text');
    const uploadingText = uploadBtn.querySelector('.uploading-text');
    
    // Show uploading state
    uploadBtn.disabled = true;
    uploadText.style.display = 'none';
    uploadingText.style.display = 'inline';
    
    fetch('/api/profile/resume', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeModal('resumeModal');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while uploading the resume');
    })
    .finally(() => {
        // Reset button state
        uploadBtn.disabled = false;
        uploadText.style.display = 'inline';
        uploadingText.style.display = 'none';
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

// Close modal when clicking outside of it
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
    }
}
</script>

<style>
/* Resume Modal Specific Styles */

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
}

.checkbox-label input[type="checkbox"] {
    width: auto;
    margin: 0;
}

.form-text {
    display: block;
    margin-top: 0.25rem;
    font-size: 0.75rem;
    color: #6b7280;
}


</style>
