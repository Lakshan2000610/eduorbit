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
        Schema::create('gig_topics', function (Blueprint $table) {
            $table->id();
        $table->foreignId('gig_subject_id')->constrained()->onDelete('cascade');
        $table->foreignId('topic_id')->constrained()->onDelete('cascade');
        $table->integer('duration')->default(1); // periods
        $table->timestamps();

        $table->unique(['gig_subject_id', 'topic_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gig_topics');
    }
};
