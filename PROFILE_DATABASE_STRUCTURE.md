# Profile Database Structure

This document outlines the new database structure for the user profile system, which has been organized into separate tables for better data management and scalability.

## Database Tables

### 1. Users Table (Enhanced)
The existing `users` table has been extended with additional personal information fields:

**New Fields Added:**
- `date_of_birth` - User's date of birth
- `linkedin_url` - LinkedIn profile URL
- `github_url` - GitHub profile URL
- `portfolio_url` - Portfolio website URL
- `bio` - User's biography/description

**Existing Fields:**
- `first_name`, `last_name` - User's full name
- `email` - User's email address
- `phone` - User's phone number
- `location` - User's location
- `avatar_path` - Path to user's avatar image

### 2. User Professional Information Table
Stores professional details and preferences:

- `id` - Primary key
- `user_id` - Foreign key to users table
- `current_title` - Current job title
- `years_of_experience` - Total years of experience
- `preferred_job_type` - Preferred employment type (full-time, part-time, contract, freelance, internship)
- `willing_to_relocate` - Boolean indicating willingness to relocate
- `expected_salary_min` - Minimum expected salary
- `expected_salary_max` - Maximum expected salary
- `work_authorization` - Work authorization status
- `summary` - Professional summary
- `created_at`, `updated_at` - Timestamps

### 3. User Skills Table
Stores categorized skills with proficiency levels:

- `id` - Primary key
- `user_id` - Foreign key to users table
- `category` - Skill category (e.g., "Programming Languages", "Frameworks & Libraries")
- `skill_name` - Name of the skill
- `proficiency_level` - Skill level (beginner, intermediate, advanced, expert)
- `years_of_experience` - Years of experience with this skill
- `created_at`, `updated_at` - Timestamps

### 4. User Experience Table
Stores work experience history:

- `id` - Primary key
- `user_id` - Foreign key to users table
- `job_title` - Job title
- `company_name` - Company name
- `location` - Job location
- `start_date` - Employment start date
- `end_date` - Employment end date (null for current position)
- `is_current` - Boolean indicating if this is the current position
- `description` - Job description
- `achievements` - Key achievements in the role
- `employment_type` - Type of employment
- `created_at`, `updated_at` - Timestamps

### 5. User Education Table
Stores educational background:

- `id` - Primary key
- `user_id` - Foreign key to users table
- `degree` - Degree obtained
- `institution` - Educational institution
- `field_of_study` - Field of study
- `graduation_year` - Year of graduation
- `gpa` - Grade Point Average
- `gpa_scale` - GPA scale (e.g., "4.0")
- `description` - Additional details about education
- `location` - Institution location
- `is_current` - Boolean indicating if currently enrolled
- `start_date` - Enrollment start date
- `end_date` - Enrollment end date
- `created_at`, `updated_at` - Timestamps

### 6. User Resumes Table
Stores resume files and metadata:

- `id` - Primary key
- `user_id` - Foreign key to users table
- `file_name` - Original filename
- `file_path` - Storage path to the file
- `file_size` - File size in bytes
- `file_type` - MIME type of the file
- `title` - User-defined title for the resume
- `description` - Resume description
- `is_primary` - Boolean indicating if this is the primary resume
- `uploaded_at` - When the resume was uploaded
- `created_at`, `updated_at` - Timestamps

## Eloquent Models

### User Model
The main User model has been enhanced with relationships to all profile tables:

```php
// Relationships
public function professionalInfo() // One-to-one
public function skills() // One-to-many
public function experience() // One-to-many
public function education() // One-to-many
public function resumes() // One-to-many
public function primaryResume() // One-to-one (primary resume)

// Accessors
public function getFullNameAttribute() // Combines first_name + last_name
public function getProfileCompletionAttribute() // Calculates profile completion percentage
```

### Profile Models
Each profile section has its own dedicated model:

- `UserProfessionalInfo` - Professional information
- `UserSkill` - Skills management
- `UserExperience` - Work experience
- `UserEducation` - Educational background
- `UserResume` - Resume management

## API Endpoints

The profile system provides RESTful API endpoints for all operations:

### Personal Information
- `PUT /api/profile/personal-info` - Update personal information

### Professional Information
- `PUT /api/profile/professional-info` - Update professional information

### Skills
- `PUT /api/profile/skills` - Update skills (replaces all existing skills)

### Experience
- `PUT /api/profile/experience` - Update work experience (replaces all existing experience)

### Education
- `PUT /api/profile/education` - Update education (replaces all existing education)

### Resume Management
- `POST /api/profile/resume` - Upload new resume
- `DELETE /api/profile/resume/{id}` - Delete resume
- `PUT /api/profile/resume/{id}/primary` - Set resume as primary

## Usage Examples

### Creating a User Profile
```php
$user = User::create([
    'first_name' => 'John',
    'last_name' => 'Doe',
    'email' => 'john@example.com',
    // ... other fields
]);

// Add professional information
$user->professionalInfo()->create([
    'current_title' => 'Software Engineer',
    'years_of_experience' => 3,
    'preferred_job_type' => 'full-time',
    // ... other fields
]);

// Add skills
$user->skills()->createMany([
    ['category' => 'Programming Languages', 'skill_name' => 'JavaScript', 'proficiency_level' => 'advanced'],
    ['category' => 'Frameworks', 'skill_name' => 'React', 'proficiency_level' => 'intermediate'],
]);
```

### Retrieving Profile Data
```php
$user = User::with([
    'professionalInfo',
    'skills',
    'experience',
    'education',
    'resumes'
])->find($userId);

// Access profile completion
$completion = $user->profile_completion; // Returns percentage

// Get skills by category
$skillsByCategory = UserSkill::getSkillsByCategory($userId);
```

## Database Migrations

To set up the database structure, run the following migrations in order:

1. `2025_08_10_020000_create_user_professional_info_table.php`
2. `2025_08_10_021000_create_user_skills_table.php`
3. `2025_08_10_022000_create_user_experience_table.php`
4. `2025_08_10_023000_create_user_education_table.php`
5. `2025_08_10_024000_create_user_resumes_table.php`
6. `2025_08_10_025000_add_additional_personal_fields_to_users_table.php`

## Seeding

Use the `ProfileDataSeeder` to populate sample data:

```bash
php artisan db:seed --class=ProfileDataSeeder
```

## Benefits of This Structure

1. **Separation of Concerns** - Each profile section has its own table
2. **Scalability** - Easy to add new fields or sections
3. **Performance** - Efficient queries for specific profile sections
4. **Maintainability** - Clear structure for developers
5. **Flexibility** - Support for multiple resumes, skills, etc.
6. **Data Integrity** - Proper foreign key constraints and validation

## Future Enhancements

Potential areas for future development:

1. **Skill Endorsements** - Allow other users to endorse skills
2. **Experience Verification** - Company verification of work experience
3. **Portfolio Projects** - Showcase of work projects
4. **Certifications** - Professional certifications and licenses
5. **References** - Professional references
6. **Social Profiles** - Integration with social media platforms
