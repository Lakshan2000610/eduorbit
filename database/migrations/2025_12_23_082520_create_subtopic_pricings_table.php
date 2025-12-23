<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subtopic_pricings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subtopic_id')->unique()->constrained()->onDelete('cascade');
            $table->decimal('min_price', 10, 2)->default(0);
            $table->decimal('max_price', 10, 2)->default(0);
            $table->string('currency')->default('LKR');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subtopic_pricings');
    }
};