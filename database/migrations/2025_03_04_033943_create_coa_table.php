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
        Schema::create('coa', function (Blueprint $table) {
            $table->string('k_type')->nullable();
            $table->string('kode_rek', 8)->nullable();
            $table->string('nama_rek', 75)->nullable();
            $table->string('gr_sub', 20)->nullable();
            $table->string('gr_head', 15);
            $table->string('total_print')->nullable();
            $table->string('space_after')->nullable();
            $table->string('line_balance')->nullable();
            $table->string('posisi', 30)->nullable();
            $table->string('gr_neraca')->nullable();
            $table->string('normal', 30)->nullable();
            $table->string('asset_sign')->nullable();
            $table->string('asset_oop_line')->nullable();
            $table->string('asset_type')->nullable();
            $table->string('asset_applic_id')->nullable();
            $table->string('asset1')->nullable();
            $table->string('sandi_bi', 7)->nullable();
            $table->string('status', 15);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coa');
    }
};
