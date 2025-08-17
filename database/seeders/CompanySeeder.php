<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        // Create sample companies with API keys for testing
        Company::create([
            'name' => 'TechCorp Solutions',
            'hr_portal_url' => 'http://localhost:8000',
            'api_key' => 'techcorp_api_key_123',
            'api_secret' => 'techcorp_secret_key_456',
        ]);

        Company::create([
            'name' => 'Innovation Labs',
            'hr_portal_url' => 'http://localhost:8000',
            'api_key' => 'innovation_api_key_789',
            'api_secret' => 'innovation_secret_key_012',
        ]);

        Company::create([
            'name' => 'Global Systems Inc',
            'hr_portal_url' => 'http://localhost:8000',
            'api_key' => 'global_api_key_345',
            'api_secret' => 'global_secret_key_678',
        ]);

        $this->command->info('Sample companies created with API keys for testing.');
    }
}
