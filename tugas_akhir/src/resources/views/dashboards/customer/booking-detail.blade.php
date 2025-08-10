@extends('dashboards.templates.base') {{-- Sesuaikan dengan path layout utama Anda --}}

@section('title', 'Detail Booking Saya')

@section('content')
    <div class="row">
        <!-- Kolom Kiri: Detail Booking -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detail Booking #{{ $booking->booking_code }}</h5>
                     <a href="{{ route('customer.dashboard') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Riwayat
                    </a>
                </div>
                <div class="card-body">
                    <!-- Detail Reservasi -->
                    <h6>Informasi Reservasi</h6>
                    <ul class="list-unstyled mb-4">
                        <li class="d-flex justify-content-between"><span>Tempat:</span> <strong>{{ $booking->place->name }}</strong></li>
                        <li class="d-flex justify-content-between"><span>Tanggal & Waktu:</span> <strong>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }} jam {{ \Carbon\Carbon::parse($booking->booking_time)->format('H:i') }}</strong></li>
                        <li class="d-flex justify-content-between"><span>Jumlah Orang:</span> <strong>{{ $booking->number_of_people }} orang</strong></li>
                        <li class="d-flex justify-content-between"><span>Meja Dipesan:</span> 
                            <div>
                                @foreach($booking->tables as $table)
                                    <span class="badge bg-primary">{{ $table->table_number }}</span>
                                @endforeach
                            </div>
                        </li>
                         @if($booking->notes)
                        <li class="d-flex flex-column mt-2"><span>Catatan Anda:</span> <p class="p-2 bg-light rounded mt-1"><em>"{{ $booking->notes }}"</em></p></li>
                        @endif
                    </ul>
                    <hr>
                    <!-- Detail Pesanan Menu -->
                    <h6 class="mt-4">Pesanan Menu Anda</h6>
                    <div class="table-responsive text-nowrap">
                        <table class="table">
                            <thead>
                                <tr><th>Menu</th><th>Qty</th><th class="text-end">Total</th></tr>
                            </thead>
                            <tbody>
                                @forelse($booking->menuItems as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->pivot->quantity }}x</td>
                                    <td class="text-end">Rp {{ number_format($item->pivot->price * $item->pivot->quantity, 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada menu yang dipesan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Ringkasan & Status -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Ringkasan & Status</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-3">
                        <li class="d-flex justify-content-between"><span>Subtotal Menu:</span> <span>Rp {{ number_format($booking->subtotal, 0, ',', '.') }}</span></li>
                        <li class="d-flex justify-content-between"><span>Pajak (10%):</span> <span>Rp {{ number_format($booking->tax, 0, ',', '.') }}</span></li>
                        <li class="d-flex justify-content-between fw-bold"><span>Total Tagihan:</span> <span>Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span></li>
                        <li class="d-flex justify-content-between fw-bold text-primary"><span>DP Dibayar (50%):</span> <span>Rp {{ number_format($booking->down_payment_amount, 0, ',', '.') }}</span></li>
                    </ul>
                    <hr>
                    <div class="mt-3 text-center">
                        <p class="mb-1">Status Booking Anda:</p>
                        @if ($booking->status == 'pending_confirmation')
                            <span class="badge bg-label-warning fs-6">Menunggu Konfirmasi</span>
                            <p class="text-muted small mt-2">Kami sedang memeriksa bukti pembayaran Anda. Mohon tunggu.</p>
                        @elseif ($booking->status == 'confirmed')
                            <span class="badge bg-label-success fs-6">Terkonfirmasi</span>
                             <p class="text-muted small mt-2">Booking Anda sudah dikonfirmasi. Sampai jumpa!</p>
                        @elseif ($booking->status == 'completed')
                            <span class="badge bg-label-primary fs-6">Selesai</span>
                            <p class="text-muted small mt-2">Terima kasih atas kunjungan Anda.</p>
                        @elseif ($booking->status == 'cancelled')
                            <span class="badge bg-label-danger fs-6">Dibatalkan</span>
                            {{-- PERUBAHAN DI SINI: Menampilkan Alasan Pembatalan --}}
                            @if($booking->status_description)
                            <div class="alert alert-secondary mt-3 text-start small">
                                <strong>Alasan Pembatalan:</strong><br>
                                <em>{{ $booking->status_description }}</em>
                            </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
