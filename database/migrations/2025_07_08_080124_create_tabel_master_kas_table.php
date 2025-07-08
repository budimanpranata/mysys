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
        Schema::create('tabel_master_kas', function (Blueprint $table) {
            $table->string('line');
            $table->integer('code_arus_kas');
            $table->string('nama_arus_kas');
            $table->string('unit', 4);
            $table->bigInteger('mut_debet');
            $table->bigInteger('mut_kredit');
            $table->bigInteger('saldo_awal');
            $table->bigInteger('saldo_akhir');
            $table->bigInteger('saldo_tahun');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabel_master_kas');
    }
};
