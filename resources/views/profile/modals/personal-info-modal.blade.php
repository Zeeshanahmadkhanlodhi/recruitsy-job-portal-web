<!-- Personal Information Modal -->
<div id="personalInfoModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit Personal Information</h3>
            <span class="close" onclick="closeModal('personalInfoModal')">&times;</span>
        </div>
        
        <form id="personalInfoForm" onsubmit="updatePersonalInfo(event)">
            <div class="modal-body">
                <div class="form-group">
                    <label for="first_name">First Name *</label>
                    <input type="text" id="first_name" name="first_name" value="{{ auth()->user()->first_name }}" required>
                </div>
                
                <div class="form-group">
                    <label for="last_name">Last Name *</label>
                    <input type="text" id="last_name" name="last_name" value="{{ auth()->user()->last_name }}" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone" value="{{ auth()->user()->phone }}">
                </div>
                
                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" id="location" name="location" value="{{ auth()->user()->location }}" placeholder="City, State/Country">
                </div>
                
                <div class="form-group">
                    <label for="date_of_birth">Date of Birth</label>
                    <input type="date" id="date_of_birth" name="date_of_birth" value="{{ auth()->user()->date_of_birth ? auth()->user()->date_of_birth->format('Y-m-d') : '' }}">
                </div>
                
                <div class="form-group">
                    <label for="linkedin_url">LinkedIn URL</label>
                    <input type="url" id="linkedin_url" name="linkedin_url" value="{{ auth()->user()->linkedin_url }}" placeholder="https://linkedin.com/in/username">
                </div>
                
                <div class="form-group">
                    <label for="github_url">GitHub URL</label>
                    <input type="url" id="github_url" name="github_url" value="{{ auth()->user()->github_url }}" placeholder="https://github.com/username">
                </div>
                
                <div class="form-group">
                    <label for="portfolio_url">Portfolio URL</label>
                    <input type="url" id="portfolio_url" name="portfolio_url" value="{{ auth()->user()->portfolio_url }}" placeholder="https://yourportfolio.com">
                </div>
                
                <div class="form-group full-width">
                    <label for="bio">Bio</label>
                    <textarea id="bio" name="bio" rows="4" placeholder="Tell us about yourself...">{{ auth()->user()->bio }}</textarea>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('personalInfoModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
function updatePersonalInfo(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData.entries());
    
    fetch('/api/profile/personal-info', {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeModal('personalInfoModal');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating personal information');
    });
}
</script>
