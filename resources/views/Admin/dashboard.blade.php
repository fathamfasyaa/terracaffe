@extends('Admin.layout_admin.app')


@section('content')
    <div class="container-fluid">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
            <div class="container-fluid">
                <a class="navbar-brand fw-bold" href="#">📊 Dashboard Admin</a>
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
                            <h5 class="card-title">Total Transaksi Pembelian</h5>
                            <p class="card-text fs-3 fw-bold">Rp.
                                {{ number_format(array_sum($dataPembelian), 0, ',', '.') }}</p>

                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title">Total Transaksi Penjualan</h5>
                            <p class="card-text fs-3 fw-bold">Rp.
                                {{ number_format(array_sum($dataPenjualan), 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title">Pengguna Aktif</h5>
                            <p class="card-text fs-3 fw-bold">{{ $user }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart Placeholder -->
            <div class="mt-4">
                <canvas id="chartDashboard"></canvas>
            </div>
        </div>
    </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('chartDashboard').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                        label: 'Total Pembelian (Rp)',
                        data: {!! json_encode($dataPembelian) !!},
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Total Penjualan (Rp)',
                        data: {!! json_encode($dataPenjualan) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Bulan'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Total (Rp)'
                        }
                    }
                }
            }
        });
    </script>
@endsection
