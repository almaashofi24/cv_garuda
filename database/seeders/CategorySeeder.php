<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Pemasukan
            [
                'nama' => 'Penjualan Barang & Material',
                'tipe' => 'pemasukan',
                'deskripsi' => 'Hasil penjualan barang, material konstruksi, dan suplai proyek',
                'warna' => '#10b981',
            ],
            [
                'nama' => 'Jasa Konstruksi & Angkutan',
                'tipe' => 'pemasukan',
                'deskripsi' => 'Pendapatan jasa pengerjaan proyek dan ekspedisi angkutan armada',
                'warna' => '#06b6d4',
            ],
            [
                'nama' => 'Sewa Alat Berat & Kendaraan',
                'tipe' => 'pemasukan',
                'deskripsi' => 'Pemasukan dari rental alat berat atau dump truck',
                'warna' => '#3b82f6',
            ],
            [
                'nama' => 'Pendapatan Lain-lain',
                'tipe' => 'pemasukan',
                'deskripsi' => 'Pendapatan jasa non-operasional dan penerimaan lainnya',
                'warna' => '#8b5cf6',
            ],

            // Pengeluaran
            [
                'nama' => 'Gaji & Upah Karyawan',
                'tipe' => 'pengeluaran',
                'deskripsi' => 'Pembayaran upah tukang, sopir, dan gaji bulanan staf/karyawan',
                'warna' => '#ef4444',
            ],
            [
                'nama' => 'BBM & Transportasi',
                'tipe' => 'pengeluaran',
                'deskripsi' => 'Bahan bakar minyak (Solar/Pertalite) armada, tol, dan akomodasi',
                'warna' => '#f97316',
            ],
            [
                'nama' => 'Pembelian Material & Bahan Baku',
                'tipe' => 'pengeluaran',
                'deskripsi' => 'Belanja semen, pasir, batu, besi, dan material pengerjaan proyek',
                'warna' => '#f59e0b',
            ],
            [
                'nama' => 'Biaya Listrik, Air & Internet',
                'tipe' => 'pengeluaran',
                'deskripsi' => 'Tagihan utilitas bulanan kantor ruko dan mess karyawan',
                'warna' => '#eab308',
            ],
            [
                'nama' => 'Sewa Ruko & Gudang',
                'tipe' => 'pengeluaran',
                'deskripsi' => 'Biaya kontrak/sewa ruko Simpang Ampar dan gudang material',
                'warna' => '#ec4899',
            ],
            [
                'nama' => 'Servis & Pemeliharaan Armada',
                'tipe' => 'pengeluaran',
                'deskripsi' => 'Ganti oli, suku cadang, dan perbaikan mesin dump truck/alat berat',
                'warna' => '#6366f1',
            ],
            [
                'nama' => 'Konsumsi & Operasional Kantor',
                'tipe' => 'pengeluaran',
                'deskripsi' => 'ATK kantor, konsumsi harian lapangan, dan kebersihan',
                'warna' => '#14b8a6',
            ],
            [
                'nama' => 'Pajak & Perizinan Usaha',
                'tipe' => 'pengeluaran',
                'deskripsi' => 'Biaya retribusi daerah, izin usaha, dan pembayaran pajak',
                'warna' => '#64748b',
            ],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['nama' => $cat['nama'], 'tipe' => $cat['tipe']],
                ['deskripsi' => $cat['deskripsi'], 'warna' => $cat['warna']]
            );
        }
    }
}

