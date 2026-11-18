<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Neraca Keuangan - {{ $company['nama_perusahaan'] ?? 'CV. GARUDA RAJA PERKASA DUABELAS' }}</title>
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
        .neraca-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .section-header {
            background-color: #1e293b;
            color: #ffffff;
            font-weight: bold;
            padding: 5px 8px;
            text-transform: uppercase;
            font-size: 8pt;
        }
        .sub-header {
            background-color: #f1f5f9;
            font-weight: bold;
            padding: 4px 8px;
            font-size: 8pt;
            color: #334155;
        }
        .item-row td {
            padding: 4px 8px;
            border-bottom: 1px solid #e2e8f0;
        }
        .item-indent {
            padding-left: 20px !important;
        }
        .total-row {
            background-color: #e2e8f0;
            font-weight: bold;
            border-top: 1.5px solid #0f172a;
            border-bottom: 2px solid #0f172a;
        }
        .total-row td {
            padding: 6px 8px;
        }
        .text-right {
            text-align: right;
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
            height: 55px;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
            color: #0f172a;
        }
    </style>
</head>
<body>
    <!-- Kop Surat -->
    <table class="kop-table">
        <tr>
            <td style="width: 75px; text-align: left;">
                <div style="width: 50px; height: 50px; background-color: #0f172a; border-radius: 8px; text-align: center; line-height: 50px; color: #f59e0b; font-weight: bold; font-size: 16pt;">
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

    <div class="report-title-box">
        <div class="report-title">LAPORAN NERACA (BALANCE SHEET)</div>
        <div class="report-period">
            Posisi Per Tanggal: {{ \Carbon\Carbon::parse($asOfDate)->translatedFormat('d F Y') }}
        </div>
    </div>

    <!-- Tabel Neraca Format SAK (Harta = Utang + Modal) -->
    <table class="neraca-table">
        <!-- 1. HARTA / AKTIVA -->
        <tr>
            <th colspan="2" class="section-header">I. HARTA / AKTIVA (GOLONGAN 1)</th>
        </tr>
        @foreach ($hartaAccounts as $acc)
            <tr class="item-row">
                <td class="item-indent">{{ $acc['kode'] }} - {{ $acc['nama'] }}</td>
                <td class="text-right">Rp {{ number_format($acc['saldo'], 0, ',', '.') }}</td>
            </tr>
        @endforeach
        <tr class="total-row">
            <td>TOTAL HARTA (AKTIVA)</td>
            <td class="text-right" style="color: #047857;">Rp {{ number_format($totalHarta, 0, ',', '.') }}</td>
        </tr>

        <tr><td colspan="2" style="height: 10px;"></td></tr>

        <!-- 2. UTANG / KEWAJIBAN -->
        <tr>
            <th colspan="2" class="section-header">II. KEWAJIBAN / UTANG (GOLONGAN 2)</th>
        </tr>
        @forelse ($utangAccounts as $acc)
            <tr class="item-row">
                <td class="item-indent">{{ $acc['kode'] }} - {{ $acc['nama'] }}</td>
                <td class="text-right">Rp {{ number_format($acc['saldo'], 0, ',', '.') }}</td>
            </tr>
        @empty
            <tr class="item-row">
                <td class="item-indent" style="color: #94a3b8; font-style: italic;">Tidak ada saldo utang</td>
                <td class="text-right">Rp 0</td>
            </tr>
        @endforelse
        <tr style="background-color: #f8fafc; font-weight: bold;">
            <td style="padding: 5px 8px;">Subtotal Kewajiban / Utang</td>
            <td class="text-right" style="padding: 5px 8px;">Rp {{ number_format($totalUtang, 0, ',', '.') }}</td>
        </tr>

        <tr><td colspan="2" style="height: 8px;"></td></tr>

        <!-- 3. MODAL / EKUITAS -->
        <tr>
            <th colspan="2" class="section-header">III. MODAL / EKUITAS (GOLONGAN 3 & 6)</th>
        </tr>
        @foreach ($modalAccounts as $acc)
            <tr class="item-row">
                <td class="item-indent">{{ $acc['kode'] }} - {{ $acc['nama'] }}</td>
                <td class="text-right">Rp {{ number_format($acc['saldo'], 0, ',', '.') }}</td>
            </tr>
        @endforeach
        @foreach ($priveAccounts as $acc)
            <tr class="item-row">
                <td class="item-indent">{{ $acc['kode'] }} - {{ $acc['nama'] }} (Pengurang Modal)</td>
                <td class="text-right" style="color: #dc2626;">- Rp {{ number_format($acc['saldo'], 0, ',', '.') }}</td>
            </tr>
        @endforeach
        <tr class="item-row">
            <td class="item-indent">Laba Bersih Periode Berjalan (dari Laba Rugi)</td>
            <td class="text-right" style="color: {{ $labaBersihBerjalan >= 0 ? '#047857' : '#dc2626' }};">
                Rp {{ number_format($labaBersihBerjalan, 0, ',', '.') }}
            </td>
        </tr>
        <tr style="background-color: #f8fafc; font-weight: bold;">
            <td style="padding: 5px 8px;">Subtotal Modal / Ekuitas</td>
            <td class="text-right" style="padding: 5px 8px;">Rp {{ number_format($totalModal, 0, ',', '.') }}</td>
        </tr>

        <!-- TOTAL KEWAJIBAN & MODAL -->
        <tr class="total-row">
            <td>TOTAL KEWAJIBAN & MODAL (PASIVA)</td>
            <td class="text-right" style="color: #1d4ed8;">Rp {{ number_format($totalPasiva, 0, ',', '.') }}</td>
        </tr>
    </table>

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
