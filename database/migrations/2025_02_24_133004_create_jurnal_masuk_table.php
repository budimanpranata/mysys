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
        Schema::create('jurnal_masuk', function (Blueprint $table) {
            $table->increments('nomor_jurnal');
            $table->string('kode_transaksi', 30);
            $table->dateTime('tanggal_selesai');
            $table->string('unit', 4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnal_masuk');
    }
};
