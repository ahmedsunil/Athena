<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Convert a string/text column to JSON (Spatie Translatable format).
     * Adds temp column, migrates data, drops original, renames temp.
     */
    private function toJson(string $table, array $columns): void
    {
        Schema::table($table, function (Blueprint $table) use ($columns) {
            foreach ($columns as $col) {
                $table->json("{$col}_j")->nullable()->after($col);
            }
        });

        DB::table($table)->orderBy('id')->each(function ($row) use ($table, $columns) {
            $update = [];
            foreach ($columns as $col) {
                $val = $row->{$col} ?? null;
                $update["{$col}_j"] = ($val !== null && $val !== '') ? json_encode(['en' => $val]) : null;
            }
            DB::table($table)->where('id', $row->id)->update($update);
        });

        Schema::table($table, function (Blueprint $table) use ($columns) {
            $table->dropColumn($columns);
        });
        Schema::table($table, function (Blueprint $table) use ($columns) {
            foreach ($columns as $col) {
                $table->renameColumn("{$col}_j", $col);
            }
        });
    }

    /**
     * Reverse: extract EN value back to a string column.
     */
    private function fromJson(string $table, array $columns, string $type = 'text'): void
    {
        Schema::table($table, function (Blueprint $table) use ($columns, $type) {
            foreach ($columns as $col) {
                if ($type === 'string') {
                    $table->string("{$col}_s")->nullable()->after($col);
                } else {
                    $table->text("{$col}_s")->nullable()->after($col);
                }
            }
        });

        DB::table($table)->orderBy('id')->each(function ($row) use ($table, $columns) {
            $update = [];
            foreach ($columns as $col) {
                $json = $row->{$col} ?? null;
                $decoded = $json ? json_decode($json, true) : null;
                $update["{$col}_s"] = $decoded['en'] ?? null;
            }
            DB::table($table)->where('id', $row->id)->update($update);
        });

        Schema::table($table, function (Blueprint $table) use ($columns) {
            $table->dropColumn($columns);
        });
        Schema::table($table, function (Blueprint $table) use ($columns) {
            foreach ($columns as $col) {
                $table->renameColumn("{$col}_s", $col);
            }
        });
    }

    public function up(): void
    {
        // ── Home ──────────────────────────────────────────────────────────
        $this->toJson('home_slides', ['title', 'description', 'button_1_label', 'button_2_label']);
        $this->toJson('home_stats', ['title']);
        $this->toJson('home_testimonials', ['previous_designation', 'current_designation', 'message']);
        $this->toJson('home_quick_access', ['title']);

        // ── About ─────────────────────────────────────────────────────────
        $this->toJson('school_profiles', ['school_name', 'motto', 'short_description', 'principal_designation', 'principal_message']);
        $this->toJson('missions', ['mission', 'vision']);
        $this->toJson('leadership_members', ['role', 'bio']);
        $this->toJson('founding_members', ['subject', 'tribute']);
        $this->toJson('history_sections', ['title', 'body']);
        $this->toJson('achievements', ['title', 'description', 'award', 'event_name']);
        $this->toJson('staff_members', ['designation', 'education']);

        // ── Events ────────────────────────────────────────────────────────
        $this->toJson('events', ['title', 'short_description', 'full_description', 'location']);

        // ── Announcements ─────────────────────────────────────────────────
        $this->toJson('announcements', ['title', 'category', 'description']);

        // ── Academics ─────────────────────────────────────────────────────
        $this->toJson('academics_overview', ['text', 'curriculum']);
        $this->toJson('academic_levels', ['label', 'age_range', 'year_groups']);

        // ── Student Life ──────────────────────────────────────────────────
        $this->toJson('student_life_clubs', ['name', 'description', 'meeting_schedule', 'patron_role']);
        $this->toJson('student_life_houses', ['name', 'motto', 'description', 'house_master_role']);
        $this->toJson('student_life_prefects', ['role', 'quote']);
        $this->toJson('student_life_uniform_bodies', ['name', 'description', 'meeting_schedule', 'patron_role']);

        // ── Gallery ───────────────────────────────────────────────────────
        $this->toJson('gallery_albums', ['title', 'category']);

        // ── Digital Services ──────────────────────────────────────────────
        $this->toJson('digital_service_documents', ['title', 'category']);
        $this->toJson('digital_service_resources', ['title', 'description']);
        $this->toJson('digital_service_calendars', ['title', 'description']);
    }

    public function down(): void
    {
        $this->fromJson('home_slides', ['title', 'description', 'button_1_label', 'button_2_label']);
        $this->fromJson('home_stats', ['title'], 'string');
        $this->fromJson('home_testimonials', ['previous_designation', 'current_designation', 'message']);
        $this->fromJson('home_quick_access', ['title'], 'string');
        $this->fromJson('school_profiles', ['school_name', 'motto', 'short_description', 'principal_designation', 'principal_message']);
        $this->fromJson('missions', ['mission', 'vision']);
        $this->fromJson('leadership_members', ['role', 'bio']);
        $this->fromJson('founding_members', ['subject', 'tribute']);
        $this->fromJson('history_sections', ['title', 'body']);
        $this->fromJson('achievements', ['title', 'description', 'award', 'event_name']);
        $this->fromJson('staff_members', ['designation', 'education']);
        $this->fromJson('events', ['title', 'short_description', 'full_description', 'location']);
        $this->fromJson('announcements', ['title', 'category', 'description']);
        $this->fromJson('academics_overview', ['text', 'curriculum']);
        $this->fromJson('academic_levels', ['label', 'age_range', 'year_groups']);
        $this->fromJson('student_life_clubs', ['name', 'description', 'meeting_schedule', 'patron_role']);
        $this->fromJson('student_life_houses', ['name', 'motto', 'description', 'house_master_role']);
        $this->fromJson('student_life_prefects', ['role', 'quote']);
        $this->fromJson('student_life_uniform_bodies', ['name', 'description', 'meeting_schedule', 'patron_role']);
        $this->fromJson('gallery_albums', ['title', 'category']);
        $this->fromJson('digital_service_documents', ['title', 'category']);
        $this->fromJson('digital_service_resources', ['title', 'description']);
        $this->fromJson('digital_service_calendars', ['title', 'description']);
    }
};
