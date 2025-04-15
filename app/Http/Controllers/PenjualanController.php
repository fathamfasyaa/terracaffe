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


class PenjualanController extends Controller
{
    public function index()
    {
        $penjualan = Penjualan::with('pelanggan', 'user')->latest()->get();
        $user = auth()->user();
        
        if ($user->role == 'admin') {
            return view('admin/penjualan/index', compact('penjualan'));
        } elseif($user->role == 'kasir') {
            return view('kasir/penjualan/index', compact('penjualan'));
        }else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function create()
    {
        $pelanggan = Pelanggan::all();
        $barang = Barang::where('stok', '>', 0)->get();$user = auth()->user();
        
        if ($user->role == 'admin') {
            return view('admin/penjualan/create', compact('pelanggan', 'barang'));
        } elseif($user->role == 'kasir') {
            return view('kasir/penjualan/create', compact('pelanggan', 'barang'));
        }else {
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

        // dd($request->all());

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

    public function show($id)
    {
        $penjualan = Penjualan::with('detailPenjualan.barang')->findOrFail($id);
        $user = auth()->user();
        
        if ($user->role == 'admin') {
            return view('admin/penjualan/show', compact('penjualan'));
        } elseif($user->role == 'kasir') {
            return view('kasir/penjualan/show', compact('penjualan'));
        }else {
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

public function cetakStruk(Request $request)
{
    // Ambil query parameters
    $totalHarga = $request->query('total_bayar');
    $uangDiberikan = $request->query('uang_diberikan');
    $kembalian = $uangDiberikan - $totalHarga;
    $selectedBarang = json_decode(urldecode($request->query('barang')), true);

    // Get the current year
    $currentYear = date('Y');

    // Get the last invoice number for the current year from the penjualan table
    $lastInvoice = DB::table('penjualan')
        ->where('no_faktur', 'like', 'PJ' . $currentYear . '%')
        ->orderBy('no_faktur', 'desc')
        ->first();
    // Determine the new sequential number
    if ($lastInvoice) {
        $lastNoFaktur = $lastInvoice->no_faktur;
        $lastSequentialNumber = (int)substr($lastNoFaktur, -3);
        $newSequentialNumber = str_pad($lastSequentialNumber + 1, 3, '0', STR_PAD_LEFT);
    } else {
        $newSequentialNumber = '001';
    }

    // Create the new invoice number
    $newInvoiceNumber = 'PJ' . $currentYear . $newSequentialNumber;

    // Buat data penjualan sementara untuk struk
    $penjualanData = Penjualan::where('no_faktur', $newInvoiceNumber)->first();

$penjualan = (object) [
    'no_faktur' => $penjualanData->no_faktur ?? $newInvoiceNumber,
    'total_bayar' => $penjualanData->total_harga ?? 0, // Ambil dari tabel penjualan
    'uang_diberikan' => $penjualanData->uang_diberikan ?? 0,
    'kembalian' => $penjualanData->kembalian ?? 0,
    'tgl_faktur' => $penjualanData->created_at ?? now(),
    'details' => array_map(function ($barang) {
        $barangData = Barang::find($barang['barang_id']);
        return (object) [
            'barang' => (object) [
                'nama_barang' => $barangData->nama_barang ?? 'Tidak Diketahui',
            ],
            'harga_jual' => $barangData->harga_jual ?? 0,
            'jumlah' => $barang['jumlah'],
        ];
    }, $selectedBarang),
];


    // Format tanggal
    $penjualan->tanggal_formatted = Carbon::parse($penjualan->tgl_faktur)->locale('id');

    // Tampilkan view struk
    $user = auth()->user();
    if ($user->role == 'admin') {
        return view('Admin.penjualan.struk', compact('penjualan', 'totalHarga', 'uangDiberikan', 'kembalian'));
    } else {
        abort(403, 'Unauthorized action.');
    }
}
}