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
        Schema::create('anggota_details', function (Blueprint $table) {
            $table->id();
            $table->string('no_anggota', 50);
            $table->string('alamat_domisili')->nullable();
            $table->string('desa_domisili', 50)->nullable();
            $table->string('kecamatan_domisili', 50)->nullable();
            $table->string('kota_domisili', 50)->nullable();
            $table->string('rtrw_domisili', 50)->nullable();
            $table->string('kode_pos_domisili', 10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota_details');
    }
};
