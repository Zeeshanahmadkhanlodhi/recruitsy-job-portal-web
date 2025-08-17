<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Job;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class JobSyncService
{
    /**
     * Fetch jobs from all tenant HR portals and merge into local DB.
     */
    public function syncAllTenants(): void
    {
        Company::query()
            ->orderBy('id')
            ->each(function (Company $company): void {
                $this->syncCompanyJobs($company);
            });
    }

    /**
     * Fetch and upsert jobs for a single company.
     */
    public function syncCompanyJobs(Company $company): void
    {
        if (empty($company->hr_portal_url)) {
            Log::warning('JobSync: Missing hr_portal_url for company', ['company_id' => $company->id]);
            return;
        }

        $endpoint = rtrim($company->hr_portal_url, '/').'/api/portal/jobs';

        $timestamp = (string) now()->getTimestamp();
        $signature = $this->generateSignature($company->api_key, $company->api_secret, $timestamp);

        try {
            $response = Http::timeout(15)
                ->retry(2, 500)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'X-API-KEY' => $company->api_key,
                    'X-API-TIMESTAMP' => $timestamp,
                    'X-API-SIGNATURE' => $signature,
                ])
                ->get($endpoint);

            // Fallback: if unauthorized with tenant secret, try global HR portal credentials
            if ($response->status() === 401) {
                $globalKey = Config::get('services.hr_portal.api_key');
                $globalSecret = Config::get('services.hr_portal.api_secret');
                if ($globalKey && $globalSecret) {
                    $globalSig = $this->generateSignature($globalKey, $globalSecret, $timestamp);
                    $response = Http::timeout(15)
                        ->retry(2, 500)
                        ->withHeaders([
                            'Accept' => 'application/json',
                            'X-API-KEY' => $globalKey,
                            'X-API-TIMESTAMP' => $timestamp,
                            'X-API-SIGNATURE' => $globalSig,
                        ])
                        ->get($endpoint);
                }
            }

            if (!$response->successful()) {
                Log::warning('JobSync: Non-success response', [
                    'company_id' => $company->id,
                    'status' => $response->status(),
                    'body' => Str::limit($response->body(), 500),
                ]);
                return; // gracefully skip
            }

            $payload = $response->json();
            if (!is_array($payload)) {
                Log::warning('JobSync: Unexpected response format', [
                    'company_id' => $company->id,
                    'payload_type' => gettype($payload),
                ]);
                return;
            }

            // Accept various response shapes
            $jobs = array_is_list($payload)
                ? $payload
                : ($payload['data']['jobs'] ?? ($payload['data'] ?? ($payload['jobs'] ?? [])));

            if (!is_array($jobs)) {
                Log::warning('JobSync: Jobs container not array', [
                    'company_id' => $company->id,
                ]);
                return;
            }

            Log::info('JobSync: Parsed jobs', [
                'company_id' => $company->id,
                'count' => count($jobs),
            ]);

            $this->mergeJobs($company->id, $jobs);
        } catch (Throwable $e) {
            Log::error('JobSync: Exception while syncing company', [
                'company_id' => $company->id,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Upsert jobs into the database from a generic payload.
     * Expected payload: array of associative arrays with keys that can be mapped below.
     */
    protected function mergeJobs(int $companyId, array $jobs): void
    {
        $now = now();
        $rows = [];

        foreach ($jobs as $job) {
            if (!is_array($job)) {
                continue;
            }

            $externalId = $job['id'] ?? $job['external_id'] ?? null;
            $title = $job['title'] ?? null;
            if (!$title) {
                continue; // minimally require a title
            }

            $rows[] = [
                'company_id' => $companyId,
                'external_id' => $externalId,
                'title' => $title,
                'description' => $job['description'] ?? null,
                'location' => $job['location'] ?? null,
                'employment_type' => $job['employment_type'] ?? ($job['type'] ?? null),
                'salary_min' => $job['salary_min'] ?? null,
                'salary_max' => $job['salary_max'] ?? null,
                'currency' => $job['currency'] ?? null,
                'posted_at' => $job['posted_at'] ?? ($job['created_at'] ?? null),
                'apply_url' => $job['apply_url'] ?? ($job['url'] ?? null),
                'is_remote' => (bool) ($job['is_remote'] ?? ($job['remote'] ?? false)),
                'updated_at' => $now,
                'created_at' => $now,
            ];
        }

        if (empty($rows)) {
            return;
        }

        // Use upsert for efficient merge on (company_id, external_id). If external_id is null,
        // uniqueness will not apply, but it will still insert records.
        Job::upsert(
            $rows,
            ['company_id', 'external_id'],
            [
                'title', 'description', 'location', 'employment_type',
                'salary_min', 'salary_max', 'currency', 'posted_at', 'apply_url', 'is_remote', 'updated_at'
            ]
        );
    }

    protected function generateSignature(string $apiKey, string $apiSecret, string $timestamp): string
    {
        $payload = $apiKey.'|'.$timestamp;
        return hash_hmac('sha256', $payload, $apiSecret);
    }
}


