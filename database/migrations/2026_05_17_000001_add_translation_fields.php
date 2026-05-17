<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('school_profiles', function (Blueprint $table) {
            $table->text('address')->nullable()->change();
        });

        Schema::table('footer_links', function (Blueprint $table) {
            $table->string('label_dv')->nullable()->after('label');
        });

        Schema::table('home_testimonials', function (Blueprint $table) {
            $table->string('name_dv')->nullable()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('school_profiles', function (Blueprint $table) {
            $table->string('address')->nullable()->change();
        });

        Schema::table('footer_links', function (Blueprint $table) {
            $table->dropColumn('label_dv');
        });

        Schema::table('home_testimonials', function (Blueprint $table) {
            $table->dropColumn('name_dv');
        });
    }
};
