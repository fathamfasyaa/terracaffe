@extends('Admin.layout_admin.app')

@section('content')
<div class="container">
    <h2>Tambah Pengajuan Produk </h2>

    
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
    <form action="{{ route('pengajuan-barang.store') }}" method="POST" autocomplete="off">
    @csrf
    <div class="mb-3">
        <label>Nama Pengaju</label>
        <input type="text" name="pengaju" class="form-control" value="" required autocomplete="off">
    </div>
    <div class="mb-3">
        <label>Nama Produk</label>
        <input type="text" name="nama_barang" class="form-control" value="" required autocomplete="off">
    </div>
    <div class="mb-3">
        <label>Deskripsi</label>
        <textarea name="deskripsi" class="form-control" autocomplete="off"></textarea>
    </div>
    <div class="mb-3">
        <label>Tanggal Pengajuan</label>
        <input type="date" name="tgl_pengajuan" class="form-control" value="" required autocomplete="off">
    </div>
    <div class="mb-3">
        <label>Quantity</label>
        <input type="number" name="qty" class="form-control" value="" required autocomplete="off">
    </div>
    <button type="submit" class="btn btn-success">Simpan</button>
</form>

@endsection
