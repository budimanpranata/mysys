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
            $table->string('gl_arus_kas', 13)->primary();
            $table->string('line', 8);
            $table->string('code_arus_kas', 8)->unique();
            $table->string('nama_arus_kas', 150);
            $table->string('unit', 4);
            $table->bigInteger('mut_debet')->default(0);
            $table->bigInteger('mut_kredit')->default(0);
            $table->bigInteger('saldo_awal')->default(0);
            $table->bigInteger('saldo_akhir')->default(0);
            $table->bigInteger('saldo_tahun')->default(0);
            $table->string('group_heading');
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
