@extends('admin.layouts.app')

@section('title', 'Import Data')
@section('page-title', 'Import Data')

@section('content')
<div class="import-container">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="import-sections">
        <!-- Import Companies Section -->
        <div class="import-section">
            <div class="section-header">
                <h3><i class="fas fa-building"></i> Import Companies</h3>
                <div class="section-actions">
                    <a href="{{ route('admin.import.sample', 'companies') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-download"></i> Download Sample
                    </a>
                </div>
            </div>
            
            <div class="import-tabs">
                <div class="tab-buttons">
                    <button class="tab-btn active" data-tab="file-companies">File Upload</button>
                    <button class="tab-btn" data-tab="manual-companies">Manual Input</button>
                </div>

                <!-- File Upload Tab -->
                <div class="tab-content active" id="file-companies">
                    <form method="POST" action="{{ route('admin.import.companies') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="import_type" value="file">
                        
                        <div class="form-group">
                            <label for="companies_file">Select JSON File</label>
                            <input type="file" id="companies_file" name="companies_file" accept=".json,.csv,.txt" required>
                            <small>Upload a JSON file with company data. Max size: 2MB</small>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Import Companies
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Manual Input Tab -->
                <div class="tab-content" id="manual-companies">
                    <form method="POST" action="{{ route('admin.import.companies') }}">
                        @csrf
                        <input type="hidden" name="import_type" value="manual">
                        
                        <div class="form-group">
                            <label for="companies_data">Company Data (JSON Format)</label>
                            <textarea id="companies_data" name="companies_data" rows="10" placeholder='[{"name": "Company Name", "hr_portal_url": "https://hr.company.com", "api_key": "key", "api_secret": "secret"}]' required></textarea>
                            <small>Enter company data in JSON format. You can add multiple companies in an array.</small>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Import Companies
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Import Jobs Section -->
        <div class="import-section">
            <div class="section-header">
                <h3><i class="fas fa-briefcase"></i> Import Jobs</h3>
                <div class="section-actions">
                    <a href="{{ route('admin.import.sample', 'jobs') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-download"></i> Download Sample
                    </a>
                </div>
            </div>
            
            <div class="import-tabs">
                <div class="tab-buttons">
                    <button class="tab-btn active" data-tab="file-jobs">File Upload</button>
                    <button class="tab-btn" data-tab="manual-jobs">Manual Input</button>
                </div>

                <!-- File Upload Tab -->
                <div class="tab-content active" id="file-jobs">
                    <form method="POST" action="{{ route('admin.import.jobs') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="import_type" value="file">
                        
                        <div class="form-group">
                            <label for="company_id">Select Company</label>
                            <select id="company_id" name="company_id" required>
                                <option value="">Choose a company...</option>
                                @foreach(\App\Models\Company::orderBy('name')->get() as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="jobs_file">Select JSON File</label>
                            <input type="file" id="jobs_file" name="jobs_file" accept=".json,.csv,.txt" required>
                            <small>Upload a JSON file with job data. Max size: 2MB</small>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Import Jobs
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Manual Input Tab -->
                <div class="tab-content" id="manual-jobs">
                    <form method="POST" action="{{ route('admin.import.jobs') }}">
                        @csrf
                        <input type="hidden" name="import_type" value="manual">
                        
                        <div class="form-group">
                            <label for="company_id_manual">Select Company</label>
                            <select id="company_id_manual" name="company_id" required>
                                <option value="">Choose a company...</option>
                                @foreach(\App\Models\Company::orderBy('name')->get() as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="jobs_data">Job Data (JSON Format)</label>
                            <textarea id="jobs_data" name="jobs_data" rows="10" placeholder='[{"title": "Job Title", "description": "Job description...", "location": "Location", "employment_type": "Full-time"}]' required></textarea>
                            <small>Enter job data in JSON format. You can add multiple jobs in an array.</small>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Import Jobs
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Import Guidelines -->
    <div class="import-guidelines">
        <h3><i class="fas fa-info-circle"></i> Import Guidelines</h3>
        
        <div class="guidelines-content">
            <div class="guideline-section">
                <h4>Company Import</h4>
                <ul>
                    <li><strong>Required fields:</strong> name</li>
                    <li><strong>Optional fields:</strong> description, location, industry, website, hr_portal_url, api_key, api_secret</li>
                    <li>Companies with the same name will be updated if they already exist</li>
                    <li>All imported companies are set as active by default</li>
                </ul>
            </div>

            <div class="guideline-section">
                <h4>Job Import</h4>
                <ul>
                    <li><strong>Required fields:</strong> title, company_id</li>
                    <li><strong>Optional fields:</strong> description, location, employment_type, salary_min, salary_max, currency, posted_at, apply_url, is_remote, external_id</li>
                    <li>Jobs with the same external_id and company_id will be updated if they already exist</li>
                    <li>All imported jobs are set as active by default</li>
                </ul>
            </div>

            <div class="guideline-section">
                <h4>File Formats</h4>
                <ul>
                    <li><strong>JSON:</strong> Preferred format, supports all data types</li>
                    <li><strong>CSV:</strong> Basic support, limited data types</li>
                    <li><strong>TXT:</strong> Basic support, limited data types</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
.import-container {
    max-width: 1200px;
    margin: 0 auto;
}

.alert {
    padding: 1rem 1.5rem;
    border-radius: 10px;
    margin-bottom: 2rem;
    font-size: 0.9rem;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}

.alert-error {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

.alert ul {
    margin: 0;
    padding-left: 1.5rem;
}

.import-sections {
    display: grid;
    gap: 2rem;
    margin-bottom: 2rem;
}

.import-section {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e5e7eb;
}

.section-header h3 {
    margin: 0;
    font-size: 1.25rem;
    color: #1f2937;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.import-tabs {
    margin-top: 1rem;
}

.tab-buttons {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
}

.tab-btn {
    padding: 0.75rem 1.5rem;
    border: none;
    background: none;
    color: #6b7280;
    cursor: pointer;
    border-bottom: 2px solid transparent;
    transition: all 0.3s ease;
}

.tab-btn.active {
    color: #667eea;
    border-bottom-color: #667eea;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #374151;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-group textarea {
    resize: vertical;
    min-height: 120px;
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    font-size: 0.875rem;
}

.form-group small {
    display: block;
    margin-top: 0.25rem;
    font-size: 0.875rem;
    color: #6b7280;
}

.form-actions {
    margin-top: 2rem;
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 10px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

.btn-secondary:hover {
    background: #4b5563;
}

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
}

.import-guidelines {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.import-guidelines h3 {
    margin: 0 0 1.5rem 0;
    font-size: 1.25rem;
    color: #1f2937;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.guidelines-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.guideline-section h4 {
    margin: 0 0 1rem 0;
    font-size: 1.125rem;
    color: #374151;
}

.guideline-section ul {
    margin: 0;
    padding-left: 1.5rem;
    color: #6b7280;
}

.guideline-section li {
    margin-bottom: 0.5rem;
}

@media (max-width: 768px) {
    .section-header {
        flex-direction: column;
        align-items: stretch;
        gap: 1rem;
    }
    
    .guidelines-content {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Remove active class from all buttons and contents
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Add active class to clicked button and target content
            this.classList.add('active');
            document.getElementById(targetTab).classList.add('active');
        });
    });

    // Auto-resize textareas
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    });
});
</script>
@endsection

