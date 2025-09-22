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
       Schema::create('tagihan_pelunasan', function (Blueprint $table) {
            $table->id();
            $table->string('unit')->nullable();
            $table->date('tgl_tagih')->nullable();
            $table->string('code_kel')->nullable();
            $table->string('cif')->nullable();
            $table->string('cao')->nullable();
            $table->string('norek')->nullable();
            $table->decimal('angsuran_pokok', 18, 2)->default(0);
            $table->decimal('angsuran_margin', 18, 2)->default(0);
            $table->decimal('angsuran', 18, 2)->default(0);
            $table->decimal('bayar', 18, 2)->default(0);
            $table->string('status_realisasi')->nullable();
            $table->integer('pb')->default(0);
            $table->integer('ke')->default(0);
            $table->decimal('tunggakan', 18, 2)->default(0);
            $table->string('hari')->default(0);
            $table->decimal('twm', 18, 2)->default(0);
            $table->decimal('bulat', 18, 2)->default(0);
            $table->decimal('simpanan_wajib', 18, 2)->default(0);
            $table->decimal('simpanan_pokok', 18, 2)->default(0);
            $table->decimal('os', 18, 2)->default(0);
            $table->string('nama')->nullable();
            $table->string('nama_kel')->nullable();
            $table->decimal('saldo_margin', 18, 2)->default(0);
            $table->decimal('plafond', 18, 2)->default(0);
            $table->string('jenis_pull')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihan_pelunasan');
    }
};
