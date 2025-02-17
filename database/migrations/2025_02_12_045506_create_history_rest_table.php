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
        Schema::create('history_rest', function (Blueprint $table) {
            $table->id();
            $table->date('tgl_rest');
            $table->string('code_kel', 10);
            $table->string('cif', 10);
            $table->bigInteger('plafond');
            $table->bigInteger('pokok');
            $table->bigInteger('margin');
            $table->bigInteger('angsuran');
            $table->integer('tenor');
            $table->string('jenis_rest', 50);
            $table->string('status', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_rest');
    }
};
