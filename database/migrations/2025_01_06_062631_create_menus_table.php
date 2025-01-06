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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama menu/submenu
            $table->string('icon')->nullable(); // Ikon menu
            $table->unsignedBigInteger('parent_id')->nullable(); // Menu induk
            $table->string('url')->nullable(); // URL menu/submenu
            $table->integer('order')->default(0); // Urutan menu
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
