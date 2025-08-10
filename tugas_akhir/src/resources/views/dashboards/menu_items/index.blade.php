@extends('dashboards.templates.base') {{-- Sesuaikan dengan path layout utama Anda --}}

@section('title', 'Kelola Menu')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Menu Makanan & Minuman</h5>
            <a href="{{ route('admin.menu-items.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Tambah Menu Baru
            </a>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Nama Menu</th>
                        <th>Harga</th>
                        <th>Status</th> {{-- Kolom Baru --}}
                        <th>Kategori</th>
                        <th>Rekomendasi</th> {{-- Kolom Baru --}}
                        <th>Aksi</th>

                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($menuItems as $item)
                        <tr>
                            <td>
                                @if ($item->image)
                                    <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}"
                                        class="img-fluid rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                @else
                                    <span class="text-muted">Tanpa Gambar</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $item->name }}</strong>
                                <p class="text-muted mb-0">{{ Str::limit($item->description, 70) }}</p>
                            </td>
                            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            {{-- Kolom Baru --}}
                            <td>
                                @if ($item->is_available == 'yes')
                                    <span class="badge bg-label-success">Tersedia</span>
                                @else
                                    <span class="badge bg-label-secondary">Habis</span>
                                @endif

                                @if ($item->is_recommended == 'yes')
                                    <span class="badge bg-label-warning mt-1">Rekomendasi</span>
                                @endif
                            </td>
                            <td>{{ $item->category }}</td>
                            <td>
                                @if ($item->is_recommended == 'yes')
                                    <span class="badge bg-label-warning">Ya</span>
                                @else
                                    <span class="badge bg-label-secondary">Tidak</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex">
                                    <a class="btn btn-sm btn-warning me-2" href="{{ route('admin.menu-items.edit', $item->id) }}">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.menu-items.destroy', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Anda yakin ingin menghapus menu ini?');">
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
                            <td colspan="5" class="text-center py-5">
                                Tidak ada data menu. Silakan tambahkan menu baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4 px-4">
            {{ $menuItems->links() }}
        </div>
    </div>
@endsection
