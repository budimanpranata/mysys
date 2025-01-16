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
        Schema::create('ao', function (Blueprint $table) {
            $table->string('cao', 8)->primary();
            $table->string('nama_ao', 50);
            $table->string('no_tlp', 14);
            $table->string('kode_unit', 4);
            $table->string('atasan', 16);
            $table->string('nik_ao', 30);
            $table->index('nama_ao');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ao');
    }
};
