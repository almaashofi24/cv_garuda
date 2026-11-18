<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'tipe',
        'deskripsi',
        'warna',
    ];

    /**
     * Relasi ke transaksi pada kategori ini.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Scope untuk kategori pemasukan.
     */
    public function scopePemasukan($query)
    {
        return $query->where('categories.tipe', 'pemasukan');
    }

    /**
     * Scope untuk kategori pengeluaran.
     */
    public function scopePengeluaran($query)
    {
        return $query->where('categories.tipe', 'pengeluaran');
    }
}

