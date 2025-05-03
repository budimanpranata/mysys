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
        Schema::create('rek_loan', function (Blueprint $table) {
            $table->integer('ref')->autoIncrement();
            $table->dateTime('tgl_realisasi');
            $table->string('unit', 8);
            $table->string('no_anggota', 20)->nullable();
            $table->bigInteger('saldo_kredit');
            $table->bigInteger('debet');
            $table->string('tipe', 4);
            $table->string('ket');
            $table->string('userid', 15);
            $table->string('status', 50);
            $table->string('cif', 10);
            $table->string('ao', 7)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rek_loan');
    }
};
