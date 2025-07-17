@extends('layouts.app')

@section('title', 'Edit Slot Pengiriman')

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

        input[type="date"],
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
            background-color: #28a745;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #218838;
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
        <h2>Edit Slot Pengiriman</h2>

        @if ($errors->any())
            <div class="error-text">
                <ul style="padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('slot-deliveries.update', $slot->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div>
                <label for="tanggal_pengiriman">Tanggal Pengiriman</label>
                <input type="date" name="tanggal_pengiriman" id="tanggal_pengiriman"
                    value="{{ $slot->tanggal_pengiriman ? \Carbon\Carbon::parse($slot->tanggal_pengiriman)->format('Y-m-d') : '' }}"
                    required>
            </div>

            <div>
                <label for="slot_pengiriman">Slot Pengiriman</label>
                <input type="number" name="slot_pengiriman" id="slot_pengiriman"
                    value="{{ $slot->slot_pengiriman }}" required min="0">
            </div>

            <div>
                <label for="permintaan_kirim">Permintaan Kirim</label>
                <input type="number" name="permintaan_kirim" id="permintaan_kirim"
                    value="{{ $slot->permintaan_kirim }}" required min="0">
            </div>

            <div>
                <label for="over_sisa">Over / Sisa (otomatis)</label>
                <input type="text" id="over_sisa" class="form-control" readonly>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('slot-deliveries.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const slotInput = document.getElementById('slot_pengiriman');
            const permintaanInput = document.getElementById('permintaan_kirim');
            const resultField = document.getElementById('over_sisa');

            function updateOverSisa() {
                const slot = parseInt(slotInput.value) || 0;
                const permintaan = parseInt(permintaanInput.value) || 0;
                const selisih = slot - permintaan;

                if (selisih < 0) {
                    resultField.value = `${selisih} over`;
                } else {
                    resultField.value = `${selisih} sisa`;
                }
            }

            slotInput.addEventListener('input', updateOverSisa);
            permintaanInput.addEventListener('input', updateOverSisa);
            updateOverSisa(); // hitung saat pertama kali tampil
        });
    </script>
@endsection
