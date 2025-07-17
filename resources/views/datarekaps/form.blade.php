<div class="row">
    @php
        $fields = [
            'no_faktur', 'tgl_faktur', 'no_sj_mutasi', 'tgl_sj_mutasi',
            'nama_konsumen', 'kecamatan_kirim', 'kota_kirim', 'leasing',
            'nama_type', 'warna', 'cabang', 'supir', 'tgl_kirim', 'stock',
            'harga', 'kwitansi', 'konsumen_bayar', 'keterangan_tambahan',
            'tgl_serah_terima_unit', 'pengiriman_leadtime', 'performance_pengiriman_hari',
            'status_pengiriman', 'keterangan_pending', 'keterangan_lainnya'
        ];

        $dateFields = ['tgl_faktur', 'tgl_sj_mutasi', 'tgl_kirim', 'tgl_serah_terima_unit'];
    @endphp

    @foreach ($fields as $field)
        <div class="col-md-6 mb-3">
            <label for="{{ $field }}" class="form-label">{{ ucwords(str_replace('_', ' ', $field)) }}</label>

            @if ($field === 'status_pengiriman')
                <input 
                    type="text" 
                    name="{{ $field }}" 
                    id="{{ $field }}"
                    class="form-control" 
                    value="{{ old($field, $datarekap->$field ?? '') }}" 
                    readonly>
            @elseif ($field === 'pengiriman_leadtime' || $field === 'performance_pengiriman_hari')
                <input 
                    type="text" 
                    name="{{ $field }}" 
                    id="{{ $field }}" 
                    class="form-control" 
                    value="{{ old($field, $datarekap->$field ?? '') }}" 
                    readonly>
            @else
                <input 
                    type="{{ in_array($field, $dateFields) ? 'date' : 'text' }}" 
                    name="{{ $field }}" 
                    id="{{ $field }}"
                    class="form-control" 
                    value="{{ old($field, isset($datarekap) && $datarekap->$field ? (in_array($field, $dateFields) ? \Carbon\Carbon::parse($datarekap->$field)->format('Y-m-d') : $datarekap->$field) : '') }}">
            @endif
        </div>
    @endforeach
</div>

<div class="col-12 mt-3 d-flex justify-content-end">
    <a href="{{ route('datarekaps.index') }}" class="btn btn-secondary me-2">Batal</a>
    <button type="submit" class="btn btn-primary">Update</button>
</div>
