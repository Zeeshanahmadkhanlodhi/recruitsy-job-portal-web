<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_professional_info', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('current_title')->nullable();
            $table->integer('years_of_experience')->nullable();
            $table->enum('preferred_job_type', ['full-time', 'part-time', 'contract', 'freelance', 'internship'])->nullable();
            $table->boolean('willing_to_relocate')->default(false);
            $table->string('expected_salary_min')->nullable();
            $table->string('expected_salary_max')->nullable();
            $table->string('work_authorization')->nullable();
            $table->text('summary')->nullable();
            $table->timestamps();
            
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_professional_info');
    }
};
