<?php

namespace App\Exports;

use App\Models\CompanySetting;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExpenseReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle, WithCustomStartCell, WithEvents
{
    protected $expenses;
    protected $totalPengeluaran;
    protected $startDate;
    protected $endDate;
    protected $rowNumber = 0;

    public function __construct(array $data)
    {
        $this->expenses = $data['expenses'];
        $this->totalPengeluaran = $data['totalPengeluaran'];
        $this->startDate = $data['startDate'];
        $this->endDate = $data['endDate'];
    }

    public function collection()
    {
        return $this->expenses;
    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function title(): string
    {
        return 'Laporan Pengeluaran';
    }

    public function headings(): array
    {
        return [
            'No.',
            'Tanggal',
            'Keterangan',
            'Akun',
            'Golongan',
            'Jumlah (Rp)',
        ];
    }

    public function map($row): array
    {
        $this->rowNumber++;
        $tgl = $row->tanggal ? Carbon::parse($row->tanggal)->format('d-m-Y') : '-';
        $akun = $row->account ? $row->account->nama : ($row->category->nama ?? '-');
        $golongan = $row->account ? $row->account->golongan : '5. Beban';

        return [
            $this->rowNumber,
            $tgl,
            $row->deskripsi,
            $akun,
            $golongan,
            (float) $row->jumlah,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            6 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF1E293B'], // Slate-800
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

                // Header Perusahaan
                $sheet->mergeCells('A1:F1');
                $sheet->setCellValue('A1', strtoupper($namaPerusahaan));
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF1E3A8A'));

                $sheet->mergeCells('A2:F2');
                $sheet->setCellValue('A2', 'LAPORAN PENGELUARAN / BEBAN (7 GOLONGAN AKUNTANSI)');
                $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);

                $tglAwal = Carbon::parse($this->startDate)->format('d-m-Y');
                $tglAkhir = Carbon::parse($this->endDate)->format('d-m-Y');
                $sheet->mergeCells('A3:F3');
                $sheet->setCellValue('A3', "Periode: {$tglAwal} s/d {$tglAkhir}");
                $sheet->getStyle('A3')->getFont()->setItalic(true)->setSize(10);

                $sheet->mergeCells('A4:F4');
                $sheet->setCellValue('A4', 'Dicetak pada: ' . Carbon::now()->format('d-m-Y H:i') . ' WIB');
                $sheet->getStyle('A4')->getFont()->setSize(9)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF64748B'));

                // Baris Total Pengeluaran di bagian bawah
                $lastRow = 6 + count($this->expenses);
                $totalRow = $lastRow + 1;

                $sheet->mergeCells("A{$totalRow}:E{$totalRow}");
                $sheet->setCellValue("A{$totalRow}", 'TOTAL PENGELUARAN');
                $sheet->setCellValue("F{$totalRow}", (float) $this->totalPengeluaran);

                // Styling Total
                $sheet->getStyle("A{$totalRow}:F{$totalRow}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFE11D48'], // Rose-600
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $sheet->getStyle("A{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                // Format border tabel
                $sheet->getStyle("A6:F{$totalRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FFCBD5E1'],
                        ],
                    ],
                ]);

                // Format angka nominal
                $sheet->getStyle("F7:F{$totalRow}")->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle("B7:B{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("A7:A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
