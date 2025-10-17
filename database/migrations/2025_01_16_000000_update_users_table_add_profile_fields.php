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
        Schema::table('users', function (Blueprint $table) {
            // Add admin reference
            $table->foreignId('admin_id')->nullable()->constrained('admins')->nullOnDelete()->after('id');
            
            // Update name column length
            $table->string('name', 120)->change();
            
            // Add contact and location fields
            $table->string('phone_no', 30)->nullable()->after('password');
            $table->string('state', 80)->nullable()->after('phone_no');
            $table->string('city', 80)->nullable()->after('state');
            
            // Add profile fields
            $table->string('resume')->nullable()->after('city');
            $table->text('self_intro')->nullable()->after('resume');
            $table->text('skills')->nullable()->after('self_intro');
            $table->text('education')->nullable()->after('skills');
            $table->text('experience')->nullable()->after('education');
            $table->string('license_cert_type', 120)->nullable()->after('experience');
            $table->string('language', 120)->nullable()->after('license_cert_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the new columns
            $table->dropForeign(['admin_id']);
            $table->dropColumn([
                'admin_id',
                'phone_no',
                'state', 
                'city',
                'resume',
                'self_intro',
                'skills',
                'education',
                'experience',
                'license_cert_type',
                'language'
            ]);
            
            // Revert name column to original length
            $table->string('name')->change();
        });
    }
};