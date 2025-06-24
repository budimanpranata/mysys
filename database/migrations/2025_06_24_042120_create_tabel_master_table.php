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
        Schema::create('tabel_master', function (Blueprint $table) {
            $table->string('rekening_gl', 20);
            $table->string('kode_rekening', 10)->default('');
            $table->string('nama_rekening', 100);
            $table->string('tanggal_awal', 12);
            $table->bigInteger('awal_debet');
            $table->bigInteger('awal_kredit');
            $table->bigInteger('mut_debet');
            $table->bigInteger('mut_kredit');
            $table->bigInteger('sisa_debet');
            $table->bigInteger('sisa_kredit');
            $table->bigInteger('rl_debet');
            $table->bigInteger('rl_kredit');
            $table->bigInteger('nrc_debet');
            $table->bigInteger('nrc_kredit');
            $table->string('posisi', 15);
            $table->string('normal', 10);
            $table->string('unit', 4);
            $table->string('gr_head', 14);
            $table->string('gr_sub', 15);
            $table->string('gr_neraca', 15);
            $table->bigInteger('saldo_awal');
            $table->bigInteger('saldo_akhir');
            $table->bigInteger('saldo_jalan');
            $table->string('LINE_BALANCE', 20);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabel_master');
    }
};
