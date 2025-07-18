<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SlotDelivery;
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
            'tanggal_pengiriman' => 'required|date',
            'slot_pengiriman' => 'required|integer|min:0',
            'permintaan_kirim' => 'required|integer|min:0',
        ], [
            'tanggal_pengiriman.required' => 'Tanggal pengiriman wajib diisi.',
            'tanggal_pengiriman.date' => 'Format tanggal pengiriman tidak valid.',
            'slot_pengiriman.required' => 'Slot pengiriman wajib diisi.',
            'slot_pengiriman.integer' => 'Slot pengiriman harus berupa angka.',
            'slot_pengiriman.min' => 'Slot pengiriman minimal 0.',
            'permintaan_kirim.required' => 'Permintaan kirim wajib diisi.',
            'permintaan_kirim.integer' => 'Permintaan kirim harus berupa angka.',
            'permintaan_kirim.min' => 'Permintaan kirim minimal 0.',
        ]);

        $date = Carbon::parse($validated['tanggal_pengiriman'])->startOfMonth(); // Simpan hanya awal bulan

        // Cek apakah sudah ada slot untuk bulan & tahun yang sama
        $existing = SlotDelivery::whereMonth('tanggal_pengiriman', $date->month)
            ->whereYear('tanggal_pengiriman', $date->year)
            ->first();

        if ($existing) {
            return redirect()->back()->withErrors([
                'tanggal_pengiriman' => 'Slot untuk bulan ' . $date->translatedFormat('F Y') . ' sudah ada.'
            ])->withInput();
        }

        $validated['tanggal_pengiriman'] = $date; // Force simpan sebagai awal bulan
        $validated['over_sisa'] = $validated['slot_pengiriman'] - $validated['permintaan_kirim'];

        SlotDelivery::create($validated);

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
            'tanggal_pengiriman' => 'required|date',
            'slot_pengiriman' => 'required|integer|min:0',
            'permintaan_kirim' => 'required|integer|min:0',
        ], [
            'tanggal_pengiriman.required' => 'Tanggal pengiriman wajib diisi.',
            'tanggal_pengiriman.date' => 'Format tanggal pengiriman tidak valid.',
            'slot_pengiriman.required' => 'Slot pengiriman wajib diisi.',
            'slot_pengiriman.integer' => 'Slot pengiriman harus berupa angka.',
            'slot_pengiriman.min' => 'Slot pengiriman minimal 0.',
            'permintaan_kirim.required' => 'Permintaan kirim wajib diisi.',
            'permintaan_kirim.integer' => 'Permintaan kirim harus berupa angka.',
            'permintaan_kirim.min' => 'Permintaan kirim minimal 0.',
        ]);

        $date = Carbon::parse($validated['tanggal_pengiriman'])->startOfMonth();

        // Cek apakah bulan ini sudah ada slot selain ID saat ini
        $existing = SlotDelivery::whereMonth('tanggal_pengiriman', $date->month)
            ->whereYear('tanggal_pengiriman', $date->year)
            ->where('id', '!=', $id)
            ->first();

        if ($existing) {
            return redirect()->back()->withErrors([
                'tanggal_pengiriman' => 'Slot untuk bulan ' . $date->translatedFormat('F Y') . ' sudah ada.'
            ])->withInput();
        }

        $validated['tanggal_pengiriman'] = $date;
        $validated['over_sisa'] = $validated['slot_pengiriman'] - $validated['permintaan_kirim'];

        $slot = SlotDelivery::findOrFail($id);
        $slot->update($validated);

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
}
