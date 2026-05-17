<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leadership_members', function (Blueprint $table) {
            $table->string('name_dv')->nullable()->after('name');
        });

        Schema::table('founding_members', function (Blueprint $table) {
            $table->string('name_dv')->nullable()->after('name');
        });

        Schema::table('achievements', function (Blueprint $table) {
            $table->string('person_name_dv')->nullable()->after('person_name');
        });

        Schema::table('staff_members', function (Blueprint $table) {
            $table->string('name_dv')->nullable()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('leadership_members', fn (Blueprint $t) => $t->dropColumn('name_dv'));
        Schema::table('founding_members',   fn (Blueprint $t) => $t->dropColumn('name_dv'));
        Schema::table('achievements',       fn (Blueprint $t) => $t->dropColumn('person_name_dv'));
        Schema::table('staff_members',      fn (Blueprint $t) => $t->dropColumn('name_dv'));
    }
};
