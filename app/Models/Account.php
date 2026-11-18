<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'golongan_kode',
        'golongan',
        'nama',
        'saldo_normal',
        'saldo_awal',
        'deskripsi',
        'is_locked',
    ];

    protected function casts(): array
    {
        return [
            'saldo_awal' => 'decimal:2',
            'is_locked' => 'boolean',
        ];
    }

    /**
     * Relasi ke baris entri jurnal.
     */
    public function journalEntries(): HasMany
    {
        return $this->hasMany(JournalEntry::class);
    }

    /**
     * Relasi ke transaksi yang terkait langsung dengan akun ini.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    // Scopes untuk 7 Golongan Akun
    public function scopeHarta($query)
    {
        return $query->where('golongan', 'like', '%Harta%');
    }

    public function scopeUtang($query)
    {
        return $query->where('golongan', 'like', '%Utang%');
    }

    public function scopeModal($query)
    {
        return $query->where('golongan', 'like', '%Modal%');
    }

    public function scopePendapatan($query)
    {
        return $query->where('golongan', 'like', '%Pendapatan%');
    }

    public function scopeBeban($query)
    {
        return $query->where('golongan', 'like', '%Beban%');
    }

    public function scopePrive($query)
    {
        return $query->where('golongan', 'like', '%Prive%');
    }

    public function scopeIkhtisar($query)
    {
        return $query->where('golongan', 'like', '%Ikhtisar%');
    }

    /**
     * Hitung saldo berjalan akun hingga tanggal tertentu atau rentang tanggal.
     */
    public function getSaldo($startDate = null, $endDate = null): float
    {
        $query = $this->journalEntries();

        if ($startDate && $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        } elseif ($endDate) {
            $query->where('tanggal', '<=', $endDate);
        }

        $debit = (float) $query->sum('debit');
        $kredit = (float) $query->sum('kredit');

        // Jika perhitungan dengan tanggal awal, saldo awal diperhitungkan sebelum tanggal awal
        $saldoAwal = (float) $this->saldo_awal;
        if ($startDate) {
            $prevEntries = $this->journalEntries()->where('tanggal', '<', $startDate);
            $prevDebit = (float) $prevEntries->sum('debit');
            $prevKredit = (float) $prevEntries->sum('kredit');
            if ($this->saldo_normal === 'Debit') {
                $saldoAwal = $saldoAwal + $prevDebit - $prevKredit;
            } else {
                $saldoAwal = $saldoAwal + $prevKredit - $prevDebit;
            }
        }

        if ($this->saldo_normal === 'Debit') {
            return $saldoAwal + $debit - $kredit;
        } elseif ($this->saldo_normal === 'Kredit') {
            return $saldoAwal + $kredit - $debit;
        } else {
            return $kredit - $debit;
        }
    }

    /**
     * Hitung total mutasi debit dalam rentang tanggal.
     */
    public function getTotalDebit($startDate = null, $endDate = null): float
    {
        $query = $this->journalEntries();
        if ($startDate && $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        } elseif ($endDate) {
            $query->where('tanggal', '<=', $endDate);
        }
        return (float) $query->sum('debit');
    }

    /**
     * Hitung total mutasi kredit dalam rentang tanggal.
     */
    public function getTotalKredit($startDate = null, $endDate = null): float
    {
        $query = $this->journalEntries();
        if ($startDate && $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        } elseif ($endDate) {
            $query->where('tanggal', '<=', $endDate);
        }
        return (float) $query->sum('kredit');
    }

    /**
     * Ambil saldo terkini (All time).
     */
    public function getCurrentSaldoAttribute(): float
    {
        return $this->getSaldo();
    }

    /**
     * Label gabungan kode & nama akun.
     */
    public function getKodeNamaAttribute(): string
    {
        return "{$this->kode} - {$this->nama}";
    }

    /**
     * Format saldo Rupiah.
     */
    public function getFormattedSaldoAttribute(): string
    {
        $saldo = $this->getCurrentSaldoAttribute();
        $isNegative = $saldo < 0;
        $formatted = 'Rp ' . number_format(abs($saldo), 0, ',', '.');
        return $isNegative ? "-{$formatted}" : $formatted;
    }

    /**
     * Format saldo awal Rupiah.
     */
    public function getFormattedSaldoAwalAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->saldo_awal, 0, ',', '.');
    }
}
