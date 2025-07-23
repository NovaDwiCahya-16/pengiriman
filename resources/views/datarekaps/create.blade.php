@extends('layouts.app')

@section('title', 'Tambah Data Rekap')

@section('content')
    @if (Auth::user()->type != 1)
        <div class="text-center mt-5">
            <h1 class="display-6 text-muted">403 | AKSES DITOLAK. HANYA ADMIN YANG BISA MENAMBAH DATA.</h1>
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
            <h2>Tambah Data Rekap</h2>

            @if ($errors->any())
                <div class="error-text">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('datarekaps.store') }}" method="POST">
                @csrf

                <label>No Faktur</label>
                <input type="text" name="no_faktur" value="{{ old('no_faktur') }}" required>

                <label>Tanggal Faktur</label>
                <input type="date" name="tgl_faktur" value="{{ old('tgl_faktur') }}" required>

                <label>No SJ / Mutasi</label>
                <input type="text" name="no_sj_mutasi" value="{{ old('no_sj_mutasi') }}">

                <label>Tanggal SJ / Mutasi</label>
                <input type="date" name="tgl_sj_mutasi" value="{{ old('tgl_sj_mutasi') }}">

                <label>Nama Konsumen</label>
                <input type="text" name="nama_konsumen" value="{{ old('nama_konsumen') }}">

                <label>Kecamatan Kirim</label>
                <input type="text" name="kecamatan_kirim" value="{{ old('kecamatan_kirim') }}">

                <label>Kota Kirim</label>
                <input type="text" name="kota_kirim" value="{{ old('kota_kirim') }}">

                <label>Leasing</label>
                <input type="text" name="leasing" value="{{ old('leasing') }}">

                <label>Nama Type</label>
                <input type="text" name="nama_type" value="{{ old('nama_type') }}">

                <label>Warna</label>
                <input type="text" name="warna" value="{{ old('warna') }}">

                <label>Cabang</label>
                <select name="cabang" class="form-select" required>
                    <option value="">-- Pilih Cabang --</option>
                    @foreach (['Ciracas', 'Condet', 'Daan Mogot', 'GSO', 'Gunung Sahari', 'Hayam Wuruk', 'Jatinegara', 'Kamal', 'Kelapa Gading', 'Klender', 'Sunter', 'Tambora', 'Tanah Tinggi', 'Wari Jatinegara'] as $cabang)
                        <option value="{{ $cabang }}" {{ old('cabang') == $cabang ? 'selected' : '' }}>
                            {{ $cabang }}</option>
                    @endforeach
                </select>

                <label for="supir">Supir</label>
                <select name="supir" id="supir" class="form-select">
                    <option value="">-- Pilih Supir --</option>
                    <option value="Adi" {{ old('supir') == 'Adi' ? 'selected' : '' }}>Adi</option>
                    <option value="Agus" {{ old('supir') == 'Agus' ? 'selected' : '' }}>Agus</option>
                    <option value="Badrus" {{ old('supir') == 'Badrus' ? 'selected' : '' }}>Badrus</option>
                    <option value="Novry" {{ old('supir') == 'Novry' ? 'selected' : '' }}>Novry</option>
                    <option value="Nono" {{ old('supir') == 'Nono' ? 'selected' : '' }}>Nono</option>
                    <option value="Supri" {{ old('supir') == 'Supri' ? 'selected' : '' }}>Supri</option>
                    <option value="Supardi" {{ old('supir') == 'Supardi' ? 'selected' : '' }}>Supardi</option>
                    <option value="Joko" {{ old('supir') == 'Joko' ? 'selected' : '' }}>Joko</option>
                    <option value="Jeki" {{ old('supir') == 'Jeki' ? 'selected' : '' }}>Jeki</option>
                    <option value="Gugun" {{ old('supir') == 'Gugun' ? 'selected' : '' }}>Gugun</option>
                </select>


                <label>Tanggal Kirim</label>
                <input type="date" name="tgl_kirim" value="{{ old('tgl_kirim') }}">

                <label>Stock</label>
                <input type="text" name="stock" value="{{ old('stock') }}">

                <label>Harga</label>
                <input type="text" name="harga" value="{{ old('harga') }}">

                <label>Kwitansi</label>
                <input type="text" name="kwitansi" value="{{ old('kwitansi') }}">

                <label>Konsumen Bayar</label>
                <input type="text" name="konsumen_bayar" value="{{ old('konsumen_bayar') }}">

                <label>Keterangan Tambahan</label>
                <input type="text" name="keterangan_tambahan" value="{{ old('keterangan_tambahan') }}">

                <label>Tanggal Serah Terima</label>
                <input type="date" name="tgl_serah_terima_unit" value="{{ old('tgl_serah_terima_unit') }}">

                <label>Pengiriman Leadtime (otomatis)</label>
                <input type="text" name="pengiriman_leadtime" readonly>

                <label>Performance Pengiriman (Hari)</label>
                <input type="text" name="performance_pengiriman_hari" readonly>

                <label>Status Pengiriman (otomatis)</label>
                <input type="text" name="status_pengiriman" readonly>

                <label>Keterangan Pending</label>
                <input type="text" name="keterangan_pending" value="{{ old('keterangan_pending') }}">

                <label>Keterangan Lainnya</label>
                <input type="text" name="keterangan_lainnya" value="{{ old('keterangan_lainnya') }}">

                <div class="btn-group">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="{{ route('datarekaps.index') }}" class="btn btn-secondary">Batal</a>
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
        const supir = document.querySelector('[name="supir"]'); // pastikan input/select punya name="supir"

        function hitungLeadtimeDanStatus() {
            const valKirim = tglKirim.value;
            const valSerah = tglSerah.value;
            const valSupir = supir ? supir.value.trim() : '';

            // Tambahan logika untuk cek jika supir dan tgl kirim kosong
            if (!valKirim && !valSupir) {
                statusPengiriman.value = 'Belum Pilih Supir dan Tanggal Kirim';
                leadtime.value = '';
                performanceHari.value = '';
                return; // hentikan proses agar logika bawah tidak jalan
            }

            if (valSerah) {
                const dSerah = new Date(valSerah);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                dSerah.setHours(0, 0, 0, 0);
                const besok = new Date(today);
                besok.setDate(today.getDate() + 1);

                if (valKirim) {
                    const dKirim = new Date(valKirim);
                    dKirim.setHours(0, 0, 0, 0);
                    const diff = Math.floor((dKirim - dSerah) / (1000 * 60 * 60 * 24));

                    leadtime.value = Math.abs(diff);
                    performanceHari.value = Math.abs(diff);

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
        if (supir) {
            supir.addEventListener('change', hitungLeadtimeDanStatus);
        }

        hitungLeadtimeDanStatus(); // saat pertama load
    });
</script>


    @endif
@endsection
