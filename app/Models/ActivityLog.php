<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'aksi',
        'model',
        'detail',
        'ip_address',
        'user_agent',
    ];

    /**
     * Relasi ke user pelaksana aksi.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Format tanggal dan jam WIB.
     */
    public function getFormattedCreatedAtAttribute(): string
    {
        return $this->created_at ? Carbon::parse($this->created_at)->format('d/m/Y H:i:s') : '-';
    }

    /**
     * Helper untuk mencatat log aktivitas secara konsisten.
     */
    public static function record(string $aksi, string $model, string $detail, ?int $userId = null): self
    {
        return self::create([
            'user_id' => $userId ?? Auth::id(),
            'aksi' => $aksi,
            'model' => $model,
            'detail' => $detail,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}

