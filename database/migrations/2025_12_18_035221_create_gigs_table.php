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
        Schema::create('gigs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->integer('grade');
            $table->string('subject'); // e.g., "Physics", "Mathematics"
            $table->json('topics')->nullable(); // e.g., ["Mechanics", "Waves"]
            $table->json('subtopics')->nullable();
            $table->json('languages'); // ["Sinhala", "English"]
            $table->integer('session_duration'); // in minutes
            $table->enum('status', ['draft', 'pending', 'active', 'rejected','disabled'])->default('active');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gigs');
    }
};
