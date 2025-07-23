<?php

namespace App\Http\Controllers;

use App\Models\SlotDelivery;
<<<<<<< HEAD
use Illuminate\Support\Facades\Auth;
=======
use App\Models\RequestModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
>>>>>>> backup-edit-datarekap

class SlotDeliveryController extends Controller
{
    public function adminSlot()
    {
        return view('slot_deliveries.slot');
    }

    public function manageSlot(Request $request)
    {
<<<<<<< HEAD
        // Hanya admin (type = 1) yang bisa akses
        if (Auth::user()->type !== 1) {
            abort(403, 'Akses ditolak. Hanya admin yang bisa menambah data.');
=======
        if ($request->ajax()) {
            try {
                $data = SlotDelivery::select(['id', 'tanggal_pengiriman', 'slot_pengiriman', 'permintaan_kirim', 'over_sisa'])
                    ->orderBy('tanggal_pengiriman', 'desc');

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('tanggal_pengiriman', function ($row) {
                        return Carbon::parse($row->tanggal_pengiriman)->format('Y-m');
                    })
                    ->editColumn('slot_pengiriman', function ($row) {
                        return number_format($row->slot_pengiriman, 0, ',', '.');
                    })
                    ->editColumn('permintaan_kirim', function ($row) {
                        return number_format($row->permintaan_kirim, 0, ',', '.');
                    })
                    ->editColumn('over_sisa', function ($row) {
                        return $row->over_sisa !== null ? number_format($row->over_sisa, 0, ',', '.') : 'N/A';
                    })
                    ->addColumn('action', function ($row) {
                        $editData = [
                            'id' => $row->id,
                            'tanggal_pengiriman' => Carbon::parse($row->tanggal_pengiriman)->format('Y-m'),
                            'slot_pengiriman' => $row->slot_pengiriman,
                            'permintaan_kirim' => $row->permintaan_kirim,
                            'over_sisa' => $row->over_sisa
                        ];

                        return '
                            <div class="btn-group">
                                <button class="btn btn-sm btn-primary me-1 rounded editBtn" 
                                        data-id="' . $editData['id'] . '" 
                                        data-tanggal_pengiriman="' . $editData['tanggal_pengiriman'] . '" 
                                        data-slot_pengiriman="' . $editData['slot_pengiriman'] . '"
                                        data-permintaan_kirim="' . $editData['permintaan_kirim'] . '"
                                        data-over_sisa="' . ($editData['over_sisa'] !== null ? $editData['over_sisa'] : '') . '"
                                        title="Edit">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger me-1 rounded deleteBtn" 
                                        data-id="' . $editData['id'] . '"
                                        data-tanggal_pengiriman="' . $editData['tanggal_pengiriman'] . '"
                                        data-slot_pengiriman="' . $editData['slot_pengiriman'] . '"
                                        data-permintaan_kirim="' . $editData['permintaan_kirim'] . '"
                                        data-over_sisa="' . ($editData['over_sisa'] !== null ? $editData['over_sisa'] : '') . '"
                                        title="Hapus">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        ';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            } catch (\Exception $e) {
                Log::error('Error in manageSlot:', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                return response()->json([
                    'error' => 'Terjadi kesalahan saat memuat data: ' . $e->getMessage()
                ], 500);
            }
>>>>>>> backup-edit-datarekap
        }

        return redirect()->route('slots');
    }

    public function storeSlot(Request $request)
    {
        $request->validate([
            'tanggal_pengiriman' => 'required|date_format:Y-m',
            'slot_pengiriman' => 'required|integer|min:1',
        ], [
<<<<<<< HEAD
            'tanggal_pengiriman.required' => 'Tanggal pengiriman wajib diisi.',
            'tanggal_pengiriman.date' => 'Format tanggal pengiriman tidak valid.',

            'slot_pengiriman.required' => 'Slot pengiriman wajib diisi.',
            'slot_pengiriman.integer' => 'Slot pengiriman harus berupa angka.',
            'slot_pengiriman.min' => 'Slot pengiriman minimal 0.',

            'permintaan_kirim.required' => 'Permintaan kirim wajib diisi.',
            'permintaan_kirim.integer' => 'Permintaan kirim harus berupa angka.',
            'permintaan_kirim.min' => 'Permintaan kirim minimal 0.',
        ]);

        $validated['over_sisa'] = $validated['slot_pengiriman'] - $validated['permintaan_kirim'];

        SlotDelivery::create($validated);

        return redirect()->route('slot-deliveries.index')->with('success', 'Slot pengiriman berhasil ditambahkan.');
=======
            'tanggal_pengiriman.required' => 'Bulan dan tahun wajib diisi',
            'tanggal_pengiriman.date_format' => 'Format bulan dan tahun tidak valid',
            'slot_pengiriman.required' => 'Slot pengiriman wajib diisi',
            'slot_pengiriman.integer' => 'Slot pengiriman harus berupa angka',
            'slot_pengiriman.min' => 'Slot pengiriman minimal 1',
        ]);

        try {
            $tanggal_pengiriman = Carbon::parse($request->tanggal_pengiriman . '-01')->startOfMonth();
            $bulan = $tanggal_pengiriman->month;
            $tahun = $tanggal_pengiriman->year;

            $existingSlot = SlotDelivery::whereMonth('tanggal_pengiriman', $bulan)
                ->whereYear('tanggal_pengiriman', $tahun)
                ->first();

            if ($existingSlot) {
                return redirect()->back()->with('error', 'Slot untuk bulan dan tahun ini sudah ada!');
            }

            $permintaan_kirim = RequestModel::whereMonth('date', $bulan)
                ->whereYear('date', $tahun)
                ->sum('unit');

            $over_sisa = $request->slot_pengiriman - $permintaan_kirim;

            $newSlot = SlotDelivery::create([
                'tanggal_pengiriman' => $tanggal_pengiriman,
                'slot_pengiriman' => $request->slot_pengiriman,
                'permintaan_kirim' => $permintaan_kirim,
                'over_sisa' => $over_sisa,
            ]);

            Log::info('Slot created successfully:', $newSlot->toArray());

            return redirect()->back()->with('success', 'Slot pengiriman berhasil ditambahkan untuk ' . $tanggal_pengiriman->format('F Y') . '!');
        } catch (\Exception $e) {
            Log::error('Error storing slot:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambah slot: ' . $e->getMessage());
        }
>>>>>>> backup-edit-datarekap
    }

    public function editSlot(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:slot_deliveries,id',
            'tanggal_pengiriman' => 'required|date_format:Y-m',
            'slot_pengiriman' => 'required|integer|min:1',
        ], [
<<<<<<< HEAD
            'tanggal_pengiriman.required' => 'Tanggal pengiriman wajib diisi.',
            'tanggal_pengiriman.date' => 'Format tanggal pengiriman tidak valid.',

            'slot_pengiriman.required' => 'Slot pengiriman wajib diisi.',
            'slot_pengiriman.integer' => 'Slot pengiriman harus berupa angka.',
            'slot_pengiriman.min' => 'Slot pengiriman minimal 0.',

            'permintaan_kirim.required' => 'Permintaan kirim wajib diisi.',
            'permintaan_kirim.integer' => 'Permintaan kirim harus berupa angka.',
            'permintaan_kirim.min' => 'Permintaan kirim minimal 0.',
        ]);

        $validated['over_sisa'] = $validated['slot_pengiriman'] - $validated['permintaan_kirim'];
=======
            'tanggal_pengiriman.required' => 'Bulan dan tahun wajib diisi',
            'tanggal_pengiriman.date_format' => 'Format bulan dan tahun tidak valid',
            'slot_pengiriman.required' => 'Slot pengiriman wajib diisi',
            'slot_pengiriman.integer' => 'Slot pengiriman harus berupa angka',
            'slot_pengiriman.min' => 'Slot pengiriman minimal 1',
        ]);

            $slot = SlotDelivery::findOrFail($request->id);
            $tanggal_pengiriman = Carbon::parse($request->tanggal_pengiriman . '-01')->startOfMonth();
            $bulan = $tanggal_pengiriman->month;
            $tahun = $tanggal_pengiriman->year;

            $existingSlot = SlotDelivery::whereMonth('tanggal_pengiriman', $bulan)
                ->whereYear('tanggal_pengiriman', $tahun)
                ->where('id', '!=', $request->id)
                ->first();

            if ($existingSlot) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slot untuk bulan dan tahun ini sudah ada!'
                ], 422);
            }

            $permintaan_kirim = RequestModel::whereMonth('date', $bulan)
                ->whereYear('date', $tahun)
                ->sum('unit');
>>>>>>> backup-edit-datarekap

            $over_sisa = $request->slot_pengiriman - $permintaan_kirim;

            $slot->update([
                'tanggal_pengiriman' => $tanggal_pengiriman,
                'slot_pengiriman' => $request->slot_pengiriman,
                'permintaan_kirim' => $permintaan_kirim,
                'over_sisa' => $over_sisa,
            ]);
            return redirect()->back()->with('success', 'Slot pengiriman berhasil diubah untuk ' . $tanggal_pengiriman->format('F Y') . '!');
    }

    public function deleteSlot(Request $request)
    {
        try {
            $slot = SlotDelivery::findOrFail($request->id);
            $slot->delete();

            return redirect()->back()->with('success', 'Slot pengiriman berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting slot:', [
                'message' => $e->getMessage(),
                'slot_id' => $request->id
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus slot!');
        }
    }

    public function getSlotDetail($id)
    {
        try {
            if (!$id || !is_numeric($id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID slot tidak valid'
                ], 400);
            }

            $slot = SlotDelivery::find($id);

            if (!$slot) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slot tidak ditemukan'
                ], 404);
            }

            $data = [
                'id' => $slot->id,
                'tanggal_pengiriman' => Carbon::parse($slot->tanggal_pengiriman)->format('Y-m'),
                'slot_pengiriman' => $slot->slot_pengiriman,
                'permintaan_kirim' => $slot->permintaan_kirim,
                'over_sisa' => $slot->over_sisa,
                'formatted_tanggal' => Carbon::parse($slot->tanggal_pengiriman)->format('F Y')
            ];

            Log::info('Slot detail retrieved successfully:', [
                'slot_id' => $id,
                'data' => $data
            ]);

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Data berhasil dimuat'
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting slot detail:', [
                'message' => $e->getMessage(),
                'slot_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat data: ' . $e->getMessage()
            ], 500);
        }
    }
}