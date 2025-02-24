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
        Schema::create('tabel_transaksi', function (Blueprint $table) {
            $table->increments('id_transaksi');
            $table->string('unit', 4);
            $table->string('kode_transaksi', 25);
            $table->string('kode_rekening', 25);
            $table->dateTime('tanggal_transaksi');
            $table->string('jenis_transaksi', 15);
            $table->text('keterangan_transaksi');
            $table->bigInteger('debet');
            $table->bigInteger('kredit');
            $table->string('tanggal_posting', 15);
            $table->text('keterangan_posting');
            $table->string('id_admin', 6);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabel_transaksi');
    }
};
