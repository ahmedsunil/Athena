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
            // Term 1
            $table->string('term1_dates')->nullable()->after('stat_total_days');
            $table->decimal('term1_total_days', 5, 1)->nullable()->after('term1_dates');
            $table->decimal('term1_teaching_days', 5, 1)->nullable()->after('term1_total_days');
            $table->decimal('term1_exam_days', 5, 1)->nullable()->after('term1_teaching_days');
            // Term 2
            $table->string('term2_dates')->nullable()->after('term1_exam_days');
            $table->decimal('term2_total_days', 5, 1)->nullable()->after('term2_dates');
            $table->decimal('term2_teaching_days', 5, 1)->nullable()->after('term2_total_days');
            $table->decimal('term2_exam_days', 5, 1)->nullable()->after('term2_teaching_days');
            // Exam series (JSON array of {title, sub, period})
            $table->json('exam_series')->nullable()->after('term2_exam_days');
        });
    }

    public function down(): void
    {
        Schema::table('digital_service_calendars', function (Blueprint $table) {
            $table->dropColumn([
                'term1_dates', 'term1_total_days', 'term1_teaching_days', 'term1_exam_days',
                'term2_dates', 'term2_total_days', 'term2_teaching_days', 'term2_exam_days',
                'exam_series',
            ]);
        });
    }
};
