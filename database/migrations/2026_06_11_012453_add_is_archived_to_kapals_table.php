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
        Schema::table('kapals', function (Blueprint $table) {
            // Kolom penanda: 0 = tampil di dashboard, 1 = diarsipkan (sembunyi)
            $table->boolean('is_archived')->default(0)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('kapals', function (Blueprint $table) {
            $table->dropColumn('is_archived');
        });
    }
};
