<x-app-layout>
    <x-slot name="header">
        Ubah Kata Sandi
    </x-slot>

    <div class="max-w-3xl mx-auto space-y-6" x-data="{
        showCurrent: false,
        showNew: false,
        showConfirm: false,
        password: '',
        passwordConfirmation: '',
        get isMatched() {
            if (!this.passwordConfirmation) return null;
            return this.password === this.passwordConfirmation;
        }
    }">
        <!-- Header Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-black text-slate-900">Ubah Kata Sandi Akun</h2>
                <p class="text-xs text-slate-500">Perbarui kata sandi secara berkala untuk menjaga keamanan data keuangan perusahaan.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                &larr; Kembali ke Dashboard
            </a>
        </div>

        <!-- Info Akun Pengguna -->
        <div class="bg-slate-900 rounded-2xl p-5 text-white shadow-md flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-amber-500 text-slate-950 font-black text-lg flex items-center justify-center shadow-md shadow-amber-500/20">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <h3 class="font-extrabold text-sm sm:text-base text-amber-400">{{ Auth::user()->name }}</h3>
                    <p class="text-xs text-slate-300 font-mono">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <div>
                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase bg-white/10 text-amber-300 border border-white/10">
                    {{ Auth::user()->roles->pluck('name')->first() ?? 'Pengguna' }}
                </span>
            </div>
        </div>

        <!-- Form Ubah Password -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm">
            <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Kata Sandi Saat Ini -->
                <div>
                    <label for="current_password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Kata Sandi Saat Ini <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            :type="showCurrent ? 'text' : 'password'" 
                            id="current_password" 
                            name="current_password" 
                            required 
                            autocomplete="current-password"
                            placeholder="Masukkan kata sandi yang sedang digunakan saat ini"
                            class="w-full pr-12 text-sm rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500 @error('current_password') border-rose-500 @enderror"
                        >
                        <button 
                            type="button" 
                            @click="showCurrent = !showCurrent" 
                            class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600 focus:outline-none"
                            tabindex="-1"
                        >
                            <svg x-show="!showCurrent" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showCurrent" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                    @if ($errors->updatePassword->has('current_password'))
                        <p class="text-xs text-rose-600 font-semibold mt-1.5">{{ $errors->updatePassword->first('current_password') }}</p>
                    @elseif ($errors->has('current_password'))
                        <p class="text-xs text-rose-600 font-semibold mt-1.5">{{ $errors->first('current_password') }}</p>
                    @endif
                </div>

                <div class="pt-2 border-t border-slate-100"></div>

                <!-- Kata Sandi Baru -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Kata Sandi Baru <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            :type="showNew ? 'text' : 'password'" 
                            id="password" 
                            name="password" 
                            x-model="password"
                            required 
                            autocomplete="new-password"
                            placeholder="Minimal 8 karakter"
                            class="w-full pr-12 text-sm rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500"
                        >
                        <button 
                            type="button" 
                            @click="showNew = !showNew" 
                            class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600 focus:outline-none"
                            tabindex="-1"
                        >
                            <svg x-show="!showNew" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showNew" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                    @if ($errors->updatePassword->has('password'))
                        <p class="text-xs text-rose-600 font-semibold mt-1.5">{{ $errors->updatePassword->first('password') }}</p>
                    @elseif ($errors->has('password'))
                        <p class="text-xs text-rose-600 font-semibold mt-1.5">{{ $errors->first('password') }}</p>
                    @endif
                    <p class="text-[11px] text-slate-400 mt-1">Gunakan kombinasi minimal 8 karakter (disarankan huruf besar, huruf kecil, dan angka).</p>
                </div>

                <!-- Konfirmasi Kata Sandi Baru -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Konfirmasi Kata Sandi Baru <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            :type="showConfirm ? 'text' : 'password'" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            x-model="passwordConfirmation"
                            required 
                            autocomplete="new-password"
                            placeholder="Ketik ulang kata sandi baru"
                            class="w-full pr-12 text-sm rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500"
                        >
                        <button 
                            type="button" 
                            @click="showConfirm = !showConfirm" 
                            class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600 focus:outline-none"
                            tabindex="-1"
                        >
                            <svg x-show="!showConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showConfirm" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                    @if ($errors->updatePassword->has('password_confirmation'))
                        <p class="text-xs text-rose-600 font-semibold mt-1.5">{{ $errors->updatePassword->first('password_confirmation') }}</p>
                    @endif
                    <!-- Live indicator -->
                    <template x-if="passwordConfirmation.length > 0">
                        <div class="mt-1.5 flex items-center gap-1.5 text-xs font-bold" :class="isMatched ? 'text-emerald-600' : 'text-rose-600'">
                            <span x-text="isMatched ? '✓ Kata sandi cocok' : '✗ Kata sandi konfirmasi belum sama'"></span>
                        </div>
                    </template>
                </div>

                <!-- Petunjuk Keamanan Sandi -->
                <div class="p-4 rounded-xl bg-amber-50/70 border border-amber-200/80 text-amber-900 text-xs space-y-1">
                    <p class="font-bold flex items-center gap-1.5 text-amber-800">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Tips Keamanan Akun Keuangan:
                    </p>
                    <ul class="list-disc list-inside text-slate-700 pl-1 space-y-0.5 text-[11px]">
                        <li>Jangan gunakan informasi yang mudah ditebak seperti tanggal lahir atau nomor telepon.</li>
                        <li>Jangan bagikan kata sandi akun Anda kepada orang lain.</li>
                        <li>Setelah mengganti kata sandi, seluruh log aktivitas akan mencatat perubahan demi keamanan.</li>
                    </ul>
                </div>

                <!-- Tombol Aksi Simpan -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
                    <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-100 text-xs sm:text-sm font-bold transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-amber-400 text-xs sm:text-sm font-extrabold shadow-md transition">
                        Simpan Kata Sandi Baru
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

