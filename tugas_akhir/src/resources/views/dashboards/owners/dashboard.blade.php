@extends('dashboards.templates.base')

@section('title', 'Dashboard Owner')

@push('scripts')
    {{-- Pastikan Anda sudah memuat library ApexCharts di layout utama Anda --}}
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const revenueCounts = {{ Js::from($revenueCounts ?? []) }};
            const months = {{ Js::from($months ?? []) }};

            if (revenueCounts.length && months.length) {
                const options = {
                    series: [{
                        name: 'Total Pendapatan (DP)',
                        data: revenueCounts
                    }],
                    chart: { type: 'area', height: 350, toolbar: { show: false } },
                    dataLabels: { enabled: false },
                    stroke: { curve: 'smooth' },
                    xaxis: {
                        categories: months,
                        title: { text: 'Bulan di Tahun {{ now()->year }}' }
                    },
                    yaxis: {
                        title: { text: 'Pendapatan (Rp)' },
                        labels: {
                            formatter: function (value) {
                                return "Rp " + new Intl.NumberFormat('id-ID').format(value);
                            }
                        },
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return "Rp " + new Intl.NumberFormat('id-ID').format(val)
                            }
                        }
                    }
                };
                const chart = new ApexCharts(document.querySelector("#monthlyRevenueChart"), options);
                chart.render();
            }
        });
    </script>
@endpush

@section('content')
    <div class="row">
        {{-- Card Metrik --}}
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Pendapatan (DP)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</div>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Booking Sukses</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalSuccessfulBookings ?? 0 }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-receipt fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Potensi Pendapatan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalPendingRevenue ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-clock fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Grafik dan Tabel --}}
    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Grafik Pendapatan Bulanan (DP)</h6></div>
                <div class="card-body"><div id="monthlyRevenueChart"></div></div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Transaksi Terbaru</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Kode Booking</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th class="text-end">Jumlah DP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestTransactions ?? [] as $booking)
                            <tr>
                                <td><strong>#{{ $booking->booking_code }}</strong></td>
                                <td>{{ $booking->user->name }}</td>
                                <td>{{ $booking->booking_date->format('d M Y') }}</td>
                                <td class="text-end">Rp {{ number_format($booking->down_payment_amount, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data transaksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
