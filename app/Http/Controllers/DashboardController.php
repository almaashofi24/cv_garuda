<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Category;
use App\Models\CompanySetting;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman ringkasan Dashboard dengan metrik 7 Golongan Akuntansi.
     */
    public function index(Request $request)
    {
        $now = Carbon::now();
        $currentMonth = $now->month;
        $currentYear = $now->year;

        // 1. Metrik Akuntansi 7 Golongan
        // Total Kas (Akun 110)
        $kasAccount = Account::where('kode', '110')->first();
        $totalKas = $kasAccount ? $kasAccount->getSaldo() : 0;

        // Total Piutang (Akun 120)
        $piutangAccount = Account::where('kode', '120')->first();
        $totalPiutang = $piutangAccount ? $piutangAccount->getSaldo() : 0;

        // Total Utang (Akun Golongan 2. Utang: 210, 220, 230)
        $utangAccounts = Account::utang()->get();
        $totalUtang = $utangAccounts->sum(fn($a) => $a->getSaldo());

        // Pendapatan Bulan Ini (Akun Golongan 4. Pendapatan)
        $startOfMonth = $now->copy()->startOfMonth()->format('Y-m-d');
        $endOfMonth = $now->copy()->endOfMonth()->format('Y-m-d');

        $pendapatanAccounts = Account::pendapatan()->get();
        $pendapatanBulanIni = $pendapatanAccounts->sum(fn($a) => $a->getSaldo($startOfMonth, $endOfMonth));

        // Beban Bulan Ini (Akun Golongan 5. Beban)
        $bebanAccounts = Account::beban()->get();
        $bebanBulanIni = $bebanAccounts->sum(fn($a) => $a->getSaldo($startOfMonth, $endOfMonth));

        // Laba Bersih Bulan Ini
        $labaBulanIni = $pendapatanBulanIni - $bebanBulanIni;

        // Nilai fallback kas masuk & kas keluar bulan ini untuk ringkasan arus kas
        $pemasukanBulanIni = (float) Transaction::where('tipe', 'pemasukan')
            ->whereMonth('tanggal', $currentMonth)
            ->whereYear('tanggal', $currentYear)
            ->sum('jumlah');

        $pengeluaranBulanIni = (float) Transaction::where('tipe', 'pengeluaran')
            ->whereMonth('tanggal', $currentMonth)
            ->whereYear('tanggal', $currentYear)
            ->sum('jumlah');

        $saldoBulanIni = $pemasukanBulanIni - $pengeluaranBulanIni;

        // 2. Data Grafik Garis (Pengeluaran & Pemasukan 12 Bulan Terakhir)
        $chartLabels = [];
        $chartIncome = [];
        $chartExpense = [];

        for ($i = 11; $i >= 0; $i--) {
            $monthDate = Carbon::now()->subMonths($i);
            $m = $monthDate->month;
            $y = $monthDate->year;
            $monthName = $monthDate->translatedFormat('M Y');

            $chartLabels[] = $monthName;

            $inc = (float) Transaction::where('tipe', 'pemasukan')
                ->whereMonth('tanggal', $m)
                ->whereYear('tanggal', $y)
                ->sum('jumlah');

            $exp = (float) Transaction::where('tipe', 'pengeluaran')
                ->whereMonth('tanggal', $m)
                ->whereYear('tanggal', $y)
                ->sum('jumlah');

            $chartIncome[] = $inc;
            $chartExpense[] = $exp;
        }

        // 3. Data Donut: Grafik Pengeluaran per Akun Beban (5. Beban)
        $bebanPerAkun = Transaction::where('transactions.tipe', 'pengeluaran')
            ->whereNull('transactions.deleted_at')
            ->whereNotNull('transactions.account_id')
            ->join('accounts', 'transactions.account_id', '=', 'accounts.id')
            ->select('accounts.nama', DB::raw('SUM(transactions.jumlah) as total'))
            ->groupBy('accounts.id', 'accounts.nama')
            ->orderByDesc('total')
            ->get();

        if ($bebanPerAkun->isEmpty()) {
            // Fallback per kategori jika akun belum terpetakan
            $bebanPerAkun = Transaction::where('transactions.tipe', 'pengeluaran')
                ->whereNull('transactions.deleted_at')
                ->join('categories', 'transactions.category_id', '=', 'categories.id')
                ->select('categories.nama', DB::raw('SUM(transactions.jumlah) as total'))
                ->groupBy('categories.id', 'categories.nama')
                ->orderByDesc('total')
                ->limit(6)
                ->get();
        }

        $donutLabels = $bebanPerAkun->pluck('nama')->toArray();
        $donutData = $bebanPerAkun->pluck('total')->map(fn($v) => (float) $v)->toArray();
        $palette = ['#ef4444', '#f97316', '#f59e0b', '#10b981', '#06b6d4', '#3b82f6', '#8b5cf6', '#ec4899'];
        $donutColors = array_slice($palette, 0, count($donutLabels));

        // 4. 10 Transaksi Terbaru
        $recentTransactions = Transaction::with(['category', 'account', 'user'])
            ->latest('tanggal')
            ->latest('id')
            ->limit(10)
            ->get();

        $company = CompanySetting::getAllKeyed();

        return view('dashboard', compact(
            'totalKas',
            'totalPiutang',
            'totalUtang',
            'labaBulanIni',
            'pendapatanBulanIni',
            'bebanBulanIni',
            'pemasukanBulanIni',
            'pengeluaranBulanIni',
            'saldoBulanIni',
            'chartLabels',
            'chartIncome',
            'chartExpense',
            'donutLabels',
            'donutData',
            'donutColors',
            'recentTransactions',
            'company'
        ));
    }
}
