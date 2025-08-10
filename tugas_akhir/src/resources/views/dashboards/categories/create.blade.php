@extends('dashboards.templates.base') {{-- Sesuaikan dengan path layout utama Anda --}}

@section('title', 'Buat Kategori')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Buat Kategori Baru</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Kategori</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Kategori</button>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        // Tambahkan script jika diperlukan
    </script>
@endsection
    