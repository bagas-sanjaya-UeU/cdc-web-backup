@extends('dashboards.templates.base') {{-- Sesuaikan dengan path layout utama Anda --}}

@section('title', 'Detail Booking')

@section('content')
    <div class="row">
        <!-- Kolom Kiri: Detail Booking & Aksi -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detail Booking #{{ $booking->booking_code }}</h5>
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <!-- Detail Pelanggan -->
                    <h6>Informasi Pelanggan</h6>
                    <ul class="list-unstyled mb-4">
                        <li class="d-flex justify-content-between"><span>Nama:</span>
                            <strong>{{ $booking->user->name }}</strong></li>
                        <li class="d-flex justify-content-between"><span>Email:</span>
                            <strong>{{ $booking->user->email }}</strong></li>
                    </ul>
                    <hr>
                    <!-- Detail Reservasi -->
                    <h6 class="mt-4">Informasi Reservasi</h6>
                    <ul class="list-unstyled mb-4">
                        <li class="d-flex justify-content-between"><span>Tempat:</span>
                            <strong>{{ $booking->place->name }}</strong></li>
                        <li class="d-flex justify-content-between"><span>Tanggal & Waktu:</span>
                            <strong>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }} jam
                                {{ \Carbon\Carbon::parse($booking->booking_time)->format('H:i') }}</strong></li>
                        <li class="d-flex justify-content-between"><span>Jumlah Orang:</span>
                            <strong>{{ $booking->number_of_people }} orang</strong></li>
                        <li class="d-flex justify-content-between"><span>Meja Dipesan:</span>
                            <div>
                                @foreach ($booking->tables as $table)
                                    <span class="badge bg-primary">{{ $table->table_number }}</span>
                                @endforeach
                            </div>
                        </li>
                        @if ($booking->notes)
                            <li class="d-flex flex-column"><span>Catatan Pelanggan:</span>
                                <p class="p-2 bg-light rounded mt-1"><em>"{{ $booking->notes }}"</em></p>
                            </li>
                        @endif
                    </ul>
                    <hr>
                    <!-- Detail Pesanan Menu -->
                    <h6 class="mt-4">Pesanan Menu</h6>
                    <div class="table-responsive text-nowrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Menu</th>
                                    <th>Qty</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($booking->menuItems as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->pivot->quantity }}x</td>
                                        <td class="text-end">Rp
                                            {{ number_format($item->pivot->price * $item->pivot->quantity, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                     <tr>
                                        <td colspan="3" class="text-center">Tidak ada menu yang dipesan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2">Subtotal</td>
                                    <td class="text-end">Rp {{ number_format($booking->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2">Pajak (10%)</td>
                                    <td class="text-end">Rp {{ number_format($booking->tax, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2"><strong>Total Tagihan</strong></td>
                                    <td class="text-end"><strong>Rp
                                            {{ number_format($booking->total_amount, 0, ',', '.') }}</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><strong>DP (50%)</strong></td>
                                    <td class="text-end"><strong>Rp
                                            {{ number_format($booking->down_payment_amount, 0, ',', '.') }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Bukti Pembayaran & Aksi -->
        <div class="col-lg-4">
             <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Status Pembayaran</h5>
                </div>
                <div class="card-body">
                    @if ($booking->payment_status == 'paid')
                        <div class="alert alert-success p-2 text-center">Pembayaran DP Lunas</div>
                    @else
                        <div class="alert alert-warning p-2 text-center">Menunggu Pembayaran DP</div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Ubah Status Booking</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.bookings.updateStatus', $booking->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="pending_payment" {{ $booking->status == 'pending_payment' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                                <option value="pending_confirmation" {{ $booking->status == 'pending_confirmation' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Terkonfirmasi</option>
                                <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="status_description" class="form-label">Alasan/Deskripsi (Opsional)</label>
                            <textarea class="form-control" id="status_description" name="status_description" rows="3" placeholder="Contoh: Bukti pembayaran tidak valid atau DP Lunas">{{ $booking->status_description }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
                    </form>

                    @if ($booking->status == 'cancelled' && $booking->status_description)
                        <div class="alert alert-secondary mt-3">
                            <strong>Alasan Pembatalan:</strong>
                            <p class="mb-0"><em>{{ $booking->status_description }}</em></p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
