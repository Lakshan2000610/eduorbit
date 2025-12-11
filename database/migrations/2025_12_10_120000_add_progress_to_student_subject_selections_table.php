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
        Schema::table('student_subject_selections', function (Blueprint $table) {
            if (! Schema::hasColumn('student_subject_selections', 'progress')) {
                $table->unsignedTinyInteger('progress')->default(0)->after('subject_id')->comment('Progress percentage 0-100');
            }

            if (! Schema::hasColumn('student_subject_selections', 'completed_topics')) {
                $table->unsignedInteger('completed_topics')->default(0)->after('progress')->comment('Number of completed topics for this selection');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_subject_selections', function (Blueprint $table) {
            if (Schema::hasColumn('student_subject_selections', 'completed_topics')) {
                $table->dropColumn('completed_topics');
            }
            if (Schema::hasColumn('student_subject_selections', 'progress')) {
                $table->dropColumn('progress');
            }
        });
    }
};