@extends('Kasir.Kasir_layout.app')
@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Daftar Kategori</h2>



        <a href="{{ route('kasir.kategori.create') }}" class="btn btn-primary mb-3">+ Tambah Kategori</a>

        <table class="table table-bordered">
            <thead class="table -dark">
                <tr>
                    <th>No</th>
                    <th>Nama Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kategoris as $key => $kategori)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $kategori->nama_kategori }}</td>
                        <td>
                            <a href="{{ route('kasir.kategori.edit', $kategori->id) }}"
                                class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('kasir.kategori.destroy', $kategori->id) }}" method="POST"
                                class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection


@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    title: "Berhasil!",
                    text: {!! json_encode(session('success')) !!},
                    icon: "success",
                    confirmButtonColor: "#4a69bd",
                    timer: 1000,
                    timerProgressBar: true,
                    showConfirmButton: true,
                    allowOutsideClick: false,
                    allowEscapeKey: false
                });
            });
        </script>
    @endif
@endpush
