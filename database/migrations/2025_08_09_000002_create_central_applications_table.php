<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('central_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('central_job_id')->constrained('central_jobs')->cascadeOnDelete();
            $table->string('tenant_id');
            $table->string('external_job_id');
            $table->unsignedBigInteger('candidate_id')->nullable();
            $table->string('candidate_email');
            $table->string('candidate_name')->nullable();
            $table->string('candidate_phone')->nullable();
            $table->string('resume_url')->nullable();
            $table->text('cover_letter')->nullable();
            $table->json('payload')->nullable();
            $table->string('status', 32)->default('forwarded');
            $table->json('tenant_response')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'external_job_id']);
            $table->index(['candidate_email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('central_applications');
    }
};


