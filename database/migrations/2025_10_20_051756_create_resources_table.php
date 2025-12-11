<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->morphs('resourceable'); // for polymorphic relation to Subject or Topic
            $table->string('type'); // text, video, image
            $table->text('content'); // text or URL
            $table->timestamps();
        });

        if (! Schema::hasColumn('resources', 'title')) {
            Schema::table('resources', function (Blueprint $table) {
                $table->string('title')->nullable()->after('content');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};