@extends('Kasir.Kasir_layout.app')

@section('content')
    <div class="container">
        <h1>Edit Barang</h1>
        <a href="{{ route('kasir.barang') }}" class="btn btn-secondary mb-3">Kembali</a>

        <form action="{{ route('kasir.barang.update', $barang->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label>Pemasok</label>
                <input type="text" class="form-control" value="{{ $barang->pemasok->nama_pemasok }}" readonly>
                <input type="hidden" name="pemasok_id" value="{{ $barang->pemasok_id }}">
            </div>

            <div class="mb-3">
                <label>Kode Barang</label>
                <input type="text" name="kode_barang" class="form-control" value="{{ $barang->kode_barang }}" readonly>
            </div>

            <div class="mb-3">
                <label>Nama Barang</label>
                <input type="text" name="nama_barang" class="form-control" value="{{ $barang->nama_barang }}" required>
            </div>

            <div class="mb-3">
                <label>Satuan</label>
                <input type="text" name="satuan" class="form-control" value="{{ $barang->satuan }}" required>
            </div>

            <div class="mb-3">
                <label>Harga Jual</label>
                <input type="number" name="harga_jual" class="form-control" value="{{ $barang->harga_jual }}" required>
            </div>

            <div class="mb-3">
                <label>Stok</label>
                <input type="number" name="stok" class="form-control" value="{{ $barang->stok }}" required>
            </div>

            <div class="mb-3">
                <label>Kategori</label>
                <select name="kategori_id" class="form-control" required>
                    <option value="" disabled>Pilih Kategori</option>
                    @foreach ($kategori as $kat)
                        <option value="{{ $kat->id }}" {{ $barang->kategori_id == $kat->id ? 'selected' : '' }}>
                            {{ $kat->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Gambar (Opsional)</label>
                <input type="file" name="gambar" class="form-control">
                @if ($barang->gambar)
                    <img src="{{ asset('storage/' . $barang->gambar) }}" alt="Gambar" width="100">
                @endif
            </div>

            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
@endsection
