<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            // Change 'visibility' column to ENUM('public', 'private')
            $table->enum('visibility', ['public', 'private'])
                  ->default('public')
                  ->change();
        });
    }

    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            // Revert back to string if needed
            $table->string('visibility')->default('public')->change();
        });
    }
};
