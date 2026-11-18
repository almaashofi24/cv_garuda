<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            // 1. Harta (Kode Golongan: 1-100, Saldo Normal: Debit)
            [
                'kode' => '110',
                'golongan_kode' => '1-100',
                'golongan' => '1. Harta',
                'nama' => 'Kas',
                'saldo_normal' => 'Debit',
                'saldo_awal' => 35000000,
                'deskripsi' => 'Kas operasional dan rekening bank utama perusahaan',
                'is_locked' => true,
            ],
            [
                'kode' => '120',
                'golongan_kode' => '1-100',
                'golongan' => '1. Harta',
                'nama' => 'Piutang Usaha',
                'saldo_normal' => 'Debit',
                'saldo_awal' => 15000000,
                'deskripsi' => 'Tagihan termin proyek dan piutang pelanggan',
                'is_locked' => false,
            ],
            [
                'kode' => '130',
                'golongan_kode' => '1-100',
                'golongan' => '1. Harta',
                'nama' => 'Perlengkapan',
                'saldo_normal' => 'Debit',
                'saldo_awal' => 5000000,
                'deskripsi' => 'Stok perlengkapan kantor, material habis pakai, dan ATK',
                'is_locked' => false,
            ],
            [
                'kode' => '140',
                'golongan_kode' => '1-100',
                'golongan' => '1. Harta',
                'nama' => 'Peralatan',
                'saldo_normal' => 'Debit',
                'saldo_awal' => 45000000,
                'deskripsi' => 'Peralatan proyek, mesin, armada dump truck, dan inventaris kantor',
                'is_locked' => false,
            ],

            // 2. Utang (Kode Golongan: 2-100, Saldo Normal: Kredit)
            [
                'kode' => '210',
                'golongan_kode' => '2-100',
                'golongan' => '2. Utang',
                'nama' => 'Utang Usaha',
                'saldo_normal' => 'Kredit',
                'saldo_awal' => 10000000,
                'deskripsi' => 'Kewajiban kepada vendor material dan pihak ketiga',
                'is_locked' => false,
            ],
            [
                'kode' => '220',
                'golongan_kode' => '2-100',
                'golongan' => '2. Utang',
                'nama' => 'Utang Bank',
                'saldo_normal' => 'Kredit',
                'saldo_awal' => 15000000,
                'deskripsi' => 'Pinjaman modal usaha lembaga perbankan',
                'is_locked' => false,
            ],
            [
                'kode' => '230',
                'golongan_kode' => '2-100',
                'golongan' => '2. Utang',
                'nama' => 'Utang Gaji',
                'saldo_normal' => 'Kredit',
                'saldo_awal' => 0,
                'deskripsi' => 'Kewajiban upah dan tunjangan karyawan terutang',
                'is_locked' => false,
            ],

            // 3. Modal (Kode Golongan: 3-100, Saldo Normal: Kredit)
            [
                'kode' => '310',
                'golongan_kode' => '3-100',
                'golongan' => '3. Modal',
                'nama' => 'Modal Pemilik',
                'saldo_normal' => 'Kredit',
                'saldo_awal' => 75000000,
                'deskripsi' => 'Modal disetor pemilik CV. Garuda Raja Perkasa Duabelas',
                'is_locked' => true,
            ],
            [
                'kode' => '320',
                'golongan_kode' => '3-100',
                'golongan' => '3. Modal',
                'nama' => 'Modal Tambahan',
                'saldo_normal' => 'Kredit',
                'saldo_awal' => 0,
                'deskripsi' => 'Tambahan modal dan cadangan ekuitas perusahaan',
                'is_locked' => false,
            ],

            // 4. Pendapatan (Kode Golongan: 4-100, Saldo Normal: Kredit)
            [
                'kode' => '410',
                'golongan_kode' => '4-100',
                'golongan' => '4. Pendapatan',
                'nama' => 'Pendapatan Jasa',
                'saldo_normal' => 'Kredit',
                'saldo_awal' => 0,
                'deskripsi' => 'Pendapatan jasa konstruksi, angkutan material, dan sewa alat berat',
                'is_locked' => false,
            ],
            [
                'kode' => '420',
                'golongan_kode' => '4-100',
                'golongan' => '4. Pendapatan',
                'nama' => 'Pendapatan Lain-lain',
                'saldo_normal' => 'Kredit',
                'saldo_awal' => 0,
                'deskripsi' => 'Pendapatan operasional lainnya dan jasa non-konstruksi',
                'is_locked' => false,
            ],

            // 5. Beban (Kode Golongan: 5-100, Saldo Normal: Debit)
            [
                'kode' => '510',
                'golongan_kode' => '5-100',
                'golongan' => '5. Beban',
                'nama' => 'Beban Gaji',
                'saldo_normal' => 'Debit',
                'saldo_awal' => 0,
                'deskripsi' => 'Gaji, upah harian tukang/sopir, dan insentif karyawan',
                'is_locked' => false,
            ],
            [
                'kode' => '520',
                'golongan_kode' => '5-100',
                'golongan' => '5. Beban',
                'nama' => 'Beban Sewa',
                'saldo_normal' => 'Debit',
                'saldo_awal' => 0,
                'deskripsi' => 'Sewa ruko kantor, gudang penyimpanan, dan lahan parkir alat berat',
                'is_locked' => false,
            ],
            [
                'kode' => '530',
                'golongan_kode' => '5-100',
                'golongan' => '5. Beban',
                'nama' => 'Beban Listrik',
                'saldo_normal' => 'Debit',
                'saldo_awal' => 0,
                'deskripsi' => 'Tagihan listrik kantor, air PDAM, dan internet operasional',
                'is_locked' => false,
            ],
            [
                'kode' => '540',
                'golongan_kode' => '5-100',
                'golongan' => '5. Beban',
                'nama' => 'Beban Perlengkapan',
                'saldo_normal' => 'Debit',
                'saldo_awal' => 0,
                'deskripsi' => 'Pemakaian perlengkapan kerja dan bahan penunjang operasional',
                'is_locked' => false,
            ],
            [
                'kode' => '550',
                'golongan_kode' => '5-100',
                'golongan' => '5. Beban',
                'nama' => 'Beban Transport',
                'saldo_normal' => 'Debit',
                'saldo_awal' => 0,
                'deskripsi' => 'Biaya BBM solar armada truk, tol, oli, dan perawatan kendaraan operasional',
                'is_locked' => false,
            ],

            // 6. Prive (Kode Golongan: 6-100, Saldo Normal: Debit)
            [
                'kode' => '610',
                'golongan_kode' => '6-100',
                'golongan' => '6. Prive',
                'nama' => 'Prive Pemilik',
                'saldo_normal' => 'Debit',
                'saldo_awal' => 0,
                'deskripsi' => 'Pengambilan kas oleh pemilik perusahaan untuk keperluan pribadi',
                'is_locked' => false,
            ],

            // 7. Ikhtisar Laba Rugi (Kode Golongan: 7-100, Saldo Normal: Penutup)
            [
                'kode' => '710',
                'golongan_kode' => '7-100',
                'golongan' => '7. Ikhtisar Laba Rugi',
                'nama' => 'Ikhtisar Laba Rugi',
                'saldo_normal' => 'Penutup',
                'saldo_awal' => 0,
                'deskripsi' => 'Akun perantara penutupan pendapatan dan beban di akhir periode',
                'is_locked' => true,
            ],
        ];

        foreach ($accounts as $acc) {
            Account::updateOrCreate(
                ['kode' => $acc['kode']],
                $acc
            );
        }

        // Tautkan transaksi yang sudah ada sebelumnya ke akun yang sesuai dan sinkronkan jurnalnya
        $trans = Transaction::all();
        $accJasa = Account::where('kode', '410')->first();
        $accLain = Account::where('kode', '420')->first();
        $accGaji = Account::where('kode', '510')->first();
        $accSewa = Account::where('kode', '520')->first();
        $accListrik = Account::where('kode', '530')->first();
        $accPerlengkapan = Account::where('kode', '540')->first();
        $accTransport = Account::where('kode', '550')->first();

        foreach ($trans as $t) {
            if (!$t->account_id) {
                if ($t->tipe === 'pemasukan') {
                    $desc = strtolower($t->deskripsi . ' ' . ($t->category->nama ?? ''));
                    if (str_contains($desc, 'jasa') || str_contains($desc, 'sewa') || str_contains($desc, 'proyek') || str_contains($desc, 'angkutan')) {
                        $t->account_id = $accJasa?->id;
                    } else {
                        $t->account_id = $accLain?->id ?: $accJasa?->id;
                    }
                } else {
                    $desc = strtolower($t->deskripsi . ' ' . ($t->category->nama ?? ''));
                    if (str_contains($desc, 'gaji') || str_contains($desc, 'upah')) {
                        $t->account_id = $accGaji?->id;
                    } elseif (str_contains($desc, 'sewa')) {
                        $t->account_id = $accSewa?->id;
                    } elseif (str_contains($desc, 'listrik') || str_contains($desc, 'pdam') || str_contains($desc, 'internet')) {
                        $t->account_id = $accListrik?->id;
                    } elseif (str_contains($desc, 'bbm') || str_contains($desc, 'solar') || str_contains($desc, 'transport') || str_contains($desc, 'oli')) {
                        $t->account_id = $accTransport?->id;
                    } else {
                        $t->account_id = $accPerlengkapan?->id ?: $accTransport?->id;
                    }
                }
                $t->save();
            } else {
                $t->syncJournalEntries();
            }
        }
    }
}
