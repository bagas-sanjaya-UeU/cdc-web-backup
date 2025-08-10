<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman utama dashboard customer dengan riwayat booking.
     */
    public function index()
    {
        $user = Auth::user();
        // Ubah dari paginate() menjadi get() untuk mengirim semua data ke DataTables
        $bookings = $user->bookings()->with('place')->latest()->get();
        // Pastikan path view sudah benar
        return view('dashboards.customer.dashboard', compact('bookings'));
    }

    /**
     * Menampilkan detail dari satu booking milik customer.
     */
    public function show(Booking $booking)
    {
        // Pastikan customer hanya bisa melihat booking miliknya sendiri
        if (Auth::id() !== $booking->user_id) {
            abort(403, 'AKSES DITOLAK');
        }

        $booking->load('place', 'tables', 'menuItems');

        // Pastikan path view sudah benar
        return view('dashboards.customer.booking-detail', compact('booking'));
    }

    /**
     * Method baru untuk membatalkan booking.
     */
    public function cancel(Request $request, Booking $booking) // <-- Tambahkan Request $request
    {
        // Pastikan customer hanya bisa membatalkan booking miliknya sendiri
        if (Auth::id() !== $booking->user_id) {
            abort(403, 'AKSES DITOLAK');
        }

        // Hanya booking dengan status tertentu yang bisa dibatalkan
        if (!in_array($booking->status, ['pending_confirmation', 'confirmed'])) {
            return redirect()->route('customer.dashboard')
                             ->with('error', 'Booking ini tidak dapat dibatalkan.');
        }

        // Validasi alasan pembatalan
        $request->validate([
            'cancellation_reason' => 'required|string|max:255',
        ]);

        // Ubah status dan simpan alasan pembatalan
        $booking->update([
            'status' => 'cancelled',
            'status_description' => $request->cancellation_reason,
        ]);

        return redirect()->route('customer.dashboard')
                         ->with('success', 'Booking Anda berhasil dibatalkan.');
    }
}
