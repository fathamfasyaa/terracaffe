<?php

namespace App\Http\Controllers;

use App\Models\AbsensiKerja;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AbsensiKerjaExport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Imports\AbsensiKerjaImport;

class AbsensiKerjaController extends Controller
{
    /**
     * Menampilkan halaman utama absensi kerja dan menangani request AJAX untuk DataTables.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = AbsensiKerja::with('user')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama_user', fn($row) => $row->user->name ?? '-')
                ->addColumn('tanggal_masuk', fn($row) => $row->waktu_mulai_kerja ? Carbon::parse($row->waktu_mulai_kerja)->format('Y-m-d') : '-')
                ->addColumn('waktu_masuk', fn($row) => $row->waktu_mulai_kerja ? Carbon::parse($row->waktu_mulai_kerja)->format('H:i') : '-')
                ->addColumn('status_select', function ($row) {
                    $selected = fn($val) => $row->status_masuk === $val ? 'selected' : '';
                    return '<select class="form-control status-select" data-id="' . $row->id . '">
                    <option value="masuk" ' . $selected('masuk') . '>Masuk</option>
                    <option value="sakit" ' . $selected('sakit') . '>Sakit</option>
                    <option value="cuti" ' . $selected('cuti') . '>Cuti</option>
                </select>';
                })
                ->addColumn('waktu_selesai_kerja', function ($row) {
                    if (is_null($row->waktu_akhir_kerja)) {
                        return '<button class="btn btn-success btn-sm selesai-kerja" data-id="' . $row->id . '">Selesai</button>';
                    }
                    return Carbon::parse($row->waktu_akhir_kerja)->format('H:i');
                })
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-warning btn-sm edit" data-id="' . $row->id . '">Edit</button>
                        <button class="btn btn-danger btn-sm delete" data-id="' . $row->id . '">Hapus</button>';
                })
                ->rawColumns(['status_select', 'waktu_selesai_kerja', 'action'])
                ->make(true);
        }

        $users = \App\Models\User::all();
        return view('Admin.absensi_kerja.index', compact('users'));
    }

    /**
     * Menyimpan data absensi kerja baru.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'status_masuk' => 'required|in:masuk,sakit,cuti',
            'waktu_mulai_kerja' => 'required|date'
        ]);

        AbsensiKerja::create($validated);
        return response()->json(['success' => true]);
    }

    /**
     * Mengambil data absensi kerja berdasarkan ID untuk diedit.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $absensi = AbsensiKerja::findOrFail($id);
        return response()->json($absensi);
    }

    /**
     * Memperbarui data absensi kerja.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'status_masuk' => 'required|in:masuk,sakit,cuti',
            'waktu_mulai_kerja' => 'required|date'
        ]);

        $absensi = AbsensiKerja::findOrFail($id);
        $absensi->update($validated);

        return response()->json(['success' => true]);
    }

    /**
     * Menghapus data absensi kerja berdasarkan ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $absensi = AbsensiKerja::findOrFail($id);
        $absensi->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Memperbarui status absensi kerja (masuk/sakit/cuti).
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request)
    {
        try {
            $absensi = AbsensiKerja::findOrFail($request->id);

            $updateData = ['status_masuk' => $request->status_masuk];

            if (in_array($request->status_masuk, ['sakit', 'cuti'])) {
                $updateData['waktu_akhir_kerja'] = Carbon::today()->format('Y-m-d H:i:s');
            }

            $absensi->update($updateData);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menandai waktu selesai kerja berdasarkan ID absensi.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function selesaiKerja(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:tbl_absen_kerja,id'
        ]);

        AbsensiKerja::findOrFail($request->id)->update([
            'waktu_akhir_kerja' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Mengekspor data absensi kerja ke dalam file Excel.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportExcel()
    {
        return Excel::download(new AbsensiKerjaExport, 'absensi_kerja.xlsx');
    }

    /**
     * Mengekspor data absensi kerja ke dalam file PDF.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportPdf()
    {
        $data = AbsensiKerja::with('user')->get();
        $pdf = Pdf::loadView('Admin.absensi_kerja.pdf', compact('data'))->setPaper('A4', 'landscape');
        return $pdf->download('laporan-absensi.pdf');
    }

    /**
     * Mengimpor data absensi kerja dari file Excel.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new AbsensiKerjaImport, $request->file('file'));

        return redirect()->back()->with('success', 'Import berhasil!');
    }
}
