<?php

namespace App\Exports;

use App\Models\CompanySetting;
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

class ProfitLossExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithTitle, WithCustomStartCell, WithEvents
{
    protected $pemasukanList;
    protected $pengeluaranList;
    protected $totalPemasukan;
    protected $totalPengeluaran;
    protected $labaBersih;
    protected $periodeJudul;

    public function __construct($pemasukanList, $pengeluaranList, $totalPemasukan, $totalPengeluaran, $labaBersih, string $periodeJudul = '')
    {
        $this->pemasukanList = $pemasukanList;
        $this->pengeluaranList = $pengeluaranList;
        $this->totalPemasukan = $totalPemasukan;
        $this->totalPengeluaran = $totalPengeluaran;
        $this->labaBersih = $labaBersih;
        $this->periodeJudul = $periodeJudul;
    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function title(): string
    {
        return 'Laba Rugi';
    }

    public function headings(): array
    {
        return [
            'Komponen / Kategori Akun',
            'Jumlah Transaksi',
            'Nominal (Rp)',
        ];
    }

    public function collection()
    {
        $rows = collect();

        // 1. Bagian Pendapatan
        $rows->push(['I. PENDAPATAN OPERASIONAL', '', '']);
        foreach ($this->pemasukanList as $item) {
            $rows->push([
                '   ' . $item->nama,
                $item->total_transaksi . ' transaksi',
                (float) $item->total_nominal,
            ]);
        }
        $rows->push(['TOTAL PENDAPATAN', '', (float) $this->totalPemasukan]);
        $rows->push(['', '', '']); // spasi

        // 2. Bagian Beban
        $rows->push(['II. BEBAN OPERASIONAL', '', '']);
        foreach ($this->pengeluaranList as $item) {
            $rows->push([
                '   ' . $item->nama,
                $item->total_transaksi . ' transaksi',
                (float) $item->total_nominal,
            ]);
        }
        $rows->push(['TOTAL BEBAN OPERASIONAL', '', (float) $this->totalPengeluaran]);
        $rows->push(['', '', '']); // spasi

        // 3. Laba / Rugi Bersih
        $rows->push(['LABA / (RUGI) BERSIH', '', (float) $this->labaBersih]);

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            6 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF1E3A8A'],
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

                // Header
                $sheet->mergeCells('A1:C1');
                $sheet->setCellValue('A1', strtoupper($companyName));
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells('A2:C2');
                $sheet->setCellValue('A2', $companyAddress);
                $sheet->getStyle('A2')->getFont()->setSize(10)->setItalic(true);
                $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells('A3:C3');
                $sheet->setCellValue('A3', 'LAPORAN LABA RUGI SEDERHANA');
                $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells('A4:C4');
                $sheet->setCellValue('A4', 'Periode: ' . $this->periodeJudul);
                $sheet->getStyle('A4')->getFont()->setSize(10);
                $sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Format number
                $sheet->getStyle('C7:C40')->getNumberFormat()->setFormatCode('#,##0');
            },
        ];
    }
}

