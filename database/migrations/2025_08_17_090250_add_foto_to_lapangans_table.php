<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('lapangans', function (Blueprint $table) {
        $table->string('foto')->nullable()->after('nama');
    });
}

public function down()
{
    Schema::table('lapangans', function (Blueprint $table) {
        $table->dropColumn('foto');
    });
}

};
