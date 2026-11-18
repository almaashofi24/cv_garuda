<?php

namespace App\Http\Controllers;

use App\Exports\GeneralLedgerExport;
use App\Models\Account;
use App\Models\CompanySetting;
use App\Models\JournalEntry;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class GeneralLedgerController extends Controller
{
    /**
     * Tampilkan Buku Besar (General Ledger) per akun atau semua akun.
     */
    public function index(Request $request)
    {
        $data = $this->prepareLedgerData($request);

        return view('general-ledger.index', $data);
    }

    /**
     * Ekspor Buku Besar ke format PDF A4 rapi ber-kop surat.
     */
    public function exportPdf(Request $request)
    {
        $data = $this->prepareLedgerData($request);
        $data['company'] = CompanySetting::getAllKeyed();

        $pdf = Pdf::loadView('reports.pdf-buku-besar', $data);
        $pdf->setPaper('a4', 'portrait');

        $accountName = $data['selectedAccount'] ? str_replace(' ', '_', $data['selectedAccount']->nama) : 'Semua_Akun';
        $filename = "Buku_Besar_{$accountName}_" . date('Ymd_His') . ".pdf";

        return $pdf->download($filename);
    }

    /**
     * Ekspor Buku Besar ke format Excel.
     */
    public function exportExcel(Request $request)
    {
        $data = $this->prepareLedgerData($request);
        $accountName = $data['selectedAccount'] ? str_replace(' ', '_', $data['selectedAccount']->nama) : 'Semua_Akun';
        $filename = "Buku_Besar_{$accountName}_" . date('Ymd_His') . ".xlsx";

        return Excel::download(new GeneralLedgerExport($data), $filename);
    }

    /**
     * Helper untuk menghitung data Buku Besar, saldo awal, saldo berjalan, dan total mutasi.
     */
    protected function prepareLedgerData(Request $request): array
    {
        $accounts = Account::orderBy('kode')->get();

        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Default pilih akun Kas (110) jika tidak ada akun yang dipilih
        $accountId = $request->get('account_id');
        if (!$accountId && $accountId !== 'all') {
            $kas = $accounts->firstWhere('kode', '110');
            $accountId = $kas ? $kas->id : ($accounts->first()?->id);
        }

        $selectedAccount = ($accountId && $accountId !== 'all') ? Account::find($accountId) : null;

        $ledgerData = [];

        $accountsToProcess = $selectedAccount ? collect([$selectedAccount]) : $accounts;

        foreach ($accountsToProcess as $acc) {
            // 1. Hitung Saldo Awal sebelum $startDate
            $baseSaldoAwal = (float) $acc->saldo_awal;
            $prevEntries = JournalEntry::where('account_id', $acc->id)
                ->where('tanggal', '<', $startDate);

            $prevDebit = (float) $prevEntries->sum('debit');
            $prevKredit = (float) $prevEntries->sum('kredit');

            if ($acc->saldo_normal === 'Debit') {
                $saldoAwal = $baseSaldoAwal + $prevDebit - $prevKredit;
            } elseif ($acc->saldo_normal === 'Kredit') {
                $saldoAwal = $baseSaldoAwal + $prevKredit - $prevDebit;
            } else {
                $saldoAwal = $prevKredit - $prevDebit;
            }

            // 2. Ambil entri dalam rentang tanggal
            $entries = JournalEntry::where('account_id', $acc->id)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->orderBy('tanggal', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            // 3. Hitung saldo berjalan
            $runningBalance = $saldoAwal;
            $rows = [];
            $totalDebit = 0;
            $totalKredit = 0;

            foreach ($entries as $entry) {
                $d = (float) $entry->debit;
                $k = (float) $entry->kredit;
                $totalDebit += $d;
                $totalKredit += $k;

                if ($acc->saldo_normal === 'Debit') {
                    $runningBalance += ($d - $k);
                } elseif ($acc->saldo_normal === 'Kredit') {
                    $runningBalance += ($k - $d);
                } else {
                    $runningBalance += ($k - $d);
                }

                $rows[] = [
                    'id' => $entry->id,
                    'tanggal' => $entry->tanggal,
                    'nomor_bukti' => $entry->nomor_bukti ?: '-',
                    'keterangan' => $entry->keterangan,
                    'debit' => $d,
                    'kredit' => $k,
                    'saldo' => $runningBalance,
                ];
            }

            $saldoAkhir = $runningBalance;

            $ledgerData[] = [
                'account' => $acc,
                'saldo_awal' => $saldoAwal,
                'rows' => $rows,
                'total_debit' => $totalDebit,
                'total_kredit' => $totalKredit,
                'saldo_akhir' => $saldoAkhir,
            ];
        }

        return [
            'accounts' => $accounts,
            'selectedAccount' => $selectedAccount,
            'accountId' => $accountId,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'ledgerData' => $ledgerData,
        ];
    }
}
