<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_life_people', function (Blueprint $table) {
            $table->id();
            $table->morphs('personable');
            $table->unsignedSmallInteger('year');
            $table->string('name');
            $table->string('designation');
            $table->string('grade', 100)->nullable();
            $table->string('avatar_path')->nullable();
            $table->boolean('is_teacher_in_charge')->default(false);
            $table->boolean('is_active')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['personable_type', 'personable_id', 'year', 'sort_order'], 'student_life_people_owner_year_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_life_people');
    }
};
