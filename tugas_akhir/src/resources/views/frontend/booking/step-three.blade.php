<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Langkah 3: Pilih Menu - CDC Booking</title>
    <!-- Memuat Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com/"></script>
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
        /* Style untuk tombol kategori aktif */
        .category-btn-active {
            background-color: #2563eb; /* bg-blue-600 */
            color: white;
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800">

    <!-- Header & Navigasi -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <nav class="container mx-auto px-4 sm:px-6 py-4 flex justify-between items-center">
            <a href="/" class="text-xl sm:text-2xl font-bold text-blue-600">
                <i class="fas fa-calendar-check mr-2"></i>CDC Booking
            </a>
            <div class="flex items-center space-x-2 sm:space-x-4">
                @guest
                    <a href="{{ route('login') }}"
                        class="px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">Login</a>
                    <a href="{{ route('register') }}"
                        class="px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Register</a>
                @else
                    <a href="{{ route(auth()->user()->role . '.dashboard') }}"
                        class="px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Dashboard</a>
                @endguest
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="py-12 sm:py-16">
        <div class="container mx-auto px-4 sm:px-6">
            <div class="max-w-4xl mx-auto">
                <!-- Progress Bar -->
                <div class="mb-12">
                    <div class="flex items-start text-center">
                        <div class="w-1/4"><div class="w-8 h-8 sm:w-10 sm:h-10 mx-auto bg-blue-600 rounded-full text-lg text-white flex items-center justify-center"><i class="fas fa-check"></i></div><p class="text-xs mt-1 font-semibold text-blue-600">Tempat</p></div>
                        <div class="w-1/4 border-t-2 border-blue-600 mt-4 sm:mt-5"></div>
                        <div class="w-1/4"><div class="w-8 h-8 sm:w-10 sm:h-10 mx-auto bg-blue-600 rounded-full text-lg text-white flex items-center justify-center"><i class="fas fa-check"></i></div><p class="text-xs mt-1 font-semibold text-blue-600">Meja</p></div>
                        <div class="w-1/4 border-t-2 border-blue-600 mt-4 sm:mt-5"></div>
                        <div class="w-1/4"><div class="w-8 h-8 sm:w-10 sm:h-10 mx-auto bg-blue-600 rounded-full text-lg text-white flex items-center justify-center"><i class="fas fa-utensils"></i></div><p class="text-xs mt-1 font-semibold text-blue-600">Menu</p></div>
                        <div class="w-1/4 border-t-2 border-gray-300 mt-4 sm:mt-5"></div>
                        <div class="w-1/4"><div class="w-8 h-8 sm:w-10 sm:h-10 mx-auto bg-gray-300 rounded-full text-lg text-gray-500 flex items-center justify-center"><i class="fas fa-credit-card"></i></div><p class="text-xs mt-1 text-gray-500">Bayar</p></div>
                    </div>
                </div>

                <!-- Pilihan Menu -->
                <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-lg">
                    <h1 class="text-xl sm:text-2xl font-bold mb-2">Pilih Menu Makanan & Minuman</h1>
                    <p class="text-gray-600 text-sm sm:text-base mb-6">Pilih menu yang ingin Anda nikmati.</p>

                    <!-- Filter dan Pencarian -->
                    <div class="mb-6 space-y-4">
                        <input type="text" id="searchInput" placeholder="Cari nama menu..." class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        @if(isset($categories) && !$categories->isEmpty())
                        <div id="categoryButtons" class="flex flex-wrap gap-2">
                            <button type="button" data-category="All" class="category-btn category-btn-active px-3 py-1 text-sm font-medium rounded-full">Semua</button>
                            @foreach($categories as $category)
                            <button type="button" data-category="{{ $category->name }}" class="category-btn px-3 py-1 text-sm font-medium text-gray-700 bg-gray-200 rounded-full hover:bg-gray-300">{{ $category->name }}</button>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    <form action="{{ route('booking.post.step-three') }}" method="POST">
                        @csrf
                        <div id="menuList" class="space-y-4">
                            @forelse ($menuItems as $index => $item)
                                <div class="menu-item flex items-center bg-gray-50 p-3 sm:p-4 rounded-lg" 
                                     data-name="{{ strtolower($item->name) }}" 
                                     data-category="{{ $item->category ?? 'Lainnya' }}">
                                    <input type="hidden" name="menu_items[{{ $index }}][id]" value="{{ $item->id }}">
                                    <img src="{{ $item->image ? Storage::url($item->image) : 'https://placehold.co/100x100/e2e8f0/333333?text=Menu' }}"
                                        alt="{{ $item->name }}" class="w-16 h-16 sm:w-24 sm:h-24 object-cover rounded-md mr-4 flex-shrink-0">
                                    <div class="flex-grow">
                                        <div class="flex items-center gap-x-2 mb-1">
                                            <h3 class="font-bold text-base sm:text-lg leading-tight">{{ $item->name }}</h3>
                                            @if ($item->is_recommended == 'yes')
                                                <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-amber-600 bg-amber-200">
                                                    <i class="fas fa-star text-xs"></i> Rec
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-blue-600 font-semibold">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="flex items-center space-x-2 ml-2">
                                        <button type="button" onclick="updateQuantity(this, -1)" class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-gray-200 text-gray-700 hover:bg-gray-300 font-bold flex-shrink-0">-</button>
                                        <input type="number" name="menu_items[{{ $index }}][quantity]" value="0" class="quantity-input w-10 sm:w-12 text-center font-semibold border-gray-300 rounded-md shadow-sm p-1">
                                        <button type="button" onclick="updateQuantity(this, 1)" class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-gray-200 text-gray-700 hover:bg-gray-300 font-bold flex-shrink-0">+</button>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-10 text-gray-500">
                                    <p>Mohon maaf, tidak ada menu yang tersedia saat ini.</p>
                                </div>
                            @endforelse
                        </div>
                        <div id="noResultsMessage" class="text-center py-10 text-gray-500 hidden"><p>Menu tidak ditemukan atau tidak tersedia.</p></div>
                        @error('menu_items')<p class="text-red-500 text-xs mt-2">{{ $message }}</p>@enderror
                        <div class="mt-8"><label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan (Opsional)</label><textarea name="notes" id="notes" rows="3" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-sm" placeholder="Contoh: Alergi kacang...">{{ old('notes') }}</textarea></div>
                        <div class="mt-8 flex flex-col-reverse sm:flex-row sm:justify-between sm:items-center gap-4"><a href="{{ route('booking.step-two') }}" class="w-full sm:w-auto text-center px-6 py-3 text-base font-bold text-gray-700 bg-gray-200 rounded-lg"> Kembali</a><button type="submit" class="w-full sm:w-auto px-8 py-3 text-base font-bold text-white bg-blue-600 rounded-lg">Lanjut ke Pembayaran</button></div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    {{-- ... Kode footer Anda tetap sama ... --}}

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchInput');
            const categoryButtonsContainer = document.getElementById('categoryButtons');
            const menuItems = document.querySelectorAll('.menu-item');
            const noResultsMessage = document.getElementById('noResultsMessage');
            
            let selectedCategory = 'All';
            let searchTerm = '';

            function filterAndSearch() {
                let visibleItems = 0;
                menuItems.forEach(item => {
                    const itemName = item.dataset.name;
                    const itemCategory = item.dataset.category;

                    const categoryMatch = selectedCategory === 'All' || itemCategory === selectedCategory;
                    const searchMatch = searchTerm === '' || itemName.includes(searchTerm);

                    if (categoryMatch && searchMatch) {
                        item.style.display = 'flex';
                        visibleItems++;
                    } else {
                        item.style.display = 'none';
                    }
                });
                noResultsMessage.style.display = visibleItems === 0 ? 'block' : 'none';
            }

            if (searchInput) {
                searchInput.addEventListener('keyup', function (e) {
                    searchTerm = e.target.value.toLowerCase();
                    filterAndSearch();
                });
            }

            if (categoryButtonsContainer) {
                categoryButtonsContainer.addEventListener('click', function (e) {
                    if (e.target.classList.contains('category-btn')) {
                        selectedCategory = e.target.dataset.category;
                        
                        // Update active button style
                        document.querySelectorAll('.category-btn').forEach(btn => {
                            btn.classList.remove('category-btn-active');
                            btn.classList.add('bg-gray-200', 'text-gray-700');
                        });
                        e.target.classList.add('category-btn-active');
                        e.target.classList.remove('bg-gray-200', 'text-gray-700');
                        
                        filterAndSearch();
                    }
                });
            }
        });

        function updateQuantity(button, amount) {
            const input = button.parentElement.querySelector('.quantity-input');
            let currentValue = parseInt(input.value, 10);
            let newValue = currentValue + amount;
            if (newValue < 0) {
                newValue = 0;
            }
            input.value = newValue;
        }
    </script>
</body>
</html>
