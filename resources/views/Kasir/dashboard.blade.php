@extends('Kasir.Kasir_layout.app')

@section('content')
    <div class="container-fluid">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
            <div class="container-fluid">
                <a class="navbar-brand fw-bold" href="#">📊 Dashboard Kasir</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

            </div>
        </nav>

        <!-- Main Content -->
        <!-- Dashboard Content -->
        <div class="col-md-9">
            <h3 class="fw-bold mb-3">👋 Selamat Datang di Dashboard</h3>
            @if (auth()->check())
                <p>Halo, {{ auth()->user()->name }}! Anda login sebagai <strong>{{ auth()->user()->role }}</strong>.</p>
            @endif


            <div class="row">
                <!-- Card Statistik -->
                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title">Total Produk</h5>
                            <p class="card-text fs-3 fw-bold">{{ $barang }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title">Total Kategori</h5>
                            <p class="card-text fs-3 fw-bold">{{ $kategori }}</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Chart Placeholder -->
            <div class="mt-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title">Stok Barang</h5>
                        <div class="row">
                            @foreach ($stokBarang as $data)
                                <div class="col-md-4">
                                    <div class="card shadow-sm border-0 mb-3">
                                        <div class="card-body text-center">
                                            <h5 class="card-title">{{ $data->nama_barang }}</h5>
                                            <p class="card-text fs-3 fw-bold">{{ $data->stok }} stok</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
