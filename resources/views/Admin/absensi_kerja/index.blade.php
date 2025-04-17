@extends('Admin.layout_admin.app')

@section('content')
<div class="container">
    <h2>Absensi Kerja Karyawan</h2>
    <div class="d-flex gap-2 mb-4 flex-wrap align-items-center">
        <button class="btn btn-primary btn-sm px-2 py-1 rounded-pill shadow-sm" id="btnTambah">+ Tambah Absensi</button>
        <a href="{{ route('absensi-kerja.export.excel') }}" class="btn btn-success btn-sm px-2 py-1 rounded-pill shadow-sm">Export Excel</a>
        <a href="{{ route('absensi-kerja.export.pdf') }}" class="btn btn-danger btn-sm px-2 py-1 rounded-pill shadow-sm">Export PDF</a>
        <button class="btn btn-info btn-sm px-2 py-1 rounded-pill shadow-sm" id="btnImport">Import Excel</button>

        <!-- Hidden Form Import Excel -->
        <form id="importForm" action="{{ route('absensi-kerja.import') }}" method="POST" enctype="multipart/form-data" class="d-none">
            @csrf
            <input type="file" name="file" id="importFile" class="d-none" required>
        </form>
    </div>

    <table class="table table-striped table-bordered" id="absensiTable">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama Karyawan</th>
                <th>Tanggal Masuk</th>
                <th>Waktu Masuk</th>
                <th>Status</th>
                <th>Waktu Selesai Kerja</th>
                <th>Aksi</th>
            </tr>
        </thead>
    </table>
</div>

{{-- Modal --}}
<div class="modal fade" id="modalAbsensi" tabindex="-1">
    <div class="modal-dialog">
        <form id="formAbsensi">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Absensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="absensi_id">
                    <div class="mb-3">
                        <label for="user_id">Nama Karyawan</label>
                        <select class="form-control" name="user_id" id="user_id">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status_masuk">Status</label>
                        <select class="form-control" name="status_masuk" id="status_masuk">
                            <option value="masuk">Masuk</option>
                            <option value="sakit">Sakit</option>
                            <option value="cuti">Cuti</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="waktu_mulai_kerja">Waktu Masuk</label>
                        <input type="datetime-local" class="form-control" name="waktu_mulai_kerja" id="waktu_mulai_kerja">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('script')
<script>
$(document).ready(function () {
    let modal = new bootstrap.Modal(document.getElementById('modalAbsensi'));
    let table = $('#absensiTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('absensi-kerja.index') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            { data: 'nama_user', name: 'nama_user' },
            { data: 'tanggal_masuk', name: 'tanggal_masuk' },
            { data: 'waktu_masuk', name: 'waktu_masuk' },
            { data: 'status_select', name: 'status_select', orderable: false, searchable: false },
            { data: 'waktu_selesai_kerja', name: 'waktu_selesai_kerja', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    $('#btnTambah').click(function () {
        $('#formAbsensi')[0].reset();
        $('#absensi_id').val('');
        modal.show();
    });

    $('#formAbsensi').submit(function (e) {
        e.preventDefault();
        let id = $('#absensi_id').val();
        let url = id ? `/absensi-kerja/${id}` : "{{ route('absensi-kerja.store') }}";
        let method = id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            type: method,
            data: $('#formAbsensi').serialize(),
            success: function () {
                table.ajax.reload();
                modal.hide();
            }
        });
    });

    $('#absensiTable').on('click', '.edit', function () {
        let id = $(this).data('id');
        $.get(`/absensi-kerja/${id}/edit`, function (data) {
            $('#absensi_id').val(data.id);
            $('#user_id').val(data.user_id);
            $('#status_masuk').val(data.status_masuk);
            $('#waktu_mulai_kerja').val(data.waktu_mulai_kerja.replace(' ', 'T'));
            modal.show();
        });
    });

    $('#absensiTable').on('click', '.delete', function () {
        let id = $(this).data('id');
        if (confirm('Yakin ingin menghapus?')) {
            $.ajax({
                url: `/absensi-kerja/${id}`,
                type: 'DELETE',
                data: { _token: "{{ csrf_token() }}" },
                success: function () {
                    table.ajax.reload();
                }
            });
        }
    });

    $('#absensiTable').on('change', '.status-select', function () {
        let id = $(this).data('id');
        let status = $(this).val();

        $.post("{{ route('absensi-kerja.update-status') }}", {
            _token: "{{ csrf_token() }}",
            id: id,
            status_masuk: status
        }, () => table.ajax.reload());
    });

    $('#absensiTable').on('click', '.selesai-kerja', function () {
        let id = $(this).data('id');
        $.post("{{ route('absensi-kerja.selesai-kerja') }}", {
            _token: "{{ csrf_token() }}",
            id: id
        }, () => table.ajax.reload());
    });

    // Handle Import Excel
    $('#btnImport').click(function () {
        $('#importFile').click();
    });

    $('#importFile').on('change', function () {
        let form = $('#importForm')[0];
        let formData = new FormData(form);

        $.ajax({
            url: form.action,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function () {
                alert('Import berhasil!');
                $('#absensiTable').DataTable().ajax.reload();
            },
            error: function () {
                alert('Gagal import. Pastikan file sesuai format.');
            }
        });
    });
});
</script>
@endpush
