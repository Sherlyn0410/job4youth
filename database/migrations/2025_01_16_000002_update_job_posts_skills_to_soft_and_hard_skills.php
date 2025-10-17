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
        Schema::table('job_posts', function (Blueprint $table) {
            // Drop the old skills column
            $table->dropColumn('skills');
            
            // Add new skills columns
            $table->text('soft_skills')->nullable()->after('requirements');
            $table->text('hard_skills')->nullable()->after('soft_skills');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_posts', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn(['soft_skills', 'hard_skills']);
            
            // Restore old skills column
            $table->text('skills')->nullable()->after('requirements');
        });
    }
};