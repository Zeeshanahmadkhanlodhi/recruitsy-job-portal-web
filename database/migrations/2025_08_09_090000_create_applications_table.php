<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained('job_listings')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('candidate_name');
            $table->string('candidate_email');
            $table->string('candidate_phone')->nullable();
            $table->string('resume_url')->nullable();
            $table->text('cover_letter')->nullable();
            $table->enum('status', ['pending', 'forwarded', 'success', 'failed'])->default('pending');
            $table->json('hr_response')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index(['job_id', 'candidate_email']);
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
