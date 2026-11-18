<?php

namespace App\Http\Controllers;

use App\Exports\ProfitLossExport;
use App\Exports\TransactionsExport;
use App\Models\Account;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\CompanySetting;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Tampilkan halaman Laporan Arus Kas / Rekapitulasi Keuangan.
     */
    public function index(Request $request)
    {
        $filters = $this->parseReportFilters($request);
        $query = $filters['query'];
        $periodeJudul = $filters['periodeJudul'];

        $transactions = (clone $query)->orderBy('tanggal', 'asc')->orderBy('id', 'asc')->get();
        $totalPemasukan = $transactions->where('tipe', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = $transactions->where('tipe', 'pengeluaran')->sum('jumlah');
        $saldoBersih = $totalPemasukan - $totalPengeluaran;

        $categories = Category::orderBy('tipe')->orderBy('nama')->get();
        $company = CompanySetting::getAllKeyed();

        return view('reports.index', compact(
            'transactions',
            'categories',
            'periodeJudul',
            'totalPemasukan',
            'totalPengeluaran',
            'saldoBersih',
            'company',
            'filters'
        ));
    }

    /**
     * Tampilkan Laporan Laba Rugi Sederhana.
     */
    public function labaRugi(Request $request)
    {
        $filters = $this->parseReportFilters($request);
        $periodeJudul = $filters['periodeJudul'];
        $startDate = $filters['startDate'];
        $endDate = $filters['endDate'];

        // Rekapitulasi Pendapatan per Akun (Golongan 4. Pendapatan)
        $pendapatanAccounts = Account::pendapatan()
            ->leftJoin('transactions', function ($join) use ($startDate, $endDate) {
                $join->on('accounts.id', '=', 'transactions.account_id')
                     ->whereNull('transactions.deleted_at')
                     ->whereBetween('transactions.tanggal', [$startDate, $endDate]);
            })
            ->select(
                'accounts.id',
                'accounts.kode',
                'accounts.nama',
                DB::raw('COUNT(transactions.id) as total_transaksi'),
                DB::raw('COALESCE(SUM(transactions.jumlah), 0) as total_nominal')
            )
            ->groupBy('accounts.id', 'accounts.kode', 'accounts.nama')
            ->orderBy('accounts.kode')
            ->get();

        // Rekapitulasi Beban per Akun (Golongan 5. Beban)
        $bebanAccounts = Account::beban()
            ->leftJoin('transactions', function ($join) use ($startDate, $endDate) {
                $join->on('accounts.id', '=', 'transactions.account_id')
                     ->whereNull('transactions.deleted_at')
                     ->whereBetween('transactions.tanggal', [$startDate, $endDate]);
            })
            ->select(
                'accounts.id',
                'accounts.kode',
                'accounts.nama',
                DB::raw('COUNT(transactions.id) as total_transaksi'),
                DB::raw('COALESCE(SUM(transactions.jumlah), 0) as total_nominal')
            )
            ->groupBy('accounts.id', 'accounts.kode', 'accounts.nama')
            ->orderBy('accounts.kode')
            ->get();

        $totalPendapatan = (float) $pendapatanAccounts->sum('total_nominal');
        $totalBeban = (float) $bebanAccounts->sum('total_nominal');
        $labaBersih = $totalPendapatan - $totalBeban;

        $pemasukanList = $pendapatanAccounts;
        $pengeluaranList = $bebanAccounts;
        $totalPemasukan = $totalPendapatan;
        $totalPengeluaran = $totalBeban;

        $company = CompanySetting::getAllKeyed();

        return view('reports.laba-rugi', compact(
            'pendapatanAccounts',
            'bebanAccounts',
            'totalPendapatan',
            'totalBeban',
            'pemasukanList',
            'pengeluaranList',
            'totalPemasukan',
            'totalPengeluaran',
            'labaBersih',
            'periodeJudul',
            'company',
            'filters'
        ));
    }

    /**
     * Ekspor Laporan Transaksi ke PDF.
     */
    public function exportPdf(Request $request)
    {
        $filters = $this->parseReportFilters($request);
        $query = $filters['query'];
        $periodeJudul = $filters['periodeJudul'];

        $transactions = (clone $query)->orderBy('tanggal', 'asc')->orderBy('id', 'asc')->get();
        $totalPemasukan = $transactions->where('tipe', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = $transactions->where('tipe', 'pengeluaran')->sum('jumlah');
        $saldoBersih = $totalPemasukan - $totalPengeluaran;

        $company = CompanySetting::getAllKeyed();
        $tanggalCetak = Carbon::now()->translatedFormat('d F Y');

        ActivityLog::record(
            'ekspor',
            'Laporan',
            "Mengekspor Laporan Transaksi PDF (Periode: {$periodeJudul})",
            Auth::id()
        );

        $pdf = Pdf::loadView('reports.pdf-transaksi', compact(
            'transactions',
            'periodeJudul',
            'totalPemasukan',
            'totalPengeluaran',
            'saldoBersih',
            'company',
            'tanggalCetak'
        ))->setPaper('a4', 'landscape');

        $fileName = 'Laporan_Keuangan_CV_Garuda_' . date('Ymd_His') . '.pdf';
        return $pdf->download($fileName);
    }

    /**
     * Ekspor Laporan Laba Rugi ke PDF.
     */
    public function exportLabaRugiPdf(Request $request)
    {
        $filters = $this->parseReportFilters($request);
        $periodeJudul = $filters['periodeJudul'];
        $startDate = $filters['startDate'];
        $endDate = $filters['endDate'];

        $pendapatanAccounts = Account::pendapatan()
            ->leftJoin('transactions', function ($join) use ($startDate, $endDate) {
                $join->on('accounts.id', '=', 'transactions.account_id')
                     ->whereNull('transactions.deleted_at')
                     ->whereBetween('transactions.tanggal', [$startDate, $endDate]);
            })
            ->select(
                'accounts.id',
                'accounts.kode',
                'accounts.nama',
                DB::raw('COUNT(transactions.id) as total_transaksi'),
                DB::raw('COALESCE(SUM(transactions.jumlah), 0) as total_nominal')
            )
            ->groupBy('accounts.id', 'accounts.kode', 'accounts.nama')
            ->orderBy('accounts.kode')
            ->get();

        $bebanAccounts = Account::beban()
            ->leftJoin('transactions', function ($join) use ($startDate, $endDate) {
                $join->on('accounts.id', '=', 'transactions.account_id')
                     ->whereNull('transactions.deleted_at')
                     ->whereBetween('transactions.tanggal', [$startDate, $endDate]);
            })
            ->select(
                'accounts.id',
                'accounts.kode',
                'accounts.nama',
                DB::raw('COUNT(transactions.id) as total_transaksi'),
                DB::raw('COALESCE(SUM(transactions.jumlah), 0) as total_nominal')
            )
            ->groupBy('accounts.id', 'accounts.kode', 'accounts.nama')
            ->orderBy('accounts.kode')
            ->get();

        $totalPemasukan = (float) $pendapatanAccounts->sum('total_nominal');
        $totalPengeluaran = (float) $bebanAccounts->sum('total_nominal');
        $labaBersih = $totalPemasukan - $totalPengeluaran;

        $pemasukanList = $pendapatanAccounts;
        $pengeluaranList = $bebanAccounts;

        $company = CompanySetting::getAllKeyed();
        $tanggalCetak = Carbon::now()->translatedFormat('d F Y');

        ActivityLog::record(
            'ekspor',
            'Laporan',
            "Mengekspor Laporan Laba Rugi PDF (Periode: {$periodeJudul})",
            Auth::id()
        );

        $pdf = Pdf::loadView('reports.pdf-laba-rugi', compact(
            'pemasukanList',
            'pengeluaranList',
            'pendapatanAccounts',
            'bebanAccounts',
            'totalPemasukan',
            'totalPengeluaran',
            'labaBersih',
            'periodeJudul',
            'company',
            'tanggalCetak'
        ))->setPaper('a4', 'portrait');

        $fileName = 'Laporan_Laba_Rugi_CV_Garuda_' . date('Ymd_His') . '.pdf';
        return $pdf->download($fileName);
    }

    /**
     * Ekspor Laporan Transaksi ke Excel.
     */
    public function exportExcel(Request $request)
    {
        $filters = $this->parseReportFilters($request);
        $query = $filters['query'];
        $periodeJudul = $filters['periodeJudul'];

        $transactions = (clone $query)->orderBy('tanggal', 'asc')->orderBy('id', 'asc')->get();
        $totalPemasukan = $transactions->where('tipe', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = $transactions->where('tipe', 'pengeluaran')->sum('jumlah');

        ActivityLog::record(
            'ekspor',
            'Laporan',
            "Mengekspor Laporan Transaksi Excel (Periode: {$periodeJudul})",
            Auth::id()
        );

        $fileName = 'Laporan_Keuangan_CV_Garuda_' . date('Ymd_His') . '.xlsx';
        return Excel::download(new TransactionsExport($transactions, $periodeJudul, $totalPemasukan, $totalPengeluaran), $fileName);
    }

    /**
     * Ekspor Laporan Laba Rugi ke Excel.
     */
    public function exportLabaRugiExcel(Request $request)
    {
        $filters = $this->parseReportFilters($request);
        $periodeJudul = $filters['periodeJudul'];
        $startDate = $filters['startDate'];
        $endDate = $filters['endDate'];

        $pendapatanAccounts = Account::pendapatan()
            ->leftJoin('transactions', function ($join) use ($startDate, $endDate) {
                $join->on('accounts.id', '=', 'transactions.account_id')
                     ->whereNull('transactions.deleted_at')
                     ->whereBetween('transactions.tanggal', [$startDate, $endDate]);
            })
            ->select(
                'accounts.id',
                'accounts.kode',
                'accounts.nama',
                DB::raw('COUNT(transactions.id) as total_transaksi'),
                DB::raw('COALESCE(SUM(transactions.jumlah), 0) as total_nominal')
            )
            ->groupBy('accounts.id', 'accounts.kode', 'accounts.nama')
            ->orderBy('accounts.kode')
            ->get();

        $bebanAccounts = Account::beban()
            ->leftJoin('transactions', function ($join) use ($startDate, $endDate) {
                $join->on('accounts.id', '=', 'transactions.account_id')
                     ->whereNull('transactions.deleted_at')
                     ->whereBetween('transactions.tanggal', [$startDate, $endDate]);
            })
            ->select(
                'accounts.id',
                'accounts.kode',
                'accounts.nama',
                DB::raw('COUNT(transactions.id) as total_transaksi'),
                DB::raw('COALESCE(SUM(transactions.jumlah), 0) as total_nominal')
            )
            ->groupBy('accounts.id', 'accounts.kode', 'accounts.nama')
            ->orderBy('accounts.kode')
            ->get();

        $totalPemasukan = (float) $pendapatanAccounts->sum('total_nominal');
        $totalPengeluaran = (float) $bebanAccounts->sum('total_nominal');
        $labaBersih = $totalPemasukan - $totalPengeluaran;

        $pemasukanList = $pendapatanAccounts;
        $pengeluaranList = $bebanAccounts;

        ActivityLog::record(
            'ekspor',
            'Laporan',
            "Mengekspor Laporan Laba Rugi Excel (Periode: {$periodeJudul})",
            Auth::id()
        );

        $fileName = 'Laporan_Laba_Rugi_CV_Garuda_' . date('Ymd_His') . '.xlsx';
        return Excel::download(new ProfitLossExport($pemasukanList, $pengeluaranList, $totalPemasukan, $totalPengeluaran, $labaBersih, $periodeJudul), $fileName);
    }

    /**
     * Helper untuk parse filter laporan (Harian, Bulanan, Tahunan, Custom).
     */
    protected function parseReportFilters(Request $request): array
    {
        $jenisPeriode = $request->get('jenis_periode', 'bulan_ini');
        $now = Carbon::now();

        $startDate = null;
        $endDate = null;
        $periodeJudul = '';

        switch ($jenisPeriode) {
            case 'hari_ini':
                $startDate = $now->copy()->format('Y-m-d');
                $endDate = $now->copy()->format('Y-m-d');
                $periodeJudul = 'Hari Ini (' . $now->translatedFormat('d F Y') . ')';
                break;

            case 'bulan_ini':
                $bulan = (int) $request->get('bulan', $now->month);
                $tahun = (int) $request->get('tahun', $now->year);
                $cDate = Carbon::create($tahun, $bulan, 1);
                $startDate = $cDate->copy()->startOfMonth()->format('Y-m-d');
                $endDate = $cDate->copy()->endOfMonth()->format('Y-m-d');
                $periodeJudul = 'Bulan ' . $cDate->translatedFormat('F Y');
                break;

            case 'tahun_ini':
                $tahun = (int) $request->get('tahun', $now->year);
                $startDate = "{$tahun}-01-01";
                $endDate = "{$tahun}-12-31";
                $periodeJudul = 'Tahun ' . $tahun;
                break;

            case 'custom':
            default:
                if ($request->filled('start_date') && $request->filled('end_date')) {
                    $startDate = $request->start_date;
                    $endDate = $request->end_date;
                    $periodeJudul = Carbon::parse($startDate)->format('d/m/Y') . ' s/d ' . Carbon::parse($endDate)->format('d/m/Y');
                } else {
                    $jenisPeriode = 'bulan_ini';
                    $startDate = $now->copy()->startOfMonth()->format('Y-m-d');
                    $endDate = $now->copy()->endOfMonth()->format('Y-m-d');
                    $periodeJudul = 'Bulan ' . $now->translatedFormat('F Y');
                }
                break;
        }

        $query = Transaction::with(['category', 'user'])
            ->whereBetween('tanggal', [$startDate, $endDate]);

        if ($request->filled('tipe') && in_array($request->tipe, ['pemasukan', 'pengeluaran'])) {
            $query->where('tipe', $request->tipe);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('metode_pembayaran')) {
            $query->where('metode_pembayaran', $request->metode_pembayaran);
        }

        return [
            'query' => $query,
            'jenisPeriode' => $jenisPeriode,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'periodeJudul' => $periodeJudul,
            'tipe' => $request->get('tipe'),
            'category_id' => $request->get('category_id'),
            'metode_pembayaran' => $request->get('metode_pembayaran'),
            'bulan' => $request->get('bulan', $now->month),
            'tahun' => $request->get('tahun', $now->year),
        ];
    }
}

