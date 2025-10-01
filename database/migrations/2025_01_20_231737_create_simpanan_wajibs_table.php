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
        Schema::create('simpanan_wajib', function (Blueprint $table) {
            $table->string('reff', 191)->primary(); // Sesuaikan panjang jika perlu
            $table->dateTime('buss_date');
            $table->string('norek', 16);
            $table->string('unit', 4);
            $table->string('cif', 16);
            $table->string('code_kel', 10);
            $table->decimal('debet', 18, 2)->default(0);
            $table->string('type', 14)->nullable();
            $table->decimal('kredit', 18, 2)->default(0);
            $table->string('userid', 8)->nullable();
            $table->string('ket', 75);
            $table->string('cao', 10);
            $table->integer('blok')->nullable();
            $table->date('tgl_input')->nullable();
            $table->string('kode_transaksi', 20)->nullable();
            $table->index('cif');
            $table->index('unit');
            $table->index('code_kel');
            $table->index('cao');
            $table->index('kode_transaksi');
            $table->index('debet');
            $table->index('type');
            $table->index('buss_date');
            $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simpanan_wajib');
    }
};
