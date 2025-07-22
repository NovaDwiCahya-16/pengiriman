<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataRekap;
use Illuminate\Support\Facades\Auth;

class DataRekapController extends Controller
{
 public function index(Request $request)
    {
        $filter = $request->query('filter');

        $query = Datarekap::query();

        if ($filter === 'final') {
            // FINAL: Semua status_pengiriman selain 'Belum Pilih Supir dan Tanggal Kirim'
            $query->where('status_pengiriman', '!=', 'Belum Pilih Supir dan Tanggal Kirim');
        } elseif ($filter === 'non-final') {
            // NON-FINAL:
            // 1. Jika supir NULL dan tgl_kirim NULL
            // 2. Jika supir NULL dan tgl_kirim TIDAK NULL, dan status_pengiriman = 'Belum Pilih Supir dan Tanggal Kirim'
            $query->where(function ($q) {
                $q->where(function ($sub) {
                    $sub->whereNull('supir')
                        ->whereNull('tgl_kirim');
                })->orWhere(function ($sub) {
                    $sub->whereNull('supir')
                        ->whereNotNull('tgl_kirim')
                        ->where('status_pengiriman', 'Belum Pilih Supir dan Tanggal Kirim');
                });
            });
        }

        $datarekaps = $query->orderByDesc('id')->paginate(10);

        return view('datarekaps.index', compact('datarekaps'));
    }

    public function create()
    {
        $this->authorizeAdmin();
        return view('datarekaps.create');
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate($this->rules());

        // Logika otomatis status_pengiriman
        if (empty($validated['supir']) && empty($validated['tgl_kirim'])) {
            $validated['status_pengiriman'] = 'Belum Pilih Supir dan Tanggal Kirim';
        }

        DataRekap::create($validated);

        return redirect()->route('datarekaps.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $this->authorizeAdmin();

        $datarekap = DataRekap::findOrFail($id);
        return view('datarekaps.edit', compact('datarekap'));
    }

    public function update(Request $request, $id)
    {
        $this->authorizeAdmin();

        $validated = $request->validate($this->rules());

        // Logika otomatis status_pengiriman saat update
        if (empty($validated['supir']) && empty($validated['tgl_kirim'])) {
            $validated['status_pengiriman'] = 'Belum Pilih Supir dan Tanggal Kirim';
        }

        $datarekap = DataRekap::findOrFail($id);
        $datarekap->update($validated);

        return redirect()->route('datarekaps.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->authorizeAdmin();

        $datarekap = DataRekap::findOrFail($id);
        $datarekap->delete();

        return redirect()->route('datarekaps.index')->with('success', 'Data berhasil dihapus.');
    }

    private function authorizeAdmin()
    {
        if (Auth::user()->type !== 1) {
            abort(403, 'Akses ditolak. Hanya admin yang bisa melakukan aksi ini.');
        }
    }

    private function rules()
    {
        return [
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
        ];
    }
}
