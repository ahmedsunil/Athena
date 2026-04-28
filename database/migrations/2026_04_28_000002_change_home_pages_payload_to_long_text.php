<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // The home_pages table now creates payload as longText from the start.
        // Keep this migration as a no-op so existing migration histories stay valid.
    }

    public function down(): void
    {
        //
    }
};
