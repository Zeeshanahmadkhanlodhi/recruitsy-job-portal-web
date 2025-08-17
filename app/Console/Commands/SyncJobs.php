<?php

namespace App\Console\Commands;

use App\Services\JobSyncService;
use Illuminate\Console\Command;

class SyncJobs extends Command
{
    protected $signature = 'jobs:sync {--company-id= : Sync jobs for a specific company only}';

    protected $description = 'Sync jobs from all tenant HR portals or a specific company';

    public function handle(JobSyncService $sync): int
    {
        $companyId = $this->option('company-id');

        if ($companyId) {
            $this->info("Syncing jobs for company ID: {$companyId}");
            $company = \App\Models\Company::find($companyId);
            
            if (!$company) {
                $this->error("Company with ID {$companyId} not found.");
                return self::FAILURE;
            }
            
            $sync->syncCompanyJobs($company);
            $this->info("Job sync completed for company: {$company->name}");
        } else {
            $this->info('Starting job sync for all tenants...');
            $sync->syncAllTenants();
            $this->info('Job sync completed for all tenants.');
        }

        return self::SUCCESS;
    }
}
