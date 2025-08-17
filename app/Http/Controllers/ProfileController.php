<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProfessionalInfo;
use App\Models\UserSkill;
use App\Models\UserExperience;
use App\Models\UserEducation;
use App\Models\UserResume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show()
    {
        $user = Auth::user()->load([
            'professionalInfo',
            'skills',
            'experience',
            'education',
            'resumes'
        ]);

        return view('pages.profile', compact('user'));
    }

    /**
     * Update personal information.
     */
    public function updatePersonalInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'linkedin_url' => 'nullable|url|max:255',
            'github_url' => 'nullable|url|max:255',
            'portfolio_url' => 'nullable|url|max:255',
            'bio' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $user->update($request->only([
            'first_name', 'last_name', 'phone', 'location', 'date_of_birth',
            'linkedin_url', 'github_url', 'portfolio_url', 'bio'
        ]));

        return response()->json(['success' => true, 'message' => 'Personal information updated successfully']);
    }

    /**
     * Update or create professional information.
     */
    public function updateProfessionalInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_title' => 'nullable|string|max:255',
            'years_of_experience' => 'nullable|integer|min:0|max:50',
            'preferred_job_type' => 'nullable|in:full-time,part-time,contract,freelance,internship',
            'willing_to_relocate' => 'boolean',
            'expected_salary_min' => 'nullable|string|max:50',
            'expected_salary_max' => 'nullable|string|max:50',
            'work_authorization' => 'nullable|string|max:255',
            'summary' => 'nullable|string|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $user->professionalInfo()->updateOrCreate(
            ['user_id' => $user->id],
            $request->all()
        );

        return response()->json(['success' => true, 'message' => 'Professional information updated successfully']);
    }

    /**
     * Update skills.
     */
    public function updateSkills(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'skills' => 'required|array',
            'skills.*.category' => 'required|string|max:255',
            'skills.*.skill_name' => 'required|string|max:255',
            'skills.*.proficiency_level' => 'required|in:beginner,intermediate,advanced,expert',
            'skills.*.years_of_experience' => 'nullable|integer|min:0|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        
        // Delete existing skills
        $user->skills()->delete();
        
        // Create new skills
        foreach ($request->skills as $skill) {
            $user->skills()->create($skill);
        }

        return response()->json(['success' => true, 'message' => 'Skills updated successfully']);
    }

    /**
     * Add or update professional experience.
     */
    public function updateExperience(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'experiences_to_add' => 'array',
            'experiences_to_add.*.job_title' => 'required|string|max:255',
            'experiences_to_add.*.company_name' => 'required|string|max:255',
            'experiences_to_add.*.start_date' => 'required|date',
            'experiences_to_add.*.end_date' => 'nullable|date|after:experiences_to_add.*.start_date',
            'experiences_to_add.*.job_description' => 'required|string|max:2000',
            'experiences_to_remove' => 'array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        
        // Remove experiences marked for deletion
        if ($request->has('experiences_to_remove') && is_array($request->experiences_to_remove)) {
            foreach ($request->experiences_to_remove as $experienceId) {
                $user->experience()->where('id', $experienceId)->delete();
            }
        }
        
        // Add new experiences
        if ($request->has('experiences_to_add') && is_array($request->experiences_to_add)) {
            foreach ($request->experiences_to_add as $experience) {
                $user->experience()->create([
                    'job_title' => $experience['job_title'],
                    'company_name' => $experience['company_name'],
                    'start_date' => $experience['start_date'],
                    'end_date' => $experience['end_date'],
                    'description' => $experience['job_description'], // Map job_description to description
                ]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Professional experience updated successfully']);
    }

    /**
     * Add or update education.
     */
    public function updateEducation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'education_to_add' => 'array',
            'education_to_add.*.degree' => 'required|string|max:255',
            'education_to_add.*.institution' => 'required|string|max:255',
            'education_to_add.*.field_of_study' => 'nullable|string|max:255',
            'education_to_add.*.start_date' => 'nullable|date',
            'education_to_add.*.end_date' => 'nullable|date|after:education_to_add.*.start_date',
            'education_to_add.*.gpa' => 'nullable|numeric|min:0|max:10',
            'education_to_add.*.scale' => 'nullable|string|max:10',
            'education_to_add.*.description' => 'nullable|string|max:2000',
            'education_to_add.*.location' => 'nullable|string|max:255',
            'education_to_remove' => 'array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        
        // Remove education marked for deletion
        if ($request->has('education_to_remove') && is_array($request->education_to_remove)) {
            foreach ($request->education_to_remove as $educationId) {
                $user->education()->where('id', $educationId)->delete();
            }
        }
        
        // Add new education
        if ($request->has('education_to_add') && is_array($request->education_to_add)) {
            foreach ($request->education_to_add as $edu) {
                $user->education()->create([
                    'degree' => $edu['degree'],
                    'institution' => $edu['institution'],
                    'field_of_study' => $edu['field_of_study'],
                    'start_date' => $edu['start_date'],
                    'end_date' => $edu['end_date'],
                    'gpa' => $edu['gpa'],
                    'gpa_scale' => $edu['scale'], // Map scale to gpa_scale
                    'description' => $edu['description'],
                    'location' => $edu['location'],
                ]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Education updated successfully']);
    }

    /**
     * Upload resume.
     */
    public function uploadResume(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'resume_file' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB max
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_primary' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $file = $request->file('resume_file');
        
        // Store file locally in storage/app/public/resumes
        $path = $file->store('resumes', 'public');
        
        // If this is primary, unset other primary resumes
        if ($request->boolean('is_primary')) {
            $user->resumes()->update(['is_primary' => false]);
        }
        
        // Create resume record
        $resume = $user->resumes()->create([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'file_type' => $file->getMimeType(),
            'title' => $request->title,
            'description' => $request->description,
            'is_primary' => $request->boolean('is_primary'),
        ]);

        return response()->json([
            'success' => true, 
            'message' => 'Resume uploaded successfully',
            'resume' => [
                'id' => $resume->id,
                'title' => $resume->title,
                'file_path' => $resume->file_path,
                'file_size' => $resume->file_size
            ]
        ]);
    }

    /**
     * Delete resume.
     */
    public function deleteResume($id)
    {
        $user = Auth::user();
        $resume = $user->resumes()->findOrFail($id);
        
        // Delete file from storage
        Storage::disk('public')->delete($resume->file_path);
        
        // Delete record
        $resume->delete();
        
        return response()->json(['success' => true, 'message' => 'Resume deleted successfully']);
    }

    /**
     * Set primary resume.
     */
    public function setPrimaryResume($id)
    {
        $user = Auth::user();
        $resume = $user->resumes()->findOrFail($id);
        
        // Unset other primary resumes
        $user->resumes()->update(['is_primary' => false]);
        
        // Set this as primary
        $resume->update(['is_primary' => true]);
        
        return response()->json(['success' => true, 'message' => 'Primary resume updated successfully']);
    }
}
