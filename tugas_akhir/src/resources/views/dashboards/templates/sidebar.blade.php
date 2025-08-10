<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo" style="background-color: 
{{ auth()->user()->role == 'admin' ? '#0d6efd' : (auth()->user()->role == 'owner' ? '#198754' : '#ffc107') }}; color: white; text-transform: uppercase
    !important;">
        <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo" style="font-size: 8px;">
                {{-- Icon yang relevan dengan booking/restoran --}}
                <i class="fas fa-calendar-check fa-2x text-white"></i>
            </span>
            <span class="app-brand-text demo menu-text fw-bolder ms-2"
                  style="text-transform: uppercase !important; color: white;">
                 @if(auth()->user()->role == 'admin')
                    CDC App
                 @elseif(auth()->user()->role == 'owner')
                    Owner 
                 @elseif(auth()->user()->role == 'customer')
                    Customer 
                 @else
                    Dashboard
                    @endif
                </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item mt-2 {{ request()->routeIs(auth()->user()->role . '.dashboard') ? 'active' : '' }}">
            {{-- Link dashboard dinamis sesuai role user --}}
            <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons fas fa-tachometer-alt"></i>
                <div data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>


        {{-- =================================================================== --}}
        {{-- ======================== MENU UNTUK ADMIN ========================= --}}
        {{-- =================================================================== --}}
        @if (auth()->user()->role == 'admin')
            <li class="menu-header small text-uppercase"><span class="menu-header-text">Manajemen Reservasi</span></li>

            {{-- Kelola Kategori --}}
            <li class="menu-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <a href="{{ route('admin.categories.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons fas fa-tags"></i>
                    <div data-i18n="Categories">Kelola Kategori</div>
                </a>
            </li>
            
            {{-- Kelola Tempat --}}
            <li class="menu-item {{ request()->routeIs('admin.places.*') ? 'active' : '' }}">
                <a href="{{ route('admin.places.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons fas fa-map-marker-alt"></i>
                    <div data-i18n="Places">Kelola Tempat</div>
                </a>
            </li>

            {{-- Kelola Meja --}}
            <li class="menu-item {{ request()->routeIs('admin.tables.*') ? 'active' : '' }}">
                <a href="{{ route('admin.tables.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons fas fa-chair"></i>
                    <div data-i18n="Tables">Kelola Meja</div>
                </a>
            </li>

            {{-- Kelola Menu --}}
            <li class="menu-item {{ request()->routeIs('admin.menu-items.*') ? 'active' : '' }}">
                <a href="{{ route('admin.menu-items.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons fas fa-book-open"></i>
                    <div data-i18n="Menu Items">Kelola Menu</div>
                </a>
            </li>

            <li class="menu-header small text-uppercase"><span class="menu-header-text">Transaksi</span></li>

            {{-- Kelola Booking --}}
            <li class="menu-item {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                <a href="{{ route('admin.bookings.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons fas fa-receipt"></i>
                    <div data-i18n="Bookings">Kelola Booking</div>
                </a>
            </li>

            {{-- =================================================================== --}}
            {{-- MENU BARU DITAMBAHKAN DI SINI --}}
            {{-- =================================================================== --}}
            <li class="menu-header small text-uppercase"><span class="menu-header-text">Manajemen Pengguna</span></li>

            {{-- Kelola Pengguna --}}
            <li class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <a href="{{ route('admin.users.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons fas fa-users"></i>
                    <div data-i18n="Users">Kelola Pengguna</div>
                </a>
            </li>
        @endif


        {{-- =================================================================== --}}
        {{-- ======================== MENU UNTUK OWNER ========================= --}}
        {{-- =================================================================== --}}
        <!-- @if (auth()->user()->role == 'owner')
            <li class="menu-header small text-uppercase"><span class="menu-header-text">Laporan</span></li>
            <li class="menu-item {{ request()->routeIs('owner.reports.*') ? 'active' : '' }}">
                <a href="{{-- route('owner.reports.index') --}}" class="menu-link">
                    <i class="menu-icon tf-icons fas fa-chart-line"></i>
                    <div data-i18n="Reports">Laporan Pembayaran</div>
                </a>
            </li>
        @endif -->


        {{-- =================================================================== --}}
        {{-- ======================= MENU UNTUK CUSTOMER ======================= --}}
        {{-- =================================================================== --}}
        @if (auth()->user()->role == 'customer')
            <li class="menu-header small text-uppercase"><span class="menu-header-text">Reservasi</span></li>

            {{-- Buat Booking Baru --}}
            <li class="menu-item {{ request()->routeIs('booking.step-one') ? 'active' : '' }} ">
                <a href="{{ route('booking.step-one') }}" class="menu-link">
                    <i class="menu-icon tf-icons fas fa-plus-circle"></i>
                    <div data-i18n="New Booking">Buat Booking Baru</div>
                </a>
            </li>

            {{-- Riwayat Booking Saya --}}
            <li class="menu-item {{ request()->routeIs('customer.bookings.*') ? 'active' : '' }} ">
                <a href="{{ route('customer.dashboard') }}" class="menu-link">
                    <i class="menu-icon tf-icons fas fa-history"></i>
                    <div data-i18n="My Bookings">Riwayat Booking Saya</div>
                </a>
            </li>
        @endif


        {{-- Menu Keluar (Sama untuk semua role) --}}
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Akun</span></li>
        <li class="menu-item">
            <a href="{{ route('logout') }}" class="menu-link"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="menu-icon tf-icons fas fa-sign-out-alt"></i>
                <div data-i18n="Logout">Keluar</div>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>

    </ul>
</aside>
<!-- / Menu -->
