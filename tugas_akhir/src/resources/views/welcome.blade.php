<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di Central Duren Cisoka</title>
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

        .hero-bg {
            background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEgbqCy2CW3K0wrUx4jy_92h0euytl_8ShMKOLD8mFTlQs78E5zahqFnqL8QTDriQVDWMjxDr_nDFgknhbVkyjW95_UrN0sM1V8dYBWpnGe9pcw94FtEpzt9sGDir7qylrOZsUVeuuQXEF63SzqBKS8f_ZRubVoq-WGUdEhezu06iH0MUSRBOSvTWEDJz_mp/s16000-rw/Central-Duren-Cisoka-1.webp');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">

    <!-- Header & Navigasi -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="/" class="text-2xl font-bold text-blue-600">
                <img src="https://images.msha.ke/4192efde-8a45-42e4-b6c3-08dee1a92176?auto=format%2Ccompress&cs=tinysrgb&q=30&w=828"
                    alt="CDC Booking Logo" class="inline-block h-12 mr-2">
            </a>
            <div class="hidden md:flex items-center space-x-6">
                <a href="#features" class="text-gray-600 hover:text-blue-600">Fitur</a>
                <a href="#places" class="text-gray-600 hover:text-blue-600">Tempat</a>
                <a href="#contact" class="text-gray-600 hover:text-blue-600">Kontak</a>
            </div>
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

    <!-- Hero Section -->
    <main>
        <section class="hero-bg text-white">
            <div class="container mx-auto px-6 py-32 text-center">
                <h1 class="text-4xl md:text-6xl font-extrabold leading-tight mb-4">Central Duren Cisoka</h1>
                <p class="text-lg md:text-xl text-gray-200 mb-8 max-w-3xl mx-auto">Nikmati momen spesial Anda dengan
                    mudah. Pilih tempat, tentukan meja, dan pesan menu favorit Anda dalam beberapa klik saja.</p>
                <a href="{{ route('booking.step-one') }}"
                    class="px-8 py-4 text-lg font-bold text-white bg-blue-600 rounded-full hover:bg-blue-700 transition duration-300 transform hover:scale-105">
                    Booking Sekarang <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </section>

        <!-- Fitur / Cara Kerja -->
        <section id="features" class="py-20 bg-white">
            <div class="container mx-auto px-6">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold">Bagaimana Caranya?</h2>
                    <p class="text-gray-600 mt-2">Hanya butuh 3 langkah mudah untuk mengamankan tempat Anda.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
                    <div class="p-6">
                        <div
                            class="flex items-center justify-center h-16 w-16 mx-auto mb-4 bg-blue-100 text-blue-600 rounded-full text-2xl">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">1. Pilih Tempat & Waktu</h3>
                        <p class="text-gray-600">Pilih lokasi CDC favorit Anda, tentukan tanggal, jam, dan jumlah orang
                            yang akan datang.</p>
                    </div>
                    <div class="p-6">
                        <div
                            class="flex items-center justify-center h-16 w-16 mx-auto mb-4 bg-blue-100 text-blue-600 rounded-full text-2xl">
                            <i class="fas fa-chair"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">2. Pilih Meja & Menu</h3>
                        <p class="text-gray-600">Lihat denah meja yang tersedia, pilih meja yang Anda suka, dan langsung
                            pesan menu makanan.</p>
                    </div>
                    <div class="p-6">
                        <div
                            class="flex items-center justify-center h-16 w-16 mx-auto mb-4 bg-blue-100 text-blue-600 rounded-full text-2xl">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">3. Bayar DP & Konfirmasi</h3>
                        <p class="text-gray-600">Lakukan pembayaran DP 50% dan unggah buktinya. Booking Anda akan segera
                            kami konfirmasi.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pilihan Tempat -->
        <section id="places" class="py-20">
            <div class="container mx-auto px-6">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold">Pilihan Tempat Kami</h2>
                    <p class="text-gray-600 mt-2">Temukan suasana yang paling cocok untuk Anda.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Tempat 1 -->
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEjsJPvie3wO2pUu5Un900Foj-XYKEsS9aGPqIs97HaKmLrRtLZd4lylKvkqKcPOen14UL5EtTxYg3EglUovyroYByTSt1vJYTRod59u6DRF6VdGyKNgJP8E2IbhXeLZsG1z65aslBbfs3k8RXoK2KuUMgRo2IWXZAWL4fVJxxqEjEfjn5YWuWwG8O_505wB/s16000-rw/Central-Duren-Cisoka.webp"
                            alt="CDC 1" class="w-full h-64 object-cover">
                        <div class="p-6">
                            <h3 class="text-2xl font-bold mb-2">CDC 1</h3>
                            <p class="text-gray-700 mb-4">Suasana yang nyaman dan modern, cocok untuk kumpul bersama
                                teman dan keluarga. Dilengkapi dengan 30 meja.</p>
                            <a href="{{ route('booking.step-one') }}"
                                class="font-semibold text-blue-600 hover:text-blue-800">Reservasi di CDC 1 &rarr;</a>
                        </div>
                    </div>
                    <!-- Tempat 2 -->
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEiwWiYQCHkcyTyIePX-2yyD8LOEGyHqyV8IStY-Jz6HuyuWJ9UjqmVdgZEPabfcQqeR85Wn8U0e2RDm888exFURRDIz36Ssxi8xLIK52cKTY5hakMqpU1qGVJI2f0MWiJXqO9Jy3YuDvdbLadb_N44LAK-HVQOpe5SRVzCMF1nC1wTAccwdUbKdSTOQbq-P/s16000-rw/Foto-Central-Duren-Cisoka.webp"
                            alt="CDC 2" class="w-full h-64 object-cover">
                        <div class="p-6">
                            <h3 class="text-2xl font-bold mb-2">CDC 2</h3>
                            <p class="text-gray-700 mb-4">Tempat yang lebih privat dan eksklusif, ideal untuk pertemuan
                                bisnis atau acara spesial. Tersedia 20 meja.</p>
                            <a href="{{ route('booking.step-one') }}"
                                class="font-semibold text-blue-600 hover:text-blue-800">Reservasi di CDC 2 &rarr;</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer id="contact" class="bg-gray-800 text-white">
        <div class="container mx-auto px-6 py-10">
            <div class="text-center">
                <h3 class="text-2xl font-bold mb-2">Central Duren Cisoka</h3>
                <p class="text-gray-400 mb-4">Jl. Cisoka Cilaban, Cempaka, Kec. Cisoka, Kabupaten Tangerang, Banten
                    15730</p>
                <div class="flex justify-center space-x-6">
                    <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-700 pt-6 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} Central Duren Cisoka. All rights reserved.
            </div>
        </div>
    </footer>

</body>

</html>
