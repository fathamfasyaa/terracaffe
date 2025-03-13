@extends('layout.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Detail Pemasok</h1>
    <a href="{{ route('pemasok.index') }}" class="btn btn-secondary mb-3">Kembali</a>

    <div class="card">
        <div class="card-body">
            <h3>{{ $pemasok->nama_pemasok }}</h3>
            <p><strong>Alamat:</strong> {{ $pemasok->alamat }}</p>
            <p><strong>Nomor Telepon:</strong> {{ $pemasok->nomor_telepon }}</p>
            <p><strong>Email:</strong> {{ $pemasok->email }}</p>
            <p><strong>Catatan:</strong> {{ $pemasok->catatan }}</p>
        </div>
    </div>
</div>
@endsection
