<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Ganti favicon -->
    <link rel="icon" type="image/png" href="{{ asset('logo/logo.png') }}" sizes="32x32">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f1f1f1;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background: #fff;
            padding: 30px 25px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            position: relative;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            color: #333;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="file"] {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 18px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        input:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 4px rgba(52, 152, 219, 0.3);
        }

        button {
            background-color: #3498db;
            color: #fff;
            border: none;
            padding: 12px;
            font-size: 15px;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.2s ease;
        }

        button:hover {
            background-color: #2980b9;
        }

        .image-preview {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            display: block;
            margin: 15px auto 20px;
            border: 2px solid #ccc;
            background-color: #f9f9f9;
        }

        .toast {
            position: absolute;
            top: -10px;
            left: 50%;
            transform: translate(-50%, -100%);
            background-color: #f44336;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 10;
        }

        .toast.show {
            opacity: 1;
        }

        .back-login {
            display: block;
            text-align: center;
            margin-top: 12px;
            font-size: 14px;
            color: #3498db;
            text-decoration: none;
        }

        .back-login:hover {
            text-decoration: underline;
        }

        @media (max-width: 400px) {
            .form-container {
                padding: 20px;
                border-radius: 8px;
            }
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Register</h2>

    {{-- Notifikasi Error --}}
    @if ($errors->any())
        <div class="toast" id="toast">
            {{ $errors->first() }}
        </div>
    @elseif (session('success'))
        <div class="toast" id="toast" style="background-color: #28a745;">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('register.submit') }}" enctype="multipart/form-data">
        @csrf

        <!-- Preview Foto Profil -->
        <img id="preview" class="image-preview"
             src="{{ asset('images/default-profile.png') }}"
             alt="Foto Profil">

        <label for="profile_photo">Foto Profil</label>
        <input type="file" id="profile_photo" name="profile_photo" accept="image/*" onchange="previewImage(event)">

        <label for="name">Nama Lengkap</label>
        <input type="text" id="name" name="name" required value="{{ old('name') }}">

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required value="{{ old('email') }}">

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <label for="password_confirmation">Konfirmasi Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required>

        <button type="submit">Daftar</button>

        <a href="{{ route('login') }}" class="back-login">Kembali ke Login</a>
    </form>
</div>

<script>
    function previewImage(event) {
        const preview = document.getElementById('preview');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = () => preview.src = reader.result;
            reader.readAsDataURL(file);
        }
    }

    // Toast popup auto-close
    window.onload = function () {
        const toast = document.getElementById('toast');
        if (toast) {
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 4000);
        }
    }
</script>

</body>
</html>
