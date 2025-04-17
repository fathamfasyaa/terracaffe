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

        <!-- 🔍 Input cari barang by kode -->
        <div class="mb-3">
            <label>Cari Barang (kode)</label>
            <input type="text" id="cari_kode_barang" class="form-control" placeholder="Masukkan kode barang lalu tekan Enter">
        </div>

        <div id="barang-container">
            <div class="row mb-3 barang-row">
                <div class="col-md-4">
                    <label>Barang</label>
                    <select name="barang_id[]" class="form-control barang-select">
                        <option value="">-- Pilih Barang --</option>
                        @foreach ($barang as $b)
                            <option 
                                value="{{ $b->id }}" 
                                data-harga="{{ $b->harga_jual }}" 
                                data-kode="{{ $b->kode_barang }}">
                                {{ $b->nama_barang }} (Stok: {{ $b->stok }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Jumlah</label>
                    <input type="number" name="jumlah[]" class="form-control jumlah-input" min="1" value="1">
                </div>
                <div class="col-md-2">
                    <label>Subtotal</label>
                    <input type="text" class="form-control subtotal" readonly>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-hapus">Hapus</button>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-secondary mb-3" onclick="tambahBarang()">Tambah Barang</button>

        <div class="row mb-3">
            <div class="col-md-4">
                <label>Total Bayar</label>
                <input type="text" id="total_bayar_display" class="form-control" readonly>
                <input type="hidden" name="total_bayar" id="total_bayar">
            </div>
            <div class="col-md-4">
                <label>Uang Diberikan</label>
                <input type="text" name="uang_diberikan" id="uang_diberikan" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label>Kembalian</label>
                <input type="text" id="kembalian_display" class="form-control" readonly>
                <input type="hidden" name="kembalian" id="kembalian">
            </div>
        </div>

        <button type="submit" class="btn btn-success mb-3">Simpan</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function parseFormattedNumber(str) {
        return parseFloat(str.replace(/\./g, '')) || 0;
    }

    function updateSubtotal(row) {
        const harga = parseFloat(row.querySelector('.barang-select').selectedOptions[0]?.getAttribute('data-harga') || 0);
        const jumlah = parseFloat(row.querySelector('.jumlah-input').value) || 0;
        const subtotal = harga * jumlah;

        const subtotalField = row.querySelector('.subtotal');
        subtotalField.value = formatNumber(subtotal);
        subtotalField.dataset.rawValue = subtotal;

        updateTotalBayar();
    }

    function updateTotalBayar() {
        let total = 0;
        document.querySelectorAll('.subtotal').forEach(field => {
            total += parseFloat(field.dataset.rawValue) || 0;
        });

        document.getElementById('total_bayar_display').value = formatNumber(total);
        document.getElementById('total_bayar').value = total;
        updateKembalian();
    }

    function updateKembalian() {
        const totalBayar = parseFloat(document.getElementById('total_bayar').value) || 0;
        const uangInput = document.getElementById('uang_diberikan');
        const uangDiberikan = parseFormattedNumber(uangInput.value);
        const selisih = uangDiberikan - totalBayar;

        if (selisih >= 0) {
            document.getElementById('kembalian_display').value = formatNumber(selisih);
            document.getElementById('kembalian').value = selisih;
            document.getElementById('kembalian_display').classList.remove('text-danger');
            document.getElementById('kembalian_display').classList.add('text-success');
        } else {
            const kurang = Math.abs(selisih);
            document.getElementById('kembalian_display').value = `-${formatNumber(kurang)}`;
            document.getElementById('kembalian').value = -kurang;
            document.getElementById('kembalian_display').classList.remove('text-success');
            document.getElementById('kembalian_display').classList.add('text-danger');
        }
    }

    document.getElementById('uang_diberikan').addEventListener('input', function(e) {
        const cursorPos = this.selectionStart;
        const originalLength = this.value.length;
        const parsed = parseFormattedNumber(this.value);
        this.value = parsed > 0 ? formatNumber(parsed) : '';
        const newLength = this.value.length;
        const newCursorPos = cursorPos + (newLength - originalLength);
        this.setSelectionRange(newCursorPos, newCursorPos);
        updateKembalian();
    });

    function initRowEvent(row) {
        row.querySelector('.barang-select').addEventListener('change', () => updateSubtotal(row));
        row.querySelector('.jumlah-input').addEventListener('input', () => updateSubtotal(row));
        row.querySelector('.btn-hapus').addEventListener('click', function () {
            if (document.querySelectorAll('.barang-row').length > 1) {
                row.remove();
                updateTotalBayar();
            }
        });
    }

    document.querySelectorAll('.barang-row').forEach(initRowEvent);

    window.tambahBarang = function() {
        const container = document.getElementById('barang-container');
        const newRow = document.querySelector('.barang-row').cloneNode(true);

        newRow.querySelector('.barang-select').selectedIndex = 0;
        newRow.querySelector('.jumlah-input').value = 1;
        newRow.querySelector('.subtotal').value = '';
        newRow.querySelector('.subtotal').dataset.rawValue = '';

        initRowEvent(newRow);
        container.appendChild(newRow);
    };

    // 🔍 Handler pencarian barang by kode
    document.getElementById('cari_kode_barang').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const kode = this.value.trim();
            if (!kode) return;

            const barangOption = document.querySelector(`.barang-select option[data-kode="${kode}"]`);
            if (barangOption) {
                const container = document.getElementById('barang-container');

                let targetRow = Array.from(container.querySelectorAll('.barang-row')).find(row => {
                    const selectedOption = row.querySelector('.barang-select').selectedOptions[0];
                    return selectedOption && selectedOption.getAttribute('data-kode') === kode;
                });

                if (targetRow) {
                    const jumlahInput = targetRow.querySelector('.jumlah-input');
                    jumlahInput.value = parseInt(jumlahInput.value) + 1;
                } else {
                    targetRow = container.querySelector('.barang-row');
                    targetRow.querySelector('.barang-select').value = barangOption.value;
                    targetRow.querySelector('.jumlah-input').value = 1;
                }

                updateSubtotal(targetRow);
                this.value = ''; // clear input
            } else {
                alert('Barang dengan kode tersebut tidak ditemukan!');
            }
        }
    });
});
</script>

@endsection

