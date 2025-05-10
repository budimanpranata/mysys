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
        Schema::create('musyarokah_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->string('id_pinjam', 20);
            $table->smallInteger('angsuran_ke');
            $table->integer('omzet');
            $table->integer('setoran');
            $table->integer('angsuran_pokok');
            $table->integer('angsuran_margin');
            $table->date('tgl_jatpo')->nullable();
            $table->dateTime('tgl_bayar')->nullable();

            $table->bigInteger('margin_nisbah');
            $table->string('cif', 10);
            $table->string('unit', 4);
            $table->string('ao', 10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('musyarokah_detail');
    }
};
