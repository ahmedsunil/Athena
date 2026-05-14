<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->index(['is_active', 'date_start'], 'events_active_date_start_index');
        });

        Schema::table('gallery_albums', function (Blueprint $table) {
            $table->index(['is_active', 'date'], 'gallery_albums_active_date_index');
        });

        Schema::table('digital_service_documents', function (Blueprint $table) {
            $table->index(['is_active', 'published_at'], 'documents_active_published_at_index');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex('events_active_date_start_index');
        });

        Schema::table('gallery_albums', function (Blueprint $table) {
            $table->dropIndex('gallery_albums_active_date_index');
        });

        Schema::table('digital_service_documents', function (Blueprint $table) {
            $table->dropIndex('documents_active_published_at_index');
        });
    }
};
