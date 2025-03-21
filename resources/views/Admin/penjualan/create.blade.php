@extends('Admin.layout_admin.app')

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

        <form action="{{ route('admin.penjualan.store') }}" method="POST">

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
                    <div class="col">
                        <label>Dibayar</label>
                        <input type="number" name="uangDiberikan" class="form-control" min="1">
                    </div>

                </div>
            </div>


            <button type="button" class="btn btn-secondary" onclick="tambahBarang()">Tambah Barang</button>
            <button type="submit" class="btn btn-success">Simpan</button>
            <button type="button" class="btn btn-warning" onclick="cetakStruk()">Cetak Struk</button>
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

        function cetakStruk() {
            let barangList = document.querySelectorAll('select[name="barang_id[]"]');
            let jumlahList = document.querySelectorAll('input[name="jumlah[]"]');
            let uangDiberikanInput = document.querySelector('input[name="uangDiberikan"]');

            let barangData = [];
            let totalHarga = 0;

            barangList.forEach((barang, index) => {
                let barangId = barang.value;
                let jumlah = jumlahList[index].value;

                if (barangId && jumlah) {
                    barangData.push({
                        barang_id: barangId,
                        jumlah: jumlah
                    });

                }
            });

            let uangDiberikan = uangDiberikanInput ? parseInt(uangDiberikanInput.value) || 0 : 0;
            let kembalian = uangDiberikan - totalHarga;

            let barangEncoded = encodeURIComponent(JSON.stringify(barangData));

            // Redirect ke halaman cetak struk dengan parameter
            window.location.href =
                `/admin/penjualan/create/struk?barang=${barangEncoded}&uang_diberikan=${uangDiberikan}&kembalian=${kembalian}`;
        }
    </script>
@endsection
