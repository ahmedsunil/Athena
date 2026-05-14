<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('gallery_albums', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('title');
            $table->string('category');
            $table->date('date');
            $table->unsignedSmallInteger('photo_count')->default(0);
            $table->string('cover_image_path')->nullable();
            $table->string('facebook_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('gallery_albums'); }
};
