<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('companies')) {
            // Check if the unique index exists before trying to drop it
            if (Schema::hasIndex('companies', 'companies_hr_portal_url_unique')) {
                Schema::table('companies', function (Blueprint $table) {
                    $table->dropUnique('companies_hr_portal_url_unique');
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('companies')) {
            Schema::table('companies', function (Blueprint $table) {
                try {
                    $table->unique('hr_portal_url');
                } catch (Throwable $e) {
                    // ignore
                }
            });
        }
    }
};


