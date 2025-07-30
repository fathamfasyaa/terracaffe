<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Penjualan;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BackupPenjualan extends Command
{
    protected $signature = 'backup:penjualan';

    protected $description = 'Backup data penjualan harian ke file JSON';

    public function handle()
    {
        $tanggalHariIni = Carbon::today()->format('Y-m-d');

        // Ambil data penjualan berdasarkan tgl_faktur hari ini
        $data = Penjualan::whereDate('tgl_faktur', $tanggalHariIni)->get();

        if ($data->isEmpty()) {
            $this->info("Tidak ada data penjualan untuk tanggal $tanggalHariIni");
            return;
        }

        $namaFile = 'backup/penjualan_' . $tanggalHariIni . '.json';

        // Simpan ke file di storage/app/backup/
        Storage::put($namaFile, $data->toJson(JSON_PRETTY_PRINT));

        $this->info("Backup berhasil disimpan di: $namaFile");
    }
}
