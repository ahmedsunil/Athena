<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('student_life_houses', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('name');
            $table->string('colour', 20);
            $table->string('motto')->nullable();
            $table->text('description')->nullable();
            $table->string('house_master_name')->nullable();
            $table->string('house_master_role')->nullable();
            $table->string('captain_name')->nullable();
            $table->string('captain_class', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('student_life_houses'); }
};
