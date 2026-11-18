<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class CompanySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'tipe',
        'keterangan',
    ];

    /**
     * Ambil nilai pengaturan berdasarkan key.
     */
    public static function get(string $key, $default = null)
    {
        return Cache::remember("company_setting_{$key}", 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Simpan atau update nilai pengaturan.
     */
    public static function set(string $key, $value, ?string $keterangan = null, string $tipe = 'text'): self
    {
        Cache::forget("company_setting_{$key}");
        Cache::forget('company_settings_all');

        return self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'keterangan' => $keterangan,
                'tipe' => $tipe,
            ]
        );
    }

    /**
     * Ambil semua pengaturan dalam bentuk key => value array.
     */
    public static function getAllKeyed(): array
    {
        return Cache::remember('company_settings_all', 3600, function () {
            return self::pluck('value', 'key')->toArray();
        });
    }
}

