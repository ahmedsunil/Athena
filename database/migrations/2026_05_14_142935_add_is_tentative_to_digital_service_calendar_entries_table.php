<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('digital_service_calendar_entries', function (Blueprint $table) {
            $table->boolean('is_tentative')->default(false)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('digital_service_calendar_entries', function (Blueprint $table) {
            $table->dropColumn('is_tentative');
        });
    }
};
