<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platform_fees', function (Blueprint $table) {
            $table->id();
            $table->decimal('fee_percentage', 5, 2)->default(10.00); // e.g., 10.00%
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default value
        \DB::table('platform_fees')->insert([
            'fee_percentage' => 10.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_fees');
    }
};