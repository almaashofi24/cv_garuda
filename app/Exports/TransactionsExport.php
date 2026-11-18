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

class TransactionsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle, WithCustomStartCell, WithEvents
{
    protected $transactions;
    protected $periodeJudul;
    protected $totalPemasukan;
    protected $totalPengeluaran;

    public function __construct($transactions, string $periodeJudul = 'Semua Periode', $totalPemasukan = 0, $totalPengeluaran = 0)
    {
        $this->transactions = $transactions;
        $this->periodeJudul = $periodeJudul;
        $this->totalPemasukan = $totalPemasukan;
        $this->totalPengeluaran = $totalPengeluaran;
    }

    public function collection()
    {
        return $this->transactions;
    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function title(): string
    {
        return 'Laporan Transaksi';
    }

    public function headings(): array
    {
        return [
            'No.',
            'Nomor Bukti',
            'Tanggal',
            'Jenis Transaksi',
            'Kategori',
            'Pihak Terkait (Sumber/Vendor)',
            'Keterangan / Deskripsi',
            'Metode Pembayaran',
            'Pemasukan (Rp)',
            'Pengeluaran (Rp)',
            'Petugas Input',
        ];
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;

        $pemasukan = ($row->tipe === 'pemasukan') ? (float) $row->jumlah : 0;
        $pengeluaran = ($row->tipe === 'pengeluaran') ? (float) $row->jumlah : 0;

        return [
            $no,
            $row->nomor_bukti,
            $row->tanggal ? Carbon::parse($row->tanggal)->format('d/m/Y') : '-',
            ucfirst($row->tipe),
            $row->category ? $row->category->nama : '-',
            $row->pihak_terkait,
            $row->deskripsi,
            strtoupper($row->metode_pembayaran),
            $pemasukan,
            $pengeluaran,
            $row->user ? $row->user->name : '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            6 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF1E3A8A'], // Deep Blue
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
                $companyName = $company['nama_perusahaan'] ?? 'CV. GARUDA RAJA PERKASA DUABELAS';
                $companyAddress = $company['alamat_perusahaan'] ?? 'Simpang Ampar, Tayan Hilir, Kab. Sanggau, Kalimantan Barat';

                // Header Perusahaan
                $sheet->mergeCells('A1:K1');
                $sheet->setCellValue('A1', strtoupper($companyName));
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells('A2:K2');
                $sheet->setCellValue('A2', $companyAddress);
                $sheet->getStyle('A2')->getFont()->setSize(10)->setItalic(true);
                $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells('A3:K3');
                $sheet->setCellValue('A3', 'LAPORAN REKAPITULASI KEUANGAN (ARUS KAS)');
                $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells('A4:K4');
                $sheet->setCellValue('A4', 'Periode: ' . $this->periodeJudul);
                $sheet->getStyle('A4')->getFont()->setSize(10);
                $sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Baris Total di bagian bawah
                $rowCount = count($this->transactions);
                $totalRow = 6 + $rowCount + 1;

                $sheet->mergeCells("A{$totalRow}:H{$totalRow}");
                $sheet->setCellValue("A{$totalRow}", 'TOTAL KESELURUHAN');
                $sheet->setCellValue("I{$totalRow}", $this->totalPemasukan);
                $sheet->setCellValue("J{$totalRow}", $this->totalPengeluaran);
                $sheet->setCellValue("K{$totalRow}", '');

                $sheet->getStyle("A{$totalRow}:K{$totalRow}")->getFont()->setBold(true);
                $sheet->getStyle("A{$totalRow}:H{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("A{$totalRow}:K{$totalRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF1F5F9');

                // Baris Saldo Bersih
                $saldoRow = $totalRow + 1;
                $saldoBersih = $this->totalPemasukan - $this->totalPengeluaran;
                $sheet->mergeCells("A{$saldoRow}:H{$saldoRow}");
                $sheet->setCellValue("A{$saldoRow}", 'SALDO / LABA BERSIH (PEMASUKAN - PENGELUARAN)');
                $sheet->mergeCells("I{$saldoRow}:J{$saldoRow}");
                $sheet->setCellValue("I{$saldoRow}", $saldoBersih);
                $sheet->getStyle("A{$saldoRow}:K{$saldoRow}")->getFont()->setBold(true);
                $sheet->getStyle("A{$saldoRow}:H{$saldoRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("I{$saldoRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Format angka kolom I & J
                $startDataRow = 7;
                $endDataRow = $totalRow;
                $sheet->getStyle("I{$startDataRow}:J{$endDataRow}")
                    ->getNumberFormat()
                    ->setFormatCode('#,##0');

                $sheet->getStyle("I{$saldoRow}")
                    ->getNumberFormat()
                    ->setFormatCode('#,##0');

                // Borders
                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FFCBD5E1'],
                        ],
                    ],
                ];
                $sheet->getStyle("A6:K{$saldoRow}")->applyFromArray($styleArray);
            },
        ];
    }
}

