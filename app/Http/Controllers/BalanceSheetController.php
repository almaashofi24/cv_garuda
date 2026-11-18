<?php

namespace App\Http\Controllers;

use App\Exports\BalanceSheetExport;
use App\Models\Account;
use App\Models\CompanySetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BalanceSheetController extends Controller
{
    /**
     * Tampilkan Laporan Neraca (Balance Sheet: Harta = Utang + Modal).
     */
    public function index(Request $request)
    {
        $data = $this->prepareBalanceSheetData($request);

        return view('reports.neraca', $data);
    }

    /**
     * Ekspor Laporan Neraca ke format PDF A4.
     */
    public function exportPdf(Request $request)
    {
        $data = $this->prepareBalanceSheetData($request);
        $data['company'] = CompanySetting::getAllKeyed();

        $pdf = Pdf::loadView('reports.pdf-neraca', $data);
        $pdf->setPaper('a4', 'portrait');

        $filename = "Neraca_" . date('Ymd_His') . ".pdf";

        return $pdf->download($filename);
    }

    /**
     * Ekspor Laporan Neraca ke format Excel.
     */
    public function exportExcel(Request $request)
    {
        $data = $this->prepareBalanceSheetData($request);
        $filename = "Neraca_" . date('Ymd_His') . ".xlsx";

        return Excel::download(new BalanceSheetExport($data), $filename);
    }

    /**
     * Helper perhitungan komponen Neraca per tanggal tertentu.
     */
    protected function prepareBalanceSheetData(Request $request): array
    {
        $asOfDate = $request->get('as_of_date', Carbon::now()->format('Y-m-d'));

        // 1. Golongan 1: Harta (Aktiva)
        $hartaAccounts = Account::harta()->orderBy('kode')->get()->map(function ($acc) use ($asOfDate) {
            $saldo = $acc->getSaldo(null, $asOfDate);
            return [
                'kode' => $acc->kode,
                'nama' => $acc->nama,
                'saldo' => $saldo,
            ];
        });
        $totalHarta = (float) $hartaAccounts->sum('saldo');

        // 2. Golongan 2: Utang (Kewajiban)
        $utangAccounts = Account::utang()->orderBy('kode')->get()->map(function ($acc) use ($asOfDate) {
            $saldo = $acc->getSaldo(null, $asOfDate);
            return [
                'kode' => $acc->kode,
                'nama' => $acc->nama,
                'saldo' => $saldo,
            ];
        });
        $totalUtang = (float) $utangAccounts->sum('saldo');

        // 3. Golongan 3: Modal (Ekuitas) & Golongan 6: Prive
        $modalAccounts = Account::modal()->orderBy('kode')->get()->map(function ($acc) use ($asOfDate) {
            $saldo = $acc->getSaldo(null, $asOfDate);
            return [
                'kode' => $acc->kode,
                'nama' => $acc->nama,
                'saldo' => $saldo,
            ];
        });
        $subtotalModal = (float) $modalAccounts->sum('saldo');

        // Prive (Pengurang Modal)
        $priveAccounts = Account::prive()->orderBy('kode')->get()->map(function ($acc) use ($asOfDate) {
            $saldo = $acc->getSaldo(null, $asOfDate);
            return [
                'kode' => $acc->kode,
                'nama' => $acc->nama,
                'saldo' => $saldo,
            ];
        });
        $totalPrive = (float) $priveAccounts->sum('saldo');

        // Laba Bersih Berjalan hingga $asOfDate (Pendapatan - Beban)
        $pendapatanAccounts = Account::pendapatan()->get();
        $totalPendapatan = $pendapatanAccounts->sum(fn($a) => $a->getSaldo(null, $asOfDate));

        $bebanAccounts = Account::beban()->get();
        $totalBeban = $bebanAccounts->sum(fn($a) => $a->getSaldo(null, $asOfDate));

        $labaBersihBerjalan = $totalPendapatan - $totalBeban;

        // Total Ekuitas = Modal + Laba Berjalan - Prive
        $totalModal = $subtotalModal - $totalPrive + $labaBersihBerjalan;

        // Total Kewajiban & Modal (Pasiva)
        $totalPasiva = $totalUtang + $totalModal;

        $selisih = abs($totalHarta - $totalPasiva);
        $isBalanced = ($selisih < 0.01);

        return [
            'asOfDate' => $asOfDate,
            'hartaAccounts' => $hartaAccounts,
            'totalHarta' => $totalHarta,
            'utangAccounts' => $utangAccounts,
            'totalUtang' => $totalUtang,
            'modalAccounts' => $modalAccounts,
            'subtotalModal' => $subtotalModal,
            'priveAccounts' => $priveAccounts,
            'totalPrive' => $totalPrive,
            'labaBersihBerjalan' => $labaBersihBerjalan,
            'totalModal' => $totalModal,
            'totalPasiva' => $totalPasiva,
            'isBalanced' => $isBalanced,
            'selisih' => $selisih,
        ];
    }
}
