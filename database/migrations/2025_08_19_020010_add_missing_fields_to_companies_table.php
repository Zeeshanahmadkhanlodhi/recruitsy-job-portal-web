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
        Schema::table('companies', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
            $table->string('location')->nullable()->after('description');
            $table->string('industry')->nullable()->after('location');
            $table->string('website')->nullable()->after('industry');
            $table->boolean('is_active')->default(true)->after('website');
            $table->string('logo')->nullable()->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['description', 'location', 'industry', 'website', 'is_active', 'logo']);
        });
    }
};
