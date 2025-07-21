<?php

namespace App\Http\Controllers;

use App\Models\RequestModel;
use App\Models\Branch;
<<<<<<< HEAD
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
=======
use Illuminate\Support\Facades\Storage;
>>>>>>> e819be99bc773c1eae88bb83ad69da7759ea73c6
use PhpOffice\PhpSpreadsheet\IOFactory;

class RequestController extends Controller
{
    /*------------------------------------------
    --------------------------------------------
    Request Management
    --------------------------------------------*/
    public function adminRequest()
    {
        $branches = Branch::all();
        return view('requests.index', compact('branches'));
    }

    public function manageRequest(Request $request)
    {
        if ($request->ajax()) {
            try {
                $data = RequestModel::with('branch')
                    ->select(['id', 'branch_id', 'date', 'unit', 'status', 'created_at'])
                    ->orderBy('created_at', 'desc');

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('branch_id', function ($row) {
                        return $row->branch ? $row->branch->name : 'N/A';
                    })
                    ->editColumn('date', function ($row) {
                        return Carbon::parse($row->date)->format('d/m/Y');
                    })
                    ->editColumn('unit', function ($row) {
                        return number_format($row->unit, 0, ',', '.');
                    })
                    ->editColumn('status', function ($row) {
                        $badgeClass = match ($row->status) {
                            'Menunggu' => 'bg-warning',
                            'Disetujui' => 'bg-success',
                            'Ditolak' => 'bg-danger',
                            'Diproses' => 'bg-info',
                            default => 'bg-secondary'
                        };
                        return '<span class="badge ' . $badgeClass . '">' . $row->status . '</span>';
                    })
                    ->editColumn('created_at', function ($row) {
                        return $row->created_at->format('d/m/Y H:i');
                    })
                    ->addColumn('action', function ($row) {
                        // Pastikan data yang dikirim ke tombol bersih dan dalam format yang benar
                        $editData = [
                            'id' => $row->id,
                            'branch_id' => $row->branch_id,
                            'date' => Carbon::parse($row->date)->format('Y-m-d'),
                            'unit' => $row->unit,
                            'status' => $row->status,
                            'branch_name' => $row->branch ? $row->branch->name : 'N/A'
                        ];

                        return '
                        <div class="btn-group">
                            <button class="btn btn-sm btn-primary me-1 rounded editBtn" 
                                    data-id="' . $editData['id'] . '" 
                                    data-branch_id="' . $editData['branch_id'] . '" 
                                    data-date="' . $editData['date'] . '" 
                                    data-unit="' . $editData['unit'] . '" 
                                    data-status="' . $editData['status'] . '"
                                    data-branch_name="' . $editData['branch_name'] . '"
                                    title="Edit">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger me-1 rounded deleteBtn" 
                                    data-id="' . $editData['id'] . '"
                                    data-branch_name="' . $editData['branch_name'] . '"
                                    data-date="' . Carbon::parse($editData['date'])->format('d/m/Y') . '"
                                    data-unit="' . number_format($editData['unit'], 0, ',', '.') . '"
                                    data-status="' . $editData['status'] . '"
                                    title="Hapus">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    ';
                    })
                    ->rawColumns(['status', 'action']) // Kedua kolom ini mengandung HTML
                    ->make(true);
            } catch (\Exception $e) {
                Log::error('Error in manageRequest:', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                return response()->json([
                    'error' => 'Terjadi kesalahan saat memuat data: ' . $e->getMessage()
                ], 500);
            }
        }

        // Jika bukan Ajax request, redirect ke halaman utama
        return redirect()->route('requests');
    }

    // Store request baru dengan import Excel
    public function storeRequest(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls|max:2048',
        ], [
            'excel_file.required' => 'File Excel wajib diupload',
            'excel_file.mimes' => 'File harus berformat Excel (.xlsx atau .xls)',
            'excel_file.max' => 'Ukuran file maksimal 2MB',
        ]);

<<<<<<< HEAD
        try {
            // Parse Excel file
            $excelData = $this->parseExcelFile($request->file('excel_file'));
=======
        // Muat isi file Excel
        $spreadsheet = IOFactory::load($request->file('xlsx_file')->getRealPath());
        $worksheet = $spreadsheet->getActiveSheet();

        $rows = [];
        foreach ($worksheet->getRowIterator() as $rowIndex => $row) {
            $cells = [];
            foreach ($row->getCellIterator() as $cell) {
                $cells[] = trim($cell->getFormattedValue());
            }
            // Lewati baris kosong
            if (array_filter($cells)) {
                $rows[] = $cells;
            }
        }
>>>>>>> e819be99bc773c1eae88bb83ad69da7759ea73c6

            // Log untuk debugging
            Log::info('Parsed Excel Data:', $excelData);

<<<<<<< HEAD
            // Validasi data yang diparsing
            if (!$excelData['branch_name']) {
                return redirect()->back()->with('error', 'Nama cabang tidak ditemukan dalam file Excel! Pastikan format A2: "CABANG : [Nama Cabang]"');
            }

            if (!$excelData['date']) {
                return redirect()->back()->with('error', 'Tanggal tidak ditemukan dalam file Excel! Pastikan format A3: "DIBUAT TGL : [Tanggal]"');
            }

            if ($excelData['unit_count'] <= 0) {
                return redirect()->back()->with('error', 'Data unit tidak ditemukan! Pastikan ada data mulai dari baris A7');
            }

            // Cari branch dengan pencarian yang lebih fleksibel
            $branch = Branch::where('name', 'like', '%' . trim($excelData['branch_name']) . '%')
                ->first();

            if (!$branch) {
                // Coba cari dengan kata kunci yang lebih spesifik
                $searchTerms = explode(' ', trim($excelData['branch_name']));
                foreach ($searchTerms as $term) {
                    if (strlen($term) > 3) { // Hanya gunakan kata dengan panjang > 3
                        $branch = Branch::where('name', 'like', '%' . $term . '%')
                            ->first();
                        if ($branch) break;
                    }
=======
        foreach ($existingRequests as $existing) {
            $existingPath = storage_path("app/public/" . $existing->path);
            if (file_exists($existingPath)) {
                $existingSpreadsheet = IOFactory::load($existingPath);
                $existingSheet = $existingSpreadsheet->getActiveSheet();
                $existingRows = [];
                foreach ($existingSheet->getRowIterator() as $r) {
                    $data = [];
                    foreach ($r->getCellIterator() as $c) {
                        $data[] = trim($c->getFormattedValue());
                    }
                    if (array_filter($data)) {
                        $existingRows[] = $data;
                    }
                }

                // Bandingkan isi
                if ($existingRows == $rows) {
                    return back()->withErrors(['xlsx_file' => 'File Excel yang sama sudah pernah diunggah untuk cabang dan tanggal ini.']);
>>>>>>> e819be99bc773c1eae88bb83ad69da7759ea73c6
                }
            }

            if (!$branch) {
                return redirect()->back()->with('error', 'Cabang "' . $excelData['branch_name'] . '" tidak ditemukan di database! Cabang yang tersedia: ' . Branch::pluck('name')->implode(', '));
            }

            // Cek apakah sudah ada request dengan data yang sama
            $existingRequest = RequestModel::where('branch_id', $branch->id)
                ->where('date', $excelData['date'])
                ->where('unit', $excelData['unit_count'])
                ->first();

            if ($existingRequest) {
                return redirect()->back()->with('error', 'Request dengan data yang sama sudah ada untuk cabang ini pada tanggal tersebut!');
            }

            // Simpan request
            $newRequest = RequestModel::create([
                'branch_id' => $branch->id,
                'date' => $excelData['date'],
                'unit' => $excelData['unit_count'],
                'status' => 'Menunggu',
            ]);

            // Log untuk debugging
            Log::info('Request created successfully:', $newRequest->toArray());

            return redirect()->back()->with('success', 'Request berhasil ditambahkan dari Excel! Cabang: ' . $branch->name . ', Tanggal: ' . Carbon::parse($excelData['date'])->format('d/m/Y') . ', Unit: ' . $excelData['unit_count']);
        } catch (\Exception $e) {
            Log::error('Error processing Excel file:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses file Excel: ' . $e->getMessage());
        }
<<<<<<< HEAD
=======

        // Simpan file
        $filePath = $request->file('xlsx_file')->store('requests', 'public');

        // Simpan data
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
>>>>>>> e819be99bc773c1eae88bb83ad69da7759ea73c6
    }

    // Method untuk parsing Excel file
    private function parseExcelFile($file)
    {
        try {
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();

            // Ambil data dari posisi spesifik
            $branchCell = $worksheet->getCell('A2')->getCalculatedValue(); // "CABANG : HAYAM WURUK"
            $dateCell = $worksheet->getCell('A3')->getCalculatedValue();   // "DIBUAT TGL : 04 JULI 2025"

            // Log untuk debugging
            Log::info('Excel Cell Values:', [
                'A2' => $branchCell,
                'A3' => $dateCell
            ]);

            // Extract branch name
            $branchName = '';
            if ($branchCell && (stripos($branchCell, 'CABANG') !== false || stripos($branchCell, 'BRANCH') !== false)) {
                // Hapus kata "CABANG :" atau "BRANCH :"
                $branchName = preg_replace('/^(CABANG|BRANCH)\s*:?\s*/i', '', $branchCell);
                $branchName = trim($branchName);
            }

            // Extract date
            $date = null;
            if ($dateCell && (stripos($dateCell, 'DIBUAT') !== false || stripos($dateCell, 'TGL') !== false)) {
                $dateString = preg_replace('/^(DIBUAT\s*TGL|TGL|DIBUAT)\s*:?\s*/i', '', $dateCell);
                $dateString = trim($dateString);
                $date = $this->convertIndonesianDate($dateString);
            }

            // Hitung jumlah unit dari data tabel (mulai dari A7)
            $unitCount = 0;
            $row = 7; // Mulai dari baris ke-7

            while (true) {
                $cellValue = $worksheet->getCell('A' . $row)->getCalculatedValue();

                // Log beberapa cell pertama untuk debugging
                if ($row <= 10) {
                    Log::info("Cell A{$row}:", ['value' => $cellValue]);
                }

                // Jika cell kosong atau null, hentikan penghitungan
                if (empty($cellValue) || $cellValue === null || $cellValue === '') {
                    break;
                }

                // Konversi ke string untuk memastikan
                $cellValue = trim(strval($cellValue));

                // Jika ada data (nomor urut atau data lainnya), hitung sebagai 1 unit
                if (!empty($cellValue) && $cellValue !== '0') {
                    $unitCount++;
                }

                $row++;

                // Batasi maksimal 1000 baris untuk safety
                if ($row > 1000) {
                    break;
                }
            }

            $result = [
                'branch_name' => $branchName,
                'date' => $date,
                'unit_count' => $unitCount,
            ];

            // Log hasil parsing
            Log::info('Parsing result:', $result);

            return $result;
        } catch (\Exception $e) {
            Log::error('Error parsing Excel file:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    // Convert Indonesian date format to Y-m-d
    private function convertIndonesianDate($dateString)
    {
        try {
            $months = [
                'JANUARI' => '01',
                'FEBRUARI' => '02',
                'MARET' => '03',
                'APRIL' => '04',
                'MEI' => '05',
                'JUNI' => '06',
                'JULI' => '07',
                'AGUSTUS' => '08',
                'SEPTEMBER' => '09',
                'OKTOBER' => '10',
                'NOVEMBER' => '11',
                'DESEMBER' => '12'
            ];

            // Normalisasi string
            $dateString = strtoupper(trim($dateString));

            // Log untuk debugging
            Log::info('Converting date:', ['original' => $dateString]);

            // Parse format "04 JULI 2025"
            $parts = preg_split('/\s+/', $dateString);
            if (count($parts) >= 3) {
                $day = str_pad($parts[0], 2, '0', STR_PAD_LEFT);
                $monthName = $parts[1];
                $year = $parts[2];

                // Cari bulan
                $month = $months[$monthName] ?? null;

                if ($month) {
                    $result = $year . '-' . $month . '-' . $day;
                    Log::info('Date converted successfully:', ['result' => $result]);
                    return $result;
                }
            }

            // Coba format lain jika gagal
            // Format "04-07-2025" atau "04/07/2025"
            if (preg_match('/(\d{1,2})[-\/](\d{1,2})[-\/](\d{4})/', $dateString, $matches)) {
                $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
                $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
                $year = $matches[3];
                $result = $year . '-' . $month . '-' . $day;
                Log::info('Date converted with alternative format:', ['result' => $result]);
                return $result;
            }

            // Fallback ke tanggal hari ini
            $fallback = Carbon::now()->format('Y-m-d');
            Log::warning('Using fallback date:', ['fallback' => $fallback]);
            return $fallback;
        } catch (\Exception $e) {
            Log::error('Error converting date:', [
                'message' => $e->getMessage(),
                'dateString' => $dateString ?? 'null'
            ]);
            return Carbon::now()->format('Y-m-d');
        }
    }

    // Edit request
    public function editRequest(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:requests,id',
            'branch_id' => 'required|exists:branches,id',
            'date' => 'required|date',
            'unit' => 'required|integer|min:1',
            'status' => 'required|in:Menunggu,Disetujui,Ditolak,Diproses',
        ], [
            'branch_id.required' => 'Cabang wajib dipilih',
            'branch_id.exists' => 'Cabang tidak valid',
            'date.required' => 'Tanggal wajib diisi',
            'date.date' => 'Format tanggal tidak valid',
            'unit.required' => 'Unit wajib diisi',
            'unit.integer' => 'Unit harus berupa angka',
            'unit.min' => 'Unit minimal 1',
            'status.required' => 'Status wajib dipilih',
            'status.in' => 'Status tidak valid',
        ]);

        try {
            $requestModel = RequestModel::findOrFail($request->id);
            $requestModel->update([
                'branch_id' => $request->branch_id,
                'date' => $request->date,
                'unit' => $request->unit,
                'status' => $request->status,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Request berhasil diupdate!'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating request:', [
                'message' => $e->getMessage(),
                'request_id' => $request->id
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengupdate request!'
            ], 500);
        }
    }

    // Hapus request
    public function deleteRequest(Request $request)
    {
        try {
            $requestModel = RequestModel::findOrFail($request->id);
            $requestModel->delete();

            return redirect()->back()->with('success', 'Request berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting request:', [
                'message' => $e->getMessage(),
                'request_id' => $request->id
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus request!');
        }
    }

    public function getRequestDetail($id)
    {
        try {
            // Validasi ID
            if (!$id || !is_numeric($id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID request tidak valid'
                ], 400);
            }

            $request = RequestModel::with('branch')->find($id);

            if (!$request) {
                return response()->json([
                    'success' => false,
                    'message' => 'Request tidak ditemukan'
                ], 404);
            }

            // Format data untuk response
            $data = [
                'id' => $request->id,
                'branch_id' => $request->branch_id,
                'date' => $request->date, // Pastikan format Y-m-d
                'unit' => $request->unit,
                'status' => $request->status,
                'branch_name' => $request->branch ? $request->branch->name : 'N/A',
                'formatted_date' => Carbon::parse($request->date)->format('d/m/Y')
            ];

            Log::info('Request detail retrieved successfully:', [
                'request_id' => $id,
                'data' => $data
            ]);

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Data berhasil dimuat'
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting request detail:', [
                'message' => $e->getMessage(),
                'request_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat data: ' . $e->getMessage()
            ], 500);
        }
<<<<<<< HEAD
=======

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
        $filePath = $request->path;
        $fullPath = storage_path("app/public/{$filePath}");

        if (!file_exists($fullPath)) {
            return abort(404, 'File tidak ditemukan.');
        }

        $spreadsheet = IOFactory::load($fullPath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = [];

        foreach ($worksheet->getRowIterator() as $row) {
            $cells = [];
            foreach ($row->getCellIterator() as $cell) {
                $cells[] = $cell->getFormattedValue();
            }
            $rows[] = $cells;
        }

        return view('requests.show', compact('request', 'rows'));
    }

    public function preview($id)
    {
        $request = Request::findOrFail($id);
        $filePath = $request->path;
        $fullPath = storage_path("app/public/{$filePath}");

        if (!file_exists($fullPath)) {
            return response()->json(['error' => 'File tidak ditemukan'], 404);
        }

        $spreadsheet = IOFactory::load($fullPath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = [];

        foreach ($worksheet->getRowIterator() as $row) {
            $cells = [];
            foreach ($row->getCellIterator() as $cell) {
                $cells[] = $cell->getFormattedValue();
            }
            $rows[] = $cells;
        }

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
>>>>>>> e819be99bc773c1eae88bb83ad69da7759ea73c6
    }
}
