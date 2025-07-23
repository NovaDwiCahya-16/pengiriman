@extends('layouts.app')

@section('title', 'Slot Pengiriman')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')
    <div class="container">
        <h2 class="mb-3">Daftar Slot Pengiriman</h2>

        {{-- Tombol Tambah hanya untuk Admin --}}
        @if(auth()->user()->type === 1)
            <a href="{{ route('slot-deliveries.create') }}" class="btn btn-success mb-4">Tambah Slot</a>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="slot-table">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Tanggal Pengiriman</th>
                        <th>Slot Pengiriman</th>
                        <th>Permintaan Kirim</th>
                        <th>Over/Sisa</th>
                        @if(auth()->user()->type === 1)
                            <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($slots as $index => $slot)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($slot->tanggal_pengiriman)->format('d-m-Y') }}</td>
                            <td>{{ $slot->slot_pengiriman }}</td>
                            <td>{{ $slot->permintaan_kirim }}</td>
                            <td>
                                @php
                                    $selisih = $slot->slot_pengiriman - $slot->permintaan_kirim;
                                @endphp
                                {{ $selisih }}
                            </td>
                            @if(auth()->user()->type === 1)
                                <td>
                                    <a href="{{ route('slot-deliveries.edit', $slot->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('slot-deliveries.destroy', $slot->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin hapus?')">
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
            $('#slot-table').DataTable({
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
    </script>
@endpush
