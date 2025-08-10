@extends('dashboards.templates.base') {{-- Sesuaikan dengan path layout utama Anda --}}

@section('title', 'Kelola Tempat')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Tempat Booking</h5>
            <a href="{{ route('admin.places.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Tambah Tempat Baru
            </a>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nama Tempat</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($places as $place)
                        <tr>
                            <td>
                                <strong>{{ $place->name }}</strong>
                            </td>
                            <td>
                                <p class="text-muted mb-0">{{ Str::limit($place->description, 100) }}</p>
                            </td>
                            <td>
                                <div class="d-flex">
                                    <a class="btn btn-sm btn-warning me-2"
                                        href="{{ route('admin.places.edit', $place->id) }}">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.places.destroy', $place->id) }}" method="POST"
                                        onsubmit="return confirm('Anda yakin ingin menghapus tempat ini? Semua meja yang terhubung juga akan terhapus.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash me-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-5">
                                Tidak ada data tempat. Silakan tambahkan tempat baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4 px-4">
            {{ $places->links() }}
        </div>
    </div>
@endsection
