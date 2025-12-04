<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('lessons', function (Blueprint $table) {
        $table->string('attachments_original_name')->nullable()->after('attachments');
    });
}

public function down()
{
    Schema::table('lessons', function (Blueprint $table) {
        $table->dropColumn('attachments_original_name');
    });
}

};
