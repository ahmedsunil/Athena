<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('digital_service_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('title');
            $table->string('category');
            $table->string('file_type', 10)->default('PDF');
            $table->string('file_size', 20)->nullable();
            $table->string('audience', 20)->default('All');
            $table->date('published_at');
            $table->string('file_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('digital_service_documents'); }
};
