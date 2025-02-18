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
        Schema::create('tunggakan', function (Blueprint $table) {
            $table->dateTime('tgl_tunggak');
            $table->string('norek', 16);
            $table->string('unit', 4);
            $table->string('cif', 16);
            $table->string('code_kel', 10);
            $table->bigInteger('debet');
            $table->string('type', 14)->nullable();
            $table->string('kredit', 40)->nullable();
            $table->string('userid', 8)->nullable();
            $table->string('ket', 75);
            $table->bigIncrements('reff');
            $table->string('cao', 10);
            $table->string('blok', 5);
            $table->index('cif');
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
        Schema::dropIfExists('tunggakan');
    }
};
