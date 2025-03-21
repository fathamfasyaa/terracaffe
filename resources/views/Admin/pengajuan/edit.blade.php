@extends('Admin.layout_admin.app')

@section('content')
    <div class="container">
        <h2>Edit Pengajuan Barang</h2>

        {{-- Menampilkan pesan error jika ada --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('pengajuan-barang.update', $pengajuan->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="pengaju" class="form-label">Nama Pengaju</label>
                <input type="text" class="form-control" id="pengaju" name="pengaju" value="{{ old('pengaju', $pengajuan->pengaju) }}" required>
            </div>

            <div class="mb-3">
                <label for="nama_barang" class="form-label">Nama Barang</label>
                <input type="text" class="form-control" id="nama_barang" name="nama_barang" value="{{ old('nama_barang', $pengajuan->nama_barang) }}" required>
            </div>

            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi">{{ old('deskripsi', $pengajuan->deskripsi) }}</textarea>
            </div>

            <div class="mb-3">
                <label for="tgl_pengajuan" class="form-label">Tanggal Pengajuan</label>
                <input type="date" class="form-control" id="tgl_pengajuan" name="tgl_pengajuan" value="{{ old('tgl_pengajuan', $pengajuan->tgl_pengajuan) }}" required>
            </div>

            <div class="mb-3">
                <label for="qty" class="form-label">Jumlah (Qty)</label>
                <input type="number" class="form-control" id="qty" name="qty" value="{{ old('qty', $pengajuan->qty) }}" min="1" required>
            </div>

            {{-- <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="status" name="status" value="1" {{ $pengajuan->status ? 'checked' : '' }}>
                <label class="form-check-label" for="status">Terpenuhi</label>
            </div> --}}

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('pengajuan-barang.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection
