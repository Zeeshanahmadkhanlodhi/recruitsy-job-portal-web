<?php

namespace App\Console\Commands;

use App\Services\JobSyncService;
use App\Services\TenantImportService;
use Illuminate\Console\Command;

class ImportAll extends Command
{
    protected $signature = 'import:all 
                            {--tenants-only : Import only tenants}
                            {--jobs-only : Import only jobs}
                            {--from-file= : Import from a JSON file containing both tenants and jobs}
                            {--sync-after : Sync jobs from HR portals after import}
                            {--dry-run : Show what would be imported without actually importing}';

    protected $description = 'Import tenants and jobs from various sources';

    public function handle(TenantImportService $tenantImporter, JobSyncService $jobSync): int
    {
        $this->info('🚀 Starting comprehensive import process...');
        $this->newLine();

        if ($this->option('from-file')) {
            return $this->importFromFile($this->option('from-file'));
        }

        $importTenants = !$this->option('jobs-only');
        $importJobs = !$this->option('tenants-only');

        $success = true;

        // Import tenants
        if ($importTenants) {
            $this->info('📋 Importing tenants from HR portal...');
            $tenantCount = $tenantImporter->importFromHrPortal();
            
            if ($tenantCount > 0) {
                $this->info("✅ Successfully imported {$tenantCount} tenants!");
            } else {
                $this->warn("⚠️  No tenants were imported");
                $success = false;
            }
            $this->newLine();
        }

        // Import jobs (sync from HR portals)
        if ($importJobs) {
            $this->info('💼 Syncing jobs from HR portals...');
            try {
                $jobSync->syncAllTenants();
                $this->info('✅ Job sync completed!');
            } catch (\Exception $e) {
                $this->error("❌ Job sync failed: " . $e->getMessage());
                $success = false;
            }
            $this->newLine();
        }

        // Additional sync if requested
        if ($this->option('sync-after') && $importTenants) {
            $this->info('🔄 Performing additional job sync...');
            try {
                $jobSync->syncAllTenants();
                $this->info('✅ Additional job sync completed!');
            } catch (\Exception $e) {
                $this->error("❌ Additional job sync failed: " . $e->getMessage());
                $success = false;
            }
        }

        $this->newLine();
        if ($success) {
            $this->info('🎉 Import process completed successfully!');
        } else {
            $this->warn('⚠️  Import process completed with some issues.');
        }

        return $success ? self::SUCCESS : self::FAILURE;
    }

    protected function importFromFile(string $filePath): int
    {
        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return self::FAILURE;
        }

        if ($this->option('dry-run')) {
            $this->info("DRY RUN - Would import from file: {$filePath}");
            return self::SUCCESS;
        }

        $this->info("📁 Importing from file: {$filePath}");
        
        try {
            $content = file_get_contents($filePath);
            $data = json_decode($content, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->error("Invalid JSON file: " . json_last_error_msg());
                return self::FAILURE;
            }

            $success = true;
            $tenantCount = 0;
            $jobCount = 0;

            // Import tenants if present
            if (isset($data['tenants']) && is_array($data['tenants'])) {
                $this->info('📋 Importing tenants from file...');
                $tenantCount = $this->importTenantsFromData($data['tenants']);
                $this->info("✅ Imported {$tenantCount} tenants from file");
            }

            // Import jobs if present
            if (isset($data['jobs']) && is_array($data['jobs'])) {
                $this->info('💼 Importing jobs from file...');
                $jobCount = $this->importJobsFromData($data['jobs']);
                $this->info("✅ Imported {$jobCount} jobs from file");
            }

            if ($tenantCount === 0 && $jobCount === 0) {
                $this->warn("No tenants or jobs found in file");
                return self::SUCCESS;
            }

            $this->newLine();
            $this->info("🎉 File import completed: {$tenantCount} tenants, {$jobCount} jobs");
            
            return self::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error("Error reading file: " . $e->getMessage());
            return self::FAILURE;
        }
    }

    protected function importTenantsFromData(array $tenants): int
    {
        $imported = 0;
        foreach ($tenants as $tenant) {
            if (is_array($tenant)) {
                try {
                    \App\Models\Company::updateOrCreate(
                        ['api_key' => $tenant['api_key'] ?? null],
                        [
                            'name' => $tenant['name'] ?? 'Unknown Company',
                            'hr_portal_url' => $tenant['hr_portal_url'] ?? $tenant['base_url'] ?? '',
                            'api_secret' => $tenant['api_secret'] ?? '',
                        ]
                    );
                    $imported++;
                } catch (\Exception $e) {
                    $this->warn("Failed to import tenant: " . $e->getMessage());
                }
            }
        }
        return $imported;
    }

    protected function importJobsFromData(array $jobs): int
    {
        $imported = 0;
        foreach ($jobs as $job) {
            if (is_array($job) && isset($job['company_id'])) {
                try {
                    \App\Models\Job::create($job);
                    $imported++;
                } catch (\Exception $e) {
                    $this->warn("Failed to import job: " . $e->getMessage());
                }
            }
        }
        return $imported;
    }
}
