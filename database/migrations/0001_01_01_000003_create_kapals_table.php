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
        Schema::create('kapals', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_input');
            $table->string('nama_kapal');
            $table->integer('qty');
            $table->string('agen');
            $table->string('jenis_muatan');
            $table->date('tanggal_sandar')->nullable();
            $table->date('tanggal_muat')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->enum('status', ['Menunggu Sandar', 'Sedang Muat', 'Selesai'])->default('Menunggu Sandar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kapals');
    }
};