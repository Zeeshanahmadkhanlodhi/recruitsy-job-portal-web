<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('central_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id');
            $table->string('external_job_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->string('employment_type')->nullable();
            $table->decimal('salary_min', 10, 2)->nullable();
            $table->decimal('salary_max', 10, 2)->nullable();
            $table->string('currency', 3)->nullable();
            $table->timestamp('posted_at')->nullable();
            $table->string('apply_url')->nullable();
            $table->boolean('is_remote')->default(false);
            $table->json('payload')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'external_job_id']);
            $table->index(['tenant_id']);
            $table->index(['posted_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('central_jobs');
    }
};
