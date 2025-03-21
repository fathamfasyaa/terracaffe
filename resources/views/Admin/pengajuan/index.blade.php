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
    <label class="switch">
        <input type="checkbox" {{ $item->status ? 'checked' : '' }} disabled>
        <span class="slider"></span>
    </label>
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
   .switch {
  position: relative;
  display: inline-block;
  width: 40px;
  height: 20px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  transition: .4s;
  border-radius: 20px;
}

.slider:before {
  position: absolute;
  content: "";
  height: 14px;
  width: 14px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  transition: .4s;
  border-radius: 50%;
}

input:checked + .slider {
  background-color: #28a745;
}

input:checked + .slider:before {
  transform: translateX(20px);
}

</style>
@endsection
