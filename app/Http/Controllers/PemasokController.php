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
        return view('pemasok.index', compact('pemasok'));
    }

    /**
     * Menampilkan form tambah pemasok
     */
    public function create()
    {
        return view('pemasok.create');
    }

    /**
     * Menyimpan data pemasok baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pemasok' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'nomor_telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:pemasok,email',
            'catatan' => 'nullable|string',
        ]);

        Pemasok::create($request->all());

        return redirect()->route('pemasok.index')->with('success', 'Pemasok berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail pemasok
     */
    public function show(Pemasok $pemasok)
    {
        return view('pemasok.show', compact('pemasok'));
    }

    /**
     * Menampilkan form edit pemasok
     */
    public function edit(Pemasok $pemasok)
    {
        return view('pemasok.edit', compact('pemasok'));
    }

    /**
     * Memperbarui data pemasok
     */
    public function update(Request $request, Pemasok $pemasok)
    {
        $request->validate([
            'nama_pemasok' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'nomor_telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:pemasok,email,' . $pemasok->id,
            'catatan' => 'nullable|string',
        ]);

        $pemasok->update($request->all());

        return redirect()->route('pemasok.index')->with('success', 'Pemasok berhasil diperbarui.');
    }

    /**
     * Menghapus pemasok
     */
    public function destroy(Pemasok $pemasok)
    {
        $pemasok->delete();
        return redirect()->route('pemasok.index')->with('success', 'Pemasok berhasil dihapus.');
    }
}
