@extends('layouts.app')

@section('title', 'Buat Slot Pengiriman')

@section('content')
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

        input[type="month"],
        input[type="number"],
        input[type="text"] {
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

        button {
            padding: 12px;
            width: 100%;
            font-size: 16px;
            font-weight: bold;
            border-radius: 6px;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background-color: #007bff;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: #fff;
            margin-top: 10px;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }
    </style>

    <div class="form-wrapper">
        <h2>Buat Slot Pengiriman Baru</h2>

        @if ($errors->any())
            <div class="error-text">
                <ul style="padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('slot_delivery.store') }}" method="POST">
            @csrf

            <div>
                <label for="bulan_tahun">Bulan - Tahun</label>
                <input type="month" name="bulan_tahun" id="bulan_tahun"
                       value="{{ old('bulan_tahun') }}" required>
            </div>

            <div>
                <label for="slot">Slot Pengiriman</label>
                <input type="number" name="slot" id="slot"
                       value="{{ old('slot') }}" required min="0">
            </div>

            <div>
                <label for="unit">Permintaan Kirim</label>
                <input type="number" name="unit" id="unit"
                       value="{{ old('unit') }}" min="0">
            </div>

            <div>
                <label for="over_sisa">Over / Sisa (otomatis)</label>
                <input type="text" id="over_sisa" class="form-control" readonly>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('slot-delivery.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const slotInput = document.getElementById('slot');
            const unitInput = document.getElementById('unit');
            const resultField = document.getElementById('over_sisa');

            function updateOverSisa() {
                const slot = parseInt(slotInput.value) || 0;
                const unit = parseInt(unitInput.value) || 0;
                const selisih = slot - unit;

                if (selisih < 0) {
                    resultField.value = `${Math.abs(selisih)} over`;
                } else {
                    resultField.value = `${selisih} sisa`;
                }
            }

            slotInput.addEventListener('input', updateOverSisa);
            unitInput.addEventListener('input', updateOverSisa);
            updateOverSisa();
        });
    </script>
@endsection
