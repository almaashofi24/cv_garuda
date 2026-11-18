<x-app-layout>
    <x-slot name="header">
        Detail Voucher {{ $transaction->nomor_bukti }}
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Top Action Bar (hidden when printing) -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between gap-4 print:hidden">
            <div class="flex items-center gap-2">
                <a href="{{ $transaction->tipe === 'pemasukan' ? route('transaksi.pemasukan') : route('transaksi.pengeluaran') }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                    &larr; Kembali
                </a>
                <span class="text-xs text-slate-400">|</span>
                <span class="text-xs font-bold text-slate-700">Status: Selesai Dibukukan</span>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-amber-400 text-xs font-bold shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Cetak Voucher
                </button>
                @role('Admin|Staf Keuangan')
                <a href="{{ route('transaksi.edit', $transaction) }}" class="px-3.5 py-2 rounded-xl bg-blue-50 text-blue-700 hover:bg-blue-100 text-xs font-bold transition">
                    Edit Data
                </a>
                @endrole
            </div>
        </div>

        <!-- Voucher Slip (Printable) -->
        <div class="bg-white p-6 sm:p-10 rounded-2xl border border-slate-200 shadow-lg print:border-none print:shadow-none print:p-0">
            <!-- Kop Perusahaan -->
            <div class="border-b-2 border-slate-900 pb-5 mb-6 text-center">
                <h1 class="text-xl sm:text-2xl font-black tracking-wider text-slate-900">
                    {{ strtoupper($company['nama_perusahaan'] ?? 'CV. GARUDA RAJA PERKASA DUABELAS') }}
                </h1>
                <p class="text-xs text-slate-600 mt-1 max-w-2xl mx-auto">
                    {{ $company['alamat_perusahaan'] ?? 'Jl. Trans Kalimantan Ruko Keling Kumang No.2, Simpang Ampar, Kec. Tayan Hilir, Kabupaten Sanggau, Kalimantan Barat 78564' }}
                </p>
                <p class="text-xs text-slate-500 mt-0.5 font-medium">
                    Telp: {{ $company['telepon'] ?? '+62 852-4500-1212' }} &bull; Email: {{ $company['email'] ?? 'kontak@garudaraja.co.id' }}
                </p>
            </div>

            <!-- Judul Voucher & Badge -->
            <div class="flex items-center justify-between pb-4 border-b border-slate-200 mb-6">
                <div>
                    <h2 class="text-lg font-black tracking-wide text-slate-900 uppercase">
                        {{ $transaction->tipe === 'pemasukan' ? 'BUKTI KAS MASUK (BKM)' : 'BUKTI KAS KELUAR (BKK)' }}
                    </h2>
                    <p class="text-xs text-slate-500 font-mono mt-0.5">No: {{ $transaction->nomor_bukti }}</p>
                </div>
                <div class="text-right">
                    <span class="block text-xs font-bold text-slate-500">Tanggal Transaksi</span>
                    <span class="block text-sm font-extrabold text-slate-800">{{ $transaction->formatted_tanggal }}</span>
                </div>
            </div>

            <!-- Detail Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs mb-6">
                <div class="space-y-3">
                    <div>
                        <span class="block text-slate-500 font-bold uppercase text-[10px]">
                            {{ $transaction->tipe === 'pemasukan' ? 'Diterima Dari' : 'Dibayarkan Kepada' }}
                        </span>
                        <span class="text-sm font-extrabold text-slate-900">{{ $transaction->pihak_terkait }}</span>
                    </div>
                    <div>
                        <span class="block text-slate-500 font-bold uppercase text-[10px]">Kategori Pembukuan</span>
                        <span class="font-bold text-slate-800 text-xs">{{ $transaction->category ? $transaction->category->nama : '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-slate-500 font-bold uppercase text-[10px]">Metode Pembayaran</span>
                        <span class="inline-block mt-0.5 px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $transaction->metode_pembayaran === 'tunai' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ $transaction->metode_pembayaran === 'tunai' ? 'Tunai / Kas Kecil' : 'Transfer Bank' }}
                        </span>
                    </div>
                </div>

                <div class="space-y-3 sm:text-right">
                    <div>
                        <span class="block text-slate-500 font-bold uppercase text-[10px]">Total Nominal</span>
                        <span class="text-2xl font-black {{ $transaction->tipe === 'pemasukan' ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $transaction->formatted_jumlah }}
                        </span>
                    </div>
                    <div>
                        <span class="block text-slate-500 font-bold uppercase text-[10px]">Petugas Pencatat</span>
                        <span class="font-bold text-slate-800">{{ $transaction->user ? $transaction->user->name : '-' }}</span>
                        <span class="block text-[11px] text-slate-400">Dicatat pada: {{ $transaction->created_at->format('d/m/Y H:i') }} WIB</span>
                    </div>
                </div>
            </div>

            <!-- Deskripsi Uraian -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 mb-6 text-xs">
                <span class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Uraian / Keterangan Transaksi:</span>
                <p class="text-slate-800 leading-relaxed font-medium whitespace-pre-line">{{ $transaction->deskripsi }}</p>
            </div>

            <!-- Jurnal Akuntansi Berpasangan (Double-Entry) -->
            @if($transaction->journalEntries->isNotEmpty())
                <div class="mb-8 p-4 rounded-xl bg-slate-50 border border-slate-200">
                    <span class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Entri Jurnal Berpasangan (Double-Entry):</span>
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 font-bold text-slate-600">
                                <th class="text-left py-1.5">Kode & Nama Akun</th>
                                <th class="text-left py-1.5">Golongan</th>
                                <th class="text-right py-1.5">Debit (Rp)</th>
                                <th class="text-right py-1.5">Kredit (Rp)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($transaction->journalEntries as $je)
                                <tr>
                                    <td class="py-1.5 font-bold text-slate-800">
                                        {{ $je->account ? $je->account->kode_nama : '-' }}
                                    </td>
                                    <td class="py-1.5 text-slate-600">
                                        {{ $je->account ? $je->account->golongan : '-' }}
                                    </td>
                                    <td class="py-1.5 text-right font-mono {{ $je->debit > 0 ? 'text-emerald-700 font-bold' : 'text-slate-400' }}">
                                        {{ $je->formatted_debit }}
                                    </td>
                                    <td class="py-1.5 text-right font-mono {{ $je->kredit > 0 ? 'text-rose-700 font-bold' : 'text-slate-400' }}">
                                        {{ $je->formatted_kredit }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <!-- Lampiran Bukti Transaksi -->
            @if ($transaction->lampiran)
                <div class="mb-8 pt-4 border-t border-slate-100 print:hidden">
                    <span class="block text-xs font-bold text-slate-700 uppercase mb-3">Lampiran Bukti Fisik / Nota:</span>
                    @php
                        $ext = pathinfo($transaction->lampiran, PATHINFO_EXTENSION);
                    @endphp
                    @if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp']))
                        <div class="max-w-md rounded-xl overflow-hidden border border-slate-200 shadow-sm">
                            <img src="{{ Storage::url($transaction->lampiran) }}" alt="Bukti Transaksi" class="w-full h-auto object-cover">
                        </div>
                    @else
                        <div class="flex items-center gap-3 p-4 rounded-xl bg-slate-50 border border-slate-200">
                            <div class="w-10 h-10 rounded-lg bg-red-100 text-red-600 flex items-center justify-center font-bold">
                                PDF
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-900">Dokumen Lampiran (PDF)</p>
                                <a href="{{ Storage::url($transaction->lampiran) }}" target="_blank" class="text-xs text-blue-600 hover:underline font-semibold">
                                    Unduh atau Buka Dokumen &rarr;
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Kolom Tanda Tangan -->
            <div class="grid grid-cols-2 gap-8 text-center text-xs pt-6 border-t border-slate-300">
                <div>
                    <p class="text-slate-500 font-medium">Disetujui Oleh,</p>
                    <p class="font-bold text-slate-700">{{ $company['pimpinan_jabatan'] ?? 'Direktur Utama' }}</p>
                    <div class="h-20"></div>
                    <p class="font-extrabold text-slate-900 underline">{{ $company['pimpinan_nama'] ?? 'H. Sudirman, S.E.' }}</p>
                </div>
                <div>
                    <p class="text-slate-500 font-medium">{{ $company['kota_laporan'] ?? 'Sanggau' }}, {{ $transaction->formatted_tanggal }}</p>
                    <p class="font-bold text-slate-700">{{ $company['staf_keuangan_jabatan'] ?? 'Staf Keuangan' }}</p>
                    <div class="h-20"></div>
                    <p class="font-extrabold text-slate-900 underline">{{ $company['staf_keuangan_nama'] ?? 'Siti Aminah, A.Md.' }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

