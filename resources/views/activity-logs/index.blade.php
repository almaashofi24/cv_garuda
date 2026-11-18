<x-app-layout>
    <x-slot name="header">
        Log Aktivitas Sistem
    </x-slot>

    <div class="space-y-6">
        <!-- Header Card -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-black text-slate-900">Audit Trail & Riwayat Aktivitas</h2>
                <p class="text-xs text-slate-500">Mencatat rekam jejak pengguna: siapa yang menambah, mengubah, menghapus data transaksi, serta waktu dan IP pelaksana.</p>
            </div>
            <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-bold">
                Total Tercatat: {{ $logs->total() }} Log
            </span>
        </div>

        <!-- Filter Box -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
            <form method="GET" action="{{ route('log-aktivitas.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Cari Keterangan / IP</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Detail, nama user, IP..." class="w-full text-xs rounded-xl border-slate-200 focus:border-amber-500">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Jenis Aksi</label>
                    <select name="aksi" class="w-full text-xs rounded-xl border-slate-200 focus:border-amber-500">
                        <option value="">Semua Aksi</option>
                        <option value="tambah" {{ request('aksi') === 'tambah' ? 'selected' : '' }}>Tambah Data</option>
                        <option value="ubah" {{ request('aksi') === 'ubah' ? 'selected' : '' }}>Ubah Data</option>
                        <option value="hapus" {{ request('aksi') === 'hapus' ? 'selected' : '' }}>Hapus Data</option>
                        <option value="login" {{ request('aksi') === 'login' ? 'selected' : '' }}>Masuk (Login)</option>
                        <option value="logout" {{ request('aksi') === 'logout' ? 'selected' : '' }}>Keluar (Logout)</option>
                        <option value="ekspor" {{ request('aksi') === 'ekspor' ? 'selected' : '' }}>Ekspor Laporan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Modul / Model</label>
                    <select name="model" class="w-full text-xs rounded-xl border-slate-200 focus:border-amber-500">
                        <option value="">Semua Modul</option>
                        <option value="Transaksi" {{ request('model') === 'Transaksi' ? 'selected' : '' }}>Transaksi</option>
                        <option value="Kategori" {{ request('model') === 'Kategori' ? 'selected' : '' }}>Kategori</option>
                        <option value="Pengguna" {{ request('model') === 'Pengguna' ? 'selected' : '' }}>Pengguna</option>
                        <option value="Pengaturan" {{ request('model') === 'Pengaturan' ? 'selected' : '' }}>Pengaturan</option>
                        <option value="Laporan" {{ request('model') === 'Laporan' ? 'selected' : '' }}>Laporan</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="flex-1 py-2 px-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-amber-400 font-bold text-xs shadow-sm">
                        Filter
                    </button>
                    <a href="{{ route('log-aktivitas.index') }}" class="py-2 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Tabel Log Aktivitas -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200 uppercase tracking-wider text-[11px]">
                            <th class="px-5 py-3">Waktu (WIB)</th>
                            <th class="px-5 py-3">Pengguna</th>
                            <th class="px-5 py-3">Aksi</th>
                            <th class="px-5 py-3">Modul</th>
                            <th class="px-5 py-3">Rincian Aktivitas</th>
                            <th class="px-5 py-3 text-right">Alamat IP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($logs as $log)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-5 py-3 whitespace-nowrap text-slate-500 font-mono text-[11px]">
                                    {{ $log->formatted_created_at }}
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap font-bold text-slate-900">
                                    {{ $log->user ? $log->user->name : 'Sistem / Anonim' }}
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                        @if($log->aksi === 'tambah') bg-emerald-100 text-emerald-800
                                        @elseif($log->aksi === 'ubah') bg-blue-100 text-blue-800
                                        @elseif($log->aksi === 'hapus') bg-rose-100 text-rose-800
                                        @elseif($log->aksi === 'login') bg-indigo-100 text-indigo-800
                                        @elseif($log->aksi === 'ekspor') bg-purple-100 text-purple-800
                                        @else bg-slate-100 text-slate-700 @endif
                                    ">
                                        {{ $log->aksi }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap font-semibold text-slate-700">
                                    {{ $log->model }}
                                </td>
                                <td class="px-5 py-3 text-slate-700 leading-relaxed max-w-md">
                                    {{ $log->detail }}
                                </td>
                                <td class="px-5 py-3 text-right whitespace-nowrap text-slate-400 font-mono text-[11px]">
                                    {{ $log->ip_address ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center text-slate-400 italic">
                                    Belum ada log aktivitas yang tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($logs->hasPages())
                <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

