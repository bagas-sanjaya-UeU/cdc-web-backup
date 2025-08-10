@extends('dashboards.templates.base') {{-- Sesuaikan dengan path layout utama Anda --}}

@section('title', 'Edit Menu')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Form Edit Menu: {{ $menuItem->name }}</h5>
                    <a href="{{ route('admin.menu-items.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.menu-items.update', $menuItem->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        {{-- ... form input nama, deskripsi, harga, gambar tetap sama ... --}}

                        <div class="mb-3">
                            <label class="form-label" for="name">Nama Menu</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $menuItem->name) }}" required />
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="description">Deskripsi (Opsional)</label>
                            <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $menuItem->description) }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="price">Harga</label>
                            <div class="input-group"><span class="input-group-text">Rp</span><input type="number" id="price" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $menuItem->price) }}" required min="0"/></div>
                            @error('price')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Ganti Gambar Menu (Opsional)</label>
                            @if ($menuItem->image)<div class="mb-2"><img src="{{ Storage::url($menuItem->image) }}" alt="{{ $menuItem->name }}" class="img-fluid rounded" style="width: 150px; height: 150px; object-fit: cover;"></div>@endif
                            <input class="form-control @error('image') is-invalid @enderror" type="file" id="image" name="image">
                            <div class="form-text">Biarkan kosong jika tidak ingin mengganti gambar.</div>
                            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label">Kategori</label>
                            <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                <option value="" disabled>Pilih Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->name }}" {{ old('category', $menuItem->category) == $category->name ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <hr class="my-4">

                        {{-- Input Baru --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ketersediaan</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_available" id="available_yes" value="yes" {{ old('is_available', $menuItem->is_available) == 'yes' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="available_yes">Tersedia</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_available" id="available_no" value="no" {{ old('is_available', $menuItem->is_available) == 'no' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="available_no">Habis</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jadikan Rekomendasi?</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_recommended" id="recommended_yes" value="yes" {{ old('is_recommended', $menuItem->is_recommended) == 'yes' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="recommended_yes">Ya</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_recommended" id="recommended_no" value="no" {{ old('is_recommended', $menuItem->is_recommended) == 'no' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="recommended_no">Tidak</label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
