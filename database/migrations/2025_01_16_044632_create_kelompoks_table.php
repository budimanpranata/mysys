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
        Schema::create('kelompok', function (Blueprint $table) {
            $table->string('code_kel', 10)->primary();
            $table->string('code_unit', 4);
            $table->string('nama_kel', 40);
            $table->string('alamat', 80);
            $table->string('cao', 10);
            $table->string('cif', 10);
            $table->string('no_tlp', 16);
            $table->index('cao');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelompok');
    }
};
