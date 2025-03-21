@extends('Admin.layout_admin.app')
@section('content')
    <div class="container">
        <h2 class="mb-4">Daftar Pembelian</h2>
        <a href="{{ route('admin.pembelian.create') }}" class="btn btn-primary mb-3">Tambah Pembelian</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Tanggal</th>
                    <th>Pemasok</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pembelians as $pembelian)
                    <tr>
                        <td>{{ $pembelian->kode_masuk }}</td>
                        <td>{{ $pembelian->tanggal_masuk }}</td>
                        <td>{{ $pembelian->pemasok->nama_pemasok }}</td>
                        <td>Rp {{ number_format($pembelian->total, 0, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('admin.pembelian.show', $pembelian->id) }}"
                                class="btn btn-info btn-sm">Detail</a>
                            <form action="{{ route('admin.pembelian.destroy', $pembelian->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
