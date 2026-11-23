<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('web_apps', function (Blueprint $table) {
            $table->string('icon_path')->nullable()->after('icon_key');
        });
    }

    public function down(): void
    {
        Schema::table('web_apps', function (Blueprint $table) {
            $table->dropColumn('icon_path');
        });
    }
};
