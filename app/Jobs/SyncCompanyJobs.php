<?php

namespace App\Jobs;

use App\Models\Company;
use App\Services\JobSyncService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncCompanyJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $companyId)
    {
    }

    public function handle(JobSyncService $service): void
    {
        $company = Company::find($this->companyId);
        if ($company) {
            $service->syncCompanyJobs($company);
        }
    }
}


