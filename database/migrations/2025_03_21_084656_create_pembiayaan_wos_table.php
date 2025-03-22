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
        Schema::create('pembiayaan_wos', function (Blueprint $table) {
            $table->dateTime('buss_date'); // tanggal wo
            $table->string('code_kel', 16);
            $table->string('no_anggota', 16);
            $table->string('cif', 10);
            $table->string('nama', 40);
            $table->string('deal_type', 3);
            $table->string('suffix', 2);
            $table->double('bagi_hasil');
            $table->integer('tenor');
            $table->bigInteger('plafond');
            $table->bigInteger('os');
            $table->bigInteger('saldo_margin');
            $table->bigInteger('angsuran');
            $table->bigInteger('pokok');
            $table->bigInteger('ijaroh');
            $table->bigInteger('bulat');
            $table->string('run_tenor', 2);
            $table->string('ke', 2);
            $table->string('usaha', 3);
            $table->string('nama_usaha', 50);
            $table->string('unit', 4);
            $table->dateTime('tgl_wakalah');
            $table->date('tgl_akad');
            $table->dateTime('tgl_murab');
            $table->dateTime('next_schedule');
            $table->dateTime('maturity_date');
            $table->date('last_payment');
            $table->string('hari', 10);
            $table->string('cao', 10);
            $table->string('userid', 8)->nullable();
            $table->string('status', 20);
            $table->string('status_usia', 20);
            $table->string('status_app', 30);
            $table->string('gol', 10)->nullable();
            $table->integer('deal_produk')->nullable();
            $table->double('persen_margin', 8, 4)->nullable(); // Total 8 digit, 4 angka desimal
            $table->primary('cif');
            $table->index('no_anggota');
            $table->index('code_kel');
            $table->index('unit');
            $table->index('cao');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembiayaan_wos');
    }
};
