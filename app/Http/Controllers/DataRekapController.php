<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataRekap;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;


class DataRekapController extends Controller
{
    public function index(Request $request)
    {
        // View utama, DataTables akan handle filter
        return view('datarekaps.datarekaps');
    }

    public function manageDataRekap(Request $request)
    {
        if ($request->ajax()) {
            $query = DataRekap::query();

            // Filter final/non-final
            if ($request->filter === 'final') {
                if ($request->filter === 'final') $query->where(function ($q) {
                    $q->whereNotNull('supir')->orWhereNotNull('tgl_kirim');
                });
            } elseif ($request->filter === 'non-final') {
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

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('no_faktur', fn($row) => $row->no_faktur ?: '-')
                ->editColumn('tgl_faktur', fn($row) => $row->tgl_faktur ?: '-')
                ->editColumn('no_sj_mutasi', fn($row) => $row->no_sj_mutasi ?: '-')
                ->editColumn('tgl_sj_mutasi', fn($row) => $row->tgl_sj_mutasi ?: '-')
                ->editColumn('nama_konsumen', fn($row) => $row->nama_konsumen ?: '-')
                ->editColumn('kecamatan_kirim', fn($row) => $row->kecamatan_kirim ?: '-')
                ->editColumn('tgl_kirim', fn($row) => $row->tgl_kirim ?: '-')
                ->editColumn('supir', fn($row) => $row->supir ?: '-')
                ->addColumn('action', function ($row) {
                    $editBtn = Auth::user()->type == 1 ?
                        '<button class="btn btn-sm btn-primary me-1 rounded editBtn" ' .
                        'data-id="' . $row->id . '" ' .
                        'data-no_faktur="' . htmlspecialchars($row->no_faktur) . '" ' .
                        'data-tgl_faktur="' . htmlspecialchars($row->tgl_faktur) . '" ' .
                        'data-no_sj_mutasi="' . htmlspecialchars($row->no_sj_mutasi) . '" ' .
                        'data-tgl_sj_mutasi="' . htmlspecialchars($row->tgl_sj_mutasi) . '" ' .
                        'data-nama_konsumen="' . htmlspecialchars($row->nama_konsumen) . '" ' .
                        'data-kecamatan_kirim="' . htmlspecialchars($row->kecamatan_kirim) . '" ' .
                        'data-kota_kirim="' . htmlspecialchars($row->kota_kirim) . '" ' .
                        'data-leasing="' . htmlspecialchars($row->leasing) . '" ' .
                        'data-nama_type="' . htmlspecialchars($row->nama_type) . '" ' .
                        'data-warna="' . htmlspecialchars($row->warna) . '" ' .
                        'data-cabang="' . htmlspecialchars($row->cabang) . '" ' .
                        'data-supir="' . htmlspecialchars($row->supir) . '" ' .
                        'data-tgl_kirim="' . htmlspecialchars($row->tgl_kirim) . '" ' .
                        'data-stock="' . htmlspecialchars($row->stock) . '" ' .
                        'data-harga="' . htmlspecialchars($row->harga) . '" ' .
                        'data-kwitansi="' . htmlspecialchars($row->kwitansi) . '" ' .
                        'data-konsumen_bayar="' . htmlspecialchars($row->konsumen_bayar) . '" ' .
                        'data-keterangan_tambahan="' . htmlspecialchars($row->keterangan_tambahan) . '" ' .
                        'data-tgl_serah_terima_unit="' . htmlspecialchars($row->tgl_serah_terima_unit) . '" ' .
                        'data-pengiriman_leadtime="' . htmlspecialchars($row->pengiriman_leadtime) . '" ' .
                        'data-performance_pengiriman_hari="' . htmlspecialchars($row->performance_pengiriman_hari) . '" ' .
                        'data-status_pengiriman="' . htmlspecialchars($row->status_pengiriman) . '" ' .
                        'data-keterangan_pending="' . htmlspecialchars($row->keterangan_pending) . '" ' .
                        'data-keterangan_lainnya="' . htmlspecialchars($row->keterangan_lainnya) . '" ' .
                        'title="Edit"><i class="fa fa-edit"></i></button>' : '';

                    $deleteBtn = Auth::user()->type == 1 ?
                        '<button class="btn btn-sm btn-danger me-1 rounded deleteBtn" ' .
                        'data-id="' . $row->id . '" ' .
                        'data-no_faktur="' . htmlspecialchars($row->no_faktur) . '" ' .
                        'data-tgl_faktur="' . htmlspecialchars($row->tgl_faktur) . '" ' .
                        'data-nama_konsumen="' . htmlspecialchars($row->nama_konsumen) . '" ' .
                        'data-kecamatan_kirim="' . htmlspecialchars($row->kecamatan_kirim) . '" ' .
                        'data-tgl_kirim="' . htmlspecialchars($row->tgl_kirim) . '" ' .
                        'data-supir="' . htmlspecialchars($row->supir) . '" ' .
                        'title="Hapus"><i class="fa fa-trash"></i></button>' : '';

                    return '
                        <div class="btn-group">
                            <button class="btn btn-sm btn-info me-1 rounded detailBtn" ' .
                        'data-id="' . $row->id . '" ' .
                        'data-no_faktur="' . htmlspecialchars($row->no_faktur) . '" ' .
                        'data-tgl_faktur="' . htmlspecialchars($row->tgl_faktur) . '" ' .
                        'data-no_sj_mutasi="' . htmlspecialchars($row->no_sj_mutasi) . '" ' .
                        'data-tgl_sj_mutasi="' . htmlspecialchars($row->tgl_sj_mutasi) . '" ' .
                        'data-nama_konsumen="' . htmlspecialchars($row->nama_konsumen) . '" ' .
                        'data-kecamatan_kirim="' . htmlspecialchars($row->kecamatan_kirim) . '" ' .
                        'data-kota_kirim="' . htmlspecialchars($row->kota_kirim) . '" ' .
                        'data-leasing="' . htmlspecialchars($row->leasing) . '" ' .
                        'data-nama_type="' . htmlspecialchars($row->nama_type) . '" ' .
                        'data-warna="' . htmlspecialchars($row->warna) . '" ' .
                        'data-cabang="' . htmlspecialchars($row->cabang) . '" ' .
                        'data-supir="' . htmlspecialchars($row->supir) . '" ' .
                        'data-tgl_kirim="' . htmlspecialchars($row->tgl_kirim) . '" ' .
                        'data-stock="' . htmlspecialchars($row->stock) . '" ' .
                        'data-harga="' . htmlspecialchars($row->harga) . '" ' .
                        'data-kwitansi="' . htmlspecialchars($row->kwitansi) . '" ' .
                        'data-konsumen_bayar="' . htmlspecialchars($row->konsumen_bayar) . '" ' .
                        'data-keterangan_tambahan="' . htmlspecialchars($row->keterangan_tambahan) . '" ' .
                        'data-tgl_serah_terima_unit="' . htmlspecialchars($row->tgl_serah_terima_unit) . '" ' .
                        'data-pengiriman_leadtime="' . htmlspecialchars($row->pengiriman_leadtime) . '" ' .
                        'data-performance_pengiriman_hari="' . htmlspecialchars($row->performance_pengiriman_hari) . '" ' .
                        'data-status_pengiriman="' . htmlspecialchars($row->status_pengiriman) . '" ' .
                        'data-keterangan_pending="' . htmlspecialchars($row->keterangan_pending) . '" ' .
                        'data-keterangan_lainnya="' . htmlspecialchars($row->keterangan_lainnya) . '" ' .
                        'title="Detail"><i class="fa fa-eye"></i></button>' .
                        $editBtn . $deleteBtn .
                        '</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return redirect()->route('datarekaps.index');
    }


    public function storeDataRekap(Request $request)
    {
        $this->authorizeAdmin();
        $validated = $request->validate($this->rules());

        // Kolom otomatis
        $validated['status_pengiriman'] = $this->getStatusPengiriman($validated);
        $validated['pengiriman_leadtime'] = $this->getLeadTime($validated);

        DataRekap::create($validated);
        return redirect()->back()->with('success', 'Data rekap berhasil ditambahkan.');
    }

    public function editdatarekap(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:data_rekaps,id',
            'no_faktur' => 'nullable|string|max:255',
            'tgl_faktur' => 'nullable|date',
            'no_sj_mutasi' => 'nullable|string|max:255',
            'tgl_sj_mutasi' => 'nullable|date',
            'nama_konsumen' => 'nullable|string|max:255',
            'kecamatan_kirim' => 'nullable|string|max:255',
            'kota_kirim' => 'nullable|string|max:255',
            'leasing' => 'nullable|string|max:255',
            'nama_type' => 'nullable|string|max:255',
            'warna' => 'nullable|string|max:255',
            'cabang' => 'nullable|string|max:255',
            'supir' => 'nullable|string|max:255',
            'tgl_kirim' => 'nullable|date',
            'stock' => 'nullable|string|max:255',
            'harga' => 'nullable|string|max:255',
            'kwitansi' => 'nullable|string|max:255',
            'konsumen_bayar' => 'nullable|string|max:255',
            'keterangan_tambahan' => 'nullable|string|max:255',
            'tgl_serah_terima_unit' => 'nullable|date',
            'pengiriman_leadtime' => 'nullable|string|max:255',
            'performance_pengiriman_hari' => 'nullable|string|max:255',
            'status_pengiriman' => 'nullable|string|max:255',
            'keterangan_pending' => 'nullable|string|max:255',
            'keterangan_lainnya' => 'nullable|string|max:255',
        ]);

        $dataRekap = DataRekap::findOrFail($validated['id']);
        unset($validated['id']); // hilangkan ID dari array agar tidak ikut di-update

        $dataRekap->update($validated);

        return redirect()->route('datarekaps.index')->with('success', 'Data rekap berhasil diperbarui!');
    }


    public function deleteDataRekap(Request $request)
    {
        $this->authorizeAdmin();
        $dataRekap = DataRekap::findOrFail($request->id);
        $dataRekap->delete();
        return redirect()->back()->with('success', 'Data rekap berhasil dihapus');
    }

    public function getDataRekapDetail($id)
    {
        if (!$id || !is_numeric($id)) {
            return response()->json([
                'success' => false,
                'message' => 'ID data rekap tidak valid'
            ], 400);
        }
        $dataRekap = DataRekap::find($id);
        if (!$dataRekap) {
            return response()->json([
                'success' => false,
                'message' => 'Data rekap tidak ditemukan'
            ], 404);
        }
        $data = $dataRekap->toArray();
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Data berhasil dimuat'
        ]);
    }


    private function getStatusPengiriman($data)
    {
        // Ambil tanggal kirim dan tanggal serah terima unit
        $tgl_kirim = !empty($data['tgl_kirim']) ? strtotime($data['tgl_kirim']) : 0;
        $tgl_serah_terima_unit = !empty($data['tgl_serah_terima_unit']) ? strtotime($data['tgl_serah_terima_unit']) : 0;
        $today = strtotime(date('Y-m-d'));

        // Logic sesuai rumus Excel
        $today = Carbon::today(); //untuk hitung today

        if (empty($tgl_kirim) && Carbon::parse($tgl_serah_terima_unit)->isSameDay($today->copy()->addDay())) {
            return 'DIKIRIM BESOK';
        }
        if ($tgl_serah_terima_unit == $tgl_kirim && $tgl_kirim != 0) {
            return 'TEPAT WAKTU';
        }
        if (empty($tgl_kirim) && Carbon::parse($tgl_serah_terima_unit)->isSameDay(Carbon::today()))

        if ($tgl_kirim == 0 && $tgl_serah_terima_unit < $today) {
            return 'MENUNGGU PENGIRIMAN';
        }
        if ($tgl_serah_terima_unit < $tgl_kirim && $tgl_kirim != 0) {
            return 'TERLAMBAT';
        }
        if ($tgl_serah_terima_unit > $tgl_kirim && $tgl_kirim != 0) {
            return 'TIDAK VALID';
        }
        // Default jika tidak ada kondisi yang cocok
        return $data['status_pengiriman'] ?? 'Sudah Pilih Supir dan Tanggal Kirim';
    }

    // Logic leadtime (contoh: selisih hari tgl_kirim dan tgl_sj_mutasi)
    private function getLeadTime($data)
    {
        if (!empty($data['tgl_kirim']) && !empty($data['tgl_sj_mutasi'])) {
            try {
                $tglKirim = new \DateTime($data['tgl_kirim']);
                $tglSjMutasi = new \DateTime($data['tgl_sj_mutasi']);
                $diff = $tglKirim->diff($tglSjMutasi);
                return $diff->days . ' hari';
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
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
    //ljshuabfuikbaekbfjb
}
