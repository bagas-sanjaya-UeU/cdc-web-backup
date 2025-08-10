@extends('dashboards.templates.base') {{-- Sesuaikan dengan path layout utama Anda --}}

{{-- Tambahkan link CSS DataTables di section head layout utama Anda jika belum ada --}}
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">
@endpush

@section('title', 'Kelola Booking')

@section('content')
    {{-- Form Filter --}}
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-filter me-1"></i>
            Filter Booking
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-5">
                    <label for="filter-date" class="form-label">Filter berdasarkan Tanggal</label>
                    <input type="date" id="filter-date" class="form-control">
                </div>
                <div class="col-md-5">
                    <label for="filter-status" class="form-label">Filter berdasarkan Status</label>
                    <select id="filter-status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="Menunggu Konfirmasi">Menunggu Konfirmasi</option>
                        <option value="Terkonfirmasi">Terkonfirmasi</option>
                        <option value="Selesai">Selesai</option>
                        <option value="Dibatalkan">Dibatalkan</option>
                        <option value="Menunggu Pembayaran">Menunggu Pembayaran</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button id="reset-btn" class="btn btn-secondary w-100">Reset</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Data --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Semua Booking</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                {{-- Tambahkan ID ke tabel --}}
                <table id="bookings-table" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Kode Booking</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Total DP</th>
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
                                <td>{{ $booking->user->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('Y-m-d') }} - {{ \Carbon\Carbon::parse($booking->booking_time)->format('H:i') }}</td>
                                <td>Rp {{ number_format($booking->down_payment_amount, 0, ',', '.') }}</td>
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
                                        <span class="badge bg-label-secondary me-1">Menunggu Pembayaran</span>
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-sm btn-info" href="{{ route('admin.bookings.show', $booking->id) }}">
                                        <i class="fas fa-eye me-1"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            {{-- Kosongkan, DataTables akan menangani jika tidak ada data --}}
                        @endforelse
                    </tbody>
                </table>
            </div>
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
            var table = $('#bookings-table').DataTable({
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

            // Fungsi untuk menerapkan filter
            function applyFilters() {
                var selectedDate = $('#filter-date').val();
                var selectedStatus = $('#filter-status').val();

                // Kolom Tanggal (indeks 2)
                // Mencari string tanggal di dalam sel
                table.column(2).search(selectedDate).draw();
                
                // Kolom Status (indeks 4)
                // Mencari teks status (misal: 'Terkonfirmasi') di dalam sel
                table.column(4).search(selectedStatus).draw();
            }

            // Event handler untuk input filter
            $('#filter-date, #filter-status').on('change', function() {
                applyFilters();
            });

            // Event handler untuk tombol reset
            $('#reset-btn').on('click', function() {
                $('#filter-date').val('');
                $('#filter-status').val('');
                table.search('').columns().search('').draw();
            });
        });
    </script>
@endpush
