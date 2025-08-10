@extends('dashboards.templates.base') {{-- Sesuaikan dengan path layout utama Anda --}}

@section('title', 'Dashboard')

{{-- SECTION UNTUK SCRIPT CHART (HANYA UNTUK ADMIN) --}}
@if (auth()->user()->role == 'admin')
    @push('scripts')
        {{-- Pastikan Anda sudah memuat library ApexCharts di layout utama Anda --}}
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const bookingCounts = {{ Js::from($bookingCounts ?? []) }};
                const months = {{ Js::from($months ?? []) }};

                if (bookingCounts.length && months.length) {
                    const options = {
                        series: [{
                            name: 'Jumlah Booking',
                            data: bookingCounts
                        }],
                        chart: {
                            type: 'bar',
                            height: 350,
                            toolbar: {
                                show: false
                            }
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: '55%',
                                borderRadius: 5,
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            show: true,
                            width: 2,
                            colors: ['transparent']
                        },
                        xaxis: {
                            categories: months,
                            title: {
                                text: 'Bulan di Tahun {{ now()->year }}'
                            }
                        },
                        yaxis: {
                            title: {
                                text: 'Jumlah Booking'
                            }
                        },
                        fill: {
                            opacity: 1
                        },
                        tooltip: {
                            y: {
                                formatter: (val) => val + " booking"
                            }
                        }
                    };
                    const chart = new ApexCharts(document.querySelector("#monthlyBookingsChart"), options);
                    chart.render();
                }
            });
        </script>
    @endpush
@endif

@section('content')
    {{-- =================================================================== --}}
    {{-- ======================== TAMPILAN UNTUK ADMIN ======================= --}}
    {{-- =================================================================== --}}
    @if (auth()->user()->role == 'admin')
        <div class="row">
            {{-- Card Metrik --}}
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Booking Pending</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingBookings ?? 0 }}</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-clock fa-2x text-gray-300"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Booking Terkonfirmasi
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $confirmedBookings ?? 0 }}</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-check-circle fa-2x text-gray-300"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Pengguna</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsers ?? 0 }}</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-users fa-2x text-gray-300"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Menu</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalMenus ?? 0 }}</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-utensils fa-2x text-gray-300"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Grafik dan Tabel --}}
        <div class="row">
            <div class="col-xl-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Grafik Booking Bulanan</h6>
                    </div>
                    <div class="card-body">
                        <div id="monthlyBookingsChart"></div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- =================================================================== --}}
    {{-- ======================== TAMPILAN UNTUK OWNER ======================= --}}
    {{-- =================================================================== --}}
    @if (auth()->user()->role == 'owner')
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Pendapatan
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">Rp
                                    {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-dollar-sign fa-2x text-gray-300"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Booking Sukses
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalBookings ?? 0 }}</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-receipt fa-2x text-gray-300"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Rata-rata per Booking
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">Rp
                                    {{ number_format($averageBookingValue ?? 0, 0, ',', '.') }}</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-chart-pie fa-2x text-gray-300"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- =================================================================== --}}
    {{-- ======================= TAMPILAN UNTUK CUSTOMER ===================== --}}
    {{-- =================================================================== --}}
    @if (auth()->user()->role == 'customer')
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Booking Aktif</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeBookings ?? 0 }}</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-hourglass-half fa-2x text-gray-300"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Booking Selesai</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $completedBookings ?? 0 }}</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-check fa-2x text-gray-300"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Semua Booking
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalBookings ?? 0 }}</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-calendar-alt fa-2x text-gray-300"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Tabel Booking Terbaru (ditampilkan untuk semua role) --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Booking Terbaru</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Kode Booking</th>
                            @if (auth()->user()->role != 'customer')
                                <th>Pelanggan</th>
                            @endif
                            <th>Tempat</th>
                            <th>Tanggal</th>
                            <th class="text-center">Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestBookings ?? [] as $booking)
                            <tr>
                                <td><strong>#{{ $booking->booking_code }}</strong></td>
                                @if (auth()->user()->role != 'customer')
                                    <td>{{ $booking->user->name }}</td>
                                @endif
                                <td>{{ $booking->place->name }}</td>
                                <td>{{ $booking->booking_date->format('d M Y') }}</td>
                                <td class="text-center">
                                    @if ($booking->status == 'pending_confirmation')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif ($booking->status == 'confirmed')
                                        <span class="badge bg-success">Confirmed</span>
                                    @elseif ($booking->status == 'completed')
                                        <span class="badge bg-primary">Completed</span>
                                    @elseif ($booking->status == 'cancelled')
                                        <span class="badge bg-danger">Cancelled</span>
                                    @endif
                                </td>
                                <td>
                                    @if (auth()->user()->role == 'admin')
                                        <a href="{{ route('admin.bookings.show', $booking->id) }}"
                                            class="btn btn-info btn-sm">Detail</a>
                                    @else
                                        <a href="{{ route('customer.bookings.show', $booking->id) }}"
                                            class="btn btn-info btn-sm">Detail</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data booking.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
