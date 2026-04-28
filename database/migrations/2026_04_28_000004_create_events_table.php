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
            $table->string('public_id')->unique();
            $table->string('status');
            $table->string('title');
            $table->string('slug')->unique();
            $table->date('date_start');
            $table->date('date_end');
            $table->string('location');
            $table->string('cover_image_url')->nullable();
            $table->text('short_description');
            $table->longText('full_description');
            $table->json('attachments')->nullable();
            $table->json('contact')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('featured_sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
