@extends('layouts.app')

@section('title', 'Data Permintaan')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')
    <div class="container">
        <h2 class="mb-3">Form Permintaan</h2>

        <a href="{{ route('requests.create') }}" class="btn btn-success mb-4">Tambah Permintaan</a>

        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="request-table">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Cabang</th>
                        <th>Tanggal</th>
                        <th>Jumlah Unit</th>
                        <th>File</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($requests as $index => $req)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $req->branch->city ?? '-' }} - {{ $req->branch->location ?? '' }}</td>
                            <td>{{ \Carbon\Carbon::parse($req->date)->format('d-m-Y') }}</td>
                            <td>{{ $req->unit }}</td>
                            <td>
                                @if ($req->path)
                                    <a href="{{ route('requests.download', basename($req->path)) }}" class="btn btn-sm btn-primary">Download</a>
                                @else
                                    <span class="text-muted">Tidak ada file</span>
                                @endif
                            </td>
                            <td>
                                @if (auth()->user()->isAdmin())
                                    <form action="{{ route('requests.updateStatus', $req->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" onchange="this.form.submit()" class="form-select form-select-sm">
                                            <option value="Menunggu" {{ strtolower($req->status) == 'menunggu' ? 'selected' : '' }}>⏳ Menunggu</option>
                                            <option value="Disetujui" {{ strtolower($req->status) == 'disetujui' ? 'selected' : '' }}>✅ Disetujui</option>
                                            <option value="Ditolak" {{ strtolower($req->status) == 'ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
                                        </select>
                                    </form>
                                @else
                                    <span class="badge bg-{{ strtolower($req->status) == 'disetujui' ? 'success' : (strtolower($req->status) == 'ditolak' ? 'danger' : 'secondary') }}">
                                        @if (strtolower($req->status) == 'disetujui')
                                            ✅ Disetujui
                                        @elseif (strtolower($req->status) == 'ditolak')
                                            ❌ Ditolak
                                        @else
                                            ⏳ Menunggu
                                        @endif
                                    </span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info" onclick="previewExcel({{ $req->id }})">
                                    <i class="bi bi-eye"></i> Lihat File
                                </button>
                                <a href="{{ route('requests.edit', $req->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('requests.destroy', $req->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin mau hapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                                {{-- Tombol Isi Rekap --}}
                                <a href="{{ route('datarekaps.create', ['cabang' => $req->branch->city, 'tgl_kirim' => $req->date, 'stock' => $req->unit]) }}"
                                   class="btn btn-sm btn-success mt-1">
                                    Input Data Rekap
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Preview Excel -->
    <div class="modal fade" id="excelModal" tabindex="-1" aria-labelledby="excelModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">📄 Preview File Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div id="excelPreview" class="table-responsive"></div>
                </div>
                <div class="modal-footer">
                    <button onclick="window.print()" class="btn btn-outline-primary"><i class="bi bi-printer"></i> Cetak</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#request-table').DataTable({
                paging: true,
                searching: true,
                lengthChange: true,
                ordering: true,
                info: true,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    infoEmpty: "Tidak ada data",
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Berikutnya"
                    }
                }
            });
        });

        function previewExcel(requestId) {
            fetch(`/requests/preview/${requestId}`)
                .then(response => response.json())
                .then(data => {
                    let html = `<table class="table table-bordered table-striped table-sm">`;
                    data.rows.forEach((row, index) => {
                        html += `<tr>`;
                        row.forEach(cell => {
                            html += index === 0 ? `<th>${cell}</th>` : `<td>${cell}</td>`;
                        });
                        html += `</tr>`;
                    });
                    html += `</table>`;
                    document.getElementById('excelPreview').innerHTML = html;
                    new bootstrap.Modal(document.getElementById('excelModal')).show();
                })
                .catch(error => {
                    alert('Gagal memuat isi file Excel.');
                    console.error(error);
                });
        }
    </script>
@endpush
