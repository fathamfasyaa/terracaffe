@extends('Admin.layout_admin.app')

@section('content')
    <div style="text-align: center;">
        <h2>Laporan Penjualan</h2>

        @if (request('filter_tanggal'))
            <p><strong>Filter Tanggal:</strong> {{ \Carbon\Carbon::parse(request('filter_tanggal'))->format('d M Y') }}</p>
        @endif

        @if (request('filter_bulan'))
            <p><strong>Filter Bulan:</strong> {{ \Carbon\Carbon::parse(request('filter_bulan'))->translatedFormat('F Y') }}</p>
        @endif

        <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
            <thead>
                <tr>
                    <th style="border: 1px solid #000; padding: 6px 8px;">No Faktur</th>
                    <th style="border: 1px solid #000; padding: 6px 8px;">Barang</th>
                    <th style="border: 1px solid #000; padding: 6px 8px;">Jumlah</th>
                    <th style="border: 1px solid #000; padding: 6px 8px;">Harga</th>
                    <th style="border: 1px solid #000; padding: 6px 8px;">Total Bayar</th>
                    <th style="border: 1px solid #000; padding: 6px 8px;">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($penjualan as $p)
                    <tr>
                        <td style="border: 1px solid #000; padding: 6px 8px;">{{ $p->no_faktur }}</td>
                        <td style="border: 1px solid #000; padding: 6px 8px;">
                            <ul style="margin: 0; padding-left: 20px;">
                                @foreach ($p->detailPenjualan as $d)
                                    <li>{{ $d->barang->nama_barang }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td style="border: 1px solid #000; padding: 6px 8px;">
                            <ul style="margin: 0; padding-left: 20px;">
                                @foreach ($p->detailPenjualan as $d)
                                    <li>{{ $d->jumlah }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td style="border: 1px solid #000; padding: 6px 8px;">
                            <ul style="margin: 0; padding-left: 20px;">
                                @foreach ($p->detailPenjualan as $d)
                                    <li>Rp{{ number_format($d->harga_jual, 0, ',', '.') }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td style="border: 1px solid #000; padding: 6px 8px;">Rp{{ number_format($p->total_bayar, 0, ',', '.') }}</td>
                        <td style="border: 1px solid #000; padding: 6px 8px;">{{ \Carbon\Carbon::parse($p->tgl_faktur)->format('d-m-Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
