@extends('Kasir.Kasir_layout.app')
@section('content')
    -
    <div class="container my-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h2 class="mb-4 text-center">Detail Pembelian</h2>
                -
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Informasi Pembelian</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Kode Pembelian:</strong> {{ $pembelian->kode_masuk }}</p>
                                <p><strong>Pemasok:</strong> {{ $pembelian->pemasok->nama_pemasok }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Tanggal Masuk:</strong> {{ $pembelian->tanggal_masuk }}</p>
                                <p><strong>Total Pembelian:</strong> Rp{{ number_format($pembelian->total, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <h4 class="mb-3">Daftar Barang</h4>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Harga Beli</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pembelian->detailPembelian as $detail)
                                <tr>
                                    <td>{{ $detail->barang->nama_barang }}</td>
                                    <td>{{ $detail->jumlah }}</td>
                                    <td>Rp{{ number_format($detail->harga_beli, 0, ',', '.') }}</td>
                                    <td>Rp{{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada barang dalam transaksi ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('kasir.pembelian') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>

    </div>
@endsection
