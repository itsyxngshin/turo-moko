<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update ENUM column to include Open, Pending, Completed
        DB::statement("ALTER TABLE assignments MODIFY COLUMN status ENUM('Open', 'Pending', 'Completed') NOT NULL DEFAULT 'Open'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to previous ENUM values (adjust if needed)
        DB::statement("ALTER TABLE assignments MODIFY COLUMN status ENUM('Pending', 'Completed') NOT NULL DEFAULT 'Pending'");
    }
};
