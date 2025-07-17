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
            color: #2c3e50;
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
            padding: 18px 22px;
            border-radius: 10px;
            color: #333;
            line-height: 1.6;
        }

        .info-box strong {
            color: #1c7ed6;
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
                <p>Anda memiliki akses penuh untuk mengelola semua data seperti <strong>permintaan kirim</strong>, <strong>slot pengiriman</strong>, dan <strong>data rekap</strong> Silakan klik menu dashboard untuk melihat tampilan utama dan gunakan menu disamping untuk memulai.</p>
            @else
                <p>Anda hanya dapat <strong>menambah dan melihat data permintaan kirim</strong>. Silakan gunakan menu di samping untuk mengakses fitur dan klik <strong>Dashboard</strong> untuk melihat tampilan utama.</p>
            @endif
        </div>
    </div>
@endsection
