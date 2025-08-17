<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Job;
use App\Models\Company;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ApplicationForwardService
{
    public function forwardApplication(Application $application): bool
    {
        try {
            $job = $application->job;
            $company = $job->company;

            if (!$company) {
                $this->markAsFailed($application, 'Company not found for this job');
                return false;
            }

            // Configure HR platform endpoint (running on port 8000)
            $hrPlatformUrl = $company->hr_portal_url ?: 'http://localhost:8000';
            $endpoint = rtrim($hrPlatformUrl, '/') . '/api/portal/jobs/' . ($job->external_id ?: $job->id) . '/apply';
            
            $payload = [
                'name' => $application->candidate_name,
                'email' => $application->candidate_email,
                'phone' => $application->candidate_phone,
                'resume_url' => $application->resume_url,
                'cover_letter' => $application->cover_letter,
            ];

            // Generate HMAC signature for authentication
            $timestamp = now()->timestamp;
            $signature = $this->generateSignature($company->api_key, $timestamp, $company->api_secret);

            $headers = [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'User-Agent' => 'Recruitsy-Job-Portal/1.0',
                'X-API-KEY' => $company->api_key,
                'X-API-SIGNATURE' => $signature,
                'X-API-TIMESTAMP' => $timestamp,
            ];

            Log::info('Forwarding application to HR platform', [
                'application_id' => $application->id,
                'endpoint' => $endpoint,
                'company_id' => $company->id,
                'payload' => $payload
            ]);

            $response = Http::timeout(30)
                ->withHeaders($headers)
                ->post($endpoint, $payload);

            if ($response->successful()) {
                $this->markAsSuccess($application, $response->json());
                Log::info('Application forwarded successfully', [
                    'application_id' => $application->id,
                    'company_id' => $company->id,
                    'response' => $response->json()
                ]);
                return true;
            } else {
                $errorMessage = 'HR Platform returned status ' . $response->status() . ': ' . $response->body();
                $this->markAsFailed($application, $errorMessage);
                Log::error('Application forwarding failed', [
                    'application_id' => $application->id,
                    'company_id' => $company->id,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return false;
            }

        } catch (\Exception $e) {
            $errorMessage = 'Exception occurred: ' . $e->getMessage();
            $this->markAsFailed($application, $errorMessage);
            Log::error('Application forwarding exception', [
                'application_id' => $application->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    protected function generateSignature(string $apiKey, int $timestamp, string $apiSecret): string
    {
        $payload = $apiKey . '|' . $timestamp;
        return hash_hmac('sha256', $payload, $apiSecret);
    }

    protected function markAsSuccess(Application $application, array $response): void
    {
        $application->update([
            'status' => 'success',
            'hr_response' => $response,
        ]);
    }

    protected function markAsFailed(Application $application, string $errorMessage): void
    {
        $application->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
        ]);
    }

    public function retryFailedApplications(): int
    {
        $failedApplications = Application::where('status', 'failed')->get();
        $successCount = 0;

        foreach ($failedApplications as $application) {
            if ($this->forwardApplication($application)) {
                $successCount++;
            }
        }

        return $successCount;
    }
}
