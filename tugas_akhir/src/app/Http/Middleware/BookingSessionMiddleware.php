<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class BookingSessionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah data dari langkah pertama ada di session
        if (!Session::has('booking.place_id')) {
            // Jika tidak ada, kembalikan ke langkah pertama dengan pesan error
            return redirect()->route('booking.step-one')->with('error', 'Sesi booking tidak ditemukan, silakan mulai dari awal.');
        }

        // Jika sesi ada, lanjutkan ke request berikutnya (controller)
        return $next($request);
    }
}