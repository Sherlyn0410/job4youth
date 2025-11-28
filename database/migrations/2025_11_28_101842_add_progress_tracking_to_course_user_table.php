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
        Schema::table('course_user', function (Blueprint $table) {
            $table->integer('progress_percentage')->default(0)->after('purchased_at');
            $table->integer('completed_hours')->default(0)->after('progress_percentage');
            $table->boolean('is_completed')->default(false)->after('completed_hours');
            $table->timestamp('completed_at')->nullable()->after('is_completed');
            $table->timestamp('last_accessed_at')->nullable()->after('completed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_user', function (Blueprint $table) {
            $table->dropColumn(['progress_percentage', 'completed_hours', 'is_completed', 'completed_at', 'last_accessed_at']);
        });
    }
};
