<x-guest-layout>
    <div class="bg-white/95 backdrop-blur-md rounded-3xl shadow-2xl border border-slate-700/30 overflow-hidden">
        <!-- Header Perusahaan -->
        <div class="bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900 p-6 sm:p-8 text-center text-white border-b border-amber-500/20 relative">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-amber-600 via-amber-500 to-yellow-400 text-slate-950 shadow-lg shadow-amber-500/30 mb-3">
                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2L9.5 7H14.5L12 2Z" />
                    <path d="M4 8L10 10L6 14L2 11L4 8Z" opacity="0.9" />
                    <path d="M20 8L14 10L18 14L22 11L20 8Z" opacity="0.9" />
                    <path d="M7 13L12 11L17 13L19 19L12 22L5 19L7 13Z" />
                    <circle cx="12" cy="15" r="1.5" fill="#ffffff" />
                </svg>
            </div>
            <h1 class="text-base sm:text-lg font-black tracking-wider text-amber-400 leading-tight">
                CV. GARUDA RAJA PERKASA DUABELAS
            </h1>
            <p class="text-[11px] text-slate-300 mt-2 leading-relaxed px-2">
                Jl. Trans Kalimantan Ruko Keling Kumang No.2, Simpang Ampar,<br>
                Kec. Tayan Hilir, Kabupaten Sanggau, Kalimantan Barat 78564
            </p>
            <div class="mt-3 inline-block px-3 py-1 rounded-full bg-white/10 text-slate-300 text-[10px] font-semibold tracking-wider uppercase">
                Portal Masuk Khusus Administrator
            </div>
        </div>

        <div class="p-6 sm:p-8 space-y-6">
            <!-- Session Status / Alert -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            @if (session('error'))
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center gap-2">
                    <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                        Alamat Email Administrator
                    </label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="admin@cvgaruda.com" class="w-full text-xs rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="password" class="block text-xs font-bold text-slate-700 uppercase">
                            Kata Sandi
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-[11px] text-blue-600 hover:underline font-semibold">
                                Lupa sandi?
                            </a>
                        @endif
                    </div>
                    <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" class="w-full text-xs rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between text-xs pt-1">
                    <label for="remember_me" class="inline-flex items-center text-slate-600">
                        <input id="remember_me" type="checkbox" name="remember" class="rounded border-slate-300 text-slate-900 shadow-sm focus:ring-amber-500">
                        <span class="ms-2">Ingat sesi saya</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" class="w-full py-3 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-amber-400 font-extrabold text-sm shadow-lg shadow-slate-950/20 transition duration-150">
                        Masuk ke Aplikasi Keuangan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
