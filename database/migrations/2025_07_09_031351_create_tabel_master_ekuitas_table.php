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
        Schema::create('tabel_master_ekuitas', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_account');
            $table->bigInteger('saldo_awal')->default(0);
            $table->bigInteger('penambahan')->default(0);
            $table->bigInteger('saldo_akhir')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabel_master_ekuitas');
    }
};
