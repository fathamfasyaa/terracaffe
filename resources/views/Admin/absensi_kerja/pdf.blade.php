<!-- resources/views/absensi_kerja/pdf.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Laporan Absensi Kerja</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Laporan Absensi Kerja</h1>
    <p>Tanggal: {{ now()->format('d/m/Y H:i:s') }}</p>
    
    <table>
        <thead>
            <tr>
                <th>Nama Karyawan</th>
                <th>Status</th>
                <th>Waktu Mulai</th>
                <th>Waktu Selesai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
            <tr>
                <td>{{ $item->user->name }}</td>
                <td>{{ ucfirst($item->status_masuk) }}</td>
                <td>{{ $item->waktu_mulai_kerja }}</td>
                <td>{{ $item->waktu_akhir_kerja ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
