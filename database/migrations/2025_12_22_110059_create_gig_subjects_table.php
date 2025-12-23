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
        Schema::create('gig_subjects', function (Blueprint $table) {
            $table->id();
        $table->foreignId('gig_id')->constrained()->onDelete('cascade');
        $table->foreignId('subject_id')->constrained()->onDelete('cascade');
        $table->timestamps();

        $table->unique(['gig_id', 'subject_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gig_subjects');
    }
};
