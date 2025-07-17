@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <style>
        .dashboard-wrapper {
            max-width: 800px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 12px;
            padding: 30px 40px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .dashboard-header {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .dashboard-subtext {
            font-size: 16px;
            color: #555;
            margin-bottom: 25px;
        }

        .highlight {
            font-weight: bold;
            color: #007bff;
        }

        .info-box {
            background: #f1f5f9;
            border-left: 6px solid #007bff;
            padding: 15px 20px;
            border-radius: 8px;
            color: #333;
        }

        .info-box p {
            margin: 0;
            font-size: 15px;
        }
    </style>

    <div class="dashboard-wrapper">
        <div class="dashboard-header">
            Halo, <span class="highlight">{{ auth()->user()->name }}</span>!
        </div>

        <div class="dashboard-subtext">
            Anda login sebagai <span class="highlight">{{ auth()->user()->type == 1 ? 'Admin' : 'User' }}</span>.
        </div>

        <div class="info-box">
            @if (auth()->user()->type == 1)
                <p>Anda memiliki akses penuh untuk mengelola semua data, termasuk permintaan, slot pengiriman, dan data
                    rekap.</p>
            @else
                <p>Anda hanya dapat <strong>menambah dan melihat data permintaan kirim, data rekap dan slot pengiriman hak akses Admin</strong>
                    Silakan klik menu dashboard untuk melihat tampilan utama dan gunakan menu disamping untuk memulai.</p>
            @endif
        </div>
    </div>
@endsection
