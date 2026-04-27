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
        Schema::create('school_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->year('founded_year');
            $table->string('motto')->nullable();
            $table->string('tagline')->nullable();
            $table->longText('description')->nullable();
            $table->string('logo_path');
            $table->string('hero_image_path');
            $table->text('mission_statement')->nullable();
            $table->text('vision_statement')->nullable();
            $table->text('contact_address');
            $table->text('contact_phone');
            $table->text('contact_email');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_profiles');
    }
};
