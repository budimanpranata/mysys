<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // CREATE TABLE IF NOT EXISTS `kelompok` (
    //     `code_kel` varchar(10) NOT NULL,
    //     `code_unit` varchar(4) NOT NULL,
    //     `nama_kel` varchar(40) NOT NULL,
    //     `alamat` varchar(80) NOT NULL,
    //     `cao` varchar(8) NOT NULL,
    //     `cif` varchar(6) NOT NULL,
    //     PRIMARY KEY (`code_kel`),
    //     KEY `code_unit` (`code_unit`),
    //     KEY `cao` (`cao`)
    //   ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    public function up(): void
    {
        Schema::create('kelompoks', function (Blueprint $table) {
            $table->string('code_kel', 10)->primary();
            $table->string('code_unit', 4);
            $table->string('nama_kel', 40);
            $table->string('alamat', 80);
            $table->string('cao', 8);
            $table->string('cif', 6);
            $table->index('code_unit');
            $table->index('cao');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelompoks');
    }
};
