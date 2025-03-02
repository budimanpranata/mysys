<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pembiayaan_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->string('id_pinjam', 20);
            $table->smallInteger('cicilan');
            $table->integer('angsuran_pokok');
            $table->integer('margin');
            $table->dateTime('tgl_jatuh_tempo');
            $table->dateTime('tgl_bayar')->nullable();
            $table->bigInteger('jumlah_bayar');
            $table->text('keterangan');
            $table->string('cif', 10);
            $table->string('unit', 4);
            $table->string('ao', 10);
            $table->string('code_kel', 10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembiayaan_detail');
    }
};
