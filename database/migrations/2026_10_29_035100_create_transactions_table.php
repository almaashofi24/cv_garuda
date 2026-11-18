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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_bukti', 50)->unique();
            $table->date('tanggal')->index();
            $table->enum('tipe', ['pemasukan', 'pengeluaran'])->index();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->text('deskripsi');
            $table->decimal('jumlah', 15, 2);
            $table->enum('metode_pembayaran', ['tunai', 'transfer'])->default('transfer')->index();
            $table->string('pihak_terkait', 150); // Sumber/Pelanggan (pemasukan) atau Penerima/Vendor (pengeluaran)
            $table->string('lampiran')->nullable();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tanggal', 'tipe']);
            $table->index(['tipe', 'metode_pembayaran']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

