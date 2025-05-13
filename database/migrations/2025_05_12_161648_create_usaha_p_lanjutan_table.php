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
        Schema::create('usaha_p_lanjutan', function (Blueprint $table) {
            $table->integer('id_usaha')->primary()->autoIncrement();
            $table->integer('id_pemohon')->unsigned();
            $table->string('bidang_usaha', 10);
            $table->string('dagang', 10);
            $table->string('jenis_dagang', 50);
            $table->integer('lama_usaha');
            $table->string('pelaku_bisnis', 20);
            $table->string('tempat_usaha', 20);
            $table->string('keunggulan_usaha', 60);
            $table->string('pengguna_pembiayaan', 10);
            $table->integer('omzet');
            $table->integer('laba_bersih');
            $table->integer('pendapatan_penjamin');
            $table->integer('total_pengeluaran_rt');
            $table->integer('angsuran_lain');
            $table->integer('angsuran_diajukan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usaha_p_lanjutan');
    }
};
