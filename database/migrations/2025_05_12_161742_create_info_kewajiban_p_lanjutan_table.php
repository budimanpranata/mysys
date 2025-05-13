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
        Schema::create('info_kewajiban_p_lanjutan', function (Blueprint $table) {
            $table->integer('id_info_kewajiban')->primary()->autoIncrement();
            $table->integer('id_pemohon');
            $table->integer('kur');
            $table->integer('mbk');
            $table->integer('btpns');
            $table->string('lain_lain', 25);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('info_kewajiban_p_lanjutan');
    }
};
