@extends('layouts.app')

@section('title', 'Data Rekap Final')

@section('content')
<div class="container">
    <h4>Data Rekap Final</h4>
    <a href="{{ route('datarekaps.nonfinal') }}" class="btn btn-outline-primary mb-3">Lihat Non Final</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No Faktur</th>
                <th>Tgl Faktur</th>
                <th>No SJ Mutasi</th>
                <th>Tgl SJ Mutasi</th>
                <th>Nama Konsumen</th>
                <th>Kecamatan Kirim</th>
                <th>Tgl Kirim</th>
                <th>Supir</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($datarekaps as $item)
            <tr>
                <td>{{ $item->no_faktur }}</td>
                <td>{{ $item->tgl_faktur }}</td>
                <td>{{ $item->no_sj_mutasi }}</td>
                <td>{{ $item->tgl_sj_mutasi }}</td>
                <td>{{ $item->nama_konsumen }}</td>
                <td>{{ $item->kecamatan_kirim }}</td>
                <td>{{ $item->tgl_kirim }}</td>
                <td>{{ $item->supir }}</td>
                <td>
                    <a href="{{ route('datarekaps.show', $item->id) }}" class="btn btn-info btn-sm">Detail</a>
                    @if(Auth::user()->type === 1)
                        <a href="{{ route('datarekaps.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('datarekaps.destroy', $item->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
