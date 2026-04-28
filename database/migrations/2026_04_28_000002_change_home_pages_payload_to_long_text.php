<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE home_pages MODIFY payload LONGTEXT NOT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE home_pages MODIFY payload JSON NOT NULL');
    }
};
