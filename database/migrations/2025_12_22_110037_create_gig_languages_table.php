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
        Schema::create('gig_languages', function (Blueprint $table) {
            $table->id();
        $table->foreignId('gig_id')->constrained()->onDelete('cascade');
        $table->string('language'); // Sinhala, English, Tamil
        $table->timestamps();

        $table->unique(['gig_id', 'language']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gig_languages');
    }
};
