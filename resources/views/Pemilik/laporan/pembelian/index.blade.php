@extends('Pemilik.layout_pemilik.app')

@section('content')
    <div class="container">
        <h1 class="mb-3">Laporan Pembelian</h1>

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
                @foreach ($pembelian as $p)
                    <tr>
                        <td>{{ $p->kode_masuk }}</td>
                        <td>
                            @foreach ($p->detailPembelian as $detail)
                                {{ $detail->barang->nama_barang }}
                            @endforeach
                        </td>
                        <td>
                            @foreach ($p->detailPembelian as $detail)
                                {{ $detail->jumlah }}
                            @endforeach
                        </td>
                        <td>
                            @foreach ($p->detailPembelian as $detail)
                                Rp{{ number_format($detail->harga_beli, 0, ',', '.') }}
                            @endforeach
                        </td>
                        <td>Rp{{ number_format($p->total, 0, ',', '.') }}</td>
                        <td>{{ $p->tanggal_masuk }}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
@endsection
