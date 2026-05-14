<?php

use App\Models\Announcement;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('announcements', 'slug')) {
            Schema::table('announcements', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('title');
            });

            Announcement::query()->select(['id', 'title'])->get()->each(function (Announcement $announcement) {
                $base = Str::slug($announcement->title) ?: 'announcement-' . $announcement->id;
                $slug = $base;
                $count = 2;

                while (Announcement::where('slug', $slug)->where('id', '!=', $announcement->id)->exists()) {
                    $slug = $base . '-' . $count++;
                }

                $announcement->forceFill(['slug' => $slug])->save();
            });

            Schema::table('announcements', function (Blueprint $table) {
                $table->unique('slug');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('announcements', 'slug')) {
            Schema::table('announcements', function (Blueprint $table) {
                $table->dropUnique(['slug']);
                $table->dropColumn('slug');
            });
        }
    }
};
