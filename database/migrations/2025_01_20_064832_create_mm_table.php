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
        Schema::create('mm', function (Blueprint $table) {
            $table->string('nik', 9)->primary();
            $table->string('nama', 50);
            $table->string('alamat', 75);
            $table->datetime('tgl_lahir');
            $table->string('jabatan', 40);
            $table->string('no_tlp', 16);
            $table->datetime('tmt');
            $table->string('unit', 4);
            $table->string('foto', 75)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mm');
    }
};
