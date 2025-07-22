@extends('layouts.app')

@section('title', 'Tambah Slot Pengiriman')

@section('content')
@if (auth()->user()->type !== 1)
    <div class="text-center mt-5">
        <h3>Akses Ditolak</h3>
        <p>Halaman ini hanya dapat diakses oleh admin.</p>
    </div>
@else
    <style>
        .form-wrapper {
            max-width: 600px;
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
        }

        label {
            font-weight: 600;
            margin-bottom: 5px;
            display: block;
        }

        input[type="number"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .error-text {
            color: red;
            font-size: 14px;
            margin-bottom: 15px;
        }

        button[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 6px;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #0069d9;
        }
    </style>

    <div class="form-wrapper">
        <h2>Tambah Slot Pengiriman</h2>

        {{-- Menampilkan error --}}
        @if ($errors->any())
            <div class="error-text">
                <ul style="padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form --}}
        <form action="{{ route('slot-deliveries.store') }}" method="POST">
            @csrf

            {{-- Bulan --}}
            <div>
                <label for="bulan">Bulan</label>
                <select name="bulan" id="bulan" required>
                    @foreach(range(1, 12) as $b)
                        <option value="{{ $b }}" {{ old('bulan') == $b ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $b)->format('F') }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Tahun --}}
            <div>
                <label for="tahun">Tahun</label>
                <input type="number" name="tahun" id="tahun" value="{{ old('tahun', date('Y')) }}" required>
            </div>

            {{-- Slot Pengiriman --}}
            <div>
                <label for="slot_pengiriman">Slot Pengiriman</label>
                <input type="number" name="slot_pengiriman" id="slot_pengiriman"
                    value="{{ old('slot_pengiriman') }}" required>
            </div>

            {{-- Permintaan Kirim: readonly, dari controller --}}
            <div>
                <label for="permintaan_kirim">Permintaan Kirim (otomatis)</label>
                <input type="number" id="permintaan_kirim" readonly placeholder="Akan dihitung otomatis" value="0">
            </div>

            {{-- Over / Sisa --}}
            <div>
                <label for="over_sisa">Over / Sisa (otomatis)</label>
                <input type="text" id="over_sisa" class="form-control" readonly>
            </div>

            {{-- Submit --}}
            <button type="submit">Simpan</button>
        </form>
    </div>

    {{-- Script JS untuk fetch unit otomatis --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const bulanInput = document.getElementById('bulan');
            const tahunInput = document.getElementById('tahun');
            const permintaanInput = document.getElementById('permintaan_kirim');
            const slotInput = document.getElementById('slot_pengiriman');
            const overSisaInput = document.getElementById('over_sisa');

            function updateUnitDanSisa() {
                const bulan = bulanInput.value;
                const tahun = tahunInput.value;

                if (!bulan || !tahun) return;

                fetch(`/api/requests/unit?bulan=${bulan}&tahun=${tahun}`)
                    .then(res => res.json())
                    .then(data => {
                        permintaanInput.value = data.unit || 0;
                        hitungOverSisa();
                    });
            }

            function hitungOverSisa() {
                const slot = parseInt(slotInput.value) || 0;
                const permintaan = parseInt(permintaanInput.value) || 0;
                const selisih = slot - permintaan;
                overSisaInput.value = selisih + (selisih < 0 ? ' over' : ' sisa');
            }

            bulanInput.addEventListener('change', updateUnitDanSisa);
            tahunInput.addEventListener('change', updateUnitDanSisa);
            slotInput.addEventListener('input', hitungOverSisa);
        });
    </script>
@endif
@endsection
