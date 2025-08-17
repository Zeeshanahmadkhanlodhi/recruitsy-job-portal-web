<?php

namespace App\Services;

use App\Models\Company;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TenantImportService
{
    public function importFromHrPortal(): int
    {
        $baseUrl = rtrim(Config::get('services.hr_portal.base_url'), '/');
        $apiKey = Config::get('services.hr_portal.api_key');
        $apiSecret = Config::get('services.hr_portal.api_secret');

        if (!$baseUrl || !$apiKey || !$apiSecret) {
            Log::error('TenantImport: Missing HR portal config', [
                'base_url' => $baseUrl,
                'has_api_key' => !empty($apiKey),
                'has_api_secret' => !empty($apiSecret),
            ]);
            return 0;
        }

        $timestamp = (string) now()->getTimestamp();
        $signature = hash_hmac('sha256', $apiKey.'|'.$timestamp, $apiSecret);

        try {
            $response = Http::timeout(15)
                ->retry(3, 500)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'X-API-KEY' => $apiKey,
                    'X-API-TIMESTAMP' => $timestamp,
                    'X-API-SIGNATURE' => $signature,
                ])
                ->get($baseUrl.'/api/portal/tenants');

            if (!$response->successful()) {
                Log::warning('TenantImport: Non-success response', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'url' => $baseUrl.'/api/portal/tenants',
                ]);
                return 0;
            }

            $payload = $response->json();
            if (!is_array($payload)) {
                Log::warning('TenantImport: Unexpected payload type', ['type' => gettype($payload)]);
                return 0;
            }

            // Support either a raw array of tenants or wrapped under a 'data' key
            $tenants = array_is_list($payload) ? $payload : ($payload['data'] ?? []);
            if (!is_array($tenants)) {
                Log::warning('TenantImport: Unexpected tenants container');
                return 0;
            }

            $imported = 0;
            $errors = 0;
            
            foreach ($tenants as $index => $tenant) {
                if (!is_array($tenant)) {
                    Log::warning('TenantImport: Invalid tenant data at index', ['index' => $index]);
                    continue;
                }
                
                try {
                    $result = $this->importSingleTenant($tenant);
                    if ($result) {
                        $imported++;
                    } else {
                        $errors++;
                    }
                } catch (\Exception $e) {
                    Log::error('TenantImport: Error importing tenant', [
                        'tenant' => $tenant,
                        'error' => $e->getMessage(),
                    ]);
                    $errors++;
                }
            }

            Log::info('TenantImport: Completed', [
                'imported' => $imported,
                'errors' => $errors,
                'total_processed' => count($tenants)
            ]);
            
            return $imported;
            
        } catch (\Exception $e) {
            Log::error('TenantImport: Exception during import', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return 0;
        }
    }

    /**
     * Import a single tenant with validation
     */
    protected function importSingleTenant(array $tenant): bool
    {
        $name = $tenant['name'] ?? ($tenant['tenant_name'] ?? null);
        $url = $tenant['hr_portal_url'] ?? $tenant['base_url'] ?? Config::get('services.hr_portal.base_url');
        $key = $tenant['api_key'] ?? ($tenant['apiKey'] ?? null);
        $secret = $tenant['api_secret'] ?? ($tenant['decrypted_api_secret'] ?? ($tenant['apiSecret'] ?? ($tenant['secret'] ?? null)));
        
        if (!$name || !$url || !$key || !$secret) {
            Log::warning('TenantImport: Missing required fields', [
                'tenant' => $tenant,
                'has_name' => !empty($name),
                'has_url' => !empty($url),
                'has_key' => !empty($key),
                'has_secret' => !empty($secret),
            ]);
            return false;
        }

        // Normalize URL to ensure correct port/base for local dev
        $normalizedUrl = rtrim($this->normalizePortalUrl($url), '/');

        try {
            Company::updateOrCreate(
                ['api_key' => $key],
                [
                    'name' => $name,
                    'hr_portal_url' => $normalizedUrl,
                    'api_secret' => $secret,
                ]
            );
            
            Log::info('TenantImport: Tenant imported/updated', [
                'name' => $name,
                'api_key' => $key,
                'url' => $normalizedUrl,
            ]);
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('TenantImport: Database error for tenant', [
                'tenant' => $tenant,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Import tenants from a JSON file (for manual imports)
     */
    public function importFromJsonFile(string $filePath): int
    {
        if (!file_exists($filePath)) {
            Log::error('TenantImport: File not found', ['file' => $filePath]);
            return 0;
        }

        try {
            $content = file_get_contents($filePath);
            $tenants = json_decode($content, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('TenantImport: Invalid JSON file', ['error' => json_last_error_msg()]);
                return 0;
            }

            if (!is_array($tenants)) {
                Log::error('TenantImport: JSON file must contain an array of tenants');
                return 0;
            }

            $imported = 0;
            foreach ($tenants as $tenant) {
                if ($this->importSingleTenant($tenant)) {
                    $imported++;
                }
            }

            Log::info('TenantImport: JSON file import completed', ['imported' => $imported]);
            return $imported;
            
        } catch (\Exception $e) {
            Log::error('TenantImport: Error reading JSON file', [
                'file' => $filePath,
                'error' => $e->getMessage(),
            ]);
            return 0;
        }
    }

    /**
     * Import a single tenant manually
     */
    public function importSingleTenantManually(array $tenantData): bool
    {
        try {
            $validator = Validator::make($tenantData, [
                'name' => 'required|string|max:255',
                'hr_portal_url' => 'required|url',
                'api_key' => 'required|string|max:255',
                'api_secret' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                Log::warning('TenantImport: Validation failed for manual import', [
                    'errors' => $validator->errors()->toArray(),
                ]);
                return false;
            }

            return $this->importSingleTenant($tenantData);
            
        } catch (\Exception $e) {
            Log::error('TenantImport: Error in manual import', [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    private function normalizePortalUrl(string $url): string
    {
        $base = rtrim(Config::get('services.hr_portal.base_url'), '/');
        $parts = parse_url($url);
        $hasPort = isset($parts['port']);
        if (!$hasPort) {
            return $base; // ensure we include configured port when missing
        }
        return $url;
    }
}


