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
        Schema::table('tabel_master_kas', function (Blueprint $table) {
            $table->dropUnique('tabel_master_kas_code_arus_kas_unique');
            $table->unique(['unit', 'code_arus_kas']);
            $table->date('periode_awal')->nullable()->after('saldo_tahun');
            $table->date('periode_akhir')->nullable()->after('periode_awal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tabel_master_kas', function (Blueprint $table) {
            $table->dropColumn(['periode_awal', 'periode_akhir']);
            $table->dropUnique(['unit', 'code_arus_kas']);
            $table->unique('code_arus_kas');
        });
    }
};
