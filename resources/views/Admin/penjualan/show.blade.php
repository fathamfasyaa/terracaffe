@extends('Admin.layout_admin.app')

@section('content')
    <div class="container">
        <h1 class="mb-3">Detail Transaksi</h1>
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>No Faktur:</strong> {{ $penjualan->no_faktur }}</p>
                        <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($penjualan->tgl_faktur)->format('d/m/Y H:i:s') }}</p>
                        <p><strong>Kasir:</strong> {{ $penjualan->user->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Pelanggan:</strong> {{ $penjualan->pelanggan->nama ?? 'Umum' }}</p>
                        <p><strong>Uang Diberikan:</strong> Rp{{ number_format($penjualan->uang_diberikan, 0, ',', '.') }}</p>
                        <p><strong>Kembalian:</strong> Rp{{ number_format($penjualan->kembalian, 0, ',', '.') }}</p>
                    </div>
                </div>

                <h4 class="mt-4">Detail Barang</h4>
                <table class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Harga Jual</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($penjualan->detailPenjualan as $index => $detail)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $detail->barang->nama_barang }}</td>
                                <td>Rp{{ number_format($detail->harga_jual, 0, ',', '.') }}</td>
                                <td>{{ $detail->jumlah }}</td>
                                <td>Rp{{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-3">
                    <a href="{{ route('admin.penjualan') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Penjualan
                    </a>

                    {{-- Tombol Cetak Struk (Pastikan ada route admin.penjualan.struk) --}}
                    <form action="{{ route(auth()->user()->role == 'admin' ? 'admin.penjualan.struk' : 'kasir.penjualan.struk', $penjualan->id) }}" method="POST" style="display:inline;">
    @csrf
    <button type="submit" class="btn btn-success ml-2">
        <i class="fas fa-print"></i> Cetak Struk
    </button>
</form>
                </div>
            </div>
        </div>
    </div>
    @if (session('success'))
    <div class="alert alert-success mt-2">
        {{ session('success') }}
    </div>
@endif

@endsection
