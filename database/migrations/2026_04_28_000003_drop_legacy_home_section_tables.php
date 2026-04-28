<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('quick_links');
        Schema::dropIfExists('featured_events');
        Schema::dropIfExists('principals');
        Schema::dropIfExists('stats');
        Schema::dropIfExists('slides');
    }

    public function down(): void
    {
        Schema::create('slides', function (Blueprint $table) {
            $table->id();
            $table->string('image_path')->nullable();
            $table->string('title');
            $table->text('subtitle');
            $table->string('cta_label');
            $table->string('cta_href');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('stats', function (Blueprint $table) {
            $table->id();
            $table->string('value');
            $table->string('label');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('principals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title');
            $table->string('photo_path')->nullable();
            $table->text('message');
            $table->timestamps();
        });

        Schema::create('featured_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('date');
            $table->text('description');
            $table->string('href');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('quick_links', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('href');
            $table->string('icon');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->text('quote');
            $table->string('author');
            $table->string('role');
            $table->string('photo_path')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }
};
