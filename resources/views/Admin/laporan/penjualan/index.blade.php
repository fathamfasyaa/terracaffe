@extends('Admin.layout_admin.app')

@section('content')
    <div class="container">
        <h1 class="mb-3">Laporan Penjualan</h1>

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

        {{-- Filter form --}}
        <form action="{{ route('admin.laporan.penjualan') }}" method="GET" class="row g-3 mb-4">
            <div class="col-md-3">
                <label for="filter_tanggal" class="form-label">Filter per Hari</label>
                <input type="date" name="filter_tanggal" id="filter_tanggal" class="form-control" value="{{ request('filter_tanggal') }}">
            </div>
            <div class="col-md-3">
                <label for="filter_bulan" class="form-label">Filter per Bulan</label>
                <input type="month" name="filter_bulan" id="filter_bulan" class="form-control" value="{{ request('filter_bulan') }}">
            </div>
            <div class="col-md-3 align-self-end">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.laporan.penjualan') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No Faktur</th>
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Total Bayar</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($penjualan as $p)
                    <tr>
                        <td>{{ $p->no_faktur }}</td>
                        <td>
                            <ul class="mb-0 ps-3">
                                @foreach ($p->detailPenjualan as $detail)
                                    <li>{{ $detail->barang->nama_barang }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            <ul class="mb-0 ps-3">
                                @foreach ($p->detailPenjualan as $data)
                                    <li>{{ $data->jumlah }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            <ul class="mb-0 ps-3">
                                @foreach ($p->detailPenjualan as $data)
                                    <li>Rp{{ number_format($data->harga_jual, 0, ',', '.') }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>Rp{{ number_format($p->total_bayar, 0, ',', '.') }}</td>
                        <td>{{ $p->tgl_faktur }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Data tidak ditemukan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection


