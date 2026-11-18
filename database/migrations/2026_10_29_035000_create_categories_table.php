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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->enum('tipe', ['pemasukan', 'pengeluaran'])->index();
            $table->string('deskripsi', 255)->nullable();
            $table->string('warna', 30)->default('#2563eb'); // Optional hex color for charts/badges
            $table->timestamps();

            $table->index(['tipe', 'nama']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};

