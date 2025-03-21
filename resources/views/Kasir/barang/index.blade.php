@extends('Kasir.Kasir_layout.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Daftar Produk</h1>

        <a href="{{ route('kasir.barang.create') }}" class="btn btn-primary mb-3">Tambah Produk</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Kode</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th> <!-- Tambahkan kolom kategori -->
                    <th>Satuan</th>
                    <th>Harga Jual</th>
                    <th>Stok</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($barang as $item)
                    <tr>
                        <td>{{ $item->kode_barang }}</td>
                        <td>{{ $item->nama_barang }}</td>
                        <td>{{ $item->kategori->nama_kategori ?? '-' }}</td> <!-- Tampilkan nama kategori -->
                        <td>{{ $item->satuan }}</td>
                        <td>Rp{{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                        <td>{{ $item->stok }}</td>
                        <td>
                            {{-- {{ $item->gambar }} --}}
                            @if ($item->gambar)
                                <img src="{{ asset('barang/' . $item->gambar) }}" alt="Gambar" width="50">
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('kasir.barang.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('kasir.barang.destroy', $item->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Yakin ingin menghapus?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
