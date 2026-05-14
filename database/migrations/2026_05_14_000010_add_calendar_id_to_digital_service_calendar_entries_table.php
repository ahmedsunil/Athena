<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('digital_service_calendar_entries', function (Blueprint $table) {
            $table->foreignId('calendar_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('digital_service_calendars')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('digital_service_calendar_entries', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\DigitalServiceCalendar::class);
            $table->dropColumn('calendar_id');
        });
    }
};
