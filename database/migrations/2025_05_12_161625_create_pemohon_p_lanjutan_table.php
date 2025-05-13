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
        Schema::create('pemohon_p_lanjutan', function (Blueprint $table) {
            $table->integer('id_pemohon')->primary()->autoIncrement();
            $table->string('cao', 8);
            $table->string('unit', 4);
            $table->integer('pembiayaan_ke');
            $table->string('kode_kel', 10);
            $table->date('tgl_survei');
            $table->string('nama', 60);
            $table->string('binti', 10);
            $table->integer('umur');
            $table->string('penjamin', 60);
            $table->string('status_kawin', 10);
            $table->integer('jml_anak');
            $table->string('status_domisili', 10);
            $table->string('riwayat_kesehatan', 10);
            $table->string('anggota_kel_sakit', 10);
            $table->string('ibu_kandung', 60);
            $table->string('hubungan_dgn_tetangga', 10);
            $table->string('aktivitas_di_lingkungan', 10);
            $table->string('riwayat_pembiayaan',10);
            $table->string('gaya_hidup', 10);
            $table->string('sikap', 10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemohon_p_lanjutan');
    }
};
