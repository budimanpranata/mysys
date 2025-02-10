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
        Schema::create('loan_detail', function (Blueprint $table) {
            $table->string('code_kel', 16);
            $table->dateTime('buss_date');
            $table->string('no_anggota', 16);
            $table->string('cif', 8);
            $table->string('suffix', 3);
            $table->string('nama', 50);
            $table->string('deal_type', 3);
            $table->double('bagi_hasil', 8, 4);
            $table->integer('tenor');
            $table->bigInteger('plafond');
            $table->string('unit', 4);
            $table->dateTime('contract_date');
            $table->string('hari_tagih', 10);
            $table->bigInteger('angsuran');
            $table->bigInteger('setoran');
            $table->string('cao', 10);
            $table->string('userid', 8);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_detail');
    }
};
