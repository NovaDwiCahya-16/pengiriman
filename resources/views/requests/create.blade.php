@extends('layouts.app')

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

        select,
        input[type="date"],
        input[type="number"],
        input[type="file"] {
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
            background-color: #28a745;
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
            background-color: #218838;
        }
    </style>

    <div class="form-wrapper">
        <h2>Buat Permintaan</h2>

        {{-- General Error Alert --}}
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
        <form action="{{ route('requests.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Cabang --}}
            <div>
                <label for="branch_id">Cabang</label>
                <select name="branch_id" id="branch_id" required>
                    <option value="">-- Pilih Cabang --</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->location }}, {{ $branch->city }}
                        </option>
                    @endforeach
                </select>
                @error('branch_id')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tanggal --}}
            <div>
                <label for="date">Tanggal</label>
                <input type="date" name="date" id="date" value="{{ old('date') }}" required>
                @error('date')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            {{-- Jumlah Unit --}}
            <div>
                <label for="unit">Jumlah Unit</label>
                <input type="number" name="unit" id="unit" value="{{ old('unit') }}" required min="1">
                @error('unit')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            {{-- File Upload --}}
            <div>
                <label for="xlsx_file">Upload File (.xlsx / .xls)</label>
                <input type="file" name="xlsx_file" id="xlsx_file" accept=".xlsx,.xls" required>
                @error('xlsx_file')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            {{-- Submit --}}
            <button type="submit">Simpan</button>
        </form>
    </div>
@endsection
