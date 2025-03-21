@extends('Admin.layout_admin.app')

@section('content')
    <div class="container">
        <h1 class="mb-4 text-center fw-bold text-primary">
            <i class="bi bi-person-lines-fill"></i> Detail Pemasok
        </h1>

        <a href="{{ route('admin.pemasok.index') }}" class="btn btn-outline-primary mb-4 d-flex align-items-center">
            <i class="bi bi-arrow-left me-2"></i> Kembali
        </a>

        <div class="card border-0 shadow-lg rounded-4 overflow-hidden position-relative"
            style="background: linear-gradient(135deg, #4f94d4, #3b5998); color: white;">

            <!-- Efek Glassmorphism -->
            <div class="position-absolute top-0 start-0 w-100 h-100"
                style="backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.1);">
            </div>

            <div class="card-body p-5 position-relative">
                <div class="text-center mb-4">
                    <i class="bi bi-shop display-1 text-light"></i>
                    <h2 class="fw-bold">{{ $pemasok->nama_pemasok }}</h2>
                    <small class="text-white-50">Informasi lengkap pemasok</small>
                </div>

                <div class="d-flex justify-content-center">
                    <div class="w-75">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item bg-transparent text-white">
                                <i class="bi bi-geo-alt-fill text-warning"></i>
                                <strong>Alamat:</strong> {{ $pemasok->alamat }}
                            </li>
                            <li class="list-group-item bg-transparent text-white">
                                <i class="bi bi-telephone-fill text-success"></i>
                                <strong>Nomor Telepon:</strong> {{ $pemasok->nomor_telepon }}
                            </li>
                            <li class="list-group-item bg-transparent text-white">
                                <i class="bi bi-envelope-fill text-danger"></i>
                                <strong>Email:</strong> {{ $pemasok->email }}
                            </li>
                            <li class="list-group-item bg-transparent text-white">
                                <i class="bi bi-card-text text-info"></i>
                                <strong>Catatan:</strong> {{ $pemasok->catatan ?: 'Tidak ada catatan' }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
