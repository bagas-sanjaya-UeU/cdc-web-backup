<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Berhasil! - CDC Booking</title>
    <!-- Memuat Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Memuat Font Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Memuat Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
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
                @auth
                     <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Dashboard Saya</a>
                @endauth
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="py-24">
        <div class="container mx-auto px-6">
            <div class="max-w-2xl mx-auto bg-white p-10 rounded-2xl shadow-lg text-center">
                <div class="w-24 h-24 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-check-circle text-6xl text-green-500"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 mb-3">Pembayaran Berhasil!</h1>
                <p class="text-gray-600 mb-6">
                    Terima kasih. Booking Anda dengan kode <strong>#{{ $booking->booking_code }}</strong> telah kami terima dan akan segera diproses oleh admin.
                </p>
                <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg text-sm">
                    <p>Anda dapat memeriksa status booking Anda kapan saja di dashboard.</p>
                </div>
                <div class="mt-8">
                    <a href="{{ route('customer.dashboard') }}" class="px-8 py-3 text-base font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition duration-300">
                        Lihat Riwayat Booking Saya
                    </a>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white mt-16">
        <div class="container mx-auto px-6 py-4 border-t">
            <div class="text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} CDC Booking. All rights reserved.
            </div>
        </div>
    </footer>

</body>
</html>
