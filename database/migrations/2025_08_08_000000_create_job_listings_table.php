<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('external_id')->nullable();
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
            $table->timestamps();

            $table->unique(['company_id', 'external_id']);
            $table->index(['company_id']);
            $table->index(['posted_at']);
            $table->index(['is_remote']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_listings');
    }
};
