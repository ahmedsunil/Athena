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
        Schema::table('digital_service_calendars', function (Blueprint $table) {
            $table->decimal('stat_teaching_days', 5, 1)->nullable()->after('is_active');
            $table->decimal('stat_exam_days', 5, 1)->nullable()->after('stat_teaching_days');
            $table->decimal('stat_report_prep_days', 5, 1)->nullable()->after('stat_exam_days');
            $table->decimal('stat_teacher_pd_days', 5, 1)->nullable()->after('stat_report_prep_days');
            $table->decimal('stat_non_teaching_days', 5, 1)->nullable()->after('stat_teacher_pd_days');
            $table->decimal('stat_total_days', 5, 1)->nullable()->after('stat_non_teaching_days');
        });
    }

    public function down(): void
    {
        Schema::table('digital_service_calendars', function (Blueprint $table) {
            $table->dropColumn([
                'stat_teaching_days', 'stat_exam_days', 'stat_report_prep_days',
                'stat_teacher_pd_days', 'stat_non_teaching_days', 'stat_total_days',
            ]);
        });
    }
};
