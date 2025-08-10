{{-- Menggunakan layout app, atau bisa juga berdiri sendiri jika file ini lengkap --}}
@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-100 flex items-center justify-center">
        <div class="container mx-auto p-4">
            <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-2xl overflow-hidden grid md:grid-cols-2">

                <!-- Kolom Kiri: Gambar & Branding -->
                <div class="hidden md:block bg-cover bg-center"
                    style="background-image: url('https://i0.wp.com/abouttng.com/wp-content/uploads/2022/02/IMG20220216153641-e1645477237455-764x1024.jpg?resize=500%2C670&ssl=1');">
                    <div class="p-8 text-white bg-black bg-opacity-50 h-full flex flex-col justify-end">
                        <h2 class="text-3xl font-extrabold mb-2">Selamat Datang Kembali</h2>
                        <p class="text-gray-200">Lanjutkan untuk mengelola reservasi Anda atau membuat booking baru dengan
                            mudah.</p>
                    </div>
                </div>

                <!-- Kolom Kanan: Form Login -->
                <div class="p-8 md:p-12">
                    <a href="/" class="text-2xl font-bold text-blue-600 mb-8 inline-block">
                        <i class="fas fa-calendar-check mr-2"></i>Central Duren Cisoka
                    </a>

                    <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ __('Login') }}</h3>
                    <p class="text-gray-600 mb-8">Silakan masukkan detail akun Anda.</p>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email -->
                        <div class="mb-5">
                            <label for="email"
                                class="block mb-2 text-sm font-medium text-gray-700">{{ __('Alamat Email') }}</label>
                            <input id="email" type="email"
                                class="w-full px-4 py-3 bg-gray-50 border @error('email') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                placeholder="anda@email.com">
                            @error('email')
                                <span class="text-red-600 text-sm mt-1" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-5">
                            <label for="password"
                                class="block mb-2 text-sm font-medium text-gray-700">{{ __('Password') }}</label>
                            <input id="password" type="password"
                                class="w-full px-4 py-3 bg-gray-50 border @error('password') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                name="password" required autocomplete="current-password" placeholder="••••••••">
                            @error('password')
                                <span class="text-red-600 text-sm mt-1" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <input class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
                                    type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="ml-2 text-sm text-gray-600" for="remember">
                                    {{ __('Ingat Saya') }}
                                </label>
                            </div>

                        </div>

                        <!-- Tombol Login -->
                        <div class="mb-6">
                            <button type="submit"
                                class="w-full px-4 py-3 text-base font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                {{ __('Login') }}
                            </button>
                        </div>

                        <!-- Link ke Register -->
                        <p class="text-sm text-center text-gray-600">
                            Belum punya akun?
                            <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:underline">
                                Daftar di sini
                            </a>
                        </p>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
