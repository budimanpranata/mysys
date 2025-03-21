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
        Schema::create('pembiayaan_wo_details', function (Blueprint $table) {
            $table->id();
            $table->string('cif', 8);
            $table->integer('user_id')->unsigned();
            $table->integer('user_id_approval')->unsigned()->nullable();
            $table->bigInteger('nominal_wo');
            $table->string('status_wo', 20); // ambil dari status anggota
            $table->string('no_rekening', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembiayaan_wo_details');
    }
};
