@extends('layouts.app')

@section('title', 'Ubah Profil')

@section('content')
    <style>
        .profile-card {
            max-width: 650px;
            margin: 30px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
            font-family: 'Segoe UI', sans-serif;
        }

        .profile-card h2 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 25px;
            color: #343a40;
        }

        .form-label {
            font-weight: 500;
            margin-top: 15px;
        }

        .form-control {
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ced4da;
            margin-bottom: 15px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            margin-top: 30px;
            margin-bottom: 15px;
            color: #495057;
        }

        .btn-submit {
            background-color: #0d6efd;
            border: none;
            padding: 12px 20px;
            color: white;
            font-weight: 600;
            border-radius: 8px;
            margin-top: 20px;
            width: 100%;
        }

        .btn-submit:hover {
            background-color: #0b5ed7;
        }

        .alert {
            font-size: 14px;
        }
    </style>

    <div class="profile-card">
        <h2><i class="bi bi-person-lines-fill"></i> Ubah Profil</h2>

        @if (session('status') === 'profile-updated')
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i> Profil berhasil diperbarui!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf

            <label for="name" class="form-label">Nama Lengkap</label>
            <input type="text" name="name" id="name" class="form-control"
                value="{{ old('name', $user->name) }}" required>

            <label for="email" class="form-label">Alamat Email</label>
            <input type="email" name="email" id="email" class="form-control"
                value="{{ old('email', $user->email) }}" required>

            <label for="profile_photo" class="form-label">Foto Profil (opsional)</label>
            <input type="file" name="profile_photo" id="profile_photo" class="form-control"
                accept="image/png, image/jpg, image/jpeg">

            <div class="section-title">Ganti Password (Opsional)</div>

            <label for="current_password" class="form-label">Password Lama</label>
            <input type="password" name="current_password" id="current_password" class="form-control">

            <label for="new_password" class="form-label">Password Baru</label>
            <input type="password" name="new_password" id="new_password" class="form-control">

            <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
            <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                class="form-control">

            <button type="submit" class="btn btn-submit">
                <i class="bi bi-save"></i> Simpan Perubahan
            </button>
        </form>
    </div>
@endsection
