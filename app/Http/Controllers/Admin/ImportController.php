<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ImportController extends Controller
{
    public function index()
    {
        return view('admin.import.index');
    }

    public function importCompanies(Request $request)
    {
        $request->validate([
            'import_type' => 'required|in:file,manual',
            'companies_file' => 'required_if:import_type,file|file|mimes:json,csv,txt|max:2048',
            'companies_data' => 'required_if:import_type,manual|string',
        ]);

        try {
            if ($request->import_type === 'file') {
                $result = $this->importCompaniesFromFile($request->file('companies_file'));
            } else {
                $result = $this->importCompaniesFromData($request->companies_data);
            }

            return back()->with('success', $result['message']);
        } catch (\Exception $e) {
            Log::error('Company import failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Import failed: ' . $e->getMessage()]);
        }
    }

    public function importJobs(Request $request)
    {
        $request->validate([
            'import_type' => 'required|in:file,manual',
            'jobs_file' => 'required_if:import_type,file|file|mimes:json,csv,txt|max:2048',
            'jobs_data' => 'required_if:import_type,manual|string',
            'company_id' => 'required|exists:companies,id',
        ]);

        try {
            if ($request->import_type === 'file') {
                $result = $this->importJobsFromFile($request->file('jobs_file'), $request->company_id);
            } else {
                $result = $this->importJobsFromData($request->jobs_data, $request->company_id);
            }

            return back()->with('success', $result['message']);
        } catch (\Exception $e) {
            Log::error('Job import failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Import failed: ' . $e->getMessage()]);
        }
    }

    private function importCompaniesFromFile($file)
    {
        $content = file_get_contents($file->getPathname());
        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON format');
        }

        $companies = is_array($data) ? $data : ($data['tenants'] ?? []);
        $imported = 0;
        $errors = [];

        foreach ($companies as $companyData) {
            try {
                $validator = Validator::make($companyData, [
                    'name' => 'required|string|max:255',
                    'hr_portal_url' => 'nullable|url|max:255',
                    'api_key' => 'nullable|string|max:255',
                    'api_secret' => 'nullable|string|max:255',
                    'description' => 'nullable|string',
                    'location' => 'nullable|string|max:255',
                    'industry' => 'nullable|string|max:255',
                    'website' => 'nullable|url|max:255',
                ]);

                if ($validator->fails()) {
                    $errors[] = "Company '{$companyData['name']}': " . implode(', ', $validator->errors()->all());
                    continue;
                }

                Company::updateOrCreate(
                    ['name' => $companyData['name']],
                    array_merge($companyData, ['is_active' => true])
                );

                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Company '{$companyData['name']}': " . $e->getMessage();
            }
        }

        $message = "Successfully imported {$imported} companies.";
        if (!empty($errors)) {
            $message .= " Errors: " . implode('; ', $errors);
        }

        return ['message' => $message, 'imported' => $imported, 'errors' => $errors];
    }

    private function importCompaniesFromData($data)
    {
        $companies = json_decode($data, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON format');
        }

        $companies = is_array($companies) ? $companies : [$companies];
        $imported = 0;
        $errors = [];

        foreach ($companies as $companyData) {
            try {
                $validator = Validator::make($companyData, [
                    'name' => 'required|string|max:255',
                    'hr_portal_url' => 'nullable|url|max:255',
                    'api_key' => 'nullable|string|max:255',
                    'api_secret' => 'nullable|string|max:255',
                    'description' => 'nullable|string',
                    'location' => 'nullable|string|max:255',
                    'industry' => 'nullable|string|max:255',
                    'website' => 'nullable|url|max:255',
                ]);

                if ($validator->fails()) {
                    $errors[] = "Company '{$companyData['name']}': " . implode(', ', $validator->errors()->all());
                    continue;
                }

                Company::updateOrCreate(
                    ['name' => $companyData['name']],
                    array_merge($companyData, ['is_active' => true])
                );

                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Company '{$companyData['name']}': " . $e->getMessage();
            }
        }

        $message = "Successfully imported {$imported} companies.";
        if (!empty($errors)) {
            $message .= " Errors: " . implode('; ', $errors);
        }

        return ['message' => $message, 'imported' => $imported, 'errors' => $errors];
    }

    private function importJobsFromFile($file, $companyId)
    {
        $content = file_get_contents($file->getPathname());
        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON format');
        }

        $jobs = is_array($data) ? $data : ($data['jobs'] ?? []);
        $imported = 0;
        $errors = [];

        foreach ($jobs as $jobData) {
            try {
                $validator = Validator::make($jobData, [
                    'title' => 'required|string|max:255',
                    'description' => 'nullable|string',
                    'location' => 'nullable|string|max:255',
                    'employment_type' => 'nullable|string|max:50',
                    'salary_min' => 'nullable|numeric|min:0',
                    'salary_max' => 'nullable|numeric|min:0',
                    'currency' => 'nullable|string|max:3',
                    'posted_at' => 'nullable|date',
                    'apply_url' => 'nullable|url|max:255',
                    'is_remote' => 'nullable|boolean',
                    'external_id' => 'nullable|string|max:255',
                ]);

                if ($validator->fails()) {
                    $errors[] = "Job '{$jobData['title']}': " . implode(', ', $validator->errors()->all());
                    continue;
                }

                $jobData['company_id'] = $companyId;
                $jobData['is_active'] = true;

                Job::updateOrCreate(
                    ['external_id' => $jobData['external_id'] ?? null, 'company_id' => $companyId],
                    $jobData
                );

                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Job '{$jobData['title']}': " . $e->getMessage();
            }
        }

        $message = "Successfully imported {$imported} jobs.";
        if (!empty($errors)) {
            $message .= " Errors: " . implode('; ', $errors);
        }

        return ['message' => $message, 'imported' => $imported, 'errors' => $errors];
    }

    private function importJobsFromData($data, $companyId)
    {
        $jobs = json_decode($data, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON format');
        }

        $jobs = is_array($jobs) ? $jobs : [$jobs];
        $imported = 0;
        $errors = [];

        foreach ($jobs as $jobData) {
            try {
                $validator = Validator::make($jobData, [
                    'title' => 'required|string|max:255',
                    'description' => 'nullable|string',
                    'location' => 'nullable|string|max:255',
                    'employment_type' => 'nullable|string|max:50',
                    'salary_min' => 'nullable|numeric|min:0',
                    'salary_max' => 'nullable|numeric|min:0',
                    'currency' => 'nullable|string|max:3',
                    'posted_at' => 'nullable|date',
                    'apply_url' => 'nullable|url|max:255',
                    'is_remote' => 'nullable|boolean',
                    'external_id' => 'nullable|string|max:255',
                ]);

                if ($validator->fails()) {
                    $errors[] = "Job '{$jobData['title']}': " . implode(', ', $validator->errors()->all());
                    continue;
                }

                $jobData['company_id'] = $companyId;
                $jobData['is_active'] = true;

                Job::updateOrCreate(
                    ['external_id' => $jobData['external_id'] ?? null, 'company_id' => $companyId],
                    $jobData
                );

                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Job '{$jobData['title']}': " . $e->getMessage();
            }
        }

        $message = "Successfully imported {$imported} jobs.";
        if (!empty($errors)) {
            $message .= " Errors: " . implode('; ', $errors);
        }

        return ['message' => $message, 'imported' => $imported, 'errors' => $errors];
    }

    public function downloadSample($type)
    {
        $samples = [
            'companies' => [
                [
                    'name' => 'TechCorp Inc.',
                    'hr_portal_url' => 'https://hr.techcorp.com',
                    'api_key' => 'your_api_key_here',
                    'api_secret' => 'your_api_secret_here',
                    'description' => 'Leading technology company',
                    'location' => 'San Francisco, CA',
                    'industry' => 'Technology',
                    'website' => 'https://techcorp.com'
                ],
                [
                    'name' => 'InnovateSoft',
                    'hr_portal_url' => 'https://hr.innovatesoft.com',
                    'api_key' => 'your_api_key_here',
                    'api_secret' => 'your_api_secret_here',
                    'description' => 'Software innovation company',
                    'location' => 'New York, NY',
                    'industry' => 'Software',
                    'website' => 'https://innovatesoft.com'
                ]
            ],
            'jobs' => [
                [
                    'title' => 'Senior Software Engineer',
                    'description' => 'We are looking for a senior software engineer...',
                    'location' => 'San Francisco, CA',
                    'employment_type' => 'Full-time',
                    'salary_min' => 120000,
                    'salary_max' => 180000,
                    'currency' => 'USD',
                    'posted_at' => '2024-01-15',
                    'apply_url' => 'https://company.com/apply',
                    'is_remote' => true,
                    'external_id' => 'job_001'
                ],
                [
                    'title' => 'Product Manager',
                    'description' => 'Join our product team...',
                    'location' => 'New York, NY',
                    'employment_type' => 'Full-time',
                    'salary_min' => 100000,
                    'salary_max' => 150000,
                    'currency' => 'USD',
                    'posted_at' => '2024-01-16',
                    'apply_url' => 'https://company.com/apply',
                    'is_remote' => false,
                    'external_id' => 'job_002'
                ]
            ]
        ];

        if (!isset($samples[$type])) {
            abort(404);
        }

        $filename = "sample-{$type}.json";
        $content = json_encode($samples[$type], JSON_PRETTY_PRINT);

        return response($content)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }
}

