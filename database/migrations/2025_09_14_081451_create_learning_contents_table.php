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
        Schema::create('learning_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->nullable()->constrained('admins')->cascadeOnDelete();

            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->string('format')->nullable();
            $table->string('duration')->nullable();
            $table->string('level')->nullable();
            $table->string('language')->nullable();
            $table->text('skills_gained')->nullable();
            $table->text('learning_outcome')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->boolean('badge_available')->default(false);
            $table->dateTime('date_available')->nullable();
            $table->dateTime('scheduled_date')->nullable();

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_contents');
    }
};
