<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_levels', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('abbreviation', 10);
            $table->string('label');
            $table->string('age_range', 100);
            $table->string('year_groups');
            $table->string('lead_teacher');
            $table->string('lead_teacher_photo_path')->nullable();
            $table->json('subjects')->nullable();
            $table->json('targets')->nullable();
            $table->json('streams')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_levels');
    }
};
