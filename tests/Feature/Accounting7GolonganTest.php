<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class Accounting7GolonganTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        if (User::where('email', 'admin@cvgaruda.com')->doesntExist() || Account::count() < 7) {
            $this->seed();
        }
    }

    public function test_dashboard_displays_accounting_metrics(): void
    {
        $admin = User::where('email', 'admin@cvgaruda.com')->first();

        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Total Kas');
        $response->assertSee('Total Piutang');
        $response->assertSee('Total Utang');
        $response->assertSee('Laba Bulan Ini');
        $response->assertSee('Grafik Pengeluaran');
        $response->assertSee('10 Transaksi Terbaru');
    }

    public function test_accounts_master_page_shows_7_golongan(): void
    {
        $admin = User::where('email', 'admin@cvgaruda.com')->first();

        $response = $this->actingAs($admin)->get('/akun');
        $response->assertStatus(200);
        $response->assertSee('1. Harta');
        $response->assertSee('2. Utang');
        $response->assertSee('3. Modal');
        $response->assertSee('4. Pendapatan');
        $response->assertSee('5. Beban');
        $response->assertSee('6. Prive');
        $response->assertSee('7. Ikhtisar Laba Rugi');

        // Memastikan akun standar CV Garuda terdaftar
        $response->assertSee('110');
        $response->assertSee('Kas');
        $response->assertSee('120');
        $response->assertSee('Piutang Usaha');
        $response->assertSee('510');
        $response->assertSee('Beban Gaji');
    }

    public function test_new_account_can_be_created(): void
    {
        $admin = User::where('email', 'admin@cvgaruda.com')->first();

        $response = $this->actingAs($admin)->post('/akun/simpan', [
            'kode' => '560',
            'golongan' => '5. Beban',
            'nama' => 'Beban Pemeliharaan Armada',
            'saldo_normal' => 'Debit',
            'saldo_awal' => 0,
            'deskripsi' => 'Biaya suku cadang dan bengkel truk',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('accounts', [
            'kode' => '560',
            'nama' => 'Beban Pemeliharaan Armada',
            'golongan' => '5. Beban',
        ]);
    }

    public function test_input_transaction_7_golongan_creates_double_entry_journal(): void
    {
        $admin = User::where('email', 'admin@cvgaruda.com')->first();
        $bebanGaji = Account::where('kode', '510')->first();
        $kas = Account::where('kode', '110')->first();

        $this->assertNotNull($bebanGaji);
        $this->assertNotNull($kas);

        $response = $this->actingAs($admin)->post('/transaksi/simpan', [
            'tanggal' => Carbon::now()->format('Y-m-d'),
            'deskripsi' => 'Bayar gaji karyawan operasional',
            'tipe' => 'pengeluaran',
            'account_id' => $bebanGaji->id,
            'jumlah' => 5000000,
            'metode_pembayaran' => 'transfer',
            'pihak_terkait' => 'Karyawan',
        ]);

        $response->assertRedirect(route('transaksi.pengeluaran'));

        // Cek entri jurnal ganda (Debit Beban Gaji, Kredit Kas)
        $tx = Transaction::where('deskripsi', 'Bayar gaji karyawan operasional')->latest('id')->first();
        $this->assertNotNull($tx);

        $journalDebit = JournalEntry::where('transaction_id', $tx->id)
            ->where('account_id', $bebanGaji->id)
            ->where('debit', 5000000)
            ->where('kredit', 0)
            ->first();
        $this->assertNotNull($journalDebit);

        $journalKredit = JournalEntry::where('transaction_id', $tx->id)
            ->where('account_id', $kas->id)
            ->where('debit', 0)
            ->where('kredit', 5000000)
            ->first();
        $this->assertNotNull($journalKredit);
    }

    public function test_general_ledger_can_be_viewed_and_exported(): void
    {
        $admin = User::where('email', 'admin@cvgaruda.com')->first();

        // 1. View Buku Besar
        $response = $this->actingAs($admin)->get('/buku-besar');
        $response->assertStatus(200);
        $response->assertSee('Buku Besar');
        $response->assertSee('Saldo Berjalan');
        $response->assertSee('Mutasi Debit');
        $response->assertSee('Mutasi Kredit');

        // 2. Ekspor PDF
        $resPdf = $this->actingAs($admin)->get('/buku-besar/ekspor/pdf');
        $resPdf->assertStatus(200);
        $this->assertEquals('application/pdf', $resPdf->headers->get('content-type'));

        // 3. Ekspor Excel
        $resExcel = $this->actingAs($admin)->get('/buku-besar/ekspor/excel');
        $resExcel->assertStatus(200);
    }

    public function test_laporan_pengeluaran_can_be_viewed_and_exported(): void
    {
        $admin = User::where('email', 'admin@cvgaruda.com')->first();

        // 1. View Laporan Pengeluaran
        $response = $this->actingAs($admin)->get('/pengeluaran');
        $response->assertStatus(200);
        $response->assertSee('Laporan Pengeluaran');
        $response->assertSee('Total Pengeluaran');
        $response->assertSee('Cetak PDF');
        $response->assertSee('Download Excel');

        // 2. Ekspor PDF
        $resPdf = $this->actingAs($admin)->get('/pengeluaran/ekspor/pdf');
        $resPdf->assertStatus(200);
        $this->assertEquals('application/pdf', $resPdf->headers->get('content-type'));

        // 3. Ekspor Excel
        $resExcel = $this->actingAs($admin)->get('/pengeluaran/ekspor/excel');
        $resExcel->assertStatus(200);
    }

    public function test_laporan_laba_rugi_shows_format(): void
    {
        $admin = User::where('email', 'admin@cvgaruda.com')->first();

        // 1. Tambah pendapatan signifikan agar Laba Bersih positif
        $pendapatanAkun = Account::where('kode', '410')->first();
        Transaction::create([
            'nomor_bukti' => 'TRX-TEST-REV-01',
            'tanggal' => '2026-11-20',
            'tipe' => 'pemasukan',
            'category_id' => 1,
            'account_id' => $pendapatanAkun->id,
            'deskripsi' => 'Pendapatan Proyek Uji Laba',
            'jumlah' => 100000000,
            'metode_pembayaran' => 'transfer',
            'pihak_terkait' => 'Klien Uji',
            'user_id' => $admin->id,
        ]);

        $response = $this->actingAs($admin)->get('/laporan/laba-rugi?jenis_periode=bulanan&bulan=11&tahun=2026');
        $response->assertStatus(200);
        $response->assertSee('Laporan Laba Rugi');
        $response->assertSee('Pendapatan:');
        $response->assertSee('Total Pendapatan');
        $response->assertSee('Beban / Pengeluaran:');
        $response->assertSee('Total Beban');
        $response->assertSee('Laba Bersih');
    }

    public function test_neraca_balance_sheet_can_be_viewed_and_exported(): void
    {
        $admin = User::where('email', 'admin@cvgaruda.com')->first();

        // 1. View Neraca
        $response = $this->actingAs($admin)->get('/neraca');
        $response->assertStatus(200);
        $response->assertSee('Neraca Keuangan');
        $response->assertSee('1. HARTA');
        $response->assertSee('2. UTANG / KEWAJIBAN');
        $response->assertSee('3. MODAL');
        $response->assertSee('TOTAL HARTA (AKTIVA)');
        $response->assertSee('TOTAL KEWAJIBAN & MODAL (PASIVA)', false);

        // 2. Ekspor PDF
        $resPdf = $this->actingAs($admin)->get('/neraca/ekspor/pdf');
        $resPdf->assertStatus(200);
        $this->assertEquals('application/pdf', $resPdf->headers->get('content-type'));

        // 3. Ekspor Excel
        $resExcel = $this->actingAs($admin)->get('/neraca/ekspor/excel');
        $resExcel->assertStatus(200);
    }
}
