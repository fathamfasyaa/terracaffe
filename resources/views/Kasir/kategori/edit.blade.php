@extends('Kasir.Kasir_layout.app')
@section('content')
    <div class="container mt-4">
        <h2>Edit Kategori</h2>

        <a href="{{ route('kasir.kategori') }}" class="btn btn-secondary mb-3">Kembali</a>

        <form action="{{ route('kasir.kategori.update', $kategori->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nama Kategori</label>
                <input type="text" name="nama_kategori" class="form-control @error('nama_kategori') is-invalid @enderror"
                    value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required>
                @error('nama_kategori')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
@endsection
