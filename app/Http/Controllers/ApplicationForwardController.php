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
            
            // Validate the request
            $validated = $request->validate([
                'candidate_name' => ['required', 'string', 'max:255'],
                'candidate_email' => ['required', 'email'],
                'candidate_phone' => ['nullable', 'string', 'max:50'],
                'resume_url' => ['nullable', 'url'],
                'cover_letter' => ['nullable', 'string'],
            ]);

            // Create the application record
            $application = Application::create([
                'job_id' => $job->id,
                'user_id' => auth()->id(),
                'candidate_name' => $validated['candidate_name'],
                'candidate_email' => $validated['candidate_email'],
                'candidate_phone' => $validated['candidate_phone'],
                'resume_url' => $validated['resume_url'],
                'cover_letter' => $validated['cover_letter'],
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
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Application created but failed to forward to HR platform. Please try again.',
                    'application_id' => $application->id
                ], 200);
            }

        } catch (\Exception $e) {
            Log::error('Application submission failed', [
                'job_id' => $jobId,
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to submit application. Please try again.'
            ], 500);
        }
    }
}


