<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataRekap;
use Illuminate\Support\Facades\Auth;

class DataRekapController extends Controller
{
    public function index()
    {
        $datarekaps = DataRekap::orderByDesc('tgl_kirim')->get();
        return view('datarekaps.index', compact('datarekaps'));
    }
    
    public function create()
    {
        if (Auth::user()->type !== 1) {
            abort(403, 'Akses ditolak. Hanya admin yang bisa menambah data.');
        }

        return view('datarekaps.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->type !== 1) {
            abort(403, 'Akses ditolak. Hanya admin yang bisa menyimpan data.');
        }

        $validated = $request->validate([
            'no_faktur' => 'nullable|string',
            'tgl_faktur' => 'nullable|string',
            'no_sj_mutasi' => 'nullable|string',
            'tgl_sj_mutasi' => 'nullable|string',
            'nama_konsumen' => 'nullable|string',
            'kecamatan_kirim' => 'nullable|string',
            'kota_kirim' => 'nullable|string',
            'leasing' => 'nullable|string',
            'nama_type' => 'nullable|string',
            'warna' => 'nullable|string',
            'cabang' => 'nullable|string',
            'supir' => 'nullable|string',
            'tgl_kirim' => 'nullable|string',
            'stock' => 'nullable|string',
            'harga' => 'nullable|string',
            'kwitansi' => 'nullable|string',
            'konsumen_bayar' => 'nullable|string',
            'keterangan_tambahan' => 'nullable|string',
            'tgl_serah_terima_unit' => 'nullable|string',
            'pengiriman_leadtime' => 'nullable|string',
            'performance_pengiriman_hari' => 'nullable|string',
            'status_pengiriman' => 'nullable|string',
            'keterangan_pending' => 'nullable|string',
            'keterangan_lainnya' => 'nullable|string',
        ]);

        DataRekap::create($validated);

        return redirect()->route('datarekaps.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit($id)
    {
        if (Auth::user()->type !== 1) {
            abort(403, 'Akses ditolak. Hanya admin yang bisa mengedit data.');
        }

        $datarekap = DataRekap::findOrFail($id);
        return view('datarekaps.edit', compact('datarekap'));
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->type !== 1) {
            abort(403, 'Akses ditolak. Hanya admin yang bisa mengupdate data.');
        }

        $validated = $request->validate([
            'no_faktur' => 'nullable|string',
            'tgl_faktur' => 'nullable|string',
            'no_sj_mutasi' => 'nullable|string',
            'tgl_sj_mutasi' => 'nullable|string',
            'nama_konsumen' => 'nullable|string',
            'kecamatan_kirim' => 'nullable|string',
            'kota_kirim' => 'nullable|string',
            'leasing' => 'nullable|string',
            'nama_type' => 'nullable|string',
            'warna' => 'nullable|string',
            'cabang' => 'nullable|string',
            'supir' => 'nullable|string',
            'tgl_kirim' => 'nullable|string',
            'stock' => 'nullable|string',
            'harga' => 'nullable|string',
            'kwitansi' => 'nullable|string',
            'konsumen_bayar' => 'nullable|string',
            'keterangan_tambahan' => 'nullable|string',
            'tgl_serah_terima_unit' => 'nullable|string',
            'pengiriman_leadtime' => 'nullable|string',
            'performance_pengiriman_hari' => 'nullable|string',
            'status_pengiriman' => 'nullable|string',
            'keterangan_pending' => 'nullable|string',
            'keterangan_lainnya' => 'nullable|string',
        ]);

        $datarekap = DataRekap::findOrFail($id);
        $datarekap->update($validated);

        return redirect()->route('datarekaps.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        if (Auth::user()->type !== 1) {
            abort(403, 'Akses ditolak. Hanya admin yang bisa menghapus data.');
        }

        $datarekap = DataRekap::findOrFail($id);
        $datarekap->delete();

        return redirect()->route('datarekaps.index')->with('success', 'Data berhasil dihapus.');
    }
}