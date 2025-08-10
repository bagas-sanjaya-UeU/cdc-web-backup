<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- =================================================================== -->
    <!-- MENGGUNAKAN TAILWIND CSS AGAR SESUAI DENGAN HALAMAN LOGIN -->
    <!-- =================================================================== -->
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
    <!-- =================================================================== -->

</head>

<body>
    <div id="app">
        {{-- Navbar tidak diperlukan di sini karena halaman login/register sudah punya header sendiri --}}
        {{-- Jika Anda butuh navbar global, bisa ditambahkan di sini --}}

        <main>
            @yield('content')
        </main>
    </div>
</body>

</html>
