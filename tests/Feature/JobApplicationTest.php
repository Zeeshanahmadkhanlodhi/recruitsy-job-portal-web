<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Job;
use App\Models\Company;
use App\Models\UserSkill;
use App\Models\UserExperience;
use App\Models\UserEducation;
use App\Models\UserResume;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class JobApplicationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $job;
    protected $company;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a user with profile data
        $this->user = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
        ]);

        // Create company
        $this->company = Company::factory()->create([
            'name' => 'Test Company',
        ]);

        // Create job
        $this->job = Job::factory()->create([
            'title' => 'Software Developer',
            'company_id' => $this->company->id,
            'description' => 'We are looking for a skilled software developer',
            'location' => 'New York',
            'employment_type' => 'Full-time',
            'is_remote' => false,
            'salary_min' => 80000,
            'salary_max' => 120000,
            'currency' => 'USD',
        ]);

        // Create user profile data
        UserSkill::factory()->create(['user_id' => $this->user->id]);
        UserExperience::factory()->create(['user_id' => $this->user->id]);
        UserEducation::factory()->create(['user_id' => $this->user->id]);
        UserResume::factory()->create(['user_id' => $this->user->id]);
        
        // Refresh the job to get the actual ID
        $this->job->refresh();
    }

    /** @test */
    public function user_can_see_apply_button_on_jobs_page()
    {
        $response = $this->actingAs($this->user)
            ->get(route('dashboard-jobs'));

        $response->assertStatus(200);
        $response->assertSee('Apply Now');
        $response->assertSee('Software Developer');
        $response->assertSee('Test Company');
    }

    /** @test */
    public function apply_button_has_correct_onclick_attribute()
    {
        $response = $this->actingAs($this->user)
            ->get(route('dashboard-jobs'));

        $response->assertStatus(200);
        $response->assertSee('onclick="applyToJob(' . $this->job->id . ', this)"');
    }

    /** @test */
    public function apply_button_has_unique_id()
    {
        $response = $this->actingAs($this->user)
            ->get(route('dashboard-jobs'));

        $response->assertStatus(200);
        // Check for the button ID with flexible quote matching
        $response->assertSee('id=applyBtn' . $this->job->id);
    }

    /** @test */
    public function apply_button_shows_loading_state_when_clicked()
    {
        $response = $this->actingAs($this->user)
            ->get(route('dashboard-jobs'));

        $response->assertStatus(200);
        
        // Check that the JavaScript function exists
        $response->assertSee('function applyToJob(jobId, buttonElement)');
        $response->assertSee('buttonElement.innerHTML = \'<i class="fas fa-spinner fa-spin mr-2"></i>Applying...\'');
    }

    /** @test */
    public function apply_button_is_disabled_during_application()
    {
        $response = $this->actingAs($this->user)
            ->get(route('dashboard-jobs'));

        $response->assertStatus(200);
        $response->assertSee('buttonElement.disabled = true');
    }

    /** @test */
    public function application_data_includes_user_profile_information()
    {
        $response = $this->actingAs($this->user)
            ->get(route('dashboard-jobs'));

        $response->assertStatus(200);
        
        // Check that user profile data is included in the application
        $response->assertSee('skills: ' . $this->user->skills->count());
        $response->assertSee('experience: ' . $this->user->experience->count());
        $response->assertSee('education: ' . $this->user->education->count());
        $response->assertSee('resumes: ' . $this->user->resumes->count());
    }

    /** @test */
    public function application_makes_api_call_to_correct_endpoint()
    {
        $response = $this->actingAs($this->user)
            ->get(route('dashboard-jobs'));

        $response->assertStatus(200);
        $response->assertSee('fetch(\'/api/jobs/\' + jobId + \'/apply\'');
    }

    /** @test */
    public function application_includes_csrf_token()
    {
        $response = $this->actingAs($this->user)
            ->get(route('dashboard-jobs'));

        $response->assertStatus(200);
        $response->assertSee('X-CSRF-TOKEN');
    }

    /** @test */
    public function application_includes_correct_headers()
    {
        $response = $this->actingAs($this->user)
            ->get(route('dashboard-jobs'));

        $response->assertStatus(200);
        $response->assertSee('Content-Type: application/json');
    }

    /** @test */
    public function success_response_updates_button_to_applied_state()
    {
        $response = $this->actingAs($this->user)
            ->get(route('dashboard-jobs'));

        $response->assertStatus(200);
        
        // Check success button state changes
        $response->assertSee('buttonElement.classList.remove(\'btn-primary\')');
        $response->assertSee('buttonElement.classList.add(\'btn-success\')');
        $response->assertSee('buttonElement.innerHTML = \'<i class="fas fa-check mr-2"></i>Applied\'');
        $response->assertSee('buttonElement.disabled = true');
    }

    /** @test */
    public function success_notification_is_shown()
    {
        $response = $this->actingAs($this->user)
            ->get(route('dashboard-jobs'));

        $response->assertStatus(200);
        $response->assertSee('showNotification(\'Application submitted successfully! Your profile has been sent to the HR team.\', \'success\')');
    }

    /** @test */
    public function error_response_resets_button_state()
    {
        $response = $this->actingAs($this->user)
            ->get(route('dashboard-jobs'));

        $response->assertStatus(200);
        
        // Check error button state reset
        $response->assertSee('buttonElement.disabled = false');
        $response->assertSee('buttonElement.innerHTML = \'Apply Now\'');
    }

    /** @test */
    public function error_notification_is_shown_on_failure()
    {
        $response = $this->actingAs($this->user)
            ->get(route('dashboard-jobs'));

        $response->assertStatus(200);
        $response->assertSee('showNotification(\'Failed to submit application: \' + (data.message || \'Unknown error\'), \'error\')');
    }

    /** @test */
    public function notification_function_exists()
    {
        $response = $this->actingAs($this->user)
            ->get(route('dashboard-jobs'));

        $response->assertStatus(200);
        $response->assertSee('function showNotification(message, type = \'info\')');
    }

    /** @test */
    public function notification_has_correct_styles()
    {
        $response = $this->actingAs($this->user)
            ->get(route('dashboard-jobs'));

        $response->assertStatus(200);
        
        // Check notification CSS classes
        $response->assertSee('.notification-success');
        $response->assertSee('.notification-error');
        $response->assertSee('.notification-info');
    }

    /** @test */
    public function notification_auto_removes_after_5_seconds()
    {
        $response = $this->actingAs($this->user)
            ->get(route('dashboard-jobs'));

        $response->assertStatus(200);
        $response->assertSee('setTimeout(() => {');
        $response->assertSee('5000');
    }

    /** @test */
    public function button_success_styles_are_defined()
    {
        $response = $this->actingAs($this->user)
            ->get(route('dashboard-jobs'));

        $response->assertStatus(200);
        
        // Check button success state CSS
        $response->assertSee('.btn-success');
        $response->assertSee('background: #10b981');
        $response->assertSee('color: white');
    }

    /** @test */
    public function notification_animation_is_defined()
    {
        $response = $this->actingAs($this->user)
            ->get(route('dashboard-jobs'));

        $response->assertStatus(200);
        $response->assertSee('@keyframes slideInRight');
        $response->assertSee('animation: slideInRight 0.3s ease-out');
    }

    /** @test */
    public function application_includes_job_id()
    {
        $response = $this->actingAs($this->user)
            ->get(route('dashboard-jobs'));

        $response->assertStatus(200);
        $response->assertSee('job_id: jobId');
    }

    /** @test */
    public function application_includes_cover_letter_field()
    {
        $response = $this->actingAs($this->user)
            ->get(route('dashboard-jobs'));

        $response->assertStatus(200);
        $response->assertSee('cover_letter: \'\'');
    }

    /** @test */
    public function user_must_be_authenticated_to_see_jobs_page()
    {
        $response = $this->get(route('dashboard-jobs'));
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function multiple_jobs_show_correct_apply_buttons()
    {
        // Create additional jobs
        $job2 = Job::factory()->create([
            'title' => 'Frontend Developer',
            'company_id' => $this->company->id,
        ]);

        $job3 = Job::factory()->create([
            'title' => 'Backend Developer',
            'company_id' => $this->company->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('dashboard-jobs'));

        $response->assertStatus(200);
        
        // Check that all apply buttons have unique IDs
        $response->assertSee('id="applyBtn' . $this->job->id . '"');
        $response->assertSee('id="applyBtn' . $job2->id . '"');
        $response->assertSee('id="applyBtn' . $job3->id . '"');
        
        // Check that all apply buttons have correct onclick attributes
        $response->assertSee('onclick="applyToJob(' . $this->job->id . ', this)"');
        $response->assertSee('onclick="applyToJob(' . $job2->id . ', this)"');
        $response->assertSee('onclick="applyToJob(' . $job3->id . ', this)"');
    }
}
