<?php

namespace App\Http\Controllers;

use App\Models\Pemasok;
use Illuminate\Http\Request;

class PemasokController extends Controller
{
    /**
     * Menampilkan daftar pemasok
     */
    public function index()
    {
        $pemasok = Pemasok::all();
        $user = auth()->user();
        if($user->role == 'admin') {
            return view('Admin/pemasok/index',compact('pemasok'));
        }else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Menampilkan form tambah pemasok
     */
    public function create()
    {
        $user = auth()->user();
        if($user->role == 'admin') {
            return view('Admin/pemasok/create');
        }else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Menyimpan data pemasok baru
     */
    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'nama_pemasok' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'nomor_telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:pemasok,email',
            'catatan' => 'nullable|string',
        ]);

        // Simpan data ke database
        try {
        $pemasok = Pemasok::create($validatedData);
        $user = auth()->user();
        if($user->role == 'admin') {
            return redirect()->route('admin.pemasok.index')->with('success', 'pemasok berhasil ditambahkan');
        }else {
            abort(403, 'Unauthorized action.');
        }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
    }

    /**
     * Menampilkan detail pemasok
     */
    public function show(Pemasok $pemasok)
    {
       $user = auth()->user();
        if($user->role == 'admin') {
            return view('Admin/pemasok/show', compact('pemasok'));
        }else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Menampilkan form edit pemasok
     */
    public function edit(Pemasok $pemasok)
    {
        $user = auth()->user();
        if($user->role == 'admin') {
            return view('Admin/pemasok/edit', compact('pemasok'));
        }else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Memperbarui data pemasok
     */
    public function update(Request $request, Pemasok $pemasok)
    {
        $validatedData = $request->validate([
            'nama_pemasok' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'nomor_telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:pemasok,email,' . $pemasok->id,
            'catatan' => 'nullable|string',
        ]);

        $pemasok->update($validatedData);

        $user = auth()->user();
        if($user->role == 'admin') {
            return redirect()->route('admin.pemasok.index')->with('success', 'pemasok berhasil diperbarui');
        }else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Menghapus pemasok
     */
    public function destroy(Pemasok $pemasok)
    {
        $pemasok->delete();
        $user = auth()->user();
        if($user->role == 'admin') {
            return redirect()->route('admin.pemasok.index')->with('success', 'pemasok berhasil dihapus');
        }else {
            abort(403, 'Unauthorized action.');
        }
    }
}