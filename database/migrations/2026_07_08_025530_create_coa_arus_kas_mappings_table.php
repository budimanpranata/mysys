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
        Schema::create('coa_arus_kas_mappings', function (Blueprint $table) {
            $table->id();
            $table->enum('match_type', ['kode_rekening', 'gr_head', 'prefix']);
            $table->string('match_value', 25);
            $table->enum('arah', ['debet', 'kredit', 'both']);
            $table->string('code_arus_kas', 8);
            $table->string('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['match_type', 'match_value', 'arah'], 'uniq_mapping_rule');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coa_arus_kas_mappings');
    }
};
