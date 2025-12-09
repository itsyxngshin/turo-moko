<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Use raw SQL to modify the enum
        DB::statement("ALTER TABLE courses MODIFY status ENUM('archived', 'active', 'deleted', 'closed', 'completed') DEFAULT 'active'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE courses MODIFY status ENUM('archived', 'active', 'deleted', 'closed') DEFAULT 'active'");
    }
};
