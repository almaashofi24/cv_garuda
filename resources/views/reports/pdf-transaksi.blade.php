<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Rekapitulasi Arus Kas - CV. Garuda Raja Perkasa Duabelas</title>
    <style>
        @page {
            margin: 1.2cm 1.2cm 1.5cm 1.2cm;
            size: a4 landscape;
        }
        body {
            font-family: 'DejaVu Sans', Arial, Helvetica, sans-serif;
            font-size: 9pt;
            color: #1e293b;
            line-height: 1.3;
        }
        /* Kop Surat */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .kop-table td {
            vertical-align: middle;
        }
        .company-name {
            font-size: 15pt;
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
            margin-bottom: 12px;
        }
        /* Judul Laporan */
        .report-title-box {
            text-align: center;
            margin-bottom: 14px;
        }
        .report-title {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #0f172a;
            margin-bottom: 3px;
        }
        .report-period {
            font-size: 8.5pt;
            color: #334155;
            font-weight: bold;
        }
        /* Tabel Data */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        table.data-table th {
            background-color: #1e3a8a;
            color: #ffffff;
            font-weight: bold;
            font-size: 8pt;
            text-transform: uppercase;
            padding: 6px 5px;
            border: 1px solid #0f172a;
            text-align: left;
        }
        table.data-table td {
            padding: 5px 5px;
            border: 1px solid #cbd5e1;
            font-size: 8pt;
            vertical-align: top;
        }
        table.data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .text-emerald { color: #047857; }
        .text-rose { color: #b91c1c; }
        .text-blue { color: #1d4ed8; }

        /* Total Section */
        .total-row {
            background-color: #f1f5f9;
            font-weight: bold;
        }
        .total-row td {
            border-top: 2px solid #0f172a;
            border-bottom: 2px solid #0f172a;
        }

        /* Tanda Tangan */
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 18px;
            page-break-inside: avoid;
        }
        .signature-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            font-size: 8.5pt;
        }
        .signature-space {
            height: 55px;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
            color: #0f172a;
        }
        .footer-note {
            font-size: 7pt;
            color: #94a3b8;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <!-- Kop Surat Resmi -->
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
        <div class="report-title">LAPORAN REKAPITULASI ARUS KAS (PEMASUKAN & PENGELUARAN)</div>
        <div class="report-period">Periode: {{ $periodeJudul }}</div>
    </div>

    <!-- Tabel Rincian Transaksi -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;" class="text-center">No</th>
                <th style="width: 95px;">No. Bukti</th>
                <th style="width: 60px;">Tanggal</th>
                <th style="width: 90px;">Kategori</th>
                <th style="width: 130px;">Pihak Terkait</th>
                <th>Uraian / Keterangan</th>
                <th style="width: 50px;" class="text-center">Metode</th>
                <th style="width: 85px;" class="text-right">Pemasukan (Rp)</th>
                <th style="width: 85px;" class="text-right">Pengeluaran (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $idx => $tx)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td class="font-bold">{{ $tx->nomor_bukti }}</td>
                    <td>{{ $tx->formatted_tanggal }}</td>
                    <td>{{ $tx->category ? $tx->category->nama : '-' }}</td>
                    <td>{{ $tx->pihak_terkait }}</td>
                    <td>{{ $tx->deskripsi }}</td>
                    <td class="text-center">{{ strtoupper($tx->metode_pembayaran) }}</td>
                    <td class="text-right text-emerald font-bold">
                        {{ $tx->tipe === 'pemasukan' ? number_format($tx->jumlah, 0, ',', '.') : '-' }}
                    </td>
                    <td class="text-right text-rose font-bold">
                        {{ $tx->tipe === 'pengeluaran' ? number_format($tx->jumlah, 0, ',', '.') : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 20px;">
                        Tidak ada transaksi pada periode yang dipilih.
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="7" class="text-right font-bold">TOTAL KESELURUHAN:</td>
                <td class="text-right font-bold text-emerald">
                    Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                </td>
                <td class="text-right font-bold text-rose">
                    Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                </td>
            </tr>
            <tr style="background-color: #e2e8f0; font-weight: bold;">
                <td colspan="7" class="text-right">SALDO / SURPLUS BERSIH (PEMASUKAN - PENGELUARAN):</td>
                <td colspan="2" class="text-right text-blue" style="font-size: 9pt;">
                    Rp {{ number_format($saldoBersih, 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    </table>

    <!-- Kolom Tanda Tangan Pimpinan & Staf -->
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
        Dokumen ini dicetak otomatis dari Sistem Informasi Keuangan CV. Garuda Raja Perkasa Duabelas pada {{ date('d/m/Y H:i:s') }} WIB.
    </div>
</body>
</html>

