<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('image_url');
            $table->decimal('price', 8, 2);
            $table->string('level'); // e.g. Beginner, Intermediate
            $table->string('type'); // e.g. Course, Workshop, Webinar
            $table->string('learning_hours'); // e.g. "3 hours"
            $table->string('perks')->nullable(); // e.g. "Earn digital badge"
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
