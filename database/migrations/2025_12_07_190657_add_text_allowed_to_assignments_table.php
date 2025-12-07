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
        Schema::table('assignments', function (Blueprint $table) {
            $table->boolean('text_allowed')->default(false)->after('filetype_allowed');
        });

        // Update existing records: if filetype_allowed is false, set text_allowed to true
        // This maintains backward compatibility
        DB::table('assignments')
            ->where('filetype_allowed', false)
            ->update(['text_allowed' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn('text_allowed');
        });
    }
};
