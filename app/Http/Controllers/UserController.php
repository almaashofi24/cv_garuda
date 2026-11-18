<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Tampilkan daftar pengguna sistem.
     */
    public function index(Request $request)
    {
        $query = User::with('roles');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->role($request->role);
        }

        $users = $query->latest('id')->paginate(10)->withQueryString();
        $roles = Role::all();

        return view('users.index', compact('users', 'roles'));
    }

    /**
     * Simpan user baru.
     */
    public function store(UserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole($validated['role']);

        ActivityLog::record(
            'tambah',
            'Pengguna',
            "Menambahkan pengguna baru '{$user->name}' ({$user->email}) dengan peran {$validated['role']}",
            Auth::id()
        );

        return redirect()->route('pengguna.index')->with('success', "Pengguna {$user->name} berhasil ditambahkan.");
    }

    /**
     * Perbarui data user dan perannya.
     */
    public function update(UserRequest $request, User $user)
    {
        $validated = $request->validated();

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);
        $user->syncRoles([$validated['role']]);

        ActivityLog::record(
            'ubah',
            'Pengguna',
            "Memperbarui data pengguna '{$user->name}' ({$user->email}), peran: {$validated['role']}",
            Auth::id()
        );

        return redirect()->route('pengguna.index')->with('success', "Data pengguna {$user->name} berhasil diperbarui.");
    }

    /**
     * Hapus pengguna.
     */
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri saat sedang masuk.');
        }

        if ($user->hasRole('Admin') && User::role('Admin')->count() <= 1) {
            return back()->with('error', 'Sistem harus memiliki setidaknya satu pengguna Admin aktif.');
        }

        $name = $user->name;
        $email = $user->email;
        $user->delete();

        ActivityLog::record(
            'hapus',
            'Pengguna',
            "Menghapus pengguna '{$name}' ({$email})",
            Auth::id()
        );

        return redirect()->route('pengguna.index')->with('success', "Pengguna {$name} berhasil dihapus.");
    }
}

