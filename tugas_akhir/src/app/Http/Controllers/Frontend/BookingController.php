<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Place;
use App\Models\Table;
use App\Models\MenuItem;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
// Tambahkan use statement untuk Midtrans
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Category;

class BookingController extends Controller
{
    // Method stepOne dan postStepOne tetap sama...

    /**
     * Langkah 1: Menampilkan halaman untuk memilih tempat dan jumlah orang.
     */
    public function stepOne()
    {
        Session::forget('booking');
        $places = Place::all();
        return view('frontend.booking.step-one', compact('places'));
    }

    /**
     * Proses Langkah 1 dan lanjut ke Langkah 2: Memilih meja, tanggal, dan waktu.
     */
    public function postStepOne(Request $request)
    {
        $validated = $request->validate([
            'place_id' => 'required|exists:places,id',
            'number_of_people' => 'required|integer|min:4',
            'booking_date' => 'required|date|after_or_equal:' . Carbon::tomorrow()->toDateString(),
            'booking_time' => 'required',
        ]);

        Session::put('booking', $validated);

        $bookingDate = $validated['booking_date'];
        $bookingTime = Carbon::parse($validated['booking_time'])->format('H:i');

        $availableTables = Table::where('place_id', $validated['place_id'])
            ->whereDoesntHave('bookings', function ($query) use ($bookingDate, $bookingTime) {
                $query
                    ->whereDate('booking_date', $bookingDate)
                    ->where('booking_time', $bookingTime)
                    ->where('status', '!=', 'cancelled')
                    ->where('status', '!=', 'completed'); // Pastikan tidak ada booking yang dibatalkan atau selesai

            })
            ->get();

        return view('frontend.booking.step-two', compact('availableTables'));
    }

    /**
     * Proses Langkah 2 dan lanjut ke Langkah 3: Memilih menu.
     */
    public function postStepTwo(Request $request)
    {
        $validated = $request->validate([
            'tables' => 'required|array|min:1',
            'tables.*' => 'exists:tables,id',
        ], [
            'tables.required' => 'Anda harus memilih setidaknya satu meja.',
            'tables.min' => 'Anda harus memilih setidaknya satu meja.',
        ]);

        $bookingData = Session::get('booking');
        $bookingData['tables'] = $validated['tables'];
        Session::put('booking', $bookingData);

        $menuItems = MenuItem::where('is_available', 'yes')->get();
        $categories = Category::all(); // Ambil semua kategori untuk dropdown

        return view('frontend.booking.step-three', compact('menuItems', 'categories'));
    }


    /**
     * Proses Langkah 3, buat booking, dan siapkan pembayaran Midtrans.
     */
    public function postStepThree(Request $request)
    {
        $validated = $request->validate([
            'menu_items' => 'sometimes|array',
            'menu_items.*.id' => 'required_with:menu_items|exists:menu_items,id',
            'menu_items.*.quantity' => 'required_with:menu_items|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $bookingData = Session::get('booking');
        
        $subtotal = 0;
        $orderedMenus = [];
        if (isset($validated['menu_items'])) {
            foreach ($validated['menu_items'] as $item) {
                if(isset($item['id']) && isset($item['quantity']) && $item['quantity'] > 0){
                    $menuItem = MenuItem::find($item['id']);
                    if ($menuItem) {
                        $subtotal += $menuItem->price * $item['quantity'];
                        $orderedMenus[] = [
                            'id' => $menuItem->id,
                            'name' => $menuItem->name,
                            'quantity' => $item['quantity'],
                            'price' => $menuItem->price,
                        ];
                    }
                }
            }
        }

        if (empty($orderedMenus)) {
            return redirect()->back()
                ->withErrors(['menu_items' => 'Anda harus memilih setidaknya satu menu dengan kuantitas lebih dari 0.'])
                ->withInput();
        }
        
        $tax = $subtotal * 0.10;
        $totalAmount = $subtotal + $tax;
        $downPayment = $totalAmount * 0.50;

        // Simpan data booking ke database dengan status 'pending_payment'
        $booking = Booking::create([
            'booking_code' => 'BK-' . time() . Auth::id(),
            'user_id' => Auth::id(),
            'place_id' => $bookingData['place_id'],
            'booking_date' => $bookingData['booking_date'],
            'booking_time' => Carbon::parse($bookingData['booking_time'])->format('H:i'),
            'number_of_people' => $bookingData['number_of_people'],
            'notes' => $validated['notes'] ?? null,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total_amount' => $totalAmount,
            'down_payment_amount' => $downPayment,
            'status' => 'pending_payment', // Status awal, menunggu pembayaran
            'payment_status' => 'pending', // Status pembayaran dari midtrans
        ]);

        $booking->tables()->attach($bookingData['tables']);
        foreach ($orderedMenus as $menu) {
            $booking->menuItems()->attach($menu['id'], ['quantity' => $menu['quantity'], 'price' => $menu['price']]);
        }

        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Siapkan parameter untuk Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $booking->booking_code,
                'gross_amount' => $booking->down_payment_amount,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
        ];

        // Dapatkan Snap Token dari Midtrans
        $snapToken = Snap::getSnapToken($params);

        // Simpan data booking dan token ke session untuk ditampilkan di halaman pembayaran
        $bookingData['ordered_menus'] = $orderedMenus;
        $bookingData['notes'] = $validated['notes'] ?? null;
        $bookingData['subtotal'] = $subtotal;
        $bookingData['tax'] = $tax;
        $bookingData['total_amount'] = $totalAmount;
        $bookingData['down_payment_amount'] = $downPayment;

        return view('frontend.booking.step-four', compact('bookingData', 'snapToken', 'booking'));
    }

    /**
     * Method untuk menangani notifikasi dari Midtrans (webhook).
     */
    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id.$request->status_code.$request->gross_amount.$serverKey);
        
        // Verifikasi signature
        if($hashed == $request->signature_key){
            // Jika status transaksi 'capture' (untuk kartu kredit) atau 'settlement' (untuk metode lain)
            if($request->transaction_status == 'capture' || $request->transaction_status == 'settlement'){
                $booking = Booking::where('booking_code', $request->order_id)->first();
                if($booking) {
                    // Update status pembayaran dan status booking
                    $booking->update([
                        'payment_status' => 'paid',
                        'status' => 'pending_confirmation' // Siap dikonfirmasi admin
                    ]);
                }
            }
        }
    }

    public function success($bookingCode)
    {
        $booking = Booking::where('booking_code', $bookingCode)->firstOrFail();

        // Pastikan booking sudah ada dan belum terkonfirmasi
        if ($booking->status !== 'pending_payment') {
            return redirect()->route('booking.step-one')->withErrors(['error' => 'Booking tidak valid atau sudah terkonfirmasi.']);
        }

        // ===================================================================
        // PERUBAHAN DI SINI: Langsung ubah status booking saat di halaman sukses
        // ===================================================================
        // Cek jika statusnya masih 'pending_payment' untuk mencegah update ganda
        if ($booking->status === 'pending_payment') {
            $booking->update([
                'payment_status' => 'paid', // Asumsikan lunas karena sudah di halaman sukses
                'status' => 'pending_confirmation' // Ubah status menjadi menunggu konfirmasi admin
            ]);
        }
        // ===================================================================
        Session::forget('booking'); // Hapus sesi booking dari langkah-langkah sebelumnya
        // Kirim data booking ke view agar bisa ditampilkan
        return view('frontend.booking.success', compact('booking'));
    }

    
}
