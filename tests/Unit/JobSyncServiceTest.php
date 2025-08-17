<?php

namespace Tests\Unit;

use App\Models\Company;
use App\Models\Job;
use App\Services\JobSyncService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class JobSyncServiceTest extends TestCase
{
    use RefreshDatabase;

    private JobSyncService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new JobSyncService();
    }

    public function test_generate_signature()
    {
        $apiKey = 'test_key';
        $apiSecret = 'test_secret';
        $timestamp = '1234567890';

        // Use reflection to access protected method
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('generateSignature');
        $method->setAccessible(true);
        
        $signature = $method->invoke($this->service, $apiKey, $apiSecret, $timestamp);
        
        $expected = hash_hmac('sha256', $apiKey.'|'.$timestamp, $apiSecret);
        $this->assertEquals($expected, $signature);
    }

    public function test_debug_http_mocking()
    {
        // Create a test company
        $company = Company::factory()->create([
            'hr_portal_url' => 'https://example.com',
            'api_key' => 'test_key',
            'api_secret' => 'test_secret',
        ]);

        // Mock HTTP response
        Http::fake([
            'https://example.com/api/portal/jobs' => Http::response([
                'data' => [
                    'jobs' => [
                        [
                            'id' => 'job_1',
                            'title' => 'Software Engineer',
                            'description' => 'Test job description',
                            'location' => 'Remote',
                            'employment_type' => 'Full-time',
                            'salary_min' => 80000,
                            'salary_max' => 120000,
                            'currency' => 'USD',
                            'posted_at' => '2024-01-01T00:00:00Z',
                            'is_remote' => true,
                        ]
                    ]
                ]
            ], 200)
        ]);

        // Mock the generateSignature method to avoid reflection issues in test
        $this->service = $this->getMockBuilder(JobSyncService::class)
            ->onlyMethods(['generateSignature'])
            ->getMock();
        
        $this->service->method('generateSignature')
            ->willReturn('test_signature');

        // Enable logging to see what's happening
        Log::shouldReceive('info')->andReturn(true);
        Log::shouldReceive('warning')->andReturn(true);
        Log::shouldReceive('error')->andReturn(true);

        $this->service->syncCompanyJobs($company);

        // Check if HTTP was called
        Http::assertSent(function ($request) {
            return $request->url() === 'https://example.com/api/portal/jobs';
        });

        // Check the response
        $response = Http::get('https://example.com/api/portal/jobs');
        $this->assertEquals(200, $response->status());
        $this->assertArrayHasKey('data', $response->json());
    }

    public function test_simple_job_merge()
    {
        // Create a test company
        $company = Company::factory()->create();
        
        // Test the mergeJobs method directly
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('mergeJobs');
        $method->setAccessible(true);
        
        $jobs = [
            [
                'id' => 'job_1',
                'title' => 'Software Engineer',
                'is_remote' => true,
            ]
        ];
        
        $method->invoke($this->service, $company->id, $jobs);
        
        // Assert job was created
        $this->assertDatabaseHas('job_listings', [
            'company_id' => $company->id,
            'external_id' => 'job_1',
            'title' => 'Software Engineer',
            'is_remote' => true,
        ]);
    }

    public function test_debug_service_execution()
    {
        // Create a test company
        $company = Company::factory()->create([
            'hr_portal_url' => 'https://example.com',
            'api_key' => 'test_key',
            'api_secret' => 'test_secret',
        ]);

        // Mock HTTP response
        Http::fake([
            'https://example.com/api/portal/jobs' => Http::response([
                'data' => [
                    'jobs' => [
                        [
                            'id' => 'job_1',
                            'title' => 'Software Engineer',
                            'is_remote' => true,
                        ]
                    ]
                ]
            ], 200)
        ]);

        // Create a service instance and manually call the method
        $service = new JobSyncService();
        
        // Use reflection to call the protected method directly
        $reflection = new \ReflectionClass($service);
        $method = $reflection->getMethod('generateSignature');
        $method->setAccessible(true);
        
        // Mock the generateSignature method by replacing it
        $service = $this->getMockBuilder(JobSyncService::class)
            ->onlyMethods(['generateSignature'])
            ->getMock();
        
        $service->method('generateSignature')
            ->willReturn('test_signature');

        // Call the service
        $service->syncCompanyJobs($company);
        
        // Check what was sent
        Http::assertSent(function ($request) {
            return $request->url() === 'https://example.com/api/portal/jobs';
        });
        
        // Check if any jobs were created
        $jobCount = \DB::table('job_listings')->count();
        $this->assertGreaterThan(0, $jobCount, "No jobs were created. Expected at least 1 job.");
    }

    public function test_debug_full_sync_flow()
    {
        // Create a test company
        $company = Company::factory()->create([
            'hr_portal_url' => 'https://example.com',
            'api_key' => 'test_key',
            'api_secret' => 'test_secret',
        ]);

        // Mock HTTP response
        Http::fake([
            'https://example.com/api/portal/jobs' => Http::response([
                'data' => [
                    'jobs' => [
                        [
                            'id' => 'job_1',
                            'title' => 'Software Engineer',
                            'is_remote' => true,
                        ]
                    ]
                ]
            ], 200)
        ]);

        // Create a test double that extends the real service
        $testService = new class extends JobSyncService {
            protected function generateSignature(string $apiKey, string $apiSecret, string $timestamp): string
            {
                return 'test_signature';
            }
        };

        // Call the service
        $testService->syncCompanyJobs($company);
        
        // Check what was sent
        Http::assertSent(function ($request) {
            return $request->url() === 'https://example.com/api/portal/jobs';
        });
        
        // Check the actual response that was received
        $response = Http::get('https://example.com/api/portal/jobs');
        $this->assertEquals(200, $response->status());
        
        // Check if the response has the expected structure
        $data = $response->json();
        $this->assertArrayHasKey('data', $data);
        $this->assertArrayHasKey('jobs', $data['data']);
        $this->assertCount(1, $data['data']['jobs']);
        
        // Check if any jobs were created at all
        $jobCount = \DB::table('job_listings')->count();
        $this->assertGreaterThan(0, $jobCount, "No jobs were created. Expected at least 1 job.");
        
        // If no jobs were created, let's see what's in the database
        if ($jobCount === 0) {
            $allJobs = \DB::table('job_listings')->get();
            $this->fail("No jobs created. Database contents: " . json_encode($allJobs));
        }
    }

    public function test_sync_company_jobs_with_valid_response()
    {
        // Create a test company
        $company = Company::factory()->create([
            'hr_portal_url' => 'https://example.com',
            'api_key' => 'test_key',
            'api_secret' => 'test_secret',
        ]);

        // Mock HTTP response
        Http::fake([
            'https://example.com/api/portal/jobs' => Http::response([
                'data' => [
                    'jobs' => [
                        [
                            'id' => 'job_1',
                            'title' => 'Software Engineer',
                            'description' => 'Test job description',
                            'location' => 'Remote',
                            'employment_type' => 'Full-time',
                            'salary_min' => 80000,
                            'salary_max' => 120000,
                            'currency' => 'USD',
                            'posted_at' => '2024-01-01T00:00:00Z',
                            'is_remote' => true,
                        ]
                    ]
                ]
            ], 200)
        ]);

        // Create a real service instance and use reflection to set the signature
        $service = new JobSyncService();
        
        // Use reflection to temporarily make generateSignature public for testing
        $reflection = new \ReflectionClass($service);
        $method = $reflection->getMethod('generateSignature');
        $method->setAccessible(true);
        
        // Create a test double that extends the real service
        $testService = new class extends JobSyncService {
            protected function generateSignature(string $apiKey, string $apiSecret, string $timestamp): string
            {
                return 'test_signature';
            }
        };

        // Debug: Check what's in the database before
        $this->assertDatabaseCount('job_listings', 0);
        
        // Debug: Check if company was created
        $this->assertDatabaseHas('companies', ['id' => $company->id]);
        
        // Debug: Check if HTTP mock is working
        $response = Http::get('https://example.com/api/portal/jobs');
        $this->assertEquals(200, $response->status());
        $this->assertArrayHasKey('data', $response->json());
        
        $testService->syncCompanyJobs($company);

        // Assert job was created
        $this->assertDatabaseHas('job_listings', [
            'company_id' => $company->id,
            'external_id' => 'job_1',
            'title' => 'Software Engineer',
            'is_remote' => true,
        ]);
    }

    public function test_sync_company_jobs_with_missing_url()
    {
        $company = Company::factory()->create([
            'hr_portal_url' => null,
        ]);

        // Should not throw exception
        $this->service->syncCompanyJobs($company);
        
        // No jobs should be created
        $this->assertDatabaseCount('job_listings', 0);
    }

    public function test_sync_company_jobs_with_unauthorized_response()
    {
        $company = Company::factory()->create([
            'hr_portal_url' => 'https://example.com',
            'api_key' => 'test_key',
            'api_secret' => 'test_secret',
        ]);

        // Mock unauthorized response
        Http::fake([
            'https://example.com/api/portal/jobs' => Http::response([], 401)
        ]);

        // Should not throw exception
        $this->service->syncCompanyJobs($company);
        
        // No jobs should be created
        $this->assertDatabaseCount('job_listings', 0);
    }

    public function test_merge_jobs_with_upsert()
    {
        $company = Company::factory()->create();
        
        $jobs = [
            [
                'id' => 'job_1',
                'title' => 'Software Engineer',
                'description' => 'Updated description',
            ],
            [
                'id' => 'job_2',
                'title' => 'Product Manager',
                'description' => 'New job',
            ]
        ];

        // Use reflection to access protected method
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('mergeJobs');
        $method->setAccessible(true);
        
        $method->invoke($this->service, $company->id, $jobs);

        // Assert jobs were created
        $this->assertDatabaseCount('job_listings', 2);
        $this->assertDatabaseHas('job_listings', [
            'company_id' => $company->id,
            'external_id' => 'job_1',
            'title' => 'Software Engineer',
        ]);
    }
}
