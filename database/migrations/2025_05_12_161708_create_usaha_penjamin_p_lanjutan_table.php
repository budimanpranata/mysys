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
        Schema::create('usaha_penjamin_p_lanjutan', function (Blueprint $table) {
            $table->integer('id_usaha_penjamin')->primary()->autoIncrement();
            $table->integer('id_pemohon')->unsigned();
            $table->string('nama_penjamin', 60);
            $table->string('bin_binti', 10);
            $table->integer('umur');
            $table->string('riwayat_kesehatan', 10);
            $table->string('bidang_usaha', 10);
            $table->string('dagang', 10);
            $table->string('jenis_gadang', 50);
            $table->integer('lama_usaha');
            $table->string('tempat_usaha', 20);
            $table->integer('omzet');
            $table->integer('total_pengeluaran_usaha');
            $table->integer('laba_bersih');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usaha_penjamin_p_lanjutan');
    }
};
