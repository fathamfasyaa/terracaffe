<!DOCTYPE html>
<html>
<head>
    <title>Pengajuan Barang</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Data Pengajuan Barang</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Pengaju</th>
                <th>Nama Barang</th>
                <th>Deskripsi</th>
                <th>Tgl Pengajuan</th>
                <th>Qty</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pengajuan as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->pengaju }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td>{{ $item->deskripsi ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tgl_pengajuan)->format('d/m/Y') }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ $item->status ? 'Disetujui' : 'Pending' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
