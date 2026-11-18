<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nomor_bukti',
        'tanggal',
        'tipe',
        'category_id',
        'account_id',
        'deskripsi',
        'jumlah',
        'metode_pembayaran',
        'pihak_terkait',
        'lampiran',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'jumlah' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function (Transaction $transaction) {
            $transaction->syncJournalEntries();
        });

        static::deleted(function (Transaction $transaction) {
            $transaction->journalEntries()->delete();
        });

        static::restored(function (Transaction $transaction) {
            $transaction->syncJournalEntries();
        });
    }

    /**
     * Relasi ke kategori transaksi.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi ke akun (7 Golongan Akun).
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Relasi ke entri jurnal akuntansi.
     */
    public function journalEntries(): HasMany
    {
        return $this->hasMany(JournalEntry::class);
    }

    /**
     * Relasi ke pembuat transaksi (user).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope untuk pemasukan.
     */
    public function scopePemasukan($query)
    {
        return $query->where('transactions.tipe', 'pemasukan');
    }

    /**
     * Scope untuk pengeluaran.
     */
    public function scopePengeluaran($query)
    {
        return $query->where('transactions.tipe', 'pengeluaran');
    }

    /**
     * Format nominal ke format Rupiah standar Indonesia (contoh: Rp 1.250.000).
     */
    public function getFormattedJumlahAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->jumlah, 0, ',', '.');
    }

    /**
     * Format tanggal ke dd/mm/yyyy.
     */
    public function getFormattedTanggalAttribute(): string
    {
        return $this->tanggal ? Carbon::parse($this->tanggal)->format('d/m/Y') : '-';
    }

    /**
     * URL lampiran bukti/nota.
     */
    public function getLampiranUrlAttribute(): ?string
    {
        return $this->lampiran ? Storage::url($this->lampiran) : null;
    }

    /**
     * Sinkronisasi jurnal akuntansi berpasangan (Double-Entry).
     * Pemasukan: Kas (110) [Debit] dan Akun Terpilih [Kredit]
     * Pengeluaran: Akun Terpilih [Debit] dan Kas (110) [Kredit]
     */
    public function syncJournalEntries(): void
    {
        // Hapus entri jurnal lama terkait transaksi ini
        $this->journalEntries()->delete();

        // Cari akun kas utama (110)
        $kasAccount = Account::where('kode', '110')->first();
        if (!$kasAccount) {
            return;
        }

        // Tentukan akun lawan
        $targetAccount = null;
        if ($this->account_id) {
            $targetAccount = Account::find($this->account_id);
        }

        // Fallback jika belum memilih account_id: cari dari kategori atau default
        if (!$targetAccount) {
            if ($this->tipe === 'pemasukan') {
                $targetAccount = Account::where('kode', '410')->first() ?: Account::pendapatan()->first();
            } else {
                $targetAccount = Account::where('kode', '510')->first() ?: Account::beban()->first();
            }

            if ($targetAccount && !$this->account_id) {
                // Simpan tautan akun secara otomatis
                $this->updateQuietly(['account_id' => $targetAccount->id]);
            }
        }

        if (!$targetAccount) {
            return;
        }

        $tanggal = $this->tanggal ? Carbon::parse($this->tanggal)->format('Y-m-d') : Carbon::now()->format('Y-m-d');
        $nominal = (float) $this->jumlah;

        if ($this->tipe === 'pemasukan') {
            // Debit: Kas (110)
            JournalEntry::create([
                'transaction_id' => $this->id,
                'account_id' => $kasAccount->id,
                'tanggal' => $tanggal,
                'nomor_bukti' => $this->nomor_bukti,
                'keterangan' => $this->deskripsi ?: 'Penerimaan Kas',
                'debit' => $nominal,
                'kredit' => 0,
            ]);

            // Kredit: Akun Terkait (misal: Pendapatan Jasa, Piutang, Modal)
            JournalEntry::create([
                'transaction_id' => $this->id,
                'account_id' => $targetAccount->id,
                'tanggal' => $tanggal,
                'nomor_bukti' => $this->nomor_bukti,
                'keterangan' => $this->deskripsi ?: 'Pendapatan/Penerimaan',
                'debit' => 0,
                'kredit' => $nominal,
            ]);
        } else {
            // Pengeluaran
            // Debit: Akun Terkait (misal: Beban Gaji, Beban Sewa, Utang, Peralatan)
            JournalEntry::create([
                'transaction_id' => $this->id,
                'account_id' => $targetAccount->id,
                'tanggal' => $tanggal,
                'nomor_bukti' => $this->nomor_bukti,
                'keterangan' => $this->deskripsi ?: 'Pengeluaran/Beban',
                'debit' => $nominal,
                'kredit' => 0,
            ]);

            // Kredit: Kas (110)
            JournalEntry::create([
                'transaction_id' => $this->id,
                'account_id' => $kasAccount->id,
                'tanggal' => $tanggal,
                'nomor_bukti' => $this->nomor_bukti,
                'keterangan' => $this->deskripsi ?: 'Pembayaran Kas',
                'debit' => 0,
                'kredit' => $nominal,
            ]);
        }
    }

    /**
     * Helper untuk generate nomor bukti otomatis.
     * BKM (Bukti Kas Masuk) / BKK (Bukti Kas Keluar)
     */
    public static function generateNomorBukti(string $tipe, $tanggal = null): string
    {
        $prefix = ($tipe === 'pemasukan') ? 'BKM' : 'BKK';
        $dateStr = $tanggal ? Carbon::parse($tanggal)->format('Ymd') : Carbon::now()->format('Ymd');
        $base = "{$prefix}-{$dateStr}-";

        // Cari nomor urut terakhir pada tanggal yang sama
        $last = self::withTrashed()
            ->where('nomor_bukti', 'like', "{$base}%")
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = 1;
        if ($last) {
            $parts = explode('-', $last->nomor_bukti);
            $lastSeq = end($parts);
            if (is_numeric($lastSeq)) {
                $nextNumber = (int) $lastSeq + 1;
            }
        }

        return sprintf('%s%04d', $base, $nextNumber);
    }
}
