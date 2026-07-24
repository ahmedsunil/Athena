<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('footer_links',      fn (Blueprint $t) => $t->dropColumn('label_dv'));
        Schema::table('home_testimonials', fn (Blueprint $t) => $t->dropColumn('name_dv'));
        Schema::table('leadership_members', fn (Blueprint $t) => $t->dropColumn('name_dv'));
        Schema::table('founding_members',  fn (Blueprint $t) => $t->dropColumn('name_dv'));
        Schema::table('achievements',      fn (Blueprint $t) => $t->dropColumn('person_name_dv'));
        Schema::table('staff_members',     fn (Blueprint $t) => $t->dropColumn('name_dv'));
    }

    public function down(): void
    {
        Schema::table('footer_links',      fn (Blueprint $t) => $t->string('label_dv')->nullable()->after('label'));
        Schema::table('home_testimonials', fn (Blueprint $t) => $t->string('name_dv')->nullable()->after('name'));
        Schema::table('leadership_members', fn (Blueprint $t) => $t->string('name_dv')->nullable()->after('name'));
        Schema::table('founding_members',  fn (Blueprint $t) => $t->string('name_dv')->nullable()->after('name'));
        Schema::table('achievements',      fn (Blueprint $t) => $t->string('person_name_dv')->nullable()->after('person_name'));
        Schema::table('staff_members',     fn (Blueprint $t) => $t->string('name_dv')->nullable()->after('name'));
    }
};
