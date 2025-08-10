<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Langkah 1: Pilih Tempat & Waktu - CDC Booking</title>
    <!-- Memuat Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Memuat Font Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Memuat Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                                    <i class="fas fa-map-marked-alt"></i>
                                </div>
                            </div>
                            <div class="text-center text-xs font-semibold text-blue-600">Tempat & Waktu</div>
                        </div>
                        <div class="w-1/4 border-t-2 border-gray-300"></div>
                        <div class="w-1/4">
                            <div class="relative mb-2">
                                <div
                                    class="w-10 h-10 mx-auto bg-gray-300 rounded-full text-lg text-gray-500 flex items-center justify-center">
                                    <i class="fas fa-chair"></i>
                                </div>
                            </div>
                            <div class="text-center text-xs text-gray-500">Pilih Meja</div>
                        </div>
                        <div class="w-1/4 border-t-2 border-gray-300"></div>
                        <div class="w-1/4">
                            <div class="relative mb-2">
                                <div
                                    class="w-10 h-10 mx-auto bg-gray-300 rounded-full text-lg text-gray-500 flex items-center justify-center">
                                    <i class="fas fa-utensils"></i>
                                </div>
                            </div>
                            <div class="text-center text-xs text-gray-500">Pilih Menu</div>
                        </div>
                        <div class="w-1/4 border-t-2 border-gray-300"></div>
                        <div class="w-1/4">
                            <div class="relative mb-2">
                                <div
                                    class="w-10 h-10 mx-auto bg-gray-300 rounded-full text-lg text-gray-500 flex items-center justify-center">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                            </div>
                            <div class="text-center text-xs text-gray-500">Bayar & Konfirmasi</div>
                        </div>
                    </div>
                </div>

                <!-- Formulir Booking -->
                <div class="bg-white p-8 rounded-2xl shadow-lg">
                    <h1 class="text-2xl font-bold mb-2">Mulai Reservasi Anda</h1>
                    <p class="text-gray-600 mb-6">Silakan isi detail di bawah ini untuk memeriksa ketersediaan meja.</p>

                    <form action="{{ route('booking.post.step-one') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Pilih Tempat -->
                            <div>
                                <label for="place_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih
                                    Tempat</label>
                                <select name="place_id" id="place_id"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                                    <option value="">-- Silakan Pilih Lokasi --</option>
                                    @foreach ($places as $place)
                                        <option value="{{ $place->id }}"
                                            {{ old('place_id') == $place->id ? 'selected' : '' }}>{{ $place->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('place_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Jumlah Orang -->
                            <div>
                                <label for="number_of_people"
                                    class="block text-sm font-medium text-gray-700 mb-1">Jumlah Orang</label>
                                <input type="number" name="number_of_people" id="number_of_people"
                                    value="{{ old('number_of_people', 4) }}" min="4"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                                @error('number_of_people')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal Reservasi -->
                            <div>
                                <label for="booking_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                                    Reservasi</label>
                                <input type="date" name="booking_date" id="booking_date"
                                    value="{{ old('booking_date') }}"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                                @error('booking_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Jam Reservasi -->
                            <div>
                                <label for="booking_time" class="block text-sm font-medium text-gray-700 mb-1">Jam
                                    Reservasi</label>
                                <input type="time" name="booking_time" id="booking_time"
                                    value="{{ old('booking_time') }}"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                                @error('booking_time')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-8 text-right">
                            <button type="submit"
                                class="px-8 py-3 text-base font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition duration-300">
                                Cari Meja Tersedia <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </form>
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

    <script>
        // Set tanggal minimal untuk input tanggal adalah H-1 (besok)
        const today = new Date();
        today.setDate(today.getDate() + 1);
        const minDate = today.toISOString().split('T')[0];
        document.getElementById('booking_date').setAttribute('min', minDate);
    </script>

</body>

</html>
