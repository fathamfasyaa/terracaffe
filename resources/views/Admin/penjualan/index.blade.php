@extends('Admin.layout_admin.app')

@section('content')
    <div class="container">
        <h1 class="mb-3">Daftar Transaksi Penjualan</h1>
        <a href="{{ route('admin.penjualan.create') }}" class="btn btn-primary mb-3">Tambah Transaksi</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No Faktur</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Total Bayar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($penjualan as $p)
                    <tr>
                        <td>{{ $p->no_faktur }}</td>
                        <td>{{ $p->tgl_faktur }}</td>
                        <td>{{ $p->pelanggan->nama ?? 'Umum' }}</td>
                        <td>Rp{{ number_format($p->total_bayar, 0, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('admin.penjualan.show', $p->id) }}" class="btn btn-info btn-sm">Detail</a>
                            <form action="{{ route('admin.penjualan.destroy', $p->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
