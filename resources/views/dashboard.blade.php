@extends('layout.app')


@section('content')

<div class="container-fluid">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">📊 Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

        </div>
    </nav>

    <!-- Main Content -->
    <!-- Dashboard Content -->
        <div class="col-md-9">
            <h3 class="fw-bold mb-3">👋 Selamat Datang di Dashboard</h3>
            
            <div class="row">
                <!-- Card Statistik -->
                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title">Total Produk</h5>
                            <p class="card-text fs-3 fw-bold">150</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title">Transaksi Bulan Ini</h5>
                            <p class="card-text fs-3 fw-bold">Rp 25.000.000</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title">Pengguna Aktif</h5>
                            <p class="card-text fs-3 fw-bold">35</p>
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
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei'],
            datasets: [{
                label: 'Pendapatan',
                data: [5000000, 7000000, 8000000, 9000000, 10000000],
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        }
    });
</script>


@endsection