<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('grade');
            $table->string('language');
            $table->string('subject_name');
            $table->string('subject_code')->unique();
            $table->string('status')->default('active');
            $table->text('description')->nullable();
            $table->timestamps();
        });
        Schema::table('subjects', function (Blueprint $table) {
            $table->foreignId('parent_subject_id')->nullable()->constrained('subjects')->nullOnDelete()->after('subject_code');
            $table->boolean('is_subsubject')->default(false)->after('parent_subject_id');
        });
    }

    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropForeign(['parent_subject_id']);
            $table->dropColumn(['parent_subject_id', 'is_subsubject']);
        });
        Schema::dropIfExists('subjects');
    }
};