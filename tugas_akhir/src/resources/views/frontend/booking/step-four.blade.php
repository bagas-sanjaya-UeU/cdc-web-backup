<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Langkah 4: Pembayaran & Konfirmasi - CDC Booking</title>
    <!-- Memuat Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Memuat Font Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Memuat Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Script Midtrans Snap.js -->
    <script type="text/javascript"
      src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
      data-client-key="{{ config('midtrans.client_key') }}"></script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800">

    <!-- Header & Navigasi -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="/" class="text-2xl font-bold text-blue-600">
                <i class="fas fa-calendar-check mr-2"></i>CDC Booking
            </a>
            <div class="flex items-center space-x-4">
                @guest
                    <a href="{{ route('login') }}"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">Login</a>
                    <a href="{{ route('register') }}"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Register</a>
                @else
                    <a href="{{ route(auth()->user()->role . '.dashboard') }}"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Dashboard
                        Saya</a>
                @endguest
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="py-16">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl mx-auto">
                <!-- Progress Bar -->
                <div class="mb-12">
                    <div class="flex items-center">
                        <div class="w-1/4">
                            <div class="relative mb-2">
                                <div
                                    class="w-10 h-10 mx-auto bg-blue-600 rounded-full text-lg text-white flex items-center justify-center">
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>
                            <div class="text-center text-xs font-semibold text-blue-600">Tempat & Waktu</div>
                        </div>
                        <div class="w-1/4 border-t-2 border-blue-600"></div>
                        <div class="w-1/4">
                            <div class="relative mb-2">
                                <div
                                    class="w-10 h-10 mx-auto bg-blue-600 rounded-full text-lg text-white flex items-center justify-center">
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>
                            <div class="text-center text-xs font-semibold text-blue-600">Pilih Meja</div>
                        </div>
                        <div class="w-1/4 border-t-2 border-blue-600"></div>
                        <div class="w-1/4">
                            <div class="relative mb-2">
                                <div
                                    class="w-10 h-10 mx-auto bg-blue-600 rounded-full text-lg text-white flex items-center justify-center">
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>
                            <div class="text-center text-xs font-semibold text-blue-600">Pilih Menu</div>
                        </div>
                        <div class="w-1/4 border-t-2 border-blue-600"></div>
                        <div class="w-1/4">
                            <div class="relative mb-2">
                                <div
                                    class="w-10 h-10 mx-auto bg-blue-600 rounded-full text-lg text-white flex items-center justify-center">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                            </div>
                            <div class="text-center text-xs font-semibold text-blue-600">Bayar & Konfirmasi</div>
                        </div>
                    </div>
                </div>

                <!-- Form Konfirmasi -->
                <div class="bg-white p-8 rounded-2xl shadow-lg">
                    <h1 class="text-2xl font-bold mb-2">Satu Langkah Terakhir! ({{ $booking->booking_code }})</h1>
                    <p class="text-gray-600 mb-6">Harap periksa kembali detail reservasi Anda sebelum melanjutkan ke pembayaran.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Kolom Kiri: Rincian Booking -->
                        <div>
                            <h3 class="text-lg font-semibold border-b pb-2 mb-4">Ringkasan Reservasi</h3>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between"><span class="text-gray-600">Tempat:</span> <span
                                        class="font-semibold">{{ \App\Models\Place::find($bookingData['place_id'])->name }}</span>
                                </div>
                                <div class="flex justify-between"><span class="text-gray-600">Tanggal:</span> <span
                                        class="font-semibold">{{ \Carbon\Carbon::parse($bookingData['booking_date'])->format('d F Y') }}</span>
                                </div>
                                <div class="flex justify-between"><span class="text-gray-600">Waktu:</span> <span
                                        class="font-semibold">{{ \Carbon\Carbon::parse($bookingData['booking_time'])->format('H:i') }}
                                        WIB</span></div>
                                <div class="flex justify-between"><span class="text-gray-600">Jumlah Orang:</span> <span
                                        class="font-semibold">{{ $bookingData['number_of_people'] }} orang</span></div>
                                <div class="flex justify-between"><span class="text-gray-600">Meja:</span>
                                    <div class="text-right">
                                        @foreach (\App\Models\Table::find($bookingData['tables']) as $table)
                                            <span class="font-semibold">{{ $table->table_number }}</span>
                                            @if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <h3 class="text-lg font-semibold border-b pb-2 mt-6 mb-4">Pesanan Menu</h3>
                            <div class="space-y-2 text-sm">
                                @foreach ($bookingData['ordered_menus'] as $menu)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">{{ $menu['name'] }} <span
                                                class="text-xs">x{{ $menu['quantity'] }}</span></span>
                                        <span class="font-semibold">Rp
                                            {{ number_format($menu['price'] * $menu['quantity'], 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-4 pt-4 border-t border-dashed">
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between"><span class="text-gray-600">Subtotal</span>
                                        <span>Rp {{ number_format($bookingData['subtotal'], 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between"><span class="text-gray-600">Pajak (10%)</span>
                                        <span>Rp {{ number_format($bookingData['tax'], 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between font-bold text-base"><span class="">Total
                                            Tagihan</span> <span>Rp
                                            {{ number_format($bookingData['total_amount'], 0, ',', '.') }}</span></div>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan: Pembayaran -->
                        <div>
                            <h3 class="text-lg font-semibold border-b pb-2 mb-4">Pembayaran DP</h3>
                             <div class="bg-blue-50 p-4 rounded-lg text-center">
                                <p class="text-sm text-blue-800">Total DP yang harus dibayar (50%)</p>
                                <p class="text-3xl font-bold text-blue-600 my-2">Rp {{ number_format($bookingData['down_payment_amount'], 0, ',', '.') }}</p>
                            </div>
                            
                            <div class="mt-8">
                                <button id="pay-button" class="w-full px-8 py-4 text-lg font-bold text-white bg-green-600 rounded-lg hover:bg-green-700 transition duration-300">
                                    <i class="fas fa-credit-card mr-2"></i> Bayar Sekarang
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white mt-16">
        <div class="container mx-auto px-6 py-4 border-t">
            <div class="text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} Central Duren Cisoka. All rights reserved.
            </div>
        </div>
    </footer>

    <script type="text/javascript">
      // Ambil tombol pembayaran
      var payButton = document.getElementById('pay-button');
      payButton.addEventListener('click', function () {
        // Trigger Snap popup.
        window.snap.pay('{{ $snapToken }}', {
          onSuccess: function(result){
            /* PERUBAHAN DI SINI: Redirect ke halaman sukses dengan ID booking */
            console.log(result);
            window.location.href = '{{ route("booking.success", $booking->booking_code) }}';
          },
          onPending: function(result){
            /* Pelanggan belum menyelesaikan pembayaran */
            console.log(result);
            alert("Pembayaran Anda sedang diproses. Silakan selesaikan pembayaran Anda.");
          },
          onError: function(result){
            /* Terjadi error */
            console.log(result);
            alert("Terjadi kesalahan pada pembayaran.");
          },
          onClose: function(){
            /* Pelanggan menutup popup tanpa menyelesaikan pembayaran */
            alert('Anda menutup jendela pembayaran sebelum selesai.');
          }
        })
      });
    </script>

</body>
</html>
