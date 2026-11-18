<x-app-layout>
    <x-slot name="header">
        Profil Perusahaan & Pengaturan Laporan
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <h2 class="text-xl font-black text-slate-900">Identitas Resmi CV. GARUDA RAJA PERKASA DUABELAS</h2>
                <p class="text-xs text-slate-500">Informasi ini menjadi kop surat resmi seluruh cetakan voucher kas, berkas PDF, ekspor Excel, dan identitas sistem.</p>
            </div>
            <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold">
                Akses Khusus Admin
            </span>
        </div>

        <!-- Form Card -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm">
            <form method="POST" action="{{ route('pengaturan.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Section: Identitas Lembaga / Perusahaan -->
                <div>
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider pb-2 border-b border-slate-100 mb-4 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                        1. Informasi Instansi / Badan Usaha
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Resmi Perusahaan <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_perusahaan" value="{{ old('nama_perusahaan', $settings['nama_perusahaan']->value ?? 'CV. GARUDA RAJA PERKASA DUABELAS') }}" required class="w-full text-xs font-bold rounded-xl border-slate-300 focus:border-amber-500">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Alamat Kantor Operasional <span class="text-rose-500">*</span></label>
                            <textarea name="alamat_perusahaan" rows="2" required class="w-full text-xs rounded-xl border-slate-300 focus:border-amber-500">{{ old('alamat_perusahaan', $settings['alamat_perusahaan']->value ?? '') }}</textarea>
                            <p class="text-[11px] text-slate-400 mt-1">Alamat lengkap kantor yang tertera di baris kedua kop laporan.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nomor Telepon / WhatsApp</label>
                            <input type="text" name="telepon" value="{{ old('telepon', $settings['telepon']->value ?? '') }}" class="w-full text-xs rounded-xl border-slate-300 focus:border-amber-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email Resmi</label>
                            <input type="email" name="email" value="{{ old('email', $settings['email']->value ?? '') }}" class="w-full text-xs rounded-xl border-slate-300 focus:border-amber-500">
                        </div>
                    </div>
                </div>

                <!-- Section: Pejabat Penandatangan Laporan Keuangan -->
                <div class="pt-4">
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider pb-2 border-b border-slate-100 mb-4 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                        2. Pejabat Penandatangan Laporan Keuangan
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Pimpinan / Direktur <span class="text-rose-500">*</span></label>
                            <input type="text" name="pimpinan_nama" value="{{ old('pimpinan_nama', $settings['pimpinan_nama']->value ?? '') }}" required class="w-full text-xs font-semibold rounded-xl border-slate-300 focus:border-amber-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jabatan Pimpinan <span class="text-rose-500">*</span></label>
                            <input type="text" name="pimpinan_jabatan" value="{{ old('pimpinan_jabatan', $settings['pimpinan_jabatan']->value ?? 'Direktur Utama') }}" required class="w-full text-xs rounded-xl border-slate-300 focus:border-amber-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Staf Keuangan / Bendahara <span class="text-rose-500">*</span></label>
                            <input type="text" name="staf_keuangan_nama" value="{{ old('staf_keuangan_nama', $settings['staf_keuangan_nama']->value ?? '') }}" required class="w-full text-xs font-semibold rounded-xl border-slate-300 focus:border-amber-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jabatan Staf Keuangan <span class="text-rose-500">*</span></label>
                            <input type="text" name="staf_keuangan_jabatan" value="{{ old('staf_keuangan_jabatan', $settings['staf_keuangan_jabatan']->value ?? 'Staf Keuangan & Administrasi') }}" required class="w-full text-xs rounded-xl border-slate-300 focus:border-amber-500">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kota Penerbitan Laporan <span class="text-rose-500">*</span></label>
                            <input type="text" name="kota_laporan" value="{{ old('kota_laporan', $settings['kota_laporan']->value ?? 'Sanggau') }}" required class="w-full text-xs rounded-xl border-slate-300 focus:border-amber-500">
                            <p class="text-[11px] text-slate-400 mt-1">Dicantumkan pada titimangsa tanda tangan (contoh: Sanggau, 29 Oktober 2026).</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-200">
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-amber-400 font-extrabold text-xs sm:text-sm shadow-md transition">
                        Simpan Perubahan Identitas
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

