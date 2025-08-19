<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Job;
use App\Services\ApplicationForwardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApplicationForwardController extends Controller
{
    protected $forwardService;

    public function __construct(ApplicationForwardService $forwardService)
    {
        $this->forwardService = $forwardService;
    }

    // Candidate applies via Job Portal to a job
    public function apply(Request $request, int $jobId)
    {
        try {
            $job = Job::findOrFail($jobId);

            $user = auth()->user();

            // Merge sensible defaults from the authenticated user if not provided
            $request->merge([
                'candidate_name' => $request->input('candidate_name', $user ? ($user->full_name ?? $user->name) : null),
                'candidate_email' => $request->input('candidate_email', $user ? $user->email : null),
                'candidate_phone' => $request->input('candidate_phone', $user ? $user->phone : null),
            ]);

            // Validate the request without throwing to allow graceful failure recording
            $validator = \Validator::make($request->all(), [
                'candidate_name' => ['required', 'string', 'max:255'],
                'candidate_email' => ['required', 'email'],
                'candidate_phone' => ['nullable', 'string', 'max:50'],
                'resume_url' => ['nullable', 'url'],
                'cover_letter' => ['nullable', 'string'],
            ]);

            if ($validator->fails()) {
                // Create a failed application record capturing the validation errors
                $application = Application::create([
                    'job_id' => $job->id,
                    'user_id' => $user?->id,
                    'candidate_name' => (string) $request->input('candidate_name', ''),
                    'candidate_email' => (string) $request->input('candidate_email', ''),
                    'candidate_phone' => $request->input('candidate_phone'),
                    'resume_url' => $request->input('resume_url'),
                    'cover_letter' => $request->input('cover_letter'),
                    'status' => 'failed',
                    'error_message' => 'Validation failed: ' . json_encode($validator->errors()->toArray()),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                    'application_id' => $application->id,
                ], 422);
            }

            $validated = $validator->validated();

            // Create the application record in pending state
            $application = Application::create([
                'job_id' => $job->id,
                'user_id' => $user?->id,
                'candidate_name' => $validated['candidate_name'],
                'candidate_email' => $validated['candidate_email'],
                'candidate_phone' => $validated['candidate_phone'] ?? null,
                'resume_url' => $validated['resume_url'] ?? null,
                'cover_letter' => $validated['cover_letter'] ?? null,
                'status' => 'pending',
            ]);

            // Forward the application to the HR platform
            $forwarded = $this->forwardService->forwardApplication($application);

            if ($forwarded) {
                return response()->json([
                    'success' => true,
                    'message' => 'Application submitted successfully and forwarded to HR platform',
                    'application_id' => $application->id
                ], 201);
            }

            // Forwarding failed: include saved error message
            $application->refresh();
            return response()->json([
                'success' => false,
                'message' => $application->error_message ?: 'Application created but failed to forward to HR platform.',
                'application_id' => $application->id
            ], 200);

        } catch (\Exception $e) {
            // Ensure we record a failed application even on unexpected exception
            $application = null;
            try {
                $application = Application::create([
                    'job_id' => $jobId,
                    'user_id' => auth()->id(),
                    'candidate_name' => (string) $request->input('candidate_name', ''),
                    'candidate_email' => (string) $request->input('candidate_email', ''),
                    'candidate_phone' => $request->input('candidate_phone'),
                    'resume_url' => $request->input('resume_url'),
                    'cover_letter' => $request->input('cover_letter'),
                    'status' => 'failed',
                    'error_message' => 'Exception: ' . $e->getMessage(),
                ]);
            } catch (\Throwable $ignored) {
                // Swallow secondary errors while creating failure record
            }

            Log::error('Application submission failed', [
                'job_id' => $jobId,
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to submit application. Please try again.',
                'application_id' => $application?->id,
            ], 500);
        }
    }

    // Retry a failed application
    public function retry(int $applicationId)
    {
        try {
            $application = Application::where('id', $applicationId)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            if ($application->status !== 'failed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only failed applications can be retried'
                ], 400);
            }

            // Reset the application status to pending
            $application->update([
                'status' => 'pending',
                'error_message' => null,
            ]);

            // Attempt to forward the application again
            $forwarded = $this->forwardService->forwardApplication($application);

            if ($forwarded) {
                return response()->json([
                    'success' => true,
                    'message' => 'Application retried successfully and forwarded to HR platform',
                    'application_id' => $application->id
                ], 200);
            }

            // Forwarding failed again
            $application->refresh();
            return response()->json([
                'success' => false,
                'message' => $application->error_message ?: 'Application retry failed to forward to HR platform.',
                'application_id' => $application->id
            ], 200);

        } catch (\Exception $e) {
            Log::error('Application retry failed', [
                'application_id' => $applicationId,
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retry application. Please try again.',
            ], 500);
        }
    }
}


