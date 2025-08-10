<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;


class UserController extends Controller
{
    /**
     * Menampilkan daftar semua pengguna.
     */
    public function index()
    {
        // Ambil semua user kecuali admin yang sedang login
        $users = User::where('id', '!=', Auth::id())->latest()->paginate(10);
        return view('dashboards.users.index', compact('users'));
    }

    /**
     * Menampilkan form untuk membuat pengguna baru.
     */
    public function create()
    {
        // Pastikan path view sudah benar
        return view('dashboards.users.create');
    }

    /**
     * Menyimpan pengguna baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', Rule::in(['admin', 'customer', 'owner'])],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')
                         ->with('success', 'Pengguna baru berhasil ditambahkan.');
    }


    /**
     * Menampilkan form untuk mengedit data pengguna.
     */
    public function edit(User $user)
    {
        return view('dashboards.users.edit', compact('user'));
    }

    /**
     * Memperbarui data pengguna di database.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'customer', 'owner'])],
        ]);

        $user->update($request->all());

        return redirect()->route('admin.users.index')
                         ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Menghapus data pengguna dari database.
     */
    public function destroy(User $user)
    {
        // Pencegahan agar admin tidak bisa menghapus diri sendiri (double check)
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                             ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }
        
        // Tambahkan validasi lain jika perlu, misal user punya booking aktif
        if ($user->bookings()->where('status', '!=', 'completed')->exists()) {
             return redirect()->route('admin.users.index')
                             ->with('error', 'Tidak bisa menghapus pengguna yang masih memiliki booking aktif.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', 'Pengguna berhasil dihapus.');
    }
}
