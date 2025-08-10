@extends('dashboards.templates.base') {{-- Sesuaikan dengan path layout utama Anda --}}

@section('title', 'Kelola Meja')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Meja</h5>
            <a href="{{ route('admin.tables.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Tambah Meja Baru
            </a>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Gambar</th> {{-- Kolom Baru --}}
                        <th>Nomor Meja</th>
                        <th>Tempat</th>
                        <th>Kapasitas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($tables as $table)
                        <tr>
                            {{-- Kolom Baru --}}
                            <td>
                                @if ($table->image)
                                    <img src="{{ Storage::url($table->image) }}" alt="{{ $table->table_number }}"
                                        class="img-fluid rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                @else
                                    <span class="text-muted">Tanpa Gambar</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $table->table_number }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-label-info">{{ $table->place->name }}</span>
                            </td>
                             <td>
                                {{ $table->capacity }} orang
                            </td>
                            <td>
                                <div class="d-flex">
                                    <a class="btn btn-sm btn-warning me-2" href="{{ route('admin.tables.edit', $table->id) }}">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.tables.destroy', $table->id) }}" method="POST"
                                        onsubmit="return confirm('Anda yakin ingin menghapus meja ini?');">
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
                                Tidak ada data meja. Silakan tambahkan meja baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4 px-4">
            {{ $tables->links() }}
        </div>
    </div>
@endsection
