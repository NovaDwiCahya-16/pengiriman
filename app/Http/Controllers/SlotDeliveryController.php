<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SlotDelivery;
use Illuminate\Support\Facades\Auth;

class SlotDeliveryController extends Controller
{
    public function index()
    {
        $slots = SlotDelivery::orderByDesc('tanggal_pengiriman')->get();
        return view('slot_deliveries.index', compact('slots'));
    }

    public function create()
    {
        // Hanya admin (type = 1) yang bisa akses
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
