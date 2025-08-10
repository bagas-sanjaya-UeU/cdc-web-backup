@extends('dashboards.templates.base') {{-- Sesuaikan dengan path layout utama Anda --}}

@section('title', 'Tambah Tempat Baru')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Form Tambah Tempat</h5>
                    <a href="{{ route('admin.places.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.places.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="name">Nama Tempat</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name') }}" placeholder="Contoh: CDC 1" required />
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="description">Deskripsi (Opsional)</label>
                            <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror"
                                placeholder="Deskripsi singkat tentang tempat ini">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Tempat</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
