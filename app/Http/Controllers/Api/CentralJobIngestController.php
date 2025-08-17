<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CentralJobIngestController extends Controller
{
    /**
     * Upsert jobs from HR portal to local database
     */
    public function upsert(Request $request): JsonResponse
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
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

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

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

            Log::info('Central job ingest: Upserted jobs', [
                'company_id' => $companyId,
                'count' => count($jobs),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Jobs upserted successfully',
                'count' => count($jobs),
            ], 200);

        } catch (ValidationException $e) {
            Log::warning('Central job ingest: Validation failed', [
                'errors' => $e->errors(),
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            Log::error('Central job ingest: Exception', [
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
     * Delete/deactivate jobs for a company
     */
    public function destroy(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'company_id' => 'required|integer|exists:companies,id',
                'external_ids' => 'required|array|min:1',
                'external_ids.*' => 'string|max:255',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $companyId = $request->input('company_id');
            $externalIds = $request->input('external_ids');

            // Delete jobs with matching external IDs for the company
            $deletedCount = Job::where('company_id', $companyId)
                ->whereIn('external_id', $externalIds)
                ->delete();

            Log::info('Central job ingest: Deleted jobs', [
                'company_id' => $companyId,
                'external_ids' => $externalIds,
                'deleted_count' => $deletedCount,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Jobs deleted successfully',
                'deleted_count' => $deletedCount,
            ], 200);

        } catch (ValidationException $e) {
            Log::warning('Central job ingest: Validation failed', [
                'errors' => $e->errors(),
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            Log::error('Central job ingest: Exception', [
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


