<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('student_life_uniform_bodies', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('name');
            $table->string('group_type');
            $table->string('colour', 20);
            $table->text('description')->nullable();
            $table->string('meeting_schedule')->nullable();
            $table->string('patron_name')->nullable();
            $table->string('patron_role')->nullable();
            $table->string('leader_name')->nullable();
            $table->string('leader_class', 100)->nullable();
            $table->string('logo_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('student_life_uniform_bodies'); }
};
