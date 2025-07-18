<?php
use App\Models\Request;
use App\Models\SlotDelivery;
use Illuminate\Http\Request as HttpRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

public function store(HttpRequest $request)
{
    $validated = $request->validate([
        'branch_id' => 'required|exists:branches,id',
        'date' => 'required|date',
        'unit' => 'required|integer',
        'xlsx_file' => 'required|file|mimes:xlsx,xls',
    ]);

    // Simpan file
    $filePath = $request->file('xlsx_file')->store('requests', 'public');

    // Baca isi file Excel
    $rows = Excel::toArray([], $request->file('xlsx_file'))[0];

    $permintaanKirim = 0;
    $bulanTahun = null;

    foreach ($rows as $i => $cells) {
        if ($i < 3) continue; // Lewati header

        if (!empty($cells[0])) { // Kolom tanggal pengiriman
            try {
                $tanggal = Carbon::parse($cells[0]);
                $bulanTahun = $tanggal->format('Y-m'); // contoh: 2025-06
                $permintaanKirim++;
            } catch (\Exception $e) {
                continue;
            }
        }
    }

    // Update slot delivery jika sudah ada slot untuk bulan tersebut
    if ($bulanTahun) {
        $targetMonth = Carbon::createFromFormat('Y-m', $bulanTahun);

        $slot = SlotDelivery::whereMonth('tanggal_pengiriman', $targetMonth->month)
            ->whereYear('tanggal_pengiriman', $targetMonth->year)
            ->first();

        if ($slot) {
            $slot->permintaan_kirim += $permintaanKirim;
            $slot->over_sisa = $slot->slot_pengiriman - $slot->permintaan_kirim;
            $slot->save();
        }
    }

    // Simpan ke tabel requests
    Request::create([
        'branch_id' => $validated['branch_id'],
        'date' => $validated['date'],
        'unit' => $validated['unit'],
        'path' => $filePath,
        'user_id' => auth()->user()->id,
        'type' => auth()->user()->type,
        'status' => 'Menunggu'
    ]);

    return redirect()->route('requests.index')->with('success', 'Permintaan berhasil diunggah.');
}
