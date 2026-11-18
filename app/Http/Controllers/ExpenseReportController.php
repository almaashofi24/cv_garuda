<?php

namespace App\Http\Controllers;

use App\Exports\ExpenseReportExport;
use App\Models\Account;
use App\Models\CompanySetting;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExpenseReportController extends Controller
{
    /**
     * Halaman Laporan Pengeluaran Khusus (Filter Semua Beban/Biaya).
     */
    public function index(Request $request)
    {
        $data = $this->prepareExpenseData($request);

        return view('reports.pengeluaran', $data);
    }

    /**
     * Ekspor Laporan Pengeluaran ke format PDF.
     */
    public function exportPdf(Request $request)
    {
        $data = $this->prepareExpenseData($request);
        $data['company'] = CompanySetting::getAllKeyed();

        $pdf = Pdf::loadView('reports.pdf-pengeluaran', $data);
        $pdf->setPaper('a4', 'portrait');

        $filename = "Laporan_Pengeluaran_" . date('Ymd_His') . ".pdf";

        return $pdf->download($filename);
    }

    /**
     * Ekspor Laporan Pengeluaran ke format Excel.
     */
    public function exportExcel(Request $request)
    {
        $data = $this->prepareExpenseData($request);
        $filename = "Laporan_Pengeluaran_" . date('Ymd_His') . ".xlsx";

        return Excel::download(new ExpenseReportExport($data), $filename);
    }

    /**
     * Helper untuk memfilter data pengeluaran/beban.
     */
    protected function prepareExpenseData(Request $request): array
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $accountId = $request->get('account_id');
        $search = $request->get('search');

        $query = Transaction::with(['account', 'category'])
            ->where('tipe', 'pengeluaran')
            ->whereBetween('tanggal', [$startDate, $endDate]);

        if ($accountId) {
            $query->where('account_id', $accountId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('deskripsi', 'like', "%{$search}%")
                  ->orWhere('nomor_bukti', 'like', "%{$search}%")
                  ->orWhere('pihak_terkait', 'like', "%{$search}%");
            });
        }

        $expenses = $query->orderBy('tanggal', 'asc')->orderBy('id', 'asc')->get();
        $totalPengeluaran = (float) $expenses->sum('jumlah');

        // Akun-akun beban dan pengeluaran untuk dropdown filter
        $accounts = Account::whereIn('golongan_kode', ['5-100', '1-100', '2-100', '6-100'])->orderBy('kode')->get();

        return [
            'expenses' => $expenses,
            'totalPengeluaran' => $totalPengeluaran,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'accountId' => $accountId,
            'search' => $search,
            'accounts' => $accounts,
        ];
    }
}
