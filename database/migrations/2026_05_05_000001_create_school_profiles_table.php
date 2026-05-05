<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('school_name')->nullable();
            $table->string('motto')->nullable();
            $table->text('short_description')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('island')->nullable();
            $table->string('atoll')->nullable();
            $table->string('country')->nullable();
            $table->string('principal_name')->nullable();
            $table->string('principal_designation')->nullable();
            $table->text('principal_message')->nullable();
            $table->string('principal_photo_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_profiles');
    }
};
