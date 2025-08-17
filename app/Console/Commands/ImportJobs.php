<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Job;
use App\Services\JobSyncService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class ImportJobs extends Command
{
    protected $signature = 'jobs:import 
                            {--from-file= : Import jobs from a JSON file}
                            {--company-id= : Company ID for the jobs}
                            {--company-name= : Company name for the jobs}
                            {--title= : Job title for manual import}
                            {--description= : Job description for manual import}
                            {--location= : Job location for manual import}
                            {--employment-type= : Employment type for manual import}
                            {--salary-min= : Minimum salary for manual import}
                            {--salary-max= : Maximum salary for manual import}
                            {--currency= : Salary currency for manual import}
                            {--posted-at= : Job posted date for manual import}
                            {--apply-url= : Apply URL for manual import}
                            {--is-remote= : Whether job is remote for manual import}
                            {--external-id= : External ID for manual import}
                            {--list : List all existing jobs}
                            {--company= : Filter jobs by company ID or name}
                            {--dry-run : Show what would be imported without actually importing}';

    protected $description = 'Import jobs from various sources: JSON file, manual creation, or sync from HR portals';

    public function handle(JobSyncService $sync): int
    {
        // List existing jobs if requested
        if ($this->option('list')) {
            return $this->listJobs();
        }

        // Manual job creation
        if ($this->option('title') && $this->option('company-id')) {
            return $this->importManualJob();
        }

        // Import from JSON file
        if ($filePath = $this->option('from-file')) {
            return $this->importFromFile($filePath);
        }

        // Default: Show help
        $this->error('Please specify an import method. Use --help for options.');
        return self::FAILURE;
    }

    protected function listJobs(): int
    {
        $query = Job::with('company')->orderBy('created_at', 'desc');
        
        if ($companyFilter = $this->option('company')) {
            if (is_numeric($companyFilter)) {
                $query->where('company_id', $companyFilter);
            } else {
                $query->whereHas('company', function ($q) use ($companyFilter) {
                    $q->where('name', 'like', "%{$companyFilter}%");
                });
            }
        }

        $jobs = $query->limit(50)->get(['id', 'company_id', 'external_id', 'title', 'location', 'employment_type', 'is_remote', 'created_at']);
        
        if ($jobs->isEmpty()) {
            $this->info('No jobs found.');
            return self::SUCCESS;
        }

        $this->info('Recent Jobs:');
        $this->newLine();
        
        $headers = ['ID', 'Company', 'External ID', 'Title', 'Location', 'Type', 'Remote', 'Created'];
        $rows = $jobs->map(function ($job) {
            return [
                $job->id,
                $job->company->name ?? 'N/A',
                $job->external_id ?? 'N/A',
                substr($job->title, 0, 30) . (strlen($job->title) > 30 ? '...' : ''),
                $job->location ?? 'N/A',
                $job->employment_type ?? 'N/A',
                $job->is_remote ? 'Yes' : 'No',
                $job->created_at->format('Y-m-d H:i:s'),
            ];
        })->toArray();

        $this->table($headers, $rows);
        
        if ($query->count() > 50) {
            $this->info("Showing 50 of {$query->count()} total jobs. Use --company to filter.");
        }
        
        return self::SUCCESS;
    }

    protected function importManualJob(): int
    {
        $companyId = $this->option('company-id');
        $company = Company::find($companyId);
        
        if (!$company) {
            $this->error("Company with ID {$companyId} not found.");
            return self::FAILURE;
        }

        $jobData = [
            'company_id' => $companyId,
            'external_id' => $this->option('external-id'),
            'title' => $this->option('title'),
            'description' => $this->option('description'),
            'location' => $this->option('location'),
            'employment_type' => $this->option('employment-type'),
            'salary_min' => $this->option('salary-min'),
            'salary_max' => $this->option('salary-max'),
            'currency' => $this->option('currency'),
            'posted_at' => $this->option('posted-at') ?: now(),
            'apply_url' => $this->option('apply-url'),
            'is_remote' => $this->option('is-remote') === 'true' || $this->option('is-remote') === '1',
        ];

        if ($this->option('dry-run')) {
            $this->info('DRY RUN - Would import job:');
            $this->table(['Field', 'Value'], [
                ['Company', $company->name],
                ['Title', $jobData['title']],
                ['Location', $jobData['location'] ?? 'N/A'],
                ['Employment Type', $jobData['employment_type'] ?? 'N/A'],
                ['Remote', $jobData['is_remote'] ? 'Yes' : 'No'],
                ['External ID', $jobData['external_id'] ?? 'N/A'],
            ]);
            return self::SUCCESS;
        }

        $this->info("Importing job for company: {$company->name}");
        
        try {
            Job::create($jobData);
            $this->info("✅ Job '{$jobData['title']}' imported successfully!");
            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error("❌ Failed to import job: " . $e->getMessage());
            return self::FAILURE;
        }
    }

    protected function importFromFile(string $filePath): int
    {
        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return self::FAILURE;
        }

        try {
            $content = file_get_contents($filePath);
            $data = json_decode($content, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->error("Invalid JSON file: " . json_last_error_msg());
                return self::FAILURE;
            }

            if (!is_array($data)) {
                $this->error("JSON file must contain an array of jobs or a data structure with jobs");
                return self::FAILURE;
            }

            // Extract jobs from various possible structures
            $jobs = [];
            if (isset($data['jobs']) && is_array($data['jobs'])) {
                $jobs = $data['jobs'];
            } elseif (isset($data['data']['jobs']) && is_array($data['data']['jobs'])) {
                $jobs = $data['data']['jobs'];
            } elseif (array_is_list($data)) {
                $jobs = $data;
            } else {
                $this->error("Could not find jobs array in JSON file");
                return self::FAILURE;
            }

            if (empty($jobs)) {
                $this->warn("No jobs found in file");
                return self::SUCCESS;
            }

            $companyId = $this->option('company-id');
            $companyName = $this->option('company-name');
            
            if (!$companyId && $companyName) {
                $company = Company::where('name', 'like', "%{$companyName}%")->first();
                if ($company) {
                    $companyId = $company->id;
                }
            }

            if (!$companyId) {
                $this->error("Please specify --company-id or --company-name for job import");
                return self::FAILURE;
            }

            if ($this->option('dry-run')) {
                $this->info("DRY RUN - Would import " . count($jobs) . " jobs for company ID: {$companyId}");
                return self::SUCCESS;
            }

            $this->info("Importing " . count($jobs) . " jobs from file...");
            
            $imported = 0;
            $errors = 0;
            
            foreach ($jobs as $index => $job) {
                if (!is_array($job)) {
                    $this->warn("Skipping invalid job data at index {$index}");
                    $errors++;
                    continue;
                }

                try {
                    $job['company_id'] = $companyId;
                    $job['created_at'] = $job['created_at'] ?? now();
                    $job['updated_at'] = now();
                    
                    Job::create($job);
                    $imported++;
                } catch (\Exception $e) {
                    $this->warn("Failed to import job at index {$index}: " . $e->getMessage());
                    $errors++;
                }
            }

            $this->info("✅ Successfully imported {$imported} jobs!");
            if ($errors > 0) {
                $this->warn("⚠️  {$errors} jobs failed to import");
            }

            return $imported > 0 ? self::SUCCESS : self::FAILURE;
            
        } catch (\Exception $e) {
            $this->error("Error reading file: " . $e->getMessage());
            return self::FAILURE;
        }
    }
}
