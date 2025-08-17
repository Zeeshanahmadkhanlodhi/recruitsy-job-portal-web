<?php

namespace App\Http\Controllers;

use App\Jobs\SyncCompanyJobs;
use App\Models\Company;
use App\Models\Job;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class JobSyncController extends Controller
{
    public function syncNow(Company $company): RedirectResponse
    {
        SyncCompanyJobs::dispatch($company->id);
        return back()->with('status', 'Sync started for company: '.$company->name);
    }

    /**
     * Upsert jobs from HR portal (web route version)
     */
    public function upsert(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'company_id' => 'required|integer|exists:companies,id',
                'jobs' => 'required|array|min:1',
                'jobs.*.external_id' => 'required|string|max:255',
                'jobs.*.title' => 'required|string|max:255',
                'jobs.*.description' => 'nullable|string',
                'jobs.*.location' => 'nullable|string|max:255',
                'jobs.*.employment_type' => 'nullable|string|max:100',
                'jobs.*.salary_min' => 'nullable|numeric|min:0',
                'jobs.*.salary_max' => 'nullable|numeric|min:0',
                'jobs.*.currency' => 'nullable|string|max:10',
                'jobs.*.posted_at' => 'nullable|date',
                'jobs.*.apply_url' => 'nullable|url',
                'jobs.*.is_remote' => 'nullable|boolean',
            ]);

            $companyId = $request->input('company_id');
            $jobs = $request->input('jobs');
            $now = now();
            $rows = [];

            foreach ($jobs as $job) {
                $rows[] = [
                    'company_id' => $companyId,
                    'external_id' => $job['external_id'],
                    'title' => $job['title'],
                    'description' => $job['description'] ?? null,
                    'location' => $job['location'] ?? null,
                    'employment_type' => $job['employment_type'] ?? null,
                    'salary_min' => $job['salary_min'] ?? null,
                    'salary_max' => $job['salary_max'] ?? null,
                    'currency' => $job['currency'] ?? null,
                    'posted_at' => $job['posted_at'] ?? $now,
                    'apply_url' => $job['apply_url'] ?? null,
                    'is_remote' => (bool) ($job['is_remote'] ?? false),
                    'updated_at' => $now,
                    'created_at' => $now,
                ];
            }

            // Upsert jobs
            Job::upsert(
                $rows,
                ['company_id', 'external_id'],
                [
                    'title', 'description', 'location', 'employment_type',
                    'salary_min', 'salary_max', 'currency', 'posted_at', 
                    'apply_url', 'is_remote', 'updated_at'
                ]
            );

            Log::info('Job sync: Upserted jobs via web route', [
                'company_id' => $companyId,
                'count' => count($jobs),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Jobs upserted successfully',
                'count' => count($jobs),
            ], 200);

        } catch (\Exception $e) {
            Log::error('Job sync: Exception in upsert', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error',
            ], 500);
        }
    }

    /**
     * Deactivate jobs for a company (web route version)
     */
    public function deactivate(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'company_id' => 'required|integer|exists:companies,id',
                'external_ids' => 'required|array|min:1',
                'external_ids.*' => 'string|max:255',
            ]);

            $companyId = $request->input('company_id');
            $externalIds = $request->input('external_ids');

            // Delete jobs with matching external IDs for the company
            $deletedCount = Job::where('company_id', $companyId)
                ->whereIn('external_id', $externalIds)
                ->delete();

            Log::info('Job sync: Deactivated jobs via web route', [
                'company_id' => $companyId,
                'external_ids' => $externalIds,
                'deleted_count' => $deletedCount,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Jobs deactivated successfully',
                'deleted_count' => $deletedCount,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Job sync: Exception in deactivate', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error',
            ], 500);
        }
    }
}

