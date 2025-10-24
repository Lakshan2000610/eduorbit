<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subtopics', function (Blueprint $table) {
            $table->id(); // Creates an auto-incrementing unsignedBigInteger column
            $table->foreignId('topic_id')->constrained()->onDelete('cascade');
            $table->string('subtopic_code')->unique();
            $table->string('subtopic_name');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subtopics');
    }
};