<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::all();
        $user = auth()->user();
        
        if ($user->role == 'admin') {
            return view('admin/kategori/index', compact('kategoris'));
        } elseif($user->role == 'kasir') {
            return view('Kasir/kategori/index', compact('kategoris'));
        }else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function create()
    {
        $user = auth()->user();
        
        if ($user->role == 'admin') {
            return view('admin/kategori/create');
        } elseif($user->role == 'kasir') {
            return view('Kasir/kategori/create');
        }else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|unique:kategoris,nama_kategori|max:100',
        ]);

        Kategori::create([
            'nama_kategori' => $request->nama_kategori,
        ]);

        $user = auth()->user();
        if ($user->role == 'admin') {
            return redirect()->route('admin.kategori')->with('success', 'kategori berhasil ditambahkan');
        } elseif ($user->role == 'kasir') {
            return redirect()->route('kasir.kategori')->with('success', 'kategori berhasil ditambahkan');
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function edit($id)
    {
        $kategori = Kategori::findOrFail($id);
        $user = auth()->user();
        
        if ($user->role == 'admin') {
            return view('admin/kategori/edit', compact('kategori'));
        } elseif($user->role == 'kasir') {
            return view('Kasir/kategori/edit', compact('kategori'));
        }else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|max:100|unique:kategoris,nama_kategori,' . $id,
        ]);

        $kategori = Kategori::findOrFail($id);
        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
        ]);

        $user = auth()->user();
        if ($user->role == 'admin') {
            return redirect()->route('admin.kategori')->with('success', 'kategori berhasil diperbarui');
        } elseif ($user->role == 'kasir') {
            return redirect()->route('kasir.kategori')->with('success', 'kategori berhasil diperbarui');
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();
        $user = auth()->user();
        if ($user->role == 'admin') {
            return redirect()->route('admin.kategori')->with('success', 'kategori berhasil dihapus');
        } elseif ($user->role == 'kasir') {
            return redirect()->route('kasir.kategori')->with('success', 'kategori berhasil dihapus');
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}