<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserProfessionalInfo;
use App\Models\UserSkill;
use App\Models\UserExperience;
use App\Models\UserEducation;
use App\Models\UserResume;

class ProfileDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user or create one if none exists
        $user = User::first();
        
        if (!$user) {
            $user = User::factory()->create([
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'phone' => '+1 (555) 123-4567',
                'location' => 'San Francisco, CA',
                'date_of_birth' => '1990-03-15',
                'linkedin_url' => 'https://linkedin.com/in/johndoe',
                'github_url' => 'https://github.com/johndoe',
                'portfolio_url' => 'https://johndoe.dev',
                'bio' => 'Passionate software engineer with 5+ years of experience building scalable web applications.',
            ]);
        }

        // Create professional information
        UserProfessionalInfo::updateOrCreate(
            ['user_id' => $user->id],
            [
                'current_title' => 'Senior Software Engineer',
                'years_of_experience' => 5,
                'preferred_job_type' => 'full-time',
                'willing_to_relocate' => true,
                'expected_salary_min' => '$120,000',
                'expected_salary_max' => '$150,000',
                'work_authorization' => 'US Citizen',
                'summary' => 'Experienced full-stack developer specializing in React, Node.js, and cloud technologies.',
            ]
        );

        // Create skills
        $skills = [
            ['category' => 'Programming Languages', 'skill_name' => 'JavaScript', 'proficiency_level' => 'advanced', 'years_of_experience' => 5],
            ['category' => 'Programming Languages', 'skill_name' => 'Python', 'proficiency_level' => 'intermediate', 'years_of_experience' => 3],
            ['category' => 'Programming Languages', 'skill_name' => 'Java', 'proficiency_level' => 'intermediate', 'years_of_experience' => 2],
            ['category' => 'Programming Languages', 'skill_name' => 'TypeScript', 'proficiency_level' => 'advanced', 'years_of_experience' => 3],
            ['category' => 'Frameworks & Libraries', 'skill_name' => 'React', 'proficiency_level' => 'advanced', 'years_of_experience' => 4],
            ['category' => 'Frameworks & Libraries', 'skill_name' => 'Vue.js', 'proficiency_level' => 'intermediate', 'years_of_experience' => 2],
            ['category' => 'Frameworks & Libraries', 'skill_name' => 'Node.js', 'proficiency_level' => 'advanced', 'years_of_experience' => 4],
            ['category' => 'Frameworks & Libraries', 'skill_name' => 'Express', 'proficiency_level' => 'intermediate', 'years_of_experience' => 3],
            ['category' => 'Tools & Technologies', 'skill_name' => 'Git', 'proficiency_level' => 'advanced', 'years_of_experience' => 5],
            ['category' => 'Tools & Technologies', 'skill_name' => 'Docker', 'proficiency_level' => 'intermediate', 'years_of_experience' => 2],
            ['category' => 'Tools & Technologies', 'skill_name' => 'AWS', 'proficiency_level' => 'intermediate', 'years_of_experience' => 2],
            ['category' => 'Tools & Technologies', 'skill_name' => 'MongoDB', 'proficiency_level' => 'intermediate', 'years_of_experience' => 3],
        ];

        foreach ($skills as $skill) {
            UserSkill::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'category' => $skill['category'],
                    'skill_name' => $skill['skill_name']
                ],
                $skill
            );
        }

        // Create professional experience
        $experiences = [
            [
                'job_title' => 'Senior Software Engineer',
                'company_name' => 'TechCorp Inc.',
                'location' => 'San Francisco, CA',
                'start_date' => '2022-01-01',
                'end_date' => null,
                'is_current' => true,
                'description' => 'Led development of web applications using React and Node.js. Collaborated with cross-functional teams to deliver high-quality software solutions.',
                'achievements' => 'Improved application performance by 40% through optimization techniques. Mentored 3 junior developers.',
                'employment_type' => 'full-time',
            ],
            [
                'job_title' => 'Software Engineer',
                'company_name' => 'StartupXYZ',
                'location' => 'Remote',
                'start_date' => '2020-03-01',
                'end_date' => '2021-12-31',
                'is_current' => false,
                'description' => 'Developed and maintained frontend applications using Vue.js. Implemented responsive designs and optimized application performance.',
                'achievements' => 'Reduced page load time by 30%. Implemented automated testing pipeline.',
                'employment_type' => 'full-time',
            ],
        ];

        foreach ($experiences as $experience) {
            UserExperience::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'job_title' => $experience['job_title'],
                    'company_name' => $experience['company_name'],
                    'start_date' => $experience['start_date']
                ],
                $experience
            );
        }

        // Create education
        $education = [
            [
                'degree' => 'Bachelor of Science in Computer Science',
                'institution' => 'University of California, Berkeley',
                'field_of_study' => 'Computer Science',
                'graduation_year' => 2019,
                'gpa' => 3.80,
                'gpa_scale' => '4.0',
                'description' => 'Focused on software engineering, algorithms, and data structures.',
                'location' => 'Berkeley, CA',
                'is_current' => false,
                'start_date' => '2015-08-01',
                'end_date' => '2019-05-01',
            ],
        ];

        foreach ($education as $edu) {
            UserEducation::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'degree' => $edu['degree'],
                    'institution' => $edu['institution'],
                    'graduation_year' => $edu['graduation_year']
                ],
                $edu
            );
        }

        // Create resume (mock data)
        UserResume::updateOrCreate(
            [
                'user_id' => $user->id,
                'file_name' => 'john_doe_resume.pdf'
            ],
            [
                'file_path' => 'resumes/sample_resume.pdf',
                'file_size' => '245760', // 240 KB
                'file_type' => 'application/pdf',
                'title' => 'Professional Resume',
                'description' => 'Updated resume with latest experience and skills',
                'is_primary' => true,
            ]
        );

        $this->command->info('Profile data seeded successfully for user: ' . $user->email);
    }
}
