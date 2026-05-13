<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('public_id')->nullable()->unique();
            $table->enum('status', ['ongoing', 'upcoming', 'completed'])->default('upcoming');
            $table->string('title');
            $table->string('slug')->unique();
            $table->date('date_start');
            $table->date('date_end')->nullable();
            $table->string('location');
            $table->string('cover_image_path')->nullable();
            $table->text('short_description');
            $table->longText('full_description')->nullable();
            $table->json('attachments')->nullable();
            $table->string('contact')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->unsignedSmallInteger('featured_sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
