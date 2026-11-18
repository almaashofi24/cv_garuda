<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Buku Besar - {{ $company['nama_perusahaan'] ?? 'CV. GARUDA RAJA PERKASA DUABELAS' }}</title>
    <style>
        @page {
            margin: 1.2cm 1.2cm 1.5cm 1.2cm;
            size: a4 portrait;
        }
        body {
            font-family: 'DejaVu Sans', Arial, Helvetica, sans-serif;
            font-size: 8pt;
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
        .account-box {
            margin-bottom: 16px;
            page-break-inside: avoid;
        }
        .account-header {
            background-color: #f1f5f9;
            padding: 5px 8px;
            border: 1px solid #cbd5e1;
            font-weight: bold;
            margin-bottom: 0px;
        }
        table.ledger-table {
            width: 100%;
            border-collapse: collapse;
        }
        table.ledger-table th {
            background-color: #1e293b;
            color: #ffffff;
            font-size: 7.5pt;
            text-transform: uppercase;
            padding: 5px 6px;
            border: 1px solid #0f172a;
            text-align: left;
        }
        table.ledger-table td {
            padding: 4px 6px;
            border: 1px solid #cbd5e1;
            font-size: 7.5pt;
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
        .signature-table {
            width: 100%;
            margin-top: 20px;
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
        <div class="report-title">BUKU BESAR (GENERAL LEDGER)</div>
        <div class="report-period">
            Periode: {{ \Carbon\Carbon::parse($startDate)->format('d-m-Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d-m-Y') }}
        </div>
    </div>

    @foreach ($ledgerData as $item)
        @php $acc = $item['account']; @endphp
        <div class="account-box">
            <div class="account-header">
                <span style="font-family: monospace; background-color: #0f172a; color: #f59e0b; padding: 1px 4px; border-radius: 3px;">
                    {{ $acc->kode }}
                </span>
                <span style="margin-left: 6px;">{{ $acc->nama }}</span>
                <span style="float: right; font-weight: normal; color: #64748b;">
                    {{ $acc->golongan }} | Saldo Normal: <strong>{{ $acc->saldo_normal }}</strong>
                </span>
            </div>

            <table class="ledger-table">
                <thead>
                    <tr>
                        <th style="width: 65px;">Tanggal</th>
                        <th style="width: 85px;">No. Bukti</th>
                        <th>Keterangan</th>
                        <th style="width: 80px;" class="text-right">Debit</th>
                        <th style="width: 80px;" class="text-right">Kredit</th>
                        <th style="width: 90px;" class="text-right">Saldo Berjalan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="background-color: #f8fafc; font-style: italic;">
                        <td>{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}</td>
                        <td>-</td>
                        <td>Saldo Awal Periode</td>
                        <td class="text-right">-</td>
                        <td class="text-right">-</td>
                        <td class="text-right font-bold">Rp {{ number_format($item['saldo_awal'], 0, ',', '.') }}</td>
                    </tr>
                    @forelse ($item['rows'] as $r)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($r['tanggal'])->format('d/m/Y') }}</td>
                            <td>{{ $r['nomor_bukti'] }}</td>
                            <td>{{ $r['keterangan'] }}</td>
                            <td class="text-right">{{ $r['debit'] > 0 ? 'Rp ' . number_format($r['debit'], 0, ',', '.') : '-' }}</td>
                            <td class="text-right">{{ $r['kredit'] > 0 ? 'Rp ' . number_format($r['kredit'], 0, ',', '.') : '-' }}</td>
                            <td class="text-right font-bold">Rp {{ number_format($r['saldo'], 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center" style="padding: 8px; color: #94a3b8; font-style: italic;">
                                Tidak ada mutasi transaksi pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                    <tr style="background-color: #f1f5f9; font-weight: bold;">
                        <td colspan="3" class="text-right">TOTAL MUTASI & SALDO AKHIR:</td>
                        <td class="text-right">Rp {{ number_format($item['total_debit'], 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($item['total_kredit'], 0, ',', '.') }}</td>
                        <td class="text-right" style="color: #1e3a8a;">Rp {{ number_format($item['saldo_akhir'], 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endforeach

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
