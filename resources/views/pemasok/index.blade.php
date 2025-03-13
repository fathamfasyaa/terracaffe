@extends('layout.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Daftar Pemasok</h1>
    <a href="{{ route('pemasok.create') }}" class="btn btn-primary mb-3">Tambah Pemasok</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pemasok</th>
                <th>Nomor Telepon</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pemasok as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->nama_pemasok }}</td>
                    <td>{{ $item->nomor_telepon }}</td>
                    <td>{{ $item->email }}</td>
                    <td>
                        <a href="{{ route('pemasok.show', $item->id) }}" class="btn btn-info">Lihat Detail</a>
                        <a href="{{ route('pemasok.edit', $item->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('pemasok.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
