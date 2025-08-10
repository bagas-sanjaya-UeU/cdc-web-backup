<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Validation\Rule; 

class BookingController extends Controller
{
    // Menampilkan semua data pemesanan
    public function index()
    {
         $bookings = Booking::with('user', 'place')->latest()->get();
        return view('dashboards.bookings.index', compact('bookings'));
    }

    // Menampilkan detail satu pemesanan
    public function show(Booking $booking)
    {
        // Eager load relasi untuk efisiensi query
        $booking->load('user', 'place', 'tables', 'menuItems');
        return view('dashboards.bookings.show', compact('booking'));
    }

    // Mengubah status pemesanan (konfirmasi atau tolak)
    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            // Validasi status agar sesuai dengan pilihan yang ada
            'status' => ['required', Rule::in(['pending_confirmation', 'pending_payment', 'confirmed', 'completed', 'cancelled'])],
            // Alasan bersifat opsional
            'status_description' => 'nullable|string|max:255',
        ]);

        // Update booking dengan data baru
        $booking->update([
            'payment_status' => $request->status === 'confirmed' ? 'paid' : 'unpaid',
            'status' => $request->status,
            'status_description' => $request->status_description,
        ]);
        
        return redirect()->route('admin.bookings.show', $booking)->with('success', 'Status pemesanan berhasil diperbarui.');
    }

    // Fungsi untuk menghapus booking jika diperlukan
    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('admin.bookings.index')->with('success', 'Data pemesanan berhasil dihapus.');
    }
}
