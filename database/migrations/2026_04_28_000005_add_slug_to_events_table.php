<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('events')) {
            return;
        }

        if (! Schema::hasColumn('events', 'slug')) {
            Schema::table('events', function (Blueprint $table) {
                $table->string('slug')->nullable()->unique()->after('title');
            });
        }

        $this->backfillSlugs();
    }

    public function down(): void
    {
        if (! Schema::hasTable('events') || ! Schema::hasColumn('events', 'slug')) {
            return;
        }

        Schema::table('events', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }

    private function backfillSlugs(): void
    {
        $knownSlugs = [
            'evt-002' => 'open-day-2025',
            'evt-003' => 'inter-house-sports',
            'evt-005' => 'prize-giving-2025',
        ];

        $used = DB::table('events')
            ->whereNotNull('slug')
            ->pluck('slug')
            ->all();

        $used = array_fill_keys($used, true);

        DB::table('events')
            ->whereNull('slug')
            ->orderBy('id')
            ->get(['id', 'public_id', 'title'])
            ->each(function (object $event) use (&$used, $knownSlugs): void {
                $baseSlug = $knownSlugs[$event->public_id] ?? Str::slug($event->title);
                $slug = $baseSlug;
                $suffix = 2;

                while (isset($used[$slug])) {
                    $slug = "{$baseSlug}-{$suffix}";
                    $suffix++;
                }

                DB::table('events')
                    ->where('id', $event->id)
                    ->update(['slug' => $slug]);

                $used[$slug] = true;
            });
    }
};
