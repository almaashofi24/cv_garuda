<?php

namespace App\Exports;

use App\Models\CompanySetting;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GeneralLedgerExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithTitle, WithCustomStartCell, WithEvents
{
    protected $ledgerData;
    protected $startDate;
    protected $endDate;
    protected $selectedAccount;

    public function __construct(array $data)
    {
        $this->ledgerData = $data['ledgerData'];
        $this->startDate = $data['startDate'];
        $this->endDate = $data['endDate'];
        $this->selectedAccount = $data['selectedAccount'];
    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function title(): string
    {
        return 'Buku Besar';
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'No. Bukti',
            'Keterangan Transaksi',
            'Debit (Rp)',
            'Kredit (Rp)',
            'Saldo Berjalan (Rp)',
        ];
    }

    public function collection()
    {
        $rows = collect();

        foreach ($this->ledgerData as $item) {
            $acc = $item['account'];

            // Header Akun
            $rows->push([
                "AKUN: {$acc->kode} - {$acc->nama} ({$acc->golongan} | Saldo Normal: {$acc->saldo_normal})",
                '',
                'SALDO AWAL',
                '',
                '',
                (float) $item['saldo_awal'],
            ]);

            foreach ($item['rows'] as $r) {
                $tgl = $r['tanggal'] ? Carbon::parse($r['tanggal'])->format('d-m-Y') : '-';
                $rows->push([
                    $tgl,
                    $r['nomor_bukti'],
                    $r['keterangan'],
                    $r['debit'] > 0 ? (float) $r['debit'] : 0,
                    $r['kredit'] > 0 ? (float) $r['kredit'] : 0,
                    (float) $r['saldo'],
                ]);
            }

            // Total Akun
            $rows->push([
                "TOTAL AKUN {$acc->kode}",
                '',
                'TOTAL MUTASI',
                (float) $item['total_debit'],
                (float) $item['total_kredit'],
                (float) $item['saldo_akhir'],
            ]);

            // Spacer
            $rows->push(['', '', '', '', '', '']);
        }

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            6 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF1E293B'],
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $company = CompanySetting::getAllKeyed();
                $namaPerusahaan = $company['nama_perusahaan'] ?? 'CV. GARUDA RAJA PERKASA DUABELAS';

                $sheet->mergeCells('A1:F1');
                $sheet->setCellValue('A1', strtoupper($namaPerusahaan));
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF1E3A8A'));

                $sheet->mergeCells('A2:F2');
                $sheet->setCellValue('A2', 'BUKU BESAR (GENERAL LEDGER) - 7 GOLONGAN AKUNTANSI');
                $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);

                $tglAwal = Carbon::parse($this->startDate)->format('d-m-Y');
                $tglAkhir = Carbon::parse($this->endDate)->format('d-m-Y');
                $sheet->mergeCells('A3:F3');
                $sheet->setCellValue('A3', "Periode: {$tglAwal} s/d {$tglAkhir}");
                $sheet->getStyle('A3')->getFont()->setItalic(true)->setSize(10);

                $sheet->mergeCells('A4:F4');
                $sheet->setCellValue('A4', 'Dicetak pada: ' . Carbon::now()->format('d-m-Y H:i') . ' WIB');
                $sheet->getStyle('A4')->getFont()->setSize(9)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF64748B'));

                // Format kolom angka D, E, F
                $highestRow = $sheet->getHighestRow();
                $sheet->getStyle("D7:F{$highestRow}")->getNumberFormat()->setFormatCode('#,##0');
            },
        ];
    }
}
