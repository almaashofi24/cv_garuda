<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\CompanySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanySettingController extends Controller
{
    /**
     * Tampilkan halaman pengaturan profil perusahaan & tanda tangan.
     */
    public function index()
    {
        $settings = CompanySetting::all()->keyBy('key');
        return view('settings.index', compact('settings'));
    }

    /**
     * Perbarui pengaturan perusahaan.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama_perusahaan' => ['required', 'string', 'max:150'],
            'alamat_perusahaan' => ['required', 'string', 'max:500'],
            'telepon' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:100'],
            'pimpinan_nama' => ['required', 'string', 'max:100'],
            'pimpinan_jabatan' => ['required', 'string', 'max:100'],
            'staf_keuangan_nama' => ['required', 'string', 'max:100'],
            'staf_keuangan_jabatan' => ['required', 'string', 'max:100'],
            'kota_laporan' => ['required', 'string', 'max:50'],
        ], [
            'nama_perusahaan.required' => 'Nama perusahaan wajib diisi.',
            'alamat_perusahaan.required' => 'Alamat perusahaan wajib diisi.',
            'pimpinan_nama.required' => 'Nama pimpinan wajib diisi.',
            'pimpinan_jabatan.required' => 'Jabatan pimpinan wajib diisi.',
            'staf_keuangan_nama.required' => 'Nama staf keuangan wajib diisi.',
            'staf_keuangan_jabatan.required' => 'Jabatan staf keuangan wajib diisi.',
            'kota_laporan.required' => 'Kota penerbitan laporan wajib diisi.',
        ]);

        foreach ($validated as $key => $value) {
            CompanySetting::set($key, $value);
        }

        ActivityLog::record(
            'ubah',
            'Pengaturan',
            'Memperbarui identitas dan pengaturan instansi perusahaan',
            Auth::id()
        );

        return back()->with('success', 'Identitas dan pengaturan instansi berhasil disimpan.');
    }
}

