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
        Schema::create('job_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employer_id')->constrained('employers')->cascadeOnDelete();
            $table->foreignId('admin_id')->nullable()->constrained('admins')->cascadeOnDelete();

            $table->string('title');
            $table->string('location')->nullable();
            $table->string('job_type')->nullable();
            $table->string('specialization')->nullable();
            $table->string('education_level')->nullable();

            $table->decimal('salary_min', 10, 2)->nullable();
            $table->decimal('salary_max', 10, 2)->nullable();
            $table->boolean('salary_display')->default(false);

            $table->text('job_overview')->nullable();
            $table->text('responsibilities')->nullable();
            $table->text('requirements')->nullable();
            $table->text('skills')->nullable();

            $table->string('status')->default('open');
            $table->timestamp('posted_date')->nullable();
            $table->unsignedInteger('job_view')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_posts');
    }
};
