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
        Schema::create('arus_kas_kategori', function (Blueprint $table) {
            $table->string('code_arus_kas', 8)->primary();
            $table->enum('line', ['HEADING', 'SUB HEADING', 'DETAIL']);
            $table->string('nama', 150);
            $table->string('group_heading', 8);
            $table->unsignedInteger('urutan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arus_kas_kategori');
    }
};
