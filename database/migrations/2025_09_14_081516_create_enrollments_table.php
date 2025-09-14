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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('learning_content_id')->constrained('learning_contents')->cascadeOnDelete();

            $table->string('status')->default('active');
            $table->dateTime('enrolled_date')->useCurrent();
            $table->decimal('progress', 5, 2)->default(0);
            $table->dateTime('last_accessed')->nullable();

            $table->timestamps();
            $table->unique(['user_id','learning_content_id']);
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
