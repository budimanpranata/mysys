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
        Schema::create('branch', function (Blueprint $table) {
            $table->string('kode_branch', 4)->primary();
            $table->string('unit', 50);
            $table->string('code_area', 4);
            $table->string('area', 40);
            $table->string('code_region', 4);
            $table->string('region', 40);
            $table->string('alamat', 200);
            $table->string('GL', 20);
            $table->dateTime('tgl_open');
            $table->string('status_aktif', 30)->nullable();
            $table->string('status_approve', 2)->nullable();
            $table->index('kode_branch');
            $table->index('code_area');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch');
    }
};
