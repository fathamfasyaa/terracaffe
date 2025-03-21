@extends('Kasir.Kasir_layout.app')
@push('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="container">
        <h2 class="mb-4">Tambah Pembelian</h2>

        <form action="{{ route('kasir.pembelian.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Pemasok</label>
                <select name="pemasok_id" id="pemasok_id" class="form-control select2">
                    <option value="" selected disabled> Pilih Pemasok </option>
                    @foreach ($pemasoks as $pemasok)
                        <option value="{{ $pemasok->id }}">{{ $pemasok->nama_pemasok }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Tanggal Masuk</label>
                <input type="date" name="tanggal_masuk" class="form-control">
            </div>

            <h4>Detail Barang</h4>
            <div id="barang-list">
                <!-- Tempat menampilkan daftar barang -->
            </div>

            <button type="button" id="add-barang" class="btn btn-success">Tambah Barang</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            function initSelect2() {
                $('.select2').select2({
                    placeholder: "Pilih...",
                    allowClear: true,
                    width: '100%'
                });
            }

            // Fungsi untuk mendapatkan barang berdasarkan pemasok
            function getBarangByPemasok(pemasok_id, callback) {

                $.ajax({
                    url: `/kasir/pembelian/barang/${pemasok_id}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        let options = '<option value="" selected disabled>Pilih Barang</option>';
                        if (response.length === 0) {
                            options += '<option value="" disabled>Tidak ada barang tersedia</option>';
                        } else {
                            response.forEach(function(barang) {
                                console.log(barang);
                                options +=
                                    `<option value="${barang.id}">${barang.nama_barang}</option>`;
                            });
                        }
                        callback(options);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error AJAX:", status, error);
                        callback('<option value="" disabled>Error memuat data</option>');
                    }
                });
            }

            // Fungsi untuk menambah baris barang
            function addBarangRow(barangOptions) {
                let newRow = $(`
                    <div class="row barang-item mb-2">
                        <div class="col-md-4">
                            <select name="barang_id[]" class="form-control select2 barang-dropdown">${barangOptions}</select>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="jumlah[]" class="form-control" placeholder="Jumlah">
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="harga_beli[]" class="form-control" placeholder="Harga Beli">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-remove">Hapus</button>
                        </div>
                    </div>
                `);

                $('#barang-list').append(newRow);
                newRow.find('.select2').select2({
                    placeholder: "Pilih Barang...",
                    allowClear: true,
                    width: '100%'
                });
            }

            // Ketika pemasok dipilih, perbarui semua dropdown barang
            $('#pemasok_id').change(function() {
                let pemasok_id = $(this).val();
                if (pemasok_id) {
                    getBarangByPemasok(pemasok_id, function(barangOptions) {
                        $('#barang-list').html(''); // Kosongkan daftar barang
                        addBarangRow(barangOptions); // Tambahkan baris pertama
                    });
                }
            });

            // Tambah barang baru
            $('#add-barang').click(function() {
                let pemasok_id = $('#pemasok_id').val();
                if (pemasok_id) {
                    getBarangByPemasok(pemasok_id, function(barangOptions) {
                        addBarangRow(barangOptions);
                    });
                } else {
                    alert("Pilih pemasok terlebih dahulu!");
                }
            });

            // Hapus barang
            $(document).on('click', '.btn-remove', function() {
                $(this).closest('.barang-item').remove();
            });

            initSelect2();
        });
    </script>
@endpush
