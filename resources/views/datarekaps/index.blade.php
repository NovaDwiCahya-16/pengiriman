@extends('layouts.app')

@section('title', 'Data Rekap')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')
<div class="container">
    <h2 class="mb-3">Data Rekap</h2>

    {{-- Tombol tambah hanya untuk admin --}}
    @if(Auth::user()->type == 1)
        <a href="{{ route('datarekaps.create') }}" class="btn btn-success mb-4">Tambah Data</a>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle text-nowrap" id="rekap-table">
            <thead class="table-white">
                <tr class="text-center">
                    <th>No</th>
                    <th>No Faktur</th>
                    <th>Tgl Faktur</th>
                    <th>No SJ / Mutasi</th>
                    <th>Tgl SJ / Mutasi</th>
                    <th>Nama Konsumen</th>
                    <th>Kecamatan Kirim</th>
                    <th>Kota Kirim</th>
                    <th>Leasing</th>
                    <th>Nama Type</th>
                    <th>Warna</th>
                    <th>Cabang</th>
                    <th>Supir</th>
                    <th>Tgl Kirim</th>
                    <th>Stock</th>
                    <th>Harga</th>
                    <th>Kwitansi</th>
                    <th>Konsumen Bayar</th>
                    <th>Keterangan Tambahan</th>
                    <th>Tgl Serah Terima Unit</th>
                    <th>Lead Time</th>
                    <th>Performance (Hari)</th>
                    <th>Status Pengiriman</th>
                    <th>Keterangan Pending</th>
                    <th>Keterangan Lainnya</th>
                    @if(Auth::user()->type == 1)
                        <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($datarekaps as $index => $data)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $data->no_faktur }}</td>
                        <td>{{ $data->tgl_faktur }}</td>
                        <td>{{ $data->no_sj_mutasi }}</td>
                        <td>{{ $data->tgl_sj_mutasi }}</td>
                        <td>{{ $data->nama_konsumen }}</td>
                        <td>{{ $data->kecamatan_kirim }}</td>
                        <td>{{ $data->kota_kirim }}</td>
                        <td>{{ $data->leasing }}</td>
                        <td>{{ $data->nama_type }}</td>
                        <td>{{ $data->warna }}</td>
                        <td>{{ $data->cabang }}</td>
                        <td>{{ $data->supir }}</td>
                        <td>{{ $data->tgl_kirim }}</td>
                        <td>{{ $data->stock }}</td>
                        <td>{{ $data->harga }}</td>
                        <td>{{ $data->kwitansi }}</td>
                        <td>{{ $data->konsumen_bayar }}</td>
                        <td>{{ $data->keterangan_tambahan }}</td>
                        <td>{{ $data->tgl_serah_terima_unit }}</td>
                        <td>{{ $data->pengiriman_leadtime }}</td>
                        <td>{{ $data->performance_pengiriman_hari }}</td>
                        <td>{{ $data->status_pengiriman }}</td>
                        <td>{{ $data->keterangan_pending }}</td>
                        <td>{{ $data->keterangan_lainnya }}</td>
                        @if(Auth::user()->type == 1)
                        <td class="text-nowrap text-center">
                            <a href="{{ route('datarekaps.edit', $data->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('datarekaps.destroy', $data->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#rekap-table').DataTable({
                paging: true,
                searching: true,
                lengthChange: true,
                ordering: true,
                info: true,
                scrollX: true,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    infoEmpty: "Tidak ada data tersedia",
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Berikutnya"
                    }
                }
            });
        });
    </script>
@endpush

