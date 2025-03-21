@extends('Admin.layout_admin.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Edit Pemasok</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.pemasok.update', $pemasok->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nama_pemasok" class="form-label">Nama Pemasok</label>
                <input type="text" class="form-control" id="nama_pemasok" name="nama_pemasok"
                    value="{{ old('nama_pemasok', $pemasok->nama_pemasok) }}" required autocomplete="off">
            </div>

            <div class="mb-3">
                <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
                <input type="text" class="form-control" id="nomor_telepon" name="nomor_telepon"
                    value="{{ old('nomor_telepon', $pemasok->nomor_telepon) }}" required autocomplete="off">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email"
                    value="{{ old('email', $pemasok->email) }}" required autocomplete="off">
            </div>

            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="3" required autocomplete="off">{{ old('alamat', $pemasok->alamat) }}</textarea>
            </div>

            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
            <a href="{{ route('admin.pemasok.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection
