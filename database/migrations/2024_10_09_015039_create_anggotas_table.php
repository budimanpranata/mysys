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
            $table->String('no', 50)->primary();
            $table->String('kode_kel',10);
            $table->String('norek',20);
            $table->date('tgl_join');
            $table->String('cif',8);
            $table->String('nama');
            $table->String('unit',4);
            $table->String('deal_type');
            $table->String('alamat');
            $table->String('desa', 50);
            $table->String('kecamatan', 50);
            $table->String('kota', 50);
            $table->String('rtrw', 50);
            $table->String('kode_pos', 10);
            $table->String('no_hp', 50);
            $table->String('hp_pasangan', 50);
            $table->String('kelamin', 3);
            $table->String('tgl_lahir');
            $table->String('ktp', 50);
            $table->String('kewarganegaraan', 50);
            $table->String('status_menikah', 50);
            $table->String('agama', 50);
            $table->String('ibu_kandung');
            $table->String('npwp', 50);
            $table->String('source_income');
            $table->String('pendidikan', 50);
            $table->String('tempat_lahir');
            $table->String('id_expired');
            $table->String('waris');
            $table->String('cao',6);
            $table->String('userid', 6);
            $table->String('status', 50);
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
