@extends('Pemilik.layout_pemilik.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">📊 Laporan Transaksi</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Pilih jenis laporan yang ingin ditampilkan:</p>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a href="{{ route('pemilik.laporan.penjualan') }}" class="text-decoration-none">
                                    📈 Laporan Penjualan
                                </a>
                                <i class="fas fa-chart-line text-primary"></i>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a href="{{ route('pemilik.laporan.pembelian') }}" class="text-decoration-none">
                                    🛒 Laporan Pembelian
                                </a>
                                <i class="fas fa-receipt text-success"></i>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
