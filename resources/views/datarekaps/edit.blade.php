@extends('layouts.app')

@section('title', 'Edit Data Rekap')

@section('content')
    @if (Auth::user()->type != 1)
        <div class="text-center mt-5">
            <h1 class="display-6 text-muted">403 | AKSES DITOLAK. HANYA ADMIN YANG BISA MENGUBAH DATA.</h1>
        </div>
    @else
        <style>
            .form-wrapper {
                max-width: 800px;
                margin: 40px auto;
                padding: 30px;
                background-color: #f9f9f9;
                border-radius: 12px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            }

            .form-wrapper h2 {
                text-align: center;
                margin-bottom: 25px;
                font-size: 24px;
                font-weight: bold;
            }

            label {
                font-weight: 600;
                margin-top: 10px;
                display: block;
            }

            input[type="text"],
            input[type="date"],
            select {
                width: 100%;
                padding: 10px;
                margin-top: 5px;
                border: 1px solid #ccc;
                border-radius: 6px;
            }

            .btn-group {
                display: flex;
                justify-content: space-between;
                gap: 10px;
                margin-top: 25px;
            }

            .btn-group .btn {
                flex: 1;
            }

            .error-text {
                color: red;
                font-size: 14px;
                margin-bottom: 15px;
            }
        </style>

        <div class="form-wrapper">
            <h2>Edit Data Rekap</h2>

            @if ($errors->any())
                <div class="error-text">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('datarekaps.update', $datarekap->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    @php
                        $fields = [
                            'no_faktur',
                            'tgl_faktur',
                            'no_sj_mutasi',
                            'tgl_sj_mutasi',
                            'nama_konsumen',
                            'kecamatan_kirim',
                            'kota_kirim',
                            'leasing',
                            'nama_type',
                            'warna',
                            'cabang',
                            'supir',
                            'tgl_kirim',
                            'stock',
                            'harga',
                            'kwitansi',
                            'konsumen_bayar',
                            'keterangan_tambahan',
                            'tgl_serah_terima_unit',
                            'pengiriman_leadtime',
                            'performance_pengiriman_hari',
                            'status_pengiriman',
                            'keterangan_pending',
                            'keterangan_lainnya',
                        ];

                        $dateFields = ['tgl_faktur', 'tgl_sj_mutasi', 'tgl_kirim', 'tgl_serah_terima_unit'];
                    @endphp

                    @foreach ($fields as $field)
                        <div class="col-md-6 mb-3">
                            <label for="{{ $field }}"
                                class="form-label">{{ ucwords(str_replace('_', ' ', $field)) }}</label>

                            @if (in_array($field, ['pengiriman_leadtime', 'performance_pengiriman_hari', 'status_pengiriman']))
                                <input type="text" name="{{ $field }}" id="{{ $field }}"
                                    class="form-control" value="{{ old($field, $datarekap->$field ?? '') }}" readonly>
                            @elseif ($field === 'supir')
                                <select name="supir" id="supir" class="form-select">
                                    <option value="">-- Pilih Supir --</option>
                                    @php
                                        $supirList = [
                                            'Adi',
                                            'Agus',
                                            'Badrus',
                                            'Novry',
                                            'Nono',
                                            'Supri',
                                            'Supardi',
                                            'Joko',
                                            'Jeki',
                                            'Gugun',
                                        ];
                                    @endphp
                                    @foreach ($supirList as $s)
                                        <option value="{{ $s }}"
                                            {{ old('supir', $datarekap->supir ?? '') == $s ? 'selected' : '' }}>
                                            {{ $s }}</option>
                                    @endforeach
                                </select>
                            @else
                                <input type="{{ in_array($field, $dateFields) ? 'date' : 'text' }}"
                                    name="{{ $field }}" id="{{ $field }}" class="form-control"
                                    value="{{ old($field, isset($datarekap) && $datarekap->$field ? (in_array($field, $dateFields) ? \Carbon\Carbon::parse($datarekap->$field)->format('Y-m-d') : $datarekap->$field) : '') }}">
                            @endif
                        </div>
                    @endforeach

                </div>

                <div class="col-12 mt-3 d-flex justify-content-end">
                    <a href="{{ route('datarekaps.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const tglKirim = document.querySelector('[name="tgl_kirim"]');
                const tglSerah = document.querySelector('[name="tgl_serah_terima_unit"]');

                const leadtime = document.querySelector('[name="pengiriman_leadtime"]');
                const performanceHari = document.querySelector('[name="performance_pengiriman_hari"]');
                const statusPengiriman = document.querySelector('[name="status_pengiriman"]');

                function hitungLeadtimeDanStatus() {
                    const valKirim = tglKirim.value;
                    const valSerah = tglSerah.value;

                    if (valSerah) {
                        const dSerah = new Date(valSerah);
                        dSerah.setHours(0, 0, 0, 0);
                        const today = new Date();
                        today.setHours(0, 0, 0, 0);
                        const besok = new Date(today);
                        besok.setDate(today.getDate() + 1);

                        if (valKirim) {
                            const dKirim = new Date(valKirim);
                            dKirim.setHours(0, 0, 0, 0);
                            const diff = Math.floor((dKirim - dSerah) / (1000 * 60 * 60 * 24));
                            const absDiff = Math.abs(diff);

                            leadtime.value = absDiff;
                            performanceHari.value = absDiff;

                            if (dKirim.getTime() === dSerah.getTime()) {
                                statusPengiriman.value = 'Tepat Waktu';
                            } else if (dKirim < dSerah) {
                                statusPengiriman.value = 'Tidak Valid';
                            } else {
                                statusPengiriman.value = 'Terlambat';
                            }
                        } else {
                            leadtime.value = '';
                            performanceHari.value = '';

                            if (dSerah.getTime() === besok.getTime()) {
                                statusPengiriman.value = 'Dikirim Besok';
                            } else if (dSerah.getTime() === today.getTime()) {
                                statusPengiriman.value = 'Dalam Pengiriman';
                            } else if (dSerah < today) {
                                statusPengiriman.value = 'Menunggu Pengiriman';
                            } else {
                                statusPengiriman.value = 'Belum Diketahui';
                            }
                        }
                    } else {
                        leadtime.value = '';
                        performanceHari.value = '';
                        statusPengiriman.value = '';
                    }
                }

                tglKirim.addEventListener('change', hitungLeadtimeDanStatus);
                tglSerah.addEventListener('change', hitungLeadtimeDanStatus);
                hitungLeadtimeDanStatus(); // saat halaman pertama kali load
            });
        </script>

    @endif
@endsection
