<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as HttpRequest;
use App\Models\Request;
use App\Models\Branch;
use App\Models\SlotDelivery;
use Illuminate\Support\Facades\Storage;
use Rap2hpoutre\FastExcel\FastExcel;

class RequestController extends Controller
{
    public function index(HttpRequest $request)
    {
        $branches = Branch::all();
        $branchId = $request->query('branch');

        if ($branchId) {
            $requests = Request::where('branch_id', $branchId)->get();
            $selectedBranch = Branch::find($branchId);
        } else {
            $requests = Request::all();
            $selectedBranch = null;
        }

        return view('requests.index', compact('requests', 'branches', 'selectedBranch'));
    }

    public function create()
    {
        $branches = Branch::all();
        return view('requests.create', compact('branches'));
    }

    public function store(HttpRequest $request)
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'date' => 'required|date',
            'unit' => 'required|integer|min:1',
            'xlsx_file' => 'required|file|mimes:xlsx,xls|max:5120'
        ]);

        $file = $request->file('xlsx_file');
        $rows = (new FastExcel)->import($file);

        // Deteksi duplikat berdasarkan kombinasi branch, date, dan isi excel
        $existingRequests = Request::where('branch_id', $validated['branch_id'])
            ->where('date', $validated['date'])
            ->get();

        foreach ($existingRequests as $existing) {
            $existingPath = storage_path("app/public/" . $existing->path);
            if (file_exists($existingPath)) {
                $existingRows = (new FastExcel)->import($existingPath)->toArray();

                if ($existingRows == $rows->toArray()) {
                    return back()->withErrors(['xlsx_file' => 'File Excel yang sama sudah pernah diunggah untuk cabang dan tanggal ini.']);
                }
            }
        }

        // Hitung jumlah permintaan berdasarkan jumlah baris file
        $jumlahPermintaan = $rows->count();

        // Coba ambil slot pengiriman pada tanggal yang sama
        $slot = SlotDelivery::where('tanggal_pengiriman', $validated['date'])->first();

        if ($slot) {
            // Tambahkan permintaan ke slot
            $slot->permintaan_kirim += $jumlahPermintaan;
            $selisih = $slot->slot_pengiriman - $slot->permintaan_kirim;

            $slot->over_sisa = $selisih < 0 ? abs($selisih) . ' over' : $selisih . ' sisa';
            $slot->save();
        }

        // Simpan file
        $filePath = $file->store('requests', 'public');

        // Simpan data request
        Request::create([
            'branch_id' => $validated['branch_id'],
            'date' => $validated['date'],
            'unit' => $validated['unit'],
            'path' => $filePath,
            'user_id' => auth()->user()->id,
            'type' => auth()->user()->type,
            'status' => 'Menunggu'
        ]);

        return redirect()->route('requests.index')->with('success', 'Permintaan berhasil disimpan!');
    }

    public function edit($id)
    {
        $request = Request::findOrFail($id);
        $branches = Branch::all();
        return view('requests.edit', compact('request', 'branches'));
    }

    public function update(HttpRequest $requestData, $id)
    {
        $validated = $requestData->validate([
            'branch_id' => 'required|exists:branches,id',
            'date' => 'required|date',
            'unit' => 'required|integer|min:1',
            'xlsx_file' => 'nullable|file|mimes:xlsx,xls|max:5120'
        ]);

        $request = Request::findOrFail($id);
        $request->branch_id = $validated['branch_id'];
        $request->date = $validated['date'];
        $request->unit = $validated['unit'];

        if ($requestData->hasFile('xlsx_file')) {
            if ($request->path && Storage::disk('public')->exists($request->path)) {
                Storage::disk('public')->delete($request->path);
            }

            $newFilePath = $requestData->file('xlsx_file')->store('requests', 'public');
            $request->path = $newFilePath;
        }

        $request->save();

        return redirect()->route('requests.index')->with('success', 'Permintaan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $request = Request::findOrFail($id);

        if ($request->path && Storage::disk('public')->exists($request->path)) {
            Storage::disk('public')->delete($request->path);
        }

        $request->delete();

        return redirect()->route('requests.index')->with('success', 'Permintaan berhasil dihapus!');
    }

    public function download($filename)
    {
        $filePath = 'requests/' . $filename;

        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->download(storage_path("app/public/{$filePath}"));
    }

    public function show($id)
    {
        $request = Request::findOrFail($id);
        $filePath = storage_path("app/public/{$request->path}");

        if (!file_exists($filePath)) {
            return abort(404, 'File tidak ditemukan.');
        }

        $rows = (new FastExcel)->import($filePath);

        return view('requests.show', compact('request', 'rows'));
    }

    public function preview($id)
    {
        $request = Request::findOrFail($id);
        $filePath = storage_path("app/public/{$request->path}");

        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File tidak ditemukan'], 404);
        }

        $rows = (new FastExcel)->import($filePath);

        return response()->json(['rows' => $rows]);
    }

    public function updateStatus(HttpRequest $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:Menunggu,Disetujui,Ditolak'
        ]);

        $req = Request::findOrFail($id);
        $req->status = $validated['status'];
        $req->save();

        return redirect()->route('requests.index')->with('success', 'Status permintaan diperbarui.');
    }
}
