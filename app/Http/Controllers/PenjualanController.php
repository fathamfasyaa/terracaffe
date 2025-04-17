<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Barang;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\EscposImage;


class PenjualanController extends Controller
{
    public function index()
    {
        $penjualan = Penjualan::with('pelanggan', 'user')->latest()->get();
        $user = auth()->user();

        if ($user->role == 'admin') {
            return view('admin/penjualan/index', compact('penjualan'));
        } elseif ($user->role == 'kasir') {
            return view('kasir/penjualan/index', compact('penjualan'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function create()
    {
        $pelanggan = Pelanggan::all();
        $barang = Barang::where('stok', '>', 0)->get();
        $user = auth()->user();

        if ($user->role == 'admin') {
            return view('admin/penjualan/create', compact('pelanggan', 'barang'));
        } elseif ($user->role == 'kasir') {
            return view('kasir/penjualan/create', compact('pelanggan', 'barang'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'nullable|integer|min:0',
            'barang_id' => 'required|array',
            'barang_id.*' => 'required|exists:barang,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $penjualan = Penjualan::create([
                'no_faktur' => 'PJ' . date('YmdHis'),
                'tgl_faktur' => now(),
                'total_bayar' => 0,
                'pelanggan_id' => $request->pelanggan_id ?: null,
                'user_id' => auth()->id(),
            ]);

            $totalBayar = 0;

            foreach ($request->barang_id as $key => $barang_id) {
                $barang = Barang::find($barang_id);
                if (!$barang) {
                    throw new \Exception("Barang tidak ditemukan: " . $barang_id);
                }

                $jumlah = $request->jumlah[$key];
                $subTotal = $barang->harga_jual * $jumlah;

                DetailPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'barang_id' => $barang_id,
                    'harga_jual' => $barang->harga_jual,
                    'jumlah' => $jumlah,
                    'sub_total' => $subTotal
                ]);

                $barang->decrement('stok', $jumlah);
                $totalBayar += $subTotal;
            }

            $penjualan->update(['total_bayar' => $totalBayar]);

            DB::commit();

            // Struk otomatis setelah transaksi berhasil
            $this->cetakStruk($penjualan);

            $user = auth()->user();
            if ($user->role == 'admin') {
                return redirect()->route('admin.penjualan')->with('success', 'penjualan berhasil ditambahkan');
            } elseif ($user->role == 'kasir') {
                return redirect()->route('kasir.penjualan')->with('success', 'penjualan berhasil ditambahkan');
            } else {
                abort(403, 'Unauthorized action.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function cetakManual($id)
    {
        $penjualan = Penjualan::with(['user', 'pelanggan', 'detailPenjualan.barang'])->findOrFail($id);
        $this->cetakStruk($penjualan);
        return back()->with('success', 'Struk berhasil dicetak.');
    }

    private function cetakStruk(Penjualan $penjualan)
    {
        try {
            $connector = new WindowsPrintConnector("POS-52"); // Ganti dengan nama printer kamu
            $printer = new Printer($connector);

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("TERRA CAFE\n");
            $printer->text("Jl. Contoh Alamat No. 123\n");
            $printer->text("Telp: 0812-3456-7890\n");
            $printer->feed();

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("No Faktur : {$penjualan->no_faktur}\n");
         $printer->text("Tanggal   : " . \Carbon\Carbon::parse($penjualan->tgl_faktur)->format('d-m-Y H:i') . "\n");
            $printer->text("Kasir     : " . $penjualan->user->name . "\n");
            if ($penjualan->pelanggan) {
                $printer->text("Pelanggan : " . $penjualan->pelanggan->nama . "\n");
            }
            $printer->feed();

            $printer->text("--------------------------------\n");
            foreach ($penjualan->detailPenjualan as $detail) {
                $nama = $detail->barang->nama_barang;
                $qty = $detail->jumlah;
                $harga = number_format($detail->harga_jual, 0, ',', '.');
                $subtotal = number_format($detail->sub_total, 0, ',', '.');
                $printer->text("$nama\n");
                $printer->text("  $qty x $harga = $subtotal\n");
            }
            $printer->text("--------------------------------\n");

            $total = number_format($penjualan->total_bayar, 0, ',', '.');
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->text("Total : Rp $total\n");

            $printer->feed(2);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Terima kasih\n");
            $printer->text("~ TERRA CAFE ~\n");

            $printer->feed(4);
            $printer->cut();
            $printer->close();
        } catch (\Exception $e) {
            Log::error("Gagal mencetak struk: " . $e->getMessage());
        }
    }



    public function show($id)
    {
        $penjualan = Penjualan::with('detailPenjualan.barang')->findOrFail($id);
        $user = auth()->user();
        
        if ($user->role == 'admin') {
            return view('admin/penjualan/show', compact('penjualan'));
        } elseif ($user->role == 'kasir') {
            return view('kasir/penjualan/show', compact('penjualan'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $penjualan = Penjualan::findOrFail($id);
            foreach ($penjualan->detailPenjualan as $detail) {
                $barang = Barang::find($detail->barang_id);
                $barang->increment('stok', $detail->jumlah);
                $detail->delete();
            }

            $penjualan->delete();

            DB::commit();
            $user = auth()->user();
            if ($user->role == 'admin') {
                return redirect()->route('admin.penjualan')->with('success', 'penjualan berhasil dihapus');
            } elseif ($user->role == 'kasir') {
                return redirect()->route('kasir.penjualan')->with('success', 'penjualan berhasil dihapus');
            } else {
                abort(403, 'Unauthorized action.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        } 
    }
}
