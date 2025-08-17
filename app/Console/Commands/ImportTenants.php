<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Services\JobSyncService;
use App\Services\TenantImportService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class ImportTenants extends Command
{
    protected $signature = 'tenants:import 
                            {--and-sync : Sync jobs after importing tenants}
                            {--from-file= : Import tenants from a JSON file}
                            {--name= : Company name for manual import}
                            {--url= : HR portal URL for manual import}
                            {--api-key= : API key for manual import}
                            {--api-secret= : API secret for manual import}
                            {--list : List all existing tenants}
                            {--dry-run : Show what would be imported without actually importing}';

    protected $description = 'Import tenants from the HR portal API, JSON file, or manually create them';

    public function handle(TenantImportService $importer, JobSyncService $sync): int
    {
        // List existing tenants if requested
        if ($this->option('list')) {
            return $this->listTenants();
        }

        // Manual tenant creation
        if ($this->option('name') && $this->option('url') && $this->option('api-key') && $this->option('api-secret')) {
            return $this->importManualTenant($importer);
        }

        // Import from JSON file
        if ($filePath = $this->option('from-file')) {
            return $this->importFromFile($importer, $filePath);
        }

        // Default: Import from HR portal API
        return $this->importFromHrPortal($importer, $sync);
    }

    protected function listTenants(): int
    {
        $tenants = Company::orderBy('name')->get(['id', 'name', 'hr_portal_url', 'api_key', 'created_at']);
        
        if ($tenants->isEmpty()) {
            $this->info('No tenants found.');
            return self::SUCCESS;
        }

        $this->info('Existing Tenants:');
        $this->newLine();
        
        $headers = ['ID', 'Name', 'HR Portal URL', 'API Key', 'Created'];
        $rows = $tenants->map(function ($tenant) {
            return [
                $tenant->id,
                $tenant->name,
                $tenant->hr_portal_url,
                substr($tenant->api_key, 0, 8) . '...',
                $tenant->created_at->format('Y-m-d H:i:s'),
            ];
        })->toArray();

        $this->table($headers, $rows);
        return self::SUCCESS;
    }

    protected function importManualTenant(TenantImportService $importer): int
    {
        $tenantData = [
            'name' => $this->option('name'),
            'hr_portal_url' => $this->option('url'),
            'api_key' => $this->option('api-key'),
            'api_secret' => $this->option('api-secret'),
        ];

        if ($this->option('dry-run')) {
            $this->info('DRY RUN - Would import tenant:');
            $this->table(['Field', 'Value'], [
                ['Name', $tenantData['name']],
                ['HR Portal URL', $tenantData['hr_portal_url']],
                ['API Key', $tenantData['api_key']],
                ['API Secret', str_repeat('*', strlen($tenantData['api_secret']))],
            ]);
            return self::SUCCESS;
        }

        $this->info('Importing manual tenant...');
        
        if ($importer->importSingleTenantManually($tenantData)) {
            $this->info("✅ Tenant '{$tenantData['name']}' imported successfully!");
            return self::SUCCESS;
        } else {
            $this->error("❌ Failed to import tenant '{$tenantData['name']}'");
            return self::FAILURE;
        }
    }

    protected function importFromFile(TenantImportService $importer, string $filePath): int
    {
        if ($this->option('dry-run')) {
            $this->info("DRY RUN - Would import tenants from file: {$filePath}");
            return self::SUCCESS;
        }

        $this->info("Importing tenants from file: {$filePath}");
        
        $count = $importer->importFromJsonFile($filePath);
        
        if ($count > 0) {
            $this->info("✅ Successfully imported {$count} tenants from file!");
            return self::SUCCESS;
        } else {
            $this->error("❌ No tenants were imported from file");
            return self::FAILURE;
        }
    }

    protected function importFromHrPortal(TenantImportService $importer, JobSyncService $sync): int
    {
        if ($this->option('dry-run')) {
            $this->info('DRY RUN - Would import tenants from HR portal API');
            return self::SUCCESS;
        }

        $this->info('Importing tenants from HR portal API...');
        
        $count = $importer->importFromHrPortal();
        
        if ($count > 0) {
            $this->info("✅ Successfully imported/updated {$count} tenants!");
        } else {
            $this->warn("⚠️  No tenants were imported from HR portal API");
        }

        if ($this->option('and-sync')) {
            $this->newLine();
            $this->info('Starting job sync for all tenants...');
            $sync->syncAllTenants();
            $this->info('✅ Job sync completed!');
        }

        return $count > 0 ? self::SUCCESS : self::FAILURE;
    }
}


