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

class BalanceSheetExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithTitle, WithCustomStartCell, WithEvents
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function title(): string
    {
        return 'Neraca';
    }

    public function headings(): array
    {
        return [
            'Komponen Akun',
            'Kode Akun',
            'Nominal (Rp)',
        ];
    }

    public function collection()
    {
        $rows = collect();

        // 1. HARTA
        $rows->push(['I. HARTA / AKTIVA (GOLONGAN 1)', '', '']);
        foreach ($this->data['hartaAccounts'] as $acc) {
            $rows->push(['   ' . $acc['nama'], $acc['kode'], (float) $acc['saldo']]);
        }
        $rows->push(['TOTAL HARTA (AKTIVA)', '', (float) $this->data['totalHarta']]);
        $rows->push(['', '', '']);

        // 2. UTANG
        $rows->push(['II. KEWAJIBAN / UTANG (GOLONGAN 2)', '', '']);
        foreach ($this->data['utangAccounts'] as $acc) {
            $rows->push(['   ' . $acc['nama'], $acc['kode'], (float) $acc['saldo']]);
        }
        $rows->push(['TOTAL UTANG (KEWAJIBAN)', '', (float) $this->data['totalUtang']]);
        $rows->push(['', '', '']);

        // 3. MODAL
        $rows->push(['III. MODAL / EKUITAS (GOLONGAN 3 & 6)', '', '']);
        foreach ($this->data['modalAccounts'] as $acc) {
            $rows->push(['   ' . $acc['nama'], $acc['kode'], (float) $acc['saldo']]);
        }
        foreach ($this->data['priveAccounts'] as $acc) {
            $rows->push(['   ' . $acc['nama'] . ' (Pengurang Modal)', $acc['kode'], -(float) $acc['saldo']]);
        }
        $rows->push(['   Laba Bersih Berjalan', '', (float) $this->data['labaBersihBerjalan']]);
        $rows->push(['TOTAL MODAL (EKUITAS)', '', (float) $this->data['totalModal']]);
        $rows->push(['', '', '']);

        // 4. TOTAL KEWAJIBAN & MODAL
        $rows->push(['TOTAL KEWAJIBAN & MODAL (PASIVA)', '', (float) $this->data['totalPasiva']]);

        // 5. STATUS KESEIMBANGAN
        $status = $this->data['isBalanced'] ? 'SEIMBANG / BALANCE (Harta = Utang + Modal)' : 'TIDAK SEIMBANG';
        $rows->push(['STATUS NERACA: ' . $status, '', '']);

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

                $sheet->mergeCells('A1:C1');
                $sheet->setCellValue('A1', strtoupper($namaPerusahaan));
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF1E3A8A'));

                $sheet->mergeCells('A2:C2');
                $sheet->setCellValue('A2', 'LAPORAN NERACA KEUANGAN (HARTA = UTANG + MODAL)');
                $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);

                $asOf = Carbon::parse($this->data['asOfDate'])->format('d F Y');
                $sheet->mergeCells('A3:C3');
                $sheet->setCellValue('A3', "Per Tanggal: {$asOf}");
                $sheet->getStyle('A3')->getFont()->setItalic(true)->setSize(10);

                $sheet->mergeCells('A4:C4');
                $sheet->setCellValue('A4', 'Dicetak pada: ' . Carbon::now()->format('d-m-Y H:i') . ' WIB');
                $sheet->getStyle('A4')->getFont()->setSize(9)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF64748B'));

                $highestRow = $sheet->getHighestRow();
                $sheet->getStyle("C7:C{$highestRow}")->getNumberFormat()->setFormatCode('#,##0');
            },
        ];
    }
}
