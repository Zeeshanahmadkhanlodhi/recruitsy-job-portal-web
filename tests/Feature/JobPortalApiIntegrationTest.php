<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Job;
use App\Models\Company;
use App\Models\Application;
use App\Services\ApplicationForwardService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class JobPortalApiIntegrationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $job;
    protected $company;
    protected $forwardService;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a user with profile data
        $this->user = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
        ]);

        // Create company with API credentials
        $this->company = Company::factory()->create([
            'name' => 'Test Company',
            'hr_portal_url' => 'http://localhost:8000',
            'api_key' => 'test_api_key_123',
            'api_secret' => 'test_api_secret_456',
        ]);

        // Create job
        $this->job = Job::factory()->create([
            'title' => 'Software Developer',
            'company_id' => $this->company->id,
            'external_id' => 'EXT-001',
        ]);

        // Create application
        $this->application = Application::create([
            'job_id' => $this->job->id,
            'user_id' => $this->user->id,
            'candidate_name' => 'John Doe',
            'candidate_email' => 'john.doe@example.com',
            'candidate_phone' => '+1234567890',
            'status' => 'pending',
        ]);

        $this->forwardService = new ApplicationForwardService();
    }

    /** @test */
    public function api_integration_sends_correct_required_fields()
    {
        Http::fake([
            'http://localhost:8000/api/portal/jobs/EXT-001/apply' => Http::response([
                'success' => true,
                'message' => 'Application received'
            ], 200)
        ]);

        $result = $this->forwardService->forwardApplication($this->application);

        Http::assertSent(function ($request) {
            $payload = $request->data();
            
            // Check required fields
            $this->assertEquals('John', $payload['first_name']);
            $this->assertEquals('Doe', $payload['last_name']);
            $this->assertEquals('john.doe@example.com', $payload['email']);
            
            return true;
        });

        $this->assertTrue($result);
    }

    /** @test */
    public function api_integration_sends_correct_optional_fields()
    {
        Http::fake([
            'http://localhost:8000/api/portal/jobs/EXT-001/apply' => Http::response([
                'success' => true,
                'message' => 'Application received'
            ], 200)
        ]);

        $result = $this->forwardService->forwardApplication($this->application);

        Http::assertSent(function ($request) {
            $payload = $request->data();
            
            // Check optional fields
            $this->assertEquals('+1234567890', $payload['phone']);
            $this->assertArrayHasKey('resume_url', $payload);
            $this->assertArrayHasKey('cover_letter', $payload);
            
            return true;
        });

        $this->assertTrue($result);
    }

    /** @test */
    public function api_integration_sends_required_headers()
    {
        Http::fake([
            'http://localhost:8000/api/portal/jobs/EXT-001/apply' => Http::response([
                'success' => true,
                'message' => 'Application received'
            ], 200)
        ]);

        $result = $this->forwardService->forwardApplication($this->application);

        Http::assertSent(function ($request) {
            $headers = $request->headers();
            
            // Check required headers
            $this->assertEquals('test_api_key_123', $headers['X-API-KEY'][0]);
            $this->assertArrayHasKey('X-API-SIGNATURE', $headers);
            $this->assertArrayHasKey('X-API-TIMESTAMP', $headers);
            $this->assertEquals('application/json', $headers['Content-Type'][0]);
            $this->assertEquals('application/json', $headers['Accept'][0]);
            
            return true;
        });

        $this->assertTrue($result);
    }

    /** @test */
    public function api_integration_uses_correct_endpoint()
    {
        Http::fake([
            'http://localhost:8000/api/portal/jobs/EXT-001/apply' => Http::response([
                'success' => true,
                'message' => 'Application received'
            ], 200)
        ]);

        $result = $this->forwardService->forwardApplication($this->application);

        Http::assertSent(function ($request) {
            $this->assertEquals(
                'http://localhost:8000/api/portal/jobs/EXT-001/apply',
                $request->url()
            );
            return true;
        });

        $this->assertTrue($result);
    }

    /** @test */
    public function api_integration_handles_success_response()
    {
        $successResponse = [
            'success' => true,
            'message' => 'Application received',
            'application_id' => 'HR-APP-001'
        ];

        Http::fake([
            'http://localhost:8000/api/portal/jobs/EXT-001/apply' => Http::response($successResponse, 200)
        ]);

        $result = $this->forwardService->forwardApplication($this->application);

        $this->assertTrue($result);
        
        // Check that application was marked as successful
        $this->application->refresh();
        $this->assertEquals('success', $this->application->status);
        $this->assertEquals($successResponse, $this->application->hr_response);
    }

    /** @test */
    public function api_integration_handles_error_response()
    {
        $errorResponse = '{"error": "Invalid API key"}';

        Http::fake([
            'http://localhost:8000/api/portal/jobs/EXT-001/apply' => Http::response($errorResponse, 401)
        ]);

        $result = $this->forwardService->forwardApplication($this->application);

        $this->assertFalse($result);
        
        // Check that application was marked as failed
        $this->application->refresh();
        $this->assertEquals('failed', $this->application->status);
        $this->assertStringContainsString('HR Platform returned status 401', $this->application->error_message);
    }

    /** @test */
    public function api_integration_handles_network_exception()
    {
        Http::fake([
            'http://localhost:8000/api/portal/jobs/EXT-001/apply' => Http::response('', 500)
        ]);

        $result = $this->forwardService->forwardApplication($this->application);

        $this->assertFalse($result);
        
        // Check that application was marked as failed
        $this->application->refresh();
        $this->assertEquals('failed', $this->application->status);
        $this->assertStringContainsString('HR Platform returned status 500', $this->application->error_message);
    }

    /** @test */
    public function api_integration_uses_external_id_when_available()
    {
        // Create job with external_id
        $jobWithExternalId = Job::factory()->create([
            'company_id' => $this->company->id,
            'external_id' => 'EXT-JOB-123',
        ]);

        $application = Application::create([
            'job_id' => $jobWithExternalId->id,
            'user_id' => $this->user->id,
            'candidate_name' => 'John Doe',
            'candidate_email' => 'john.doe@example.com',
            'status' => 'pending',
        ]);

        Http::fake([
            'http://localhost:8000/api/portal/jobs/EXT-JOB-123/apply' => Http::response([
                'success' => true
            ], 200)
        ]);

        $result = $this->forwardService->forwardApplication($application);

        Http::assertSent(function ($request) {
            $this->assertEquals(
                'http://localhost:8000/api/portal/jobs/EXT-JOB-123/apply',
                $request->url()
            );
            return true;
        });

        $this->assertTrue($result);
    }

    /** @test */
    public function api_integration_falls_back_to_job_id_when_no_external_id()
    {
        // Create job without external_id
        $jobWithoutExternalId = Job::factory()->create([
            'company_id' => $this->company->id,
            'external_id' => null,
        ]);

        $application = Application::create([
            'job_id' => $jobWithoutExternalId->id,
            'user_id' => $this->user->id,
            'candidate_name' => 'John Doe',
            'candidate_email' => 'john.doe@example.com',
            'status' => 'pending',
        ]);

        Http::fake([
            'http://localhost:8000/api/portal/jobs/' . $jobWithoutExternalId->id . '/apply' => Http::response([
                'success' => true
            ], 200)
        ]);

        $result = $this->forwardService->forwardApplication($application);

        Http::assertSent(function ($request) use ($jobWithoutExternalId) {
            $this->assertEquals(
                'http://localhost:8000/api/portal/jobs/' . $jobWithoutExternalId->id . '/apply',
                $request->url()
            );
            return true;
        });

        $this->assertTrue($result);
    }

    /** @test */
    public function api_integration_generates_valid_hmac_signature()
    {
        Http::fake([
            'http://localhost:8000/api/portal/jobs/EXT-001/apply' => Http::response([
                'success' => true
            ], 200)
        ]);

        $result = $this->forwardService->forwardApplication($this->application);

        Http::assertSent(function ($request) {
            $headers = $request->headers();
            
            // Check that signature is present and valid format
            $this->assertArrayHasKey('X-API-SIGNATURE', $headers);
            $this->assertArrayHasKey('X-API-TIMESTAMP', $headers);
            
            $signature = $headers['X-API-SIGNATURE'][0];
            $timestamp = $headers['X-API-TIMESTAMP'][0];
            
            // Verify signature format (should be 64 character hex string)
            $this->assertEquals(64, strlen($signature));
            $this->assertTrue(ctype_xdigit($signature));
            
            // Verify timestamp is numeric
            $this->assertTrue(is_numeric($timestamp));
            
            return true;
        });

        $this->assertTrue($result);
    }

    /** @test */
    public function api_integration_uses_company_specific_hr_portal_url()
    {
        // Create company with custom HR portal URL
        $customCompany = Company::factory()->create([
            'name' => 'Custom Company',
            'hr_portal_url' => 'https://custom-hr.company.com',
            'api_key' => 'custom_key',
            'api_secret' => 'custom_secret',
        ]);

        $customJob = Job::factory()->create([
            'company_id' => $customCompany->id,
            'external_id' => 'CUSTOM-001',
        ]);

        $customApplication = Application::create([
            'job_id' => $customJob->id,
            'user_id' => $this->user->id,
            'candidate_name' => 'John Doe',
            'candidate_email' => 'john.doe@example.com',
            'status' => 'pending',
        ]);

        Http::fake([
            'https://custom-hr.company.com/api/portal/jobs/CUSTOM-001/apply' => Http::response([
                'success' => true
            ], 200)
        ]);

        $result = $this->forwardService->forwardApplication($customApplication);

        Http::assertSent(function ($request) {
            $this->assertEquals(
                'https://custom-hr.company.com/api/portal/jobs/CUSTOM-001/apply',
                $request->url()
            );
            return true;
        });

        $this->assertTrue($result);
    }

    /** @test */
    public function api_integration_falls_back_to_default_url_when_no_company_url()
    {
        // Create company without HR portal URL
        $defaultCompany = Company::factory()->create([
            'name' => 'Default Company',
            'hr_portal_url' => null,
            'api_key' => 'default_key',
            'api_secret' => 'default_secret',
        ]);

        $defaultJob = Job::factory()->create([
            'company_id' => $defaultCompany->id,
            'external_id' => 'DEFAULT-001',
        ]);

        $defaultApplication = Application::create([
            'job_id' => $defaultJob->id,
            'user_id' => $this->user->id,
            'candidate_name' => 'John Doe',
            'candidate_email' => 'john.doe@example.com',
            'status' => 'pending',
        ]);

        Http::fake([
            'http://localhost:8000/api/portal/jobs/DEFAULT-001/apply' => Http::response([
                'success' => true
            ], 200)
        ]);

        $result = $this->forwardService->forwardApplication($defaultApplication);

        Http::assertSent(function ($request) {
            $this->assertEquals(
                'http://localhost:8000/api/portal/jobs/DEFAULT-001/apply',
                $request->url()
            );
            return true;
        });

        $this->assertTrue($result);
    }
}
