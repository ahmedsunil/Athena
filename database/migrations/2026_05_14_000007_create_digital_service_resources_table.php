<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('digital_service_resources', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('audience', 20)->default('All');
            $table->string('icon', 50)->default('BookOpen');
            $table->string('icon_color', 20)->default('sky');
            $table->string('url');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('digital_service_resources'); }
};
