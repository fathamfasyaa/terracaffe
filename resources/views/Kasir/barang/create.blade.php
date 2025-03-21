@extends('Kasir.Kasir_layout.app')

@section('content')
    <div class="container">
        <h1>Tambah Produk</h1>
        <a href="{{ route('kasir.barang') }}" class="btn btn-secondary mb-3">Kembali</a>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('kasir.barang.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label>Nama Produk</label>
                <input type="text" name="nama_barang" class="form-control" required autocomplete="off">
            </div>
            <div class="mb-3">
                <label>Satuan</label>
                <input type="text" name="satuan" class="form-control" required autocomplete="off">
            </div>
            <div class="mb-3">
                <label>Harga Jual</label>
                <input type="number" name="harga_jual" class="form-control" required autocomplete="off">
            </div>
            <div class="mb-3">
                <label>Stok</label>
                <input type="number" name="stok" class="form-control" required autocomplete="off">
            </div>
            <div class="mb-3">
                <label>Pemasok</label>
                <select name="pemasok_id" class="form-control" required>
                    <option value=""> Pilih Pemasok </option>
                    @foreach ($pemasoks as $pemasok)
                        <option value="{{ $pemasok->id }}">{{ $pemasok->nama_pemasok }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Kategori</label>
                <select name="kategori_id" class="form-control" required>
                    <option value=""> Pilih Kategori </option>
                    @foreach ($kategori as $kategori)
                        <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Gambar (Opsional)</label>
                <input type="file" name="gambar" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Simpan</button>
        </form>
    </div>
@endsection
