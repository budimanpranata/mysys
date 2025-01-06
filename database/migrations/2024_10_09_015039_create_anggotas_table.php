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
        Schema::create('anggota', function (Blueprint $table) {
            $table->String('no')->primary();
            $table->String('kode_kel',4);
            $table->String('norek',20);
            $table->date('tgl_join');
            $table->String('cif',8);
            $table->String('nama');
            $table->String('unit',4);
            $table->String('deal_type');
            $table->String('alamat');
            $table->String('desa');
            $table->String('kecamanta');
            $table->String('kota');
            $table->String('rtrw');
            $table->String('no_hp');
            $table->String('hp_pasangan');
            $table->String('kelamin');
            $table->String('tgl_lahir');
            $table->String('ktp');
            $table->String('kewarganegaraan');
            $table->String('status_menikah');
            $table->String('agama');
            $table->String('ibu_kandung');
            $table->String('npwp');
            $table->String('source_income');
            $table->String('pendidikan');
            $table->String('tempat_lahir');
            $table->String('id_expired');
            $table->String('waris');
            $table->String('cao',6);
            $table->String('userid');
            $table->String('status');
            $table->String('pekerjaan_pasangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota');
    }
};
