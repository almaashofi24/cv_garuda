<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = Auth::user();

        // Pemeriksaan Ketat: Hanya pengguna berstatus peran 'Admin' yang dapat masuk ke web ini
        if (!$user->hasRole('Admin')) {
            ActivityLog::record(
                'login_ditolak',
                'User',
                "Upaya login ditolak untuk {$user->name} ({$user->email}) karena bukan Admin.",
                $user->id
            );

            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Akses ditolak. Hanya Administrator / Pemilik yang memiliki hak akses untuk masuk ke sistem ini.');
        }

        $request->session()->regenerate();

        ActivityLog::record('login', 'User', "Administrator {$user->name} ({$user->email}) berhasil masuk ke sistem.", $user->id);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if ($user) {
            ActivityLog::record('logout', 'User', "Pengguna {$user->name} ({$user->email}) keluar dari sistem.", $user->id);
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
