<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@cvgaruda.com')->first();
        $staf = User::where('email', 'keuangan@cvgaruda.com')->first();
        $userId = $staf ? $staf->id : ($admin ? $admin->id : 1);

        $categories = Category::all()->groupBy('tipe');
        $catPemasukan = $categories->get('pemasukan');
        $catPengeluaran = $categories->get('pengeluaran');

        if (!$catPemasukan || !$catPengeluaran) {
            return;
        }

        // Data transaksi realistis sesuai 7 Golongan Akun CV. Garuda Duabelas
        $sampleData = [
            // Periode November 2026 (Sesuai contoh panduan akuntansi sistem)
            [
                'tipe' => 'pemasukan',
                'category_nama' => 'Jasa Konstruksi & Angkutan',
                'account_kode' => '410',
                'deskripsi' => 'Pendapatan Jasa Konstruksi & Angkutan Proyek',
                'jumlah' => 15000000,
                'metode' => 'transfer',
                'pihak' => 'PT. Borneo Mega Konstruksi',
                'hari' => 1,
                'bulan' => 11,
                'tahun' => 2026,
            ],
            [
                'tipe' => 'pengeluaran',
                'category_nama' => 'Sewa Alat Berat & Kendaraan',
                'account_kode' => '520',
                'deskripsi' => 'Bayar Sewa Kantor',
                'jumlah' => 2000000,
                'metode' => 'transfer',
                'pihak' => 'Pengelola Ruko Keling Kumang',
                'hari' => 1,
                'bulan' => 11,
                'tahun' => 2026,
            ],
            [
                'tipe' => 'pengeluaran',
                'category_nama' => 'Biaya Listrik, Air & Internet',
                'account_kode' => '530',
                'deskripsi' => 'Bayar Listrik',
                'jumlah' => 500000,
                'metode' => 'transfer',
                'pihak' => 'PLN Unit Tayan Hilir',
                'hari' => 2,
                'bulan' => 11,
                'tahun' => 2026,
            ],
            [
                'tipe' => 'pengeluaran',
                'category_nama' => 'Gaji & Upah Karyawan',
                'account_kode' => '510',
                'deskripsi' => 'Bayar gaji karyawan',
                'jumlah' => 5000000,
                'metode' => 'transfer',
                'pihak' => 'Karyawan Operasional',
                'hari' => 5,
                'bulan' => 11,
                'tahun' => 2026,
            ],

            // Periode Oktober 2026
            [
                'tipe' => 'pemasukan',
                'category_nama' => 'Penjualan Barang & Material',
                'deskripsi' => 'Pembayaran termin 1 suplai batu split proyek Jembatan Tayan',
                'jumlah' => 45000000,
                'metode' => 'transfer',
                'pihak' => 'PT. Borneo Mega Konstruksi',
                'hari' => 3,
                'bulan' => 10,
                'tahun' => 2026,
            ],
            [
                'tipe' => 'pengeluaran',
                'category_nama' => 'BBM & Transportasi',
                'deskripsi' => 'Pengisian solar industri armada dump truck 4 unit',
                'jumlah' => 7850000,
                'metode' => 'transfer',
                'pihak' => 'SPBU Tayan Hilir',
                'hari' => 5,
                'bulan' => 10,
                'tahun' => 2026,
            ],
            [
                'tipe' => 'pemasukan',
                'category_nama' => 'Jasa Konstruksi & Angkutan',
                'deskripsi' => 'Jasa angkutan material timbunan 60 rit ke Simpang Ampar',
                'jumlah' => 27000000,
                'metode' => 'transfer',
                'pihak' => 'CV. Kapuas Prima Jaya',
                'hari' => 10,
                'bulan' => 10,
                'tahun' => 2026,
            ],
            [
                'tipe' => 'pengeluaran',
                'category_nama' => 'Pembelian Material & Bahan Baku',
                'deskripsi' => 'Pembelian semen padang 300 sak & kawat bendrat',
                'jumlah' => 22500000,
                'metode' => 'transfer',
                'pihak' => 'Toko Bangunan Sumber Rezeki',
                'hari' => 12,
                'bulan' => 10,
                'tahun' => 2026,
            ],
            [
                'tipe' => 'pengeluaran',
                'category_nama' => 'Biaya Listrik, Air & Internet',
                'deskripsi' => 'Pembayaran tagihan PLN pascabayar kantor Ruko No.2 & wifi Indihome',
                'jumlah' => 1850000,
                'metode' => 'transfer',
                'pihak' => 'PLN & Telkom Indonesia',
                'hari' => 15,
                'bulan' => 10,
                'tahun' => 2026,
            ],
            [
                'tipe' => 'pemasukan',
                'category_nama' => 'Sewa Alat Berat & Kendaraan',
                'deskripsi' => 'Sewa excavator PC200 selama 50 jam kerja',
                'jumlah' => 18500000,
                'metode' => 'transfer',
                'pihak' => 'PT. Sawit Mandiri Lestari',
                'hari' => 18,
                'bulan' => 10,
                'tahun' => 2026,
            ],
            [
                'tipe' => 'pengeluaran',
                'category_nama' => 'Servis & Pemeliharaan Armada',
                'deskripsi' => 'Ganti oli mesin, filter oli & kampas rem Dump Truck KB 8922 XX',
                'jumlah' => 4600000,
                'metode' => 'tunai',
                'pihak' => 'Bengkel Mobil Berkah Tayan',
                'hari' => 20,
                'bulan' => 10,
                'tahun' => 2026,
            ],
            [
                'tipe' => 'pemasukan',
                'category_nama' => 'Penjualan Barang & Material',
                'deskripsi' => 'Penjualan pasir pasang 15 truk proyek drainase',
                'jumlah' => 13500000,
                'metode' => 'tunai',
                'pihak' => 'Bpk. Hendra Gunawan',
                'hari' => 24,
                'bulan' => 10,
                'tahun' => 2026,
            ],
            [
                'tipe' => 'pengeluaran',
                'category_nama' => 'Konsumsi & Operasional Kantor',
                'deskripsi' => 'Pembelian ATK, kertas F4, tinta printer Epson, dan air galon',
                'jumlah' => 950000,
                'metode' => 'tunai',
                'pihak' => 'Toko ATK Simpang Ampar',
                'hari' => 26,
                'bulan' => 10,
                'tahun' => 2026,
            ],
            [
                'tipe' => 'pengeluaran',
                'category_nama' => 'Gaji & Upah Karyawan',
                'deskripsi' => 'Pembayaran gaji bulanan 8 staf operasional dan pengemudi',
                'jumlah' => 32000000,
                'metode' => 'transfer',
                'pihak' => 'Karyawan CV. Garuda',
                'hari' => 28,
                'bulan' => 10,
                'tahun' => 2026,
            ],

            // Bulan Sebelumnya (September 2026)
            [
                'tipe' => 'pemasukan',
                'category_nama' => 'Penjualan Barang & Material',
                'deskripsi' => 'Penjualan material sirtu dan tanah urug 120 rit',
                'jumlah' => 58000000,
                'metode' => 'transfer',
                'pihak' => 'PT. Agrindo Jaya Abadi',
                'hari' => 6,
                'bulan' => 9,
                'tahun' => 2026,
            ],
            [
                'tipe' => 'pengeluaran',
                'category_nama' => 'Pembelian Material & Bahan Baku',
                'deskripsi' => 'Pembelian solar cell & perlengkapan instalasi',
                'jumlah' => 16500000,
                'metode' => 'transfer',
                'pihak' => 'CV. Elektro Sinar Kalbar',
                'hari' => 11,
                'bulan' => 9,
                'tahun' => 2026,
            ],
            [
                'tipe' => 'pemasukan',
                'category_nama' => 'Jasa Konstruksi & Angkutan',
                'deskripsi' => 'Pelunasan jasa perataan tanah lahan ruko Simpang Ampar',
                'jumlah' => 34000000,
                'metode' => 'transfer',
                'pihak' => 'H. Anshori Sanggau',
                'hari' => 20,
                'bulan' => 9,
                'tahun' => 2026,
            ],
            [
                'tipe' => 'pengeluaran',
                'category_nama' => 'Gaji & Upah Karyawan',
                'deskripsi' => 'Gaji bulanan dan upah lembur karyawan periode September',
                'jumlah' => 31500000,
                'metode' => 'transfer',
                'pihak' => 'Karyawan CV. Garuda',
                'hari' => 28,
                'bulan' => 9,
                'tahun' => 2026,
            ],
            [
                'tipe' => 'pengeluaran',
                'category_nama' => 'Sewa Ruko & Gudang',
                'deskripsi' => 'Pembayaran cicilan sewa ruko kantor Keling Kumang No.2',
                'jumlah' => 15000000,
                'metode' => 'transfer',
                'pihak' => 'Pengelola Ruko Keling Kumang',
                'hari' => 15,
                'bulan' => 9,
                'tahun' => 2026,
            ],

            // Bulan Agustus 2026
            [
                'tipe' => 'pemasukan',
                'category_nama' => 'Penjualan Barang & Material',
                'deskripsi' => 'Pengadaan batu cor beton K-250 proyek saluran',
                'jumlah' => 62000000,
                'metode' => 'transfer',
                'pihak' => 'PT. Kalbar Prima Kontraktor',
                'hari' => 8,
                'bulan' => 8,
                'tahun' => 2026,
            ],
            [
                'tipe' => 'pengeluaran',
                'category_nama' => 'BBM & Transportasi',
                'deskripsi' => 'Solar industri armada operasional bulan Agustus',
                'jumlah' => 9200000,
                'metode' => 'transfer',
                'pihak' => 'SPBU Tayan Hilir',
                'hari' => 14,
                'bulan' => 8,
                'tahun' => 2026,
            ],
            [
                'tipe' => 'pengeluaran',
                'category_nama' => 'Gaji & Upah Karyawan',
                'deskripsi' => 'Gaji bulanan karyawan periode Agustus',
                'jumlah' => 31000000,
                'metode' => 'transfer',
                'pihak' => 'Karyawan CV. Garuda',
                'hari' => 28,
                'bulan' => 8,
                'tahun' => 2026,
            ],

            // Bulan Juli 2026
            [
                'tipe' => 'pemasukan',
                'category_nama' => 'Jasa Konstruksi & Angkutan',
                'deskripsi' => 'Pengerjaan subkon perkerasan jalan akses perkebunan',
                'jumlah' => 75000000,
                'metode' => 'transfer',
                'pihak' => 'PT. Tayan Sawit Makmur',
                'hari' => 12,
                'bulan' => 7,
                'tahun' => 2026,
            ],
            [
                'tipe' => 'pengeluaran',
                'category_nama' => 'Pembelian Material & Bahan Baku',
                'deskripsi' => 'Batu pecah 3/5 dan sirtu 15 tronton',
                'jumlah' => 28000000,
                'metode' => 'transfer',
                'pihak' => 'Quarry Batu Mandiri Sanggau',
                'hari' => 17,
                'bulan' => 7,
                'tahun' => 2026,
            ],
            [
                'tipe' => 'pengeluaran',
                'category_nama' => 'Gaji & Upah Karyawan',
                'deskripsi' => 'Gaji bulanan karyawan periode Juli',
                'jumlah' => 31000000,
                'metode' => 'transfer',
                'pihak' => 'Karyawan CV. Garuda',
                'hari' => 28,
                'bulan' => 7,
                'tahun' => 2026,
            ],

            // Bulan Juni 2026
            [
                'tipe' => 'pemasukan',
                'category_nama' => 'Sewa Alat Berat & Kendaraan',
                'deskripsi' => 'Rental vibro roller & grader pengerjaan jalan',
                'jumlah' => 42000000,
                'metode' => 'transfer',
                'pihak' => 'Dinas PU Subkon Sanggau',
                'hari' => 14,
                'bulan' => 6,
                'tahun' => 2026,
            ],
            [
                'tipe' => 'pengeluaran',
                'category_nama' => 'Servis & Pemeliharaan Armada',
                'deskripsi' => 'Perbaikan hidrolik excavator dan ganti ban luar dump truck',
                'jumlah' => 8750000,
                'metode' => 'transfer',
                'pihak' => 'CV. Sinar Hidrolik Pontianak',
                'hari' => 22,
                'bulan' => 6,
                'tahun' => 2026,
            ],
            [
                'tipe' => 'pengeluaran',
                'category_nama' => 'Gaji & Upah Karyawan',
                'deskripsi' => 'Gaji karyawan dan bonus proyek Juni',
                'jumlah' => 34500000,
                'metode' => 'transfer',
                'pihak' => 'Karyawan CV. Garuda',
                'hari' => 28,
                'bulan' => 6,
                'tahun' => 2026,
            ],

            // Bulan Mei 2026
            [
                'tipe' => 'pemasukan',
                'category_nama' => 'Penjualan Barang & Material',
                'deskripsi' => 'Pengadaan gorong-gorong beton dia 80cm 40 unit',
                'jumlah' => 38000000,
                'metode' => 'transfer',
                'pihak' => 'PT. Borneo Mega Konstruksi',
                'hari' => 10,
                'bulan' => 5,
                'tahun' => 2026,
            ],
            [
                'tipe' => 'pengeluaran',
                'category_nama' => 'Pajak & Perizinan Usaha',
                'deskripsi' => 'Pembayaran PPh Final pasal 4 ayat 2 dan retribusi daerah',
                'jumlah' => 5200000,
                'metode' => 'transfer',
                'pihak' => 'KPP Pratama Sanggau',
                'hari' => 19,
                'bulan' => 5,
                'tahun' => 2026,
            ],
            [
                'tipe' => 'pengeluaran',
                'category_nama' => 'Gaji & Upah Karyawan',
                'deskripsi' => 'Gaji bulanan karyawan periode Mei',
                'jumlah' => 30500000,
                'metode' => 'transfer',
                'pihak' => 'Karyawan CV. Garuda',
                'hari' => 28,
                'bulan' => 5,
                'tahun' => 2026,
            ],

            // Bulan April 2026
            [
                'tipe' => 'pemasukan',
                'category_nama' => 'Penjualan Barang & Material',
                'deskripsi' => 'Penjualan besi beton ulir 12mm & 16mm proyek ruko',
                'jumlah' => 49000000,
                'metode' => 'transfer',
                'pihak' => 'Bpk. Yusuf Sanjaya',
                'hari' => 15,
                'bulan' => 4,
                'tahun' => 2026,
            ],
            [
                'tipe' => 'pengeluaran',
                'category_nama' => 'Gaji & Upah Karyawan',
                'deskripsi' => 'Gaji bulanan + THR Hari Raya Idul Fitri',
                'jumlah' => 58000000,
                'metode' => 'transfer',
                'pihak' => 'Karyawan CV. Garuda',
                'hari' => 25,
                'bulan' => 4,
                'tahun' => 2026,
            ],

            // Bulan Maret 2026
            [
                'tipe' => 'pemasukan',
                'category_nama' => 'Jasa Konstruksi & Angkutan',
                'deskripsi' => 'Jasa langsir material sawit perkebunan Tayan Hilir',
                'jumlah' => 36000000,
                'metode' => 'transfer',
                'pihak' => 'Koperasi Sawit Keling Kumang',
                'hari' => 18,
                'bulan' => 3,
                'tahun' => 2026,
            ],
            [
                'tipe' => 'pengeluaran',
                'category_nama' => 'BBM & Transportasi',
                'deskripsi' => 'Solar operasional armada dump truck',
                'jumlah' => 8400000,
                'metode' => 'transfer',
                'pihak' => 'SPBU Tayan Hilir',
                'hari' => 21,
                'bulan' => 3,
                'tahun' => 2026,
            ],
            [
                'tipe' => 'pengeluaran',
                'category_nama' => 'Gaji & Upah Karyawan',
                'deskripsi' => 'Gaji karyawan periode Maret',
                'jumlah' => 30500000,
                'metode' => 'transfer',
                'pihak' => 'Karyawan CV. Garuda',
                'hari' => 28,
                'bulan' => 3,
                'tahun' => 2026,
            ],

            // Bulan Februari 2026
            [
                'tipe' => 'pemasukan',
                'category_nama' => 'Penjualan Barang & Material',
                'deskripsi' => 'Penjualan semen dan pasir cor 80 sak',
                'jumlah' => 29000000,
                'metode' => 'tunai',
                'pihak' => 'Kontraktor Perumahan Graha Tayan',
                'hari' => 14,
                'bulan' => 2,
                'tahun' => 2026,
            ],
            [
                'tipe' => 'pengeluaran',
                'category_nama' => 'Gaji & Upah Karyawan',
                'deskripsi' => 'Gaji karyawan periode Februari',
                'jumlah' => 30000000,
                'metode' => 'transfer',
                'pihak' => 'Karyawan CV. Garuda',
                'hari' => 28,
                'bulan' => 2,
                'tahun' => 2026,
            ],

            // Bulan Januari 2026
            [
                'tipe' => 'pemasukan',
                'category_nama' => 'Jasa Konstruksi & Angkutan',
                'deskripsi' => 'Pekerjaan pondasi awal pagar keliling workshop',
                'jumlah' => 41000000,
                'metode' => 'transfer',
                'pihak' => 'PT. Tayan Sawit Makmur',
                'hari' => 16,
                'bulan' => 1,
                'tahun' => 2026,
            ],
            [
                'tipe' => 'pengeluaran',
                'category_nama' => 'Gaji & Upah Karyawan',
                'deskripsi' => 'Gaji karyawan periode Januari',
                'jumlah' => 30000000,
                'metode' => 'transfer',
                'pihak' => 'Karyawan CV. Garuda',
                'hari' => 28,
                'bulan' => 1,
                'tahun' => 2026,
            ],

            // Bulan Desember 2025
            [
                'tipe' => 'pemasukan',
                'category_nama' => 'Penjualan Barang & Material',
                'deskripsi' => 'Pelunasan supply gorong-gorong drainase Trans Kalimantan',
                'jumlah' => 52000000,
                'metode' => 'transfer',
                'pihak' => 'PT. Borneo Mega Konstruksi',
                'hari' => 20,
                'bulan' => 12,
                'tahun' => 2025,
            ],
            [
                'tipe' => 'pengeluaran',
                'category_nama' => 'Gaji & Upah Karyawan',
                'deskripsi' => 'Gaji dan insentif akhir tahun 2025',
                'jumlah' => 36000000,
                'metode' => 'transfer',
                'pihak' => 'Karyawan CV. Garuda',
                'hari' => 28,
                'bulan' => 12,
                'tahun' => 2025,
            ],

            // Bulan November 2025
            [
                'tipe' => 'pemasukan',
                'category_nama' => 'Sewa Alat Berat & Kendaraan',
                'deskripsi' => 'Sewa dump truck hino 10 roda 20 hari kerja',
                'jumlah' => 35000000,
                'metode' => 'transfer',
                'pihak' => 'CV. Kapuas Prima Jaya',
                'hari' => 12,
                'bulan' => 11,
                'tahun' => 2025,
            ],
            [
                'tipe' => 'pengeluaran',
                'category_nama' => 'Gaji & Upah Karyawan',
                'deskripsi' => 'Gaji karyawan periode November',
                'jumlah' => 30000000,
                'metode' => 'transfer',
                'pihak' => 'Karyawan CV. Garuda',
                'hari' => 28,
                'bulan' => 11,
                'tahun' => 2025,
            ],
        ];

        $counterPerDate = [];

        foreach ($sampleData as $item) {
            $tgl = Carbon::create($item['tahun'], $item['bulan'], $item['hari'])->format('Y-m-d');
            $tipe = $item['tipe'];
            $prefix = ($tipe === 'pemasukan') ? 'BKM' : 'BKK';
            $dateStr = Carbon::parse($tgl)->format('Ymd');
            $key = "{$prefix}-{$dateStr}";

            if (!isset($counterPerDate[$key])) {
                $counterPerDate[$key] = 1;
            } else {
                $counterPerDate[$key]++;
            }

            $nomorBukti = sprintf('%s-%04d', $key, $counterPerDate[$key]);

            $cat = Category::where('nama', $item['category_nama'])
                ->where('tipe', $tipe)
                ->first();

            if (!$cat) {
                $cat = Category::where('tipe', $tipe)->first();
            }

            // Cari Akun 7 Golongan yang cocok
            $accountId = null;
            if (!empty($item['account_kode'])) {
                $acc = \App\Models\Account::where('kode', $item['account_kode'])->first();
                $accountId = $acc?->id;
            }

            if (!$accountId) {
                if ($tipe === 'pemasukan') {
                    $acc = \App\Models\Account::where('kode', '410')->first() ?: \App\Models\Account::pendapatan()->first();
                } else {
                    $desc = strtolower($item['deskripsi'] . ' ' . $item['category_nama']);
                    if (str_contains($desc, 'gaji') || str_contains($desc, 'upah')) {
                        $acc = \App\Models\Account::where('kode', '510')->first();
                    } elseif (str_contains($desc, 'sewa')) {
                        $acc = \App\Models\Account::where('kode', '520')->first();
                    } elseif (str_contains($desc, 'listrik') || str_contains($desc, 'air') || str_contains($desc, 'internet')) {
                        $acc = \App\Models\Account::where('kode', '530')->first();
                    } elseif (str_contains($desc, 'bbm') || str_contains($desc, 'solar') || str_contains($desc, 'transport') || str_contains($desc, 'armada') || str_contains($desc, 'bengkel') || str_contains($desc, 'oli')) {
                        $acc = \App\Models\Account::where('kode', '550')->first();
                    } else {
                        $acc = \App\Models\Account::where('kode', '540')->first() ?: \App\Models\Account::beban()->first();
                    }
                }
                $accountId = $acc?->id;
            }

            Transaction::create([
                'nomor_bukti' => $nomorBukti,
                'tanggal' => $tgl,
                'tipe' => $tipe,
                'category_id' => $cat->id,
                'account_id' => $accountId,
                'deskripsi' => $item['deskripsi'],
                'jumlah' => $item['jumlah'],
                'metode_pembayaran' => $item['metode'],
                'pihak_terkait' => $item['pihak'],
                'lampiran' => null,
                'user_id' => $userId,
            ]);
        }

        // Catat beberapa log awal
        ActivityLog::record('login', 'User', 'Admin melakukan login ke sistem', $admin ? $admin->id : 1);
        ActivityLog::record('tambah', 'Kategori', 'Inisialisasi kategori pemasukan & pengeluaran default', $admin ? $admin->id : 1);
        ActivityLog::record('tambah', 'Transaksi', 'Input data transaksi awal CV. Garuda Raja Perkasa Duabelas', $userId);
    }
}

