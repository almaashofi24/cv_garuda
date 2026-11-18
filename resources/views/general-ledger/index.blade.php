<x-app-layout>
    <x-slot name="header">
        Buku Besar (General Ledger)
    </x-slot>

    <div class="space-y-6">
        <!-- Header & Filter Form -->
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900">Buku Besar Akuntansi</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Semua riwayat mutasi debit, kredit, dan saldo berjalan per akun 7 golongan.</p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('buku-besar.export.pdf', request()->query()) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Cetak PDF
                    </a>
                    <a href="{{ route('buku-besar.export.excel', request()->query()) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Download Excel
                    </a>
                </div>
            </div>

            <!-- Filter Controls -->
            <form method="GET" action="{{ route('buku-besar.index') }}" class="pt-4 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <!-- Pilih Akun -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Pilih Akun</label>
                    <select name="account_id" class="w-full text-xs font-semibold rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                        <option value="all" {{ $accountId === 'all' ? 'selected' : '' }}>-- Semua Akun (Daftar Lengkap) --</option>
                        @foreach ($accounts as $acc)
                            <option value="{{ $acc->id }}" {{ $accountId == $acc->id ? 'selected' : '' }}>
                                {{ $acc->kode }} - {{ $acc->nama }} ({{ $acc->golongan }})
                            </option>
                        @endforeach
                    </select>
                </div>

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

                <!-- Tombol Terapkan Filter -->
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-amber-400 font-bold text-xs shadow-sm transition">
                        Filter Buku Besar
                    </button>
                    <a href="{{ route('buku-besar.index') }}" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs transition">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Tampilan Setiap Akun Buku Besar -->
        @foreach ($ledgerData as $item)
            @php
                $acc = $item['account'];
            @endphp
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <!-- Header Akun & 4 Metrik Ringkasan Saldo -->
                <div class="p-5 sm:p-6 bg-slate-50 border-b border-slate-200">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-lg bg-slate-900 text-amber-400 font-mono font-bold text-xs">
                                    {{ $acc->kode }}
                                </span>
                                <h3 class="text-lg font-black text-slate-900">{{ $acc->nama }}</h3>
                            </div>
                            <div class="flex items-center gap-2 mt-1 text-xs text-slate-500">
                                <span class="font-bold text-slate-700">{{ $acc->golongan }}</span>
                                <span>&bull;</span>
                                <span>Saldo Normal: <strong class="text-slate-800">{{ $acc->saldo_normal }}</strong></span>
                            </div>
                        </div>

                        <!-- 4 Kartu Metrik Akun -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-xs">
                            <div class="bg-white px-3 py-2 rounded-xl border border-slate-200">
                                <div class="text-[10px] text-slate-400 font-bold uppercase">Saldo Awal</div>
                                <div class="font-bold text-slate-800 mt-0.5">Rp {{ number_format($item['saldo_awal'], 0, ',', '.') }}</div>
                            </div>
                            <div class="bg-white px-3 py-2 rounded-xl border border-slate-200">
                                <div class="text-[10px] text-emerald-600 font-bold uppercase">Mutasi Debit</div>
                                <div class="font-bold text-emerald-700 mt-0.5">Rp {{ number_format($item['total_debit'], 0, ',', '.') }}</div>
                            </div>
                            <div class="bg-white px-3 py-2 rounded-xl border border-slate-200">
                                <div class="text-[10px] text-rose-600 font-bold uppercase">Mutasi Kredit</div>
                                <div class="font-bold text-rose-700 mt-0.5">Rp {{ number_format($item['total_kredit'], 0, ',', '.') }}</div>
                            </div>
                            <div class="bg-white px-3 py-2 rounded-xl border border-amber-300 bg-amber-50/50">
                                <div class="text-[10px] text-amber-700 font-bold uppercase">Saldo Akhir</div>
                                <div class="font-black text-amber-800 mt-0.5">Rp {{ number_format($item['saldo_akhir'], 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabel Riwayat Jurnal Buku Besar -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-100/70 text-slate-600 font-bold border-b border-slate-200 uppercase tracking-wider text-[11px]">
                                <th class="px-5 py-3 w-28">Tanggal</th>
                                <th class="px-5 py-3 w-36">No. Bukti</th>
                                <th class="px-5 py-3">Keterangan Transaksi</th>
                                <th class="px-5 py-3 text-right w-36">Debit</th>
                                <th class="px-5 py-3 text-right w-36">Kredit</th>
                                <th class="px-5 py-3 text-right w-40">Saldo Berjalan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <!-- Baris Saldo Awal -->
                            <tr class="bg-slate-50/50 text-slate-600 font-semibold italic">
                                <td class="px-5 py-2.5 whitespace-nowrap">{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}</td>
                                <td class="px-5 py-2.5">-</td>
                                <td class="px-5 py-2.5">Saldo Awal Periode</td>
                                <td class="px-5 py-2.5 text-right">-</td>
                                <td class="px-5 py-2.5 text-right">-</td>
                                <td class="px-5 py-2.5 text-right font-bold text-slate-800">
                                    Rp {{ number_format($item['saldo_awal'], 0, ',', '.') }}
                                </td>
                            </tr>

                            @forelse ($item['rows'] as $r)
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="px-5 py-2.5 whitespace-nowrap text-slate-600">
                                        {{ \Carbon\Carbon::parse($r['tanggal'])->format('d/m/Y') }}
                                    </td>
                                    <td class="px-5 py-2.5 whitespace-nowrap font-mono font-bold text-slate-800">
                                        {{ $r['nomor_bukti'] }}
                                    </td>
                                    <td class="px-5 py-2.5 text-slate-800 max-w-md">
                                        {{ $r['keterangan'] }}
                                    </td>
                                    <td class="px-5 py-2.5 whitespace-nowrap text-right font-semibold {{ $r['debit'] > 0 ? 'text-emerald-700' : 'text-slate-400' }}">
                                        {{ $r['debit'] > 0 ? 'Rp ' . number_format($r['debit'], 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="px-5 py-2.5 whitespace-nowrap text-right font-semibold {{ $r['kredit'] > 0 ? 'text-rose-700' : 'text-slate-400' }}">
                                        {{ $r['kredit'] > 0 ? 'Rp ' . number_format($r['kredit'], 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="px-5 py-2.5 whitespace-nowrap text-right font-bold text-slate-900">
                                        Rp {{ number_format($r['saldo'], 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-6 text-center text-slate-400 italic">
                                        Tidak ada mutasi transaksi pada periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="bg-slate-100 font-black text-slate-900 border-t-2 border-slate-300">
                                <td colspan="3" class="px-5 py-3 text-right uppercase tracking-wider text-[11px]">
                                    Total Mutasi & Saldo Akhir ({{ $acc->kode }}):
                                </td>
                                <td class="px-5 py-3 text-right text-emerald-800">
                                    Rp {{ number_format($item['total_debit'], 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-3 text-right text-rose-800">
                                    Rp {{ number_format($item['total_kredit'], 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-3 text-right text-amber-800 bg-amber-100/60 font-black">
                                    Rp {{ number_format($item['saldo_akhir'], 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
