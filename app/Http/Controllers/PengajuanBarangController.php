<?php

namespace App\Http\Controllers;

use App\Models\PengajuanBarang;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PengajuanBarangExport;

class PengajuanBarangController extends Controller
{
    public function index()
    {
        $pengajuan = PengajuanBarang::all();

        return view('admin.pengajuan.index', compact('pengajuan'));
    }

    public function create()
    {
        return view('admin.pengajuan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'pengaju' => 'required|string|max:255',
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tgl_pengajuan' => 'required|date',
            'qty' => 'required|integer|min:1',
        ]);

        PengajuanBarang::create([
            'pengaju' => $request->pengaju,
            'nama_barang' => $request->nama_barang,
            'deskripsi' => $request->deskripsi,
            'tgl_pengajuan' => $request->tgl_pengajuan,
            'qty' => $request->qty,
            'status' => false,
        ]);

        return redirect()->route('pengajuan-barang.index')->with('success', 'Pengajuan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pengajuan = PengajuanBarang::findOrFail($id);
        return view('admin.pengajuan.edit', compact('pengajuan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'pengaju' => 'required|string|max:255',
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tgl_pengajuan' => 'required|date',
            'qty' => 'required|integer|min:1',
            'status' => 'nullable|boolean'
        ]);

        $pengajuan = PengajuanBarang::findOrFail($id);
        $pengajuan->update([
            'pengaju' => $request->pengaju,
            'nama_barang' => $request->nama_barang,
            'deskripsi' => $request->deskripsi,
            'tgl_pengajuan' => $request->tgl_pengajuan,
            'qty' => $request->qty,
            'status' => $request->has('status') ? 1 : 0
        ]);

        return redirect()->route('pengajuan-barang.index')->with('success', 'Pengajuan Barang berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $pengajuanBarang = PengajuanBarang::findOrFail($id);
        $pengajuanBarang->delete();

        return redirect()->route('pengajuan-barang.index')->with('success', 'Pengajuan berhasil dihapus.');
    }

    public function exportPDF()
    {
        $pengajuan = PengajuanBarang::all();
        $pdf = Pdf::loadView('admin.pengajuan.pdf', compact('pengajuan'));
        return $pdf->download('pengajuan_barang.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new PengajuanBarangExport, 'pengajuan_barang.xlsx');
    }

    // 📑 Update status secara manual menggunakan switch
    public function updateTerpenuhi(Request $request, $id)
    {
        $pengajuan = PengajuanBarang::findOrFail($id);
        $pengajuan->terpenuhi = $request->terpenuhi;
        $pengajuan->save();

        return response()->json(['message' => 'Status terpenuhi berhasil diperbarui!'], 200);
    }
}
