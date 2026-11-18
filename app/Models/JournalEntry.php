<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'account_id',
        'tanggal',
        'nomor_bukti',
        'keterangan',
        'debit',
        'kredit',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'debit' => 'decimal:2',
            'kredit' => 'decimal:2',
        ];
    }

    /**
     * Relasi ke akun.
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Relasi ke transaksi sumber (jika ada).
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function getFormattedDebitAttribute(): string
    {
        return $this->debit > 0 ? 'Rp ' . number_format((float) $this->debit, 0, ',', '.') : '-';
    }

    public function getFormattedKreditAttribute(): string
    {
        return $this->kredit > 0 ? 'Rp ' . number_format((float) $this->kredit, 0, ',', '.') : '-';
    }

    public function getFormattedTanggalAttribute(): string
    {
        return $this->tanggal ? Carbon::parse($this->tanggal)->format('d/m/Y') : '-';
    }
}
