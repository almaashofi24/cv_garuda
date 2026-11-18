<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Pengeluaran - {{ $company['nama_perusahaan'] ?? 'CV. GARUDA RAJA PERKASA DUABELAS' }}</title>
    <style>
        @page {
            margin: 1.2cm 1.2cm 1.5cm 1.2cm;
            size: a4 portrait;
        }
        body {
            font-family: 'DejaVu Sans', Arial, Helvetica, sans-serif;
            font-size: 8.5pt;
            color: #1e293b;
            line-height: 1.3;
        }
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .company-name {
            font-size: 13pt;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }
        .company-address {
            font-size: 8pt;
            color: #475569;
            margin-bottom: 2px;
        }
        .company-contact {
            font-size: 7.5pt;
            color: #64748b;
        }
        .kop-line-double {
            border-bottom: 3px double #0f172a;
            margin-bottom: 12px;
        }
        .report-title-box {
            text-align: center;
            margin-bottom: 14px;
        }
        .report-title {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #0f172a;
            margin-bottom: 3px;
        }
        .report-period {
            font-size: 8pt;
            color: #334155;
            font-weight: bold;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        table.data-table th {
            background-color: #1e293b;
            color: #ffffff;
            font-weight: bold;
            font-size: 7.5pt;
            text-transform: uppercase;
            padding: 6px 6px;
            border: 1px solid #0f172a;
            text-align: left;
        }
        table.data-table td {
            padding: 5px 6px;
            border: 1px solid #cbd5e1;
            font-size: 8pt;
        }
        table.data-table tr.even {
            background-color: #f8fafc;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .font-bold {
            font-weight: bold;
        }
        .total-row {
            background-color: #fee2e2;
            font-weight: bold;
            font-size: 8.5pt;
            border-top: 2px solid #e11d48;
        }
        .signature-table {
            width: 100%;
            margin-top: 25px;
            border-collapse: collapse;
        }
        .signature-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            font-size: 8pt;
        }
        .signature-space {
            height: 60px;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
            color: #0f172a;
        }
    </style>
</head>
<body>
    <!-- Kop Surat Perusahaan -->
    <table class="kop-table">
        <tr>
            <td style="width: 75px; text-align: left;">
                <div style="width: 55px; height: 55px; background-color: #0f172a; border-radius: 8px; text-align: center; line-height: 55px; color: #f59e0b; font-weight: bold; font-size: 18pt;">
                    GRP
                </div>
            </td>
            <td>
                <div class="company-name">{{ $company['nama_perusahaan'] ?? 'CV. GARUDA RAJA PERKASA DUABELAS' }}</div>
                <div class="company-address">{{ $company['alamat_perusahaan'] ?? 'Jl. Trans Kalimantan Ruko Keling Kumang No.2, Simpang Ampar, Kec. Tayan Hilir, Kabupaten Sanggau, Kalimantan Barat 78564' }}</div>
                <div class="company-contact">
                    Telp: {{ $company['telepon_perusahaan'] ?? '0812-3456-7890' }} | Email: {{ $company['email_perusahaan'] ?? 'kontak@cvgaruda.com' }}
                </div>
            </td>
        </tr>
    </table>
    <div class="kop-line-double"></div>

    <!-- Judul Dokumen -->
    <div class="report-title-box">
        <div class="report-title">LAPORAN PENGELUARAN / BEBAN (7 GOLONGAN AKUNTANSI)</div>
        <div class="report-period">
            Periode: {{ \Carbon\Carbon::parse($startDate)->format('d-m-Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d-m-Y') }}
        </div>
    </div>

    <!-- Tabel Data Pengeluaran -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;" class="text-center">No</th>
                <th style="width: 70px;">Tanggal</th>
                <th>Keterangan</th>
                <th style="width: 120px;">Akun</th>
                <th style="width: 80px;">Golongan</th>
                <th style="width: 95px;" class="text-right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($expenses as $idx => $item)
                @php
                    $tglFormat = $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') : '-';
                    $namaAkun = $item->account ? $item->account->nama : ($item->category->nama ?? '-');
                    $namaGolongan = $item->account ? $item->account->golongan : '5. Beban';
                @endphp
                <tr class="{{ $idx % 2 === 1 ? 'even' : '' }}">
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td>{{ $tglFormat }}</td>
                    <td>
                        <strong>{{ $item->deskripsi }}</strong>
                        @if($item->nomor_bukti)
                            <br><span style="font-size: 7pt; color: #64748b;">No: {{ $item->nomor_bukti }}</span>
                        @endif
                    </td>
                    <td>{{ $namaAkun }}</td>
                    <td>{{ $namaGolongan }}</td>
                    <td class="text-right font-bold">
                        Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 15px; color: #94a3b8;">
                        Tidak ada catatan pengeluaran pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5" class="text-left" style="padding: 7px 6px;">
                    <strong>TOTAL PENGELUARAN</strong>
                </td>
                <td class="text-right" style="padding: 7px 6px; color: #991b1b;">
                    <strong>Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</strong>
                </td>
            </tr>
        </tfoot>
    </table>

    <!-- Tanda Tangan -->
    <table class="signature-table">
        <tr>
            <td>
                <div>Mengetahui,</div>
                <div class="font-bold">{{ $company['pimpinan_jabatan'] ?? 'Direktur Utama' }}</div>
                <div class="signature-space"></div>
                <div class="signature-name">{{ $company['pimpinan_nama'] ?? 'H. Sudirman, S.E.' }}</div>
            </td>
            <td>
                <div>{{ $company['kota_laporan'] ?? 'Sanggau' }}, {{ date('d F Y') }}</div>
                <div class="font-bold">{{ $company['staf_keuangan_jabatan'] ?? 'Staf Keuangan' }}</div>
                <div class="signature-space"></div>
                <div class="signature-name">{{ $company['staf_keuangan_nama'] ?? 'Siti Aminah, A.Md.' }}</div>
            </td>
        </tr>
    </table>
</body>
</html>
