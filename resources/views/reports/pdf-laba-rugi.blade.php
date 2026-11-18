<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Laba Rugi - CV. Garuda Raja Perkasa Duabelas</title>
    <style>
        @page {
            margin: 1.5cm 1.5cm 1.5cm 1.5cm;
            size: a4 portrait;
        }
        body {
            font-family: 'DejaVu Sans', Arial, Helvetica, sans-serif;
            font-size: 9.5pt;
            color: #1e293b;
            line-height: 1.4;
        }
        /* Kop Surat */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .company-name {
            font-size: 14pt;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: 1px;
            margin-bottom: 3px;
        }
        .company-address {
            font-size: 8.5pt;
            color: #475569;
            margin-bottom: 2px;
        }
        .company-contact {
            font-size: 8pt;
            color: #64748b;
        }
        .kop-line-double {
            border-bottom: 3px double #0f172a;
            margin-bottom: 14px;
        }
        /* Judul Dokumen */
        .report-title-box {
            text-align: center;
            margin-bottom: 16px;
        }
        .report-title {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #0f172a;
            margin-bottom: 3px;
        }
        .report-period {
            font-size: 9pt;
            color: #334155;
            font-weight: bold;
        }

        /* Section Headings */
        .section-header {
            background-color: #1e3a8a;
            color: #ffffff;
            font-weight: bold;
            padding: 6px 10px;
            font-size: 9pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 10px;
        }

        /* Tabel Laba Rugi */
        table.pl-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        table.pl-table td {
            padding: 6px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 9pt;
        }
        table.pl-table tr.subtotal-row td {
            background-color: #f8fafc;
            font-weight: bold;
            border-top: 1.5px solid #0f172a;
            border-bottom: 1.5px solid #0f172a;
        }
        .pl-indent {
            padding-left: 20px !important;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .text-emerald { color: #047857; }
        .text-rose { color: #b91c1c; }

        /* Net Profit Box */
        .net-profit-box {
            border: 2px solid #0f172a;
            background-color: #f1f5f9;
            padding: 10px 14px;
            margin: 16px 0;
        }
        .net-profit-table {
            width: 100%;
            border-collapse: collapse;
        }
        .net-profit-table td {
            padding: 0;
            vertical-align: middle;
        }
        .net-profit-title {
            font-size: 11pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
        }
        .net-profit-nominal {
            font-size: 13pt;
            font-weight: bold;
            text-align: right;
            color: #1e3a8a;
        }

        /* Tanda Tangan */
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            page-break-inside: avoid;
        }
        .signature-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            font-size: 9pt;
        }
        .signature-space {
            height: 60px;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
            color: #0f172a;
        }
        .footer-note {
            font-size: 7.5pt;
            color: #94a3b8;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <!-- Kop Surat Perusahaan -->
    <table class="kop-table">
        <tr>
            <td style="text-align: center;">
                <div class="company-name">{{ strtoupper($company['nama_perusahaan'] ?? 'CV. GARUDA RAJA PERKASA DUABELAS') }}</div>
                <div class="company-address">{{ $company['alamat_perusahaan'] ?? 'Jl. Trans Kalimantan Ruko Keling Kumang No.2, Simpang Ampar, Kec. Tayan Hilir, Kabupaten Sanggau, Kalimantan Barat 78564' }}</div>
                <div class="company-contact">Telepon: {{ $company['telepon'] ?? '+62 852-4500-1212' }} &bull; Email: {{ $company['email'] ?? 'kontak@garudaraja.co.id' }}</div>
            </td>
        </tr>
    </table>
    <div class="kop-line-double"></div>

    <!-- Judul Dokumen -->
    <div class="report-title-box">
        <div class="report-title">LAPORAN LABA RUGI SEDERHANA</div>
        <div class="report-period">Periode: {{ $periodeJudul }}</div>
    </div>

    <!-- I. PENDAPATAN OPERASIONAL -->
    <div class="section-header">I. PENDAPATAN OPERASIONAL (PEMASUKAN)</div>
    <table class="pl-table">
        <tbody>
            @forelse ($pemasukanList as $item)
                <tr>
                    <td class="pl-indent">
                        {{ $item->nama }}
                        <span style="font-size: 8pt; color: #64748b;">({{ $item->total_transaksi }} transaksi)</span>
                    </td>
                    <td class="text-right font-bold" style="width: 140px;">
                        Rp {{ number_format($item->total_nominal, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="text-center" style="color: #94a3b8; padding: 10px;">
                        Tidak ada pemasukan tercatat pada periode ini.
                    </td>
                </tr>
            @endforelse
            <tr class="subtotal-row">
                <td>TOTAL PENDAPATAN OPERASIONAL (A)</td>
                <td class="text-right text-emerald" style="font-size: 10pt;">
                    Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>

    <!-- II. BEBAN OPERASIONAL -->
    <div class="section-header" style="background-color: #991b1b;">II. BEBAN OPERASIONAL (PENGELUARAN)</div>
    <table class="pl-table">
        <tbody>
            @forelse ($pengeluaranList as $item)
                <tr>
                    <td class="pl-indent">
                        {{ $item->nama }}
                        <span style="font-size: 8pt; color: #64748b;">({{ $item->total_transaksi }} transaksi)</span>
                    </td>
                    <td class="text-right font-bold" style="width: 140px;">
                        Rp {{ number_format($item->total_nominal, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="text-center" style="color: #94a3b8; padding: 10px;">
                        Tidak ada pengeluaran tercatat pada periode ini.
                    </td>
                </tr>
            @endforelse
            <tr class="subtotal-row">
                <td>TOTAL BEBAN OPERASIONAL (B)</td>
                <td class="text-right text-rose" style="font-size: 10pt;">
                    Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>

    <!-- III. LABA / (RUGI) BERSIH -->
    <div class="net-profit-box">
        <table class="net-profit-table">
            <tr>
                <td class="net-profit-title">
                    {{ $labaBersih >= 0 ? 'LABA BERSIH PERIODE BERJALAN (A - B)' : 'RUGI BERSIH PERIODE BERJALAN (A - B)' }}
                </td>
                <td class="net-profit-nominal {{ $labaBersih >= 0 ? 'text-emerald' : 'text-rose' }}">
                    Rp {{ number_format($labaBersih, 0, ',', '.') }}
                </td>
            </tr>
        </table>
    </div>

    <!-- Kolom Tanda Tangan Resmi -->
    <table class="signature-table">
        <tr>
            <td>
                <div>Mengetahui,</div>
                <div class="font-bold">{{ $company['pimpinan_jabatan'] ?? 'Direktur Utama' }}</div>
                <div class="signature-space"></div>
                <div class="signature-name">{{ $company['pimpinan_nama'] ?? 'H. Sudirman, S.E.' }}</div>
            </td>
            <td>
                <div>{{ $company['kota_laporan'] ?? 'Sanggau' }}, {{ $tanggalCetak }}</div>
                <div class="font-bold">{{ $company['staf_keuangan_jabatan'] ?? 'Staf Keuangan' }}</div>
                <div class="signature-space"></div>
                <div class="signature-name">{{ $company['staf_keuangan_nama'] ?? 'Siti Aminah, A.Md.' }}</div>
            </td>
        </tr>
    </table>

    <div class="footer-note">
        Dokumen ini diterbitkan resmi oleh CV. Garuda Raja Perkasa Duabelas pada {{ date('d/m/Y H:i:s') }} WIB.
    </div>
</body>
</html>

