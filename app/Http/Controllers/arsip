<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SlotDelivery;
use App\Models\RequestModel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SlotDeliveryController extends Controller
{
    public function index()
    {
        $slots = SlotDelivery::orderByDesc('tanggal_pengiriman')->get();
        return view('slot_deliveries.index', compact('slots'));
    }

    public function create()
    {
        if (Auth::user()->type !== 1) {
            abort(403, 'Akses ditolak. Hanya admin yang bisa menambah data.');
        }

        return view('slot_deliveries.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->type !== 1) {
            abort(403, 'Akses ditolak. Hanya admin yang bisa menyimpan data.');
        }

        $validated = $request->validate([
            'bulan' => 'required|integer|between:1,12',
            'tahun' => 'required|integer|min:2020',
            'slot_pengiriman' => 'required|integer|min:0',
        ]);

        $tanggal = Carbon::createFromDate($validated['tahun'], $validated['bulan'], 1);

        // Cek apakah slot untuk bulan dan tahun ini sudah ada
        $existing = SlotDelivery::whereMonth('tanggal_pengiriman', $tanggal->month)
            ->whereYear('tanggal_pengiriman', $tanggal->year)
            ->first();

        if ($existing) {
            return redirect()->back()->withErrors([
                'bulan' => 'Slot untuk bulan ' . $tanggal->translatedFormat('F Y') . ' sudah ada.'
            ])->withInput();
        }

        // Simpan slot dengan default permintaan = 0
        $slot = SlotDelivery::create([
            'tanggal_pengiriman' => $tanggal,
            'slot_pengiriman' => $validated['slot_pengiriman'],
            'permintaan_kirim' => 0,
            'over_sisa' => $validated['slot_pengiriman'],
        ]);

        // Sinkronkan jumlah permintaan jika sudah ada data request
        self::syncSlotForMonth($tanggal->month, $tanggal->year);

        return redirect()->route('slot-deliveries.index')->with('success', 'Slot pengiriman berhasil ditambahkan.');
    }

    public function edit($id)
    {
        if (Auth::user()->type !== 1) {
            abort(403, 'Akses ditolak. Hanya admin yang bisa mengedit data.');
        }

        $slot = SlotDelivery::findOrFail($id);
        return view('slot_deliveries.edit', compact('slot'));
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->type !== 1) {
            abort(403, 'Akses ditolak. Hanya admin yang bisa mengupdate data.');
        }

        $validated = $request->validate([
            'bulan' => 'required|integer|between:1,12',
            'tahun' => 'required|integer|min:2020',
            'slot_pengiriman' => 'required|integer|min:0',
        ]);

        $tanggal = Carbon::createFromDate($validated['tahun'], $validated['bulan'], 1);

        $existing = SlotDelivery::whereMonth('tanggal_pengiriman', $tanggal->month)
            ->whereYear('tanggal_pengiriman', $tanggal->year)
            ->where('id', '!=', $id)
            ->first();

        if ($existing) {
            return redirect()->back()->withErrors([
                'bulan' => 'Slot untuk bulan ' . $tanggal->translatedFormat('F Y') . ' sudah ada.'
            ])->withInput();
        }

        $slot = SlotDelivery::findOrFail($id);
        $slot->update([
            'tanggal_pengiriman' => $tanggal,
            'slot_pengiriman' => $validated['slot_pengiriman'],
        ]);

        // Update permintaan & sisa
        self::syncSlotForMonth($tanggal->month, $tanggal->year);

        return redirect()->route('slot-deliveries.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        if (Auth::user()->type !== 1) {
            abort(403, 'Akses ditolak. Hanya admin yang bisa menghapus data.');
        }

        $slot = SlotDelivery::findOrFail($id);
        $slot->delete();

        return redirect()->route('slot-deliveries.index')->with('success', 'Data berhasil dihapus.');
    }

    /**
     * Sinkronisasi ulang total permintaan dan over/sisa berdasarkan data request
     */
    public static function syncSlotForMonth($month, $year)
    {
        $slot = SlotDelivery::whereMonth('tanggal_pengiriman', $month)
            ->whereYear('tanggal_pengiriman', $year)
            ->first();

        if ($slot) {
            $totalUnit = RequestModel::whereMonth('date', $month)
                ->whereYear('date', $year)
                ->sum('unit');

            $slot->update([
                'permintaan_kirim' => $totalUnit,
                'over_sisa' => $slot->slot_pengiriman - $totalUnit,
            ]);
        }
    }
}
