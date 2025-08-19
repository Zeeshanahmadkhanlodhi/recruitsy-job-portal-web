<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Job;
use App\Models\Company;
use App\Models\UserSkill;
use App\Models\UserExperience;
use App\Models\UserEducation;
use App\Models\UserResume;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class JobApplicationBrowserTest extends DuskTestCase
{
    use DatabaseMigrations;

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
            'password' => bcrypt('password'),
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
    }

    /** @test */
    public function user_can_click_apply_button_and_see_loading_state()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('dashboard-jobs'))
                ->waitForText('Apply Now')
                ->click('#applyBtn' . $this->job->id)
                ->waitForText('Applying...')
                ->assertSee('Applying...')
                ->assertAttribute('#applyBtn' . $this->job->id, 'disabled', 'true');
        });
    }

    /** @test */
    public function apply_button_shows_correct_initial_state()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('dashboard-jobs'))
                ->waitForText('Apply Now')
                ->assertSee('Apply Now')
                ->assertAttribute('#applyBtn' . $this->job->id, 'disabled', null)
                ->assertHasClass('#applyBtn' . $this->job->id, 'btn-primary');
        });
    }

    /** @test */
    public function apply_button_has_correct_structure()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('dashboard-jobs'))
                ->waitForText('Apply Now')
                ->assertPresent('#applyBtn' . $this->job->id)
                ->assertAttribute('#applyBtn' . $this->job->id, 'onclick', 'applyToJob(' . $this->job->id . ', this)');
        });
    }

    /** @test */
    public function job_information_is_displayed_correctly()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('dashboard-jobs'))
                ->waitForText('Software Developer')
                ->assertSee('Software Developer')
                ->assertSee('Test Company')
                ->assertSee('New York')
                ->assertSee('Full-time')
                ->assertSee('$80,000 - $120,000 USD');
        });
    }

    /** @test */
    public function apply_button_is_visible_for_each_job()
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

        $this->browse(function (Browser $browser) use ($job2, $job3) {
            $browser->loginAs($this->user)
                ->visit(route('dashboard-jobs'))
                ->waitForText('Apply Now')
                
                // Check first job
                ->assertPresent('#applyBtn' . $this->job->id)
                ->assertSee('Software Developer')
                
                // Check second job
                ->assertPresent('#applyBtn' . $job2->id)
                ->assertSee('Frontend Developer')
                
                // Check third job
                ->assertPresent('#applyBtn' . $job3->id)
                ->assertSee('Backend Developer');
        });
    }

    /** @test */
    public function apply_button_click_triggers_javascript_function()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('dashboard-jobs'))
                ->waitForText('Apply Now')
                ->click('#applyBtn' . $this->job->id)
                ->waitForText('Applying...')
                ->assertSee('Applying...');
        });
    }

    /** @test */
    public function apply_button_is_disabled_after_click()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('dashboard-jobs'))
                ->waitForText('Apply Now')
                ->click('#applyBtn' . $this->job->id)
                ->waitForText('Applying...')
                ->assertAttribute('#applyBtn' . $this->job->id, 'disabled', 'true');
        });
    }

    /** @test */
    public function apply_button_shows_loading_icon()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('dashboard-jobs'))
                ->waitForText('Apply Now')
                ->click('#applyBtn' . $this->job->id)
                ->waitForText('Applying...')
                ->assertPresent('#applyBtn' . $this->job->id . ' i.fa-spinner');
        });
    }

    /** @test */
    public function job_card_layout_is_correct()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('dashboard-jobs'))
                ->waitForText('Software Developer')
                
                // Check job card structure
                ->assertPresent('.job-card')
                ->assertPresent('.job-header')
                ->assertPresent('.job-info')
                ->assertPresent('.job-title')
                ->assertPresent('.company-name')
                ->assertPresent('.job-description')
                ->assertPresent('.job-meta')
                ->assertPresent('.job-actions');
        });
    }

    /** @test */
    public function view_details_button_works()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('dashboard-jobs'))
                ->waitForText('View Details')
                ->clickLink('View Details')
                ->waitForLocation(route('dashboard-jobs.detail', $this->job->id))
                ->assertPathIs('/dashboard/jobs/' . $this->job->id);
        });
    }

    /** @test */
    public function search_and_filters_are_present()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('dashboard-jobs'))
                ->waitForText('Search jobs, companies, or keywords...')
                
                // Check search box
                ->assertPresent('.search-box input[type="text"]')
                ->assertPresent('.search-btn')
                
                // Check filters
                ->assertPresent('select')
                ->assertSee('All Locations')
                ->assertSee('All Types')
                ->assertSee('All Levels')
                ->assertSee('All Ranges');
        });
    }

    /** @test */
    public function view_toggle_buttons_work()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('dashboard-jobs'))
                ->waitForText('Apply Now')
                
                // Check view toggle buttons
                ->assertPresent('[data-view="grid"]')
                ->assertPresent('[data-view="list"]')
                ->assertHasClass('[data-view="grid"]', 'active');
        });
    }

    /** @test */
    public function pagination_is_present_when_needed()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('dashboard-jobs'))
                ->waitForText('Apply Now')
                
                // Check if pagination exists (depends on number of jobs)
                ->assertPresent('.pagination');
        });
    }

    /** @test */
    public function responsive_design_works_on_mobile()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->resize(375, 667) // iPhone SE size
                ->visit(route('dashboard-jobs'))
                ->waitForText('Apply Now')
                
                // Check that content is still visible and accessible
                ->assertSee('Software Developer')
                ->assertSee('Apply Now')
                ->assertPresent('#applyBtn' . $this->job->id);
        });
    }

    /** @test */
    public function apply_button_has_correct_styling()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('dashboard-jobs'))
                ->waitForText('Apply Now')
                
                // Check button styling
                ->assertHasClass('#applyBtn' . $this->job->id, 'btn')
                ->assertHasClass('#applyBtn' . $this->job->id, 'btn-primary')
                ->assertHasClass('#applyBtn' . $this->job->id, 'btn-sm');
        });
    }

    /** @test */
    public function job_meta_information_is_displayed()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('dashboard-jobs'))
                ->waitForText('Apply Now')
                
                // Check meta information
                ->assertPresent('.meta-item')
                ->assertSee('New York')
                ->assertSee('Full-time')
                ->assertSee('$80,000 - $120,000 USD');
        });
    }

    /** @test */
    public function company_information_is_displayed()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('dashboard-jobs'))
                ->waitForText('Apply Now')
                
                // Check company information
                ->assertSee('Test Company')
                ->assertPresent('.company-name');
        });
    }
}
