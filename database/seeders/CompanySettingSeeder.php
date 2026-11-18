<?php

namespace Database\Seeders;

use App\Models\CompanySetting;
use Illuminate\Database\Seeder;

class CompanySettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'nama_perusahaan',
                'value' => 'CV. GARUDA RAJA PERKASA DUABELAS',
                'tipe' => 'text',
                'keterangan' => 'Nama resmi perusahaan yang tertera di seluruh sistem dan kop surat.',
            ],
            [
                'key' => 'alamat_perusahaan',
                'value' => 'Jl. Trans Kalimantan Ruko Keling Kumang No.2, Simpang Ampar, Kec. Tayan Hilir, Kabupaten Sanggau, Kalimantan Barat 78564',
                'tipe' => 'textarea',
                'keterangan' => 'Alamat lengkap kantor operasional perusahaan.',
            ],
            [
                'key' => 'telepon',
                'value' => '+62 852-4500-1212',
                'tipe' => 'text',
                'keterangan' => 'Nomor telepon/WhatsApp kantor.',
            ],
            [
                'key' => 'email',
                'value' => 'kontak@garudaraja.co.id',
                'tipe' => 'email',
                'keterangan' => 'Alamat email korespondensi resmi.',
            ],
            [
                'key' => 'pimpinan_nama',
                'value' => 'H. Sudirman, S.E.',
                'tipe' => 'text',
                'keterangan' => 'Nama direktur/pimpinan yang menandatangani laporan keuangan.',
            ],
            [
                'key' => 'pimpinan_jabatan',
                'value' => 'Direktur Utama',
                'tipe' => 'text',
                'keterangan' => 'Jabatan penandatangan laporan (misal: Direktur Utama).',
            ],
            [
                'key' => 'staf_keuangan_nama',
                'value' => 'Siti Aminah, A.Md.',
                'tipe' => 'text',
                'keterangan' => 'Nama staf/bendahara penyusun laporan keuangan.',
            ],
            [
                'key' => 'staf_keuangan_jabatan',
                'value' => 'Staf Keuangan & Administrasi',
                'tipe' => 'text',
                'keterangan' => 'Jabatan penyusun laporan.',
            ],
            [
                'key' => 'kota_laporan',
                'value' => 'Sanggau',
                'tipe' => 'text',
                'keterangan' => 'Kota penerbitan dokumen laporan pada kop tanda tangan.',
            ],
        ];

        foreach ($settings as $item) {
            CompanySetting::updateOrCreate(
                ['key' => $item['key']],
                [
                    'value' => $item['value'],
                    'tipe' => $item['tipe'],
                    'keterangan' => $item['keterangan'],
                ]
            );
        }
    }
}

