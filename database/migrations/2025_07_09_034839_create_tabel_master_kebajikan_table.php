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
        Schema::create('tabel_master_kebajikan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kebajikan');
            $table->date('tanggal');
            $table->string('nama_account', 80);
            $table->bigInteger('jumlah')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabel_master_kebajikan');
    }
};
