<x-app-layout>
    <x-slot name="header">
        Laporan Pengeluaran
    </x-slot>

    <div class="space-y-6">
        <!-- Header & Filter Form -->
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900">Laporan Pengeluaran / Beban</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Khusus menyaring seluruh beban operasional dan pengeluaran kas perusahaan.</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('transaksi.create', ['tipe' => 'pengeluaran']) }}" class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs shadow-sm transition">
                        + Catat Pengeluaran
                    </a>
                </div>
            </div>

            <!-- Filter: Tanggal Awal - Tanggal Akhir | Cari Akun -->
            <form method="GET" action="{{ route('pengeluaran.index') }}" class="pt-4 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <!-- Tanggal Awal -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Tanggal Awal</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="w-full text-xs rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                </div>

                <!-- Tanggal Akhir -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Tanggal Akhir</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="w-full text-xs rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                </div>

                <!-- Cari Akun -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Cari Akun</label>
                    <select name="account_id" class="w-full text-xs font-semibold rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                        <option value="">-- Semua Akun Pengeluaran --</option>
                        @foreach ($accounts as $acc)
                            <option value="{{ $acc->id }}" {{ $accountId == $acc->id ? 'selected' : '' }}>
                                {{ $acc->kode }} - {{ $acc->nama }} ({{ $acc->golongan }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tombol Aksi Filter -->
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-amber-400 font-bold text-xs shadow-sm transition">
                        Filter
                    </button>
                    <a href="{{ route('pengeluaran.index') }}" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs transition">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Tabel Sesuai Format Spesifikasi Halaman Laporan Pengeluaran -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-100 text-slate-700 font-black border-b border-slate-200 uppercase tracking-wider text-[11px]">
                            <th class="px-6 py-3.5 w-32">Tanggal</th>
                            <th class="px-6 py-3.5">Keterangan</th>
                            <th class="px-6 py-3.5 w-52">Akun</th>
                            <th class="px-6 py-3.5 w-40">Golongan</th>
                            <th class="px-6 py-3.5 text-right w-44">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($expenses as $item)
                            @php
                                $tglFormat = $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') : '-';
                                $namaAkun = $item->account ? $item->account->nama : ($item->category->nama ?? 'Beban Operasional');
                                $namaGolongan = $item->account ? $item->account->golongan : '5. Beban';
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-6 py-3.5 whitespace-nowrap font-mono font-medium text-slate-700">
                                    {{ $tglFormat }}
                                </td>
                                <td class="px-6 py-3.5 text-slate-900 font-medium">
                                    <div class="font-bold text-slate-900">{{ $item->deskripsi }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $item->nomor_bukti }} &bull; {{ $item->pihak_terkait ?: '-' }}</div>
                                </td>
                                <td class="px-6 py-3.5 whitespace-nowrap font-bold text-slate-800">
                                    {{ $namaAkun }}
                                </td>
                                <td class="px-6 py-3.5 whitespace-nowrap">
                                    <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-900">
                                        {{ $namaGolongan }}
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 whitespace-nowrap text-right font-black text-slate-900">
                                    Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">
                                    Tidak ada catatan pengeluaran pada rentang tanggal yang dipilih.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <!-- Baris Total Pengeluaran Sesuai Format -->
                        <tr class="bg-rose-50 text-rose-950 font-black border-t-2 border-rose-200">
                            <td colspan="4" class="px-6 py-4 text-left uppercase tracking-wider text-xs font-black">
                                Total Pengeluaran
                            </td>
                            <td class="px-6 py-4 text-right text-base text-rose-700 font-black whitespace-nowrap">
                                Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Di bawahnya ada tombol: [ Cetak PDF ] [ Download Excel ] -->
            <div class="p-5 sm:p-6 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-xs text-slate-500">
                    Menampilkan total <strong class="text-slate-800">{{ count($expenses) }} transaksi</strong> pengeluaran periode {{ \Carbon\Carbon::parse($startDate)->format('d-m-Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d-m-Y') }}.
                </div>

                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <a href="{{ route('pengeluaran.export.pdf', request()->query()) }}" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs sm:text-sm shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Cetak PDF
                    </a>

                    <a href="{{ route('pengeluaran.export.excel', request()->query()) }}" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs sm:text-sm shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Download Excel
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
