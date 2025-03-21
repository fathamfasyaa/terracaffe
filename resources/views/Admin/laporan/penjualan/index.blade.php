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
                @foreach ($penjualan as $p)
                    <tr>
                        <td>{{ $p->no_faktur }}</td>
                        <td>
                            @foreach ($p->detailPenjualan as $detail)
                                {{ $detail->barang->nama_barang }}
                            @endforeach
                        </td>
                        <td>
                            @foreach ($p->detailPenjualan as $data)
                                {{ $data->jumlah }}
                            @endforeach
                        </td>
                        <td>
                            @foreach ($p->detailPenjualan as $data)
                                Rp{{ number_format($data->harga_jual, 0, ',', '.') }}
                            @endforeach
                        </td>
                        <td>Rp{{ number_format($p->total_bayar, 0, ',', '.') }}</td>
                        <td>{{ $p->tgl_faktur }}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
@endsection
