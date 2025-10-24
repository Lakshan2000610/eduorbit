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
        Schema::create('contents', function (Blueprint $table) {
           $table->id();
            $table->foreignId('subtopic_id')->constrained('subtopics')->onDelete('cascade'); // Explicitly reference 'subtopics'
            $table->string('title');
            $table->enum('type', ['text', 'video', 'image']);
            $table->text('content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
