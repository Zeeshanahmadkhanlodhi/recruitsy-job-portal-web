<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('companies') && !Schema::hasColumn('companies', 'last_synced_at')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->timestamp('last_synced_at')->nullable()->after('api_secret');
            });
        }

        if (Schema::hasTable('job_listings')) {
            Schema::table('job_listings', function (Blueprint $table) {
                if (!Schema::hasColumn('job_listings', 'experience_level')) {
                    $table->string('experience_level')->nullable()->after('employment_type');
                }
                if (!Schema::hasColumn('job_listings', 'department')) {
                    $table->string('department')->nullable()->after('experience_level');
                }
                if (!Schema::hasColumn('job_listings', 'raw')) {
                    $table->json('raw')->nullable()->after('is_remote');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('companies') && Schema::hasColumn('companies', 'last_synced_at')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->dropColumn('last_synced_at');
            });
        }

        if (Schema::hasTable('job_listings')) {
            Schema::table('job_listings', function (Blueprint $table) {
                if (Schema::hasColumn('job_listings', 'experience_level')) {
                    $table->dropColumn('experience_level');
                }
                if (Schema::hasColumn('job_listings', 'department')) {
                    $table->dropColumn('department');
                }
                if (Schema::hasColumn('job_listings', 'raw')) {
                    $table->dropColumn('raw');
                }
            });
        }
    }
};


