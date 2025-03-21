<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\DetailPembelian;
use App\Models\Barang;
use App\Models\Pemasok;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PembelianController extends Controller
{
    public function index()
    {
        
        $pembelians = Pembelian::with('pemasok', 'user')->latest()->get();
        $user = auth()->user();
        
        if ($user->role == 'admin') {
            return view('admin/pembelian/index', compact('pembelians'));
        } elseif($user->role == 'kasir') {
            return view('kasir/pembelian/index', compact('pembelians'));
        }else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function create()
    {
        $pemasoks = Pemasok::all();
        $user = auth()->user();
        
        if ($user->role == 'admin') {
            return view('admin.pembelian.create', compact('pemasoks'));
        } elseif($user->role == 'kasir') {
            return view('kasir.pembelian.create', compact('pemasoks'));
        }else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function destroy($id)
    {
        $pembelian = Pembelian::find($id);

        if (!$pembelian) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $pembelian->delete();

            $user = auth()->user();
            if ($user->role == 'admin') {
                return redirect()->route('admin.pembelian')->with('success', 'pembelian berhasil dihapus');
            } elseif ($user->role == 'kasir') {
                return redirect()->route('kasir.pembelian')->with('success', 'pembelian berhasil dihapus');
            } else {
                abort(403, 'Unauthorized action.');
            }
    }


    public function getBarangByPemasok($pemasok_id)
    {
        $barang = Barang::where('pemasok_id', $pemasok_id)->get();

        if ($barang->isEmpty()) {
            return response()->json([], 200); // Jika tidak ada barang
        }

        return response()->json($barang);
    }


    public function show($id)
    {
        $pembelian = Pembelian::with('detailPembelian.barang', 'pemasok', 'user')->findOrFail($id);

        if ($pembelian->detailPembelian->isEmpty()) {
            return back()->with('error', 'Detail pembelian tidak ditemukan.');
        }

        $user = auth()->user();
        
        if ($user->role == 'admin') {
            return view('admin.pembelian.show', compact('pembelian'));
        } elseif($user->role == 'kasir') {
            return view('kasir.pembelian.show', compact('pembelian'));
        }else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'pemasok_id' => 'required',
            'tanggal_masuk' => 'required|date',
            'barang_id.*' => 'required|exists:barang,id',
            'jumlah.*' => 'required|integer|min:1',
            'harga_beli.*' => 'nullable|numeric|min:1',
        ]);
        
        DB::beginTransaction();
        try {
            $pembelian = Pembelian::create([
                'kode_masuk' => 'PMB' . time(),
                'tanggal_masuk' => $request->tanggal_masuk,
                'total' => 0,
                'pemasok_id' => $request->pemasok_id,
                'user_id' => auth()->id(),
            ]);
            
            $total = 0;
            foreach ($request->barang_id as $index => $barang_id) {
                $harga_beli = $request->harga_beli[$index];
                $jumlah = $request->jumlah[$index];
                $sub_total = $harga_beli * $jumlah;

                $detail = DetailPembelian::create([
                    'pembelian_id' => $pembelian->id,
                    'barang_id' => $barang_id,
                    'harga_beli' => $harga_beli,
                    'jumlah' => $jumlah,
                    'sub_total' => $sub_total,
                ]);
                
                Log::info("Detail Pembelian Disimpan: ", $detail->toArray());

                $barang = Barang::find($barang_id);
                $barang->increment('stok', $jumlah);

                if ($barang->harga_beli != $harga_beli) {
                    $barang->update(['harga_beli' => $harga_beli]);
                }

                $total += $sub_total;
            }

            $pembelian->update(['total' => $total]);

            DB::commit();
            $user = auth()->user();
            if ($user->role == 'admin') {
                return redirect()->route('admin.pembelian')->with('success', 'pembelian berhasil ditambahkan');
            } elseif ($user->role == 'kasir') {
                return redirect()->route('kasir.pembelian')->with('success', 'pembelian berhasil ditambahkan');
            } else {
                abort(403, 'Unauthorized action.');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}