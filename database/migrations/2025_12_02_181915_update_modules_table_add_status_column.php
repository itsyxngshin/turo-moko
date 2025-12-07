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
       Schema::table('modules', function (Blueprint $table) {

    // Add visibility only if missing
    if (!Schema::hasColumn('modules', 'visibility')) {
        $table->enum('visibility', ['visible', 'hidden'])
            ->default('visible')
            ->after('module_number');
    }

    // Add status only if missing
    if (!Schema::hasColumn('modules', 'status')) {
        $table->enum('status', [
            'pending',
            'approved',
            'rejected',
            'revision_required',
            'resubmitted'
        ])
        ->default('pending')
        ->after('visibility');
    }
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modules', function (Blueprint $table) {

            // Remove the added columns
            $table->dropColumn('visibility');
            $table->dropColumn('status');
        });
    }
};
