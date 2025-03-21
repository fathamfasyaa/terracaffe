@extends('Admin.layout_admin.app')

@section('content')
    <div class="container">
        <h1 class="mb-3">Detail Transaksi</h1>
        <p><strong>No Faktur:</strong> {{ $penjualan->no_faktur }}</p>
        <p><strong>Tanggal:</strong> {{ $penjualan->tgl_faktur }}</p>
        <p><strong>Pelanggan:</strong> {{ $penjualan->pelanggan->nama ?? 'Umum' }}</p>
        <p><strong>Total Bayar:</strong> Rp{{ number_format($penjualan->total_bayar, 0, ',', '.') }}</p>

        <h4>Detail Barang</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Harga Jual</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($penjualan->detailPenjualan as $detail)
                    <tr>
                        <td>{{ $detail->barang->nama_barang }}</td>
                        <td>Rp{{ number_format($detail->harga_jual, 0, ',', '.') }}</td>
                        <td>{{ $detail->jumlah }}</td>
                        <td>Rp{{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <a href="{{ route('admin.penjualan') }}" class="btn btn-primary">Kembali</a>
    </div>
@endsection
