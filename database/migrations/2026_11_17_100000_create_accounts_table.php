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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('golongan_kode', 20)->index('idx_accounts_gol_kode');
            $table->string('golongan', 50)->index('idx_accounts_golongan');
            $table->string('nama', 150);
            $table->enum('saldo_normal', ['Debit', 'Kredit', 'Penutup'])->default('Debit');
            $table->decimal('saldo_awal', 15, 2)->default(0);
            $table->string('deskripsi', 255)->nullable();
            $table->boolean('is_locked')->default(false);
            $table->timestamps();

            $table->index(['golongan', 'kode'], 'idx_accounts_gol_and_kode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
