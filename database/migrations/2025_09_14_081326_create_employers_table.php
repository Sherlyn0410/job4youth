<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('employers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->nullable()->constrained('admins')->cascadeOnDelete();

            $table->string('employer_name');
            $table->string('email')->unique();
            $table->string('password');                 // if employers log in separately
            $table->string('phoneNo')->nullable();

            // Company profile
            $table->string('company_name')->nullable();
            $table->text('company_description')->nullable();
            $table->string('company_logo')->nullable();
            $table->string('company_size')->nullable();
            $table->string('company_type')->nullable();
            $table->string('company_sector')->nullable();

            // Address
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postcode')->nullable();
            $table->string('country')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employers');
    }
};
