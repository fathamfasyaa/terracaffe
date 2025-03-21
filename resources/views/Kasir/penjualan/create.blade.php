@extends('Kasir.Kasir_layout.app')
@section('content')
    <div class="container">
        <h1 class="mb-3">Tambah Transaksi Penjualan</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('kasir.penjualan.store') }}" method="POST">

            @csrf
            <div class="mb-3">
                <label>Pelanggan</label>
                <select name="pelanggan_id" class="form-control">
                    <option value="0">Umum</option>
                    @foreach ($pelanggan as $p)
                        <option value="{{ $p->id }}">{{ $p->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div id="barang-container">
                <div class="row mb-3 barang-row">
                    <div class="col">
                        <label>Barang</label>
                        <select name="barang_id[]" class="form-control">
                            <option value="">-- Pilih Barang --</option>
                            @foreach ($barang as $b)
                                <option value="{{ $b->id }}">{{ $b->nama_barang }} (Stok: {{ $b->stok }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <label>Jumlah</label>
                        <input type="number" name="jumlah[]" class="form-control" min="1">
                    </div>

                </div>
            </div>


            <button type="button" class="btn btn-secondary" onclick="tambahBarang()">Tambah Barang</button>
            <button type="submit" class="btn btn-success">Simpan</button>
        </form>
    </div>

    <script>
        function tambahBarang() {
            let container = document.getElementById('barang-container');
            let row = container.firstElementChild.cloneNode(true);

            // Hapus semua event listener sebelumnya
            row.querySelector('select[name="barang_id[]"]').value = "";
            row.querySelector('input[name="jumlah[]"]').value = "";

            container.appendChild(row);
        }
    </script>
@endsection
