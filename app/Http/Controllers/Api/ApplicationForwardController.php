<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PageController;
use App\Models\Application;
use App\Models\Job;
use App\Services\ApplicationForwardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ApplicationForwardController extends Controller
{
    protected $applicationForwardService;

    public function __construct(ApplicationForwardService $applicationForwardService)
    {
        $this->applicationForwardService = $applicationForwardService;
    }

    public function apply(Request $request, $jobId)
    {
        try {
            // Find the job
            $job = Job::findOrFail($jobId);
            
            // Validate the request
            $validator = Validator::make($request->all(), [
                'candidate_name' => 'required|string|max:255',
                'candidate_email' => 'required|email|max:255',
                'candidate_phone' => 'nullable|string|max:20',
                'resume_url' => 'nullable|url|max:500',
                'cover_letter' => 'nullable|string|max:2000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Create the application record
            $application = Application::create([
                'job_id' => $job->id,
                'user_id' => auth()->id(), // null if not logged in
                'candidate_name' => $request->candidate_name,
                'candidate_email' => $request->candidate_email,
                'candidate_phone' => $request->candidate_phone,
                'resume_url' => $request->resume_url,
                'cover_letter' => $request->cover_letter,
                'status' => 'pending',
            ]);

            // Forward the application to the HR platform
            $success = $this->applicationForwardService->forwardApplication($application);

            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Application submitted successfully!',
                    'application_id' => $application->id
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Application submitted but there was an issue forwarding to the company. We will retry automatically.',
                    'application_id' => $application->id
                ], 200);
            }

        } catch (\Exception $e) {
            Log::error('Application submission error', [
                'job_id' => $jobId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting your application. Please try again.'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        return $this->apply($request, $request->job_id);
    }
}


