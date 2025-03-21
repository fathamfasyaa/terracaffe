@extends('Admin.layout_admin.app')

@section('content')
<div class="container">
    <h2>Pengajuan Barang</h2>
    
    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('pengajuan-barang.create') }}" class="btn btn-primary">+ Tambah Pengajuan</a>
        <div>
            <a href="{{ route('pengajuan.export.pdf') }}" class="btn btn-danger">Export PDF</a>
            <a href="{{ route('pengajuan.export.excel') }}" class="btn btn-success">Export Excel</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif

    <table class="table mt-3">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pengaju</th>
                <th>Nama Barang</th>
                <th>Tanggal Pengajuan</th>
                <th>Qty</th>
                <th class="text-center">Terpenuhi?</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pengajuan as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->pengaju ?? 'Unknown' }}</td>
                <td>{{ $item->nama_barang }}</td>
                <td>{{ $item->tgl_pengajuan->format('d/m/Y') }}</td>
                <td>{{ $item->qty }}</td>
                <td class="text-center">
                    <input type="checkbox" class="form-check-input checkbox-green" {{ $item->status ? 'checked' : '' }} disabled>
                </td>
                <td>
                    <a href="{{ route('pengajuan-barang.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('pengajuan-barang.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengajuan ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Tambahkan CSS untuk styling --}}
<style>
    /* Menjadikan checkbox hijau ketika dicentang */
    .checkbox-green {
        width: 18px;
        height: 18px;
        border-radius: 3px;
        border: 2px solid #28a745;
        position: relative;
    }

    .checkbox-green:checked {
        background-color: #28a745;
        border-color: #28a745;
    }

    /* Menjadikan checkbox lebih sejajar */
    .text-center {
        vertical-align: middle !important;
        text-align: center !important;
    }
</style>
@endsection
