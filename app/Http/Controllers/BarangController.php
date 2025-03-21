<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Pemasok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::with('kategori')->get();

        $user = auth()->user();
        
        if ($user->role == 'admin') {
            return view('admin/barang/index', compact('barang'));
        } elseif($user->role == 'kasir') {
            return view('Kasir/barang/index', compact('barang'));
        }else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function create()
    {
        $kategori = Kategori::all();
        $pemasoks = Pemasok::all();
        $user = auth()->user();
        
        if ($user->role == 'admin') {
            return view('admin/barang/create', compact('kategori','pemasoks'));
        } elseif($user->role == 'kasir') {
            return view('Kasir/barang/create', compact('kategori', 'pemasoks'));
        }else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'nama_barang' => 'required',
            'satuan' => 'required',
            'harga_jual' => 'required|numeric',
            'stok' => 'required|integer',
            'kategori_id' => 'required|exists:kategoris,id',
            'pemasok_id' => 'required|exists:pemasok,id',
            'gambar' => 'nullable|image|max:2048',
        ]);

        if ($request->has('gambar')) {
            $file = $request->file('gambar');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('barang'), $fileName);
            $gambarPath = 'barang/' . $fileName;
        }
        // dd($fileName);
        
        $bulanTahun = Carbon::now()->format('Ym');
        $jumlahBarang = Barang::where('kode_barang', 'LIKE', "BRG-{$bulanTahun}-%")->count() + 1;
        $kodeBarang = sprintf("BRG-%s-%04d", $bulanTahun, $jumlahBarang);

        $data = $request->all();
        $data['kode_barang'] = $kodeBarang;
        $data['gambar'] = $fileName;

        Barang::create($data);
        $user = auth()->user();
        if ($user->role == 'admin') {
            return redirect()->route('admin.barang')->with('success', 'barang berhasil ditambahkan');
        } elseif ($user->role == 'kasir') {
            return redirect()->route('kasir.barang')->with('success', 'barang berhasil ditambahkan');
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function show(Barang $barang)
    {
        $user = auth()->user();
        
        if ($user->role == 'admin') {
            return view('admin/barang/show', compact('barang'));
        } elseif($user->role == 'kasir') {
            return view('Kasir/barang/show', compact('barang'));
        }else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function edit(Barang $barang)
    {
        $kategori = Kategori::all();
        $pemasoks = Pemasok::all();
        $user = auth()->user();
        
        if ($user->role == 'admin') {
            return view('admin/barang/edit', compact('barang', 'kategori', 'pemasoks'));
        } elseif($user->role == 'kasir') {
            return view('Kasir/barang/edit', compact('barang', 'kategori', 'pemasoks'));
        }else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama_barang' => 'required',
            'satuan' => 'required',
            'harga_jual' => 'required|numeric',
            'stok' => 'required|integer',
            'kategori_id' => 'required|exists:kategoris,id',
            'pemasok_id' => 'required|exists:pemasok,id',
            'gambar' => 'nullable|image|max:2048',
        ]);

        // Ambil semua data kecuali kode_barang agar tidak bisa diubah
        $data = $request->except(['kode_barang']);

        // Jika ada gambar baru, hapus yang lama lalu simpan yang baru
        if ($request->hasFile('gambar')) {
            if ($barang->gambar) {
                Storage::disk('public')->delete($barang->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('barang', 'public');
        }

        // Debugging untuk melihat apakah data yang diupdate benar
        // dd($barang, $data); // Aktifkan jika masih ada error

        $barang->update($data);

        $user = auth()->user();
        if ($user->role == 'admin') {
            return redirect()->route('admin.barang')->with('success', 'barang berhasil diperbarui');
        } elseif ($user->role == 'kasir') {
            return redirect()->route('kasir.barang')->with('success', 'barang berhasil diperbarui');
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function destroy(Barang $barang)
    {
        if ($barang->gambar) {
            Storage::disk('public')->delete($barang->gambar);
        }

        $barang->delete();
        $user = auth()->user();
        if ($user->role == 'admin') {
            return redirect()->route('admin.barang')->with('success', 'barang berhasil dihapus');
        } elseif ($user->role == 'kasir') {
            return redirect()->route('kasir.barang')->with('success', 'barang berhasil dihapus');
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}