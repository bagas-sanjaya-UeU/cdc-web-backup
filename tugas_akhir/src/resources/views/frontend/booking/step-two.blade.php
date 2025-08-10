<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Langkah 2: Pilih Meja - CDC Booking</title>
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

        /* Custom style untuk checkbox meja */
        .table-checkbox:checked+label {
            background-color: #2563eb;
            /* bg-blue-600 */
            border-color: #1d4ed8;
            /* border-blue-700 */
            color: white;
            transform: scale(1.05);
        }

        .table-checkbox:checked+label img {
            filter: brightness(1.1) contrast(1.1);
        }

        .table-checkbox:disabled+label {
            background-color: #d1d5db;
            /* bg-gray-300 */
            border-color: #9ca3af;
            /* border-gray-400 */
            cursor: not-allowed;
            color: #6b7280;
            /* text-gray-500 */
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
            <div class="max-w-5xl mx-auto">
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
                                    <i class="fas fa-chair"></i>
                                </div>
                            </div>
                            <div class="text-center text-xs font-semibold text-blue-600">Pilih Meja</div>
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

                <!-- Pilihan Meja -->
                <div class="bg-white p-8 rounded-2xl shadow-lg">
                    <h1 class="text-2xl font-bold mb-2">Pilih Meja Anda</h1>
                    <p class="text-gray-600 mb-6">Pilih satu atau lebih meja yang tersedia di bawah ini. Anda bisa
                        memesan lebih dari satu meja.</p>

                    @error('menu_items')
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600">
                            {{ $message }}
                        </div>
                    @endif

                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg text-sm">
                        Anda memesan untuk tanggal
                        <strong>{{ \Carbon\Carbon::parse(session('booking.booking_date'))->format('d F Y') }}</strong>
                        jam <strong>{{ \Carbon\Carbon::parse(session('booking.booking_time'))->format('H:i') }}</strong>
                        di <strong>{{ \App\Models\Place::find(session('booking.place_id'))->name }}</strong>.
                    </div>

                    <form action="{{ route('booking.post.step-two') }}" method="POST">
                        @csrf
                        {{-- Denah Meja --}}
                        <div class="p-6 border border-dashed border-gray-300 rounded-lg">
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                @if ($availableTables->isEmpty())
                                    <div class="col-span-full text-center py-10 text-gray-500">
                                        <i class="fas fa-exclamation-triangle fa-3x mb-4"></i>
                                        <p class="font-semibold">Mohon Maaf!</p>
                                        <p>Tidak ada meja yang tersedia untuk tanggal dan waktu yang Anda pilih.</p>
                                        <a href="{{ route('booking.step-one') }}"
                                            class="text-blue-600 hover:underline mt-2 inline-block">Coba waktu lain</a>
                                    </div>
                                @else
                                    @foreach ($availableTables as $table)
                                        <div>
                                            <input type="checkbox" name="tables[]" id="table_{{ $table->id }}"
                                                value="{{ $table->id }}" class="hidden table-checkbox">
                                            <label for="table_{{ $table->id }}"
                                                class="block p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-100 transition-all duration-200 text-center">
                                                {{-- PERUBAHAN DI SINI --}}
                                                @if ($table->image)
                                                    <img src="{{ Storage::url($table->image) }}" alt="{{ $table->table_number }}" class="w-full h-24 object-cover rounded-md mb-2">
                                                @else
                                                    {{-- Fallback jika tidak ada gambar --}}
                                                    <div class="flex items-center justify-center w-full h-24 bg-gray-200 rounded-md mb-2">
                                                        <i class="fas fa-chair text-4xl text-gray-400"></i>
                                                    </div>
                                                @endif
                                                {{-- AKHIR PERUBAHAN --}}
                                                <span class="text-sm font-semibold">{{ $table->table_number }}</span>
                                                <span class="block text-xs text-gray-500">({{ $table->capacity }} org)</span>
                                            </label>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        @error('tables')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror

                        <div class="mt-8 flex justify-between items-center">
                            <a href="{{ route('booking.step-one') }}"
                                class="px-6 py-3 text-base font-bold text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition duration-300">
                                <i class="fas fa-arrow-left mr-2"></i> Kembali
                            </a>
                            <button type="submit"
                                class="px-8 py-3 text-base font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition duration-300"
                                @if ($availableTables->isEmpty()) disabled @endif>
                                Lanjut Pilih Menu <i class="fas fa-arrow-right ml-2"></i>
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

</body>

</html>
