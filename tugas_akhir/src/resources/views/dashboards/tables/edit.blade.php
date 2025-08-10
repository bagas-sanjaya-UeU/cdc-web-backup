@extends('dashboards.templates.base') {{-- Sesuaikan dengan path layout utama Anda --}}

@section('title', 'Edit Meja')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Form Edit Meja: {{ $table->table_number }}</h5>
                    <a href="{{ route('admin.tables.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    {{-- Tambahkan enctype untuk upload file --}}
                    <form action="{{ route('admin.tables.update', $table->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label" for="place_id">Pilih Tempat</label>
                            <select id="place_id" name="place_id" class="form-select @error('place_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Tempat --</option>
                                @foreach ($places as $place)
                                    <option value="{{ $place->id }}" {{ old('place_id', $table->place_id) == $place->id ? 'selected' : '' }}>
                                        {{ $place->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('place_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="table_number">Nomor atau Nama Meja</label>
                            <input type="text" class="form-control @error('table_number') is-invalid @enderror" id="table_number" name="table_number" value="{{ old('table_number', $table->table_number) }}" required />
                            @error('table_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="capacity">Kapasitas Orang</label>
                            <input type="number" class="form-control @error('capacity') is-invalid @enderror" id="capacity" name="capacity" value="{{ old('capacity', $table->capacity) }}" required min="1" />
                            @error('capacity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        {{-- Input Gambar Baru --}}
                        <div class="mb-3">
                            <label for="image" class="form-label">Ganti Gambar Meja (Opsional)</label>
                            @if ($table->image)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($table->image) }}" alt="{{ $table->table_number }}"
                                        class="img-fluid rounded" style="width: 150px; height: 150px; object-fit: cover;">
                                </div>
                            @endif
                            <input class="form-control @error('image') is-invalid @enderror" type="file" id="image" name="image">
                            <div class="form-text">Biarkan kosong jika tidak ingin mengganti gambar.</div>
                            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
