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
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .button-group {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 10px;
        }

        .btn-submit,
        .btn-back {
            padding: 12px 20px;
            font-weight: bold;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
        }

        .btn-submit {
            background-color: #007bff;
            color: #fff;
        }

        .btn-submit:hover {
            background-color: #0069d9;
        }

        .btn-back {
            background-color: #6c757d;
            color: #fff;
        }

        .btn-back:hover {
            background-color: #5a6268;
        }
    </style>

    <div class="form-wrapper">
        <h2>Ubah Data Permintaan</h2>

        <form action="{{ route('requests.update', $request->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div>
                <label for="branch_id">Cabang</label>
                <select name="branch_id" id="branch_id" required>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" {{ $branch->id == $request->branch_id ? 'selected' : '' }}>
                            {{ $branch->location }}, {{ $branch->city }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="date">Tanggal</label>
                <input type="date" name="date" id="date" value="{{ $request->date->format('Y-m-d') }}" required>
            </div>

            <div>
                <label for="unit">Jumlah Unit</label>
                <input type="number" name="unit" id="unit" value="{{ $request->unit }}" required min="1">
            </div>

            <div>
                <small>Biarkan kosong jika tidak ingin mengganti file</small>
                <label for="xlsx_file">Upload File Baru (.xlsx)</label>
                <input type="file" name="xlsx_file" id="xlsx_file" accept=".xlsx,.xls">
            </div>

            <div class="button-group">
                <a href="{{ route('requests.index') }}" class="btn-back">Kembali</a>
                <button type="submit" class="btn-submit">Simpan Perubahan</button>
            </div>
        </form>
    </div>
@endsection
