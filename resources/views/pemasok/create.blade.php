@extends('layout.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4>Tambah Pemasok</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('pemasok.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="nama_pemasok">Nama Pemasok</label>
                            <input type="text" name="nama_pemasok" id="nama_pemasok" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea name="alamat" id="alamat" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="nomor_telepon">Nomor Telepon</label>
                            <input type="text" name="nomor_telepon" id="nomor_telepon" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="catatan">Catatan</label>
                            <textarea name="catatan" id="catatan" class="form-control" rows="3"></textarea>
                        </div>

                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
                        <a href="{{ route('pemasok.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
