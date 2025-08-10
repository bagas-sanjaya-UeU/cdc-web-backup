<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role  Parameter role yang kita definisikan di file route (e.g., 'admin', 'customer')
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Cek apakah pengguna sudah login
        if (!Auth::check()) {
            // Jika belum login, arahkan ke halaman login
            return redirect('login');
        }

        // 2. Cek apakah role pengguna sesuai dengan yang diizinkan
        if (Auth::user()->role !== $role) {
            // Jika role tidak sesuai, tampilkan halaman error 403 (Forbidden)
            abort(403, 'AKSES DITOLAK. ANDA TIDAK MEMILIKI HAK AKSES UNTUK HALAMAN INI.');
        }

        // 3. Jika semua pengecekan berhasil, lanjutkan request ke controller
        return $next($request);
    }
}
