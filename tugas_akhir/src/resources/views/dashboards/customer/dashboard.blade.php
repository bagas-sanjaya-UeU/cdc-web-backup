@extends('dashboards.templates.base') {{-- Sesuaikan dengan path layout utama Anda --}}

{{-- Tambahkan link CSS DataTables di section head layout utama Anda jika belum ada --}}
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">
@endpush

@section('title', 'Riwayat Booking Saya')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Riwayat Booking Saya</h5>
            <a href="{{ route('booking.step-one') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Buat Booking Baru
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                {{-- Tambahkan ID ke tabel --}}
                <table id="customer-bookings-table" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Kode Booking</th>
                            <th>Tempat</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($bookings as $booking)
                            <tr>
                                <td>
                                    <strong>#{{ $booking->booking_code }}</strong>
                                </td>
                                <td>{{ $booking->place->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</td>
                                <td>
                                    @if ($booking->status == 'pending_confirmation')
                                        <span class="badge bg-label-warning me-1">Menunggu Konfirmasi</span>
                                    @elseif ($booking->status == 'confirmed')
                                        <span class="badge bg-label-success me-1">Terkonfirmasi</span>
                                    @elseif ($booking->status == 'completed')
                                        <span class="badge bg-label-primary me-1">Selesai</span>
                                    @elseif ($booking->status == 'cancelled')
                                        <span class="badge bg-label-danger me-1">Dibatalkan</span>
                                    @else
                                        <span
                                            class="badge bg-label-secondary me-1">{{ ucfirst(str_replace('_', ' ', $booking->status)) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a class="btn btn-sm btn-info me-2" href="{{ route('customer.bookings.show', $booking->id) }}">
                                            <i class="fas fa-eye me-1"></i> Detail
                                        </a>
                                        
                                        @if(in_array($booking->status, ['pending_confirmation', 'confirmed']))
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#cancelBookingModal-{{ $booking->id }}">
                                                <i class="fas fa-times-circle me-1"></i> Batal
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Pembatalan untuk setiap booking -->
                            @if(in_array($booking->status, ['pending_confirmation', 'confirmed']))
                            <div class="modal fade" id="cancelBookingModal-{{ $booking->id }}" tabindex="-1" aria-labelledby="cancelBookingModalLabel-{{ $booking->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="cancelBookingModalLabel-{{ $booking->id }}">Batalkan Booking #{{ $booking->booking_code }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('customer.bookings.cancel', $booking->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <p>Anda yakin ingin membatalkan booking ini? <br> Tindakan ini tidak dapat diurungkan dan Dana tidak kembali.</p>
                                                <div class="mt-3">
                                                    <label for="cancellation_reason-{{ $booking->id }}" class="form-label">Alasan Pembatalan <span class="text-danger">*</span></label>
                                                    <textarea class="form-control" id="cancellation_reason-{{ $booking->id }}" name="cancellation_reason" rows="3" required placeholder="Contoh: Ada acara mendadak"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                <button type="submit" class="btn btn-danger">Ya, Batalkan Booking</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @empty
                            {{-- Biarkan kosong, DataTables akan menangani jika tidak ada data --}}
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Hapus pagination bawaan Laravel karena sudah ditangani oleh DataTables --}}
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTables
            $('#customer-bookings-table').DataTable({
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ entri",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                    "infoFiltered": "(difilter dari _MAX_ total entri)",
                    "zeroRecords": "Tidak ada data yang cocok",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Berikutnya",
                        "previous": "Sebelumnya"
                    },
                }
            });
        });
    </script>
@endpush
