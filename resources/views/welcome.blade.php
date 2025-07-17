@extends('layouts.app')

@section('title', 'Dashboard Permintaan & Pengiriman')

@section('content')
<style>
    h1 {
        font-size: 1.6em;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 25px;
    }

    .action-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 25px;
    }

    .dashboard-link {
        padding: 10px 18px;
        border: none;
        border-radius: 8px;
        color: white;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-blue { background-color: #3498db; }
    .btn-green { background-color: #2ecc71; }
    .btn-orange { background-color: #e67e22; }
    .btn-purple { background-color: #9b59b6; }

    .dashboard-link:hover {
        opacity: 0.9;
        transform: translateY(-2px);
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-box {
        background-color: #fff;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        text-align: center;
        transition: all 0.2s ease;
    }

    .stat-box h2 {
        font-size: 2em;
        margin-bottom: 6px;
        color: #2c3e50;
    }

    .stat-box p {
        font-size: 1em;
        color: #7f8c8d;
        margin: 0;
    }

    .stat-box:hover {
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.07);
        transform: translateY(-3px);
    }

    .chart-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 30px;
    }

    .chart-card {
        background: #fff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        max-width: 500px;
        flex: 1;
    }

    .chart-card h5 {
        font-weight: 600;
        font-size: 1.1em;
        text-align: center;
        margin-bottom: 20px;
    }

    .chart-wrapper {
        height: 320px;
    }

    canvas {
        width: 100% !important;
        height: 100% !important;
    }
</style>

<h1>Dashboard Permintaan & Pengiriman</h1>

<div class="action-buttons">
    <a href="{{ route('requests.index') }}" class="dashboard-link btn-purple"><i class="bi bi-eye"></i> Lihat Data Permintaan</a>
    <a href="{{ route('datarekaps.index') }}" class="dashboard-link btn-green"><i class="bi bi-eye"></i> Lihat Data Rekap</a>
    <a href="{{ route('slot-deliveries.index') }}" class="dashboard-link btn-orange"><i class="bi bi-truck"></i> Lihat Slot Pengiriman</a>
</div>

<div class="summary-grid">
    <div class="stat-box">
        <h2>{{ number_format($totalPermintaan, 0, ',', '.') }}</h2>
        <p>Total Permintaan</p>
    </div>
    <div class="stat-box">
        <h2>{{ number_format($totalPengiriman, 0, ',', '.') }}</h2>
        <p>Total Rekap</p>
    </div>
</div>

<div class="chart-container">
    <div class="chart-card">
        <h5>Status Pengiriman</h5>
        <div class="chart-wrapper">
            <canvas id="statusChart"></canvas>
        </div>
    </div>

    <div class="chart-card">
        <h5>Status Permintaan</h5>
        <div class="chart-wrapper">
            <canvas id="permintaanChart"></canvas>
        </div>
    </div>

    <div class="chart-card">
        <h5>Performa Supir</h5>
        <div class="chart-wrapper">
            <canvas id="supirChart"></canvas>
        </div>
    </div>
</div>

<h4 style="margin-top:30px;">Permintaan per Cabang</h4>
<div class="summary-grid">
    @foreach($cabangPermintaan as $item)
        @php
            // Ambil status terakhir permintaan cabang tsb
            $statusTerakhir = \App\Models\Request::where('branch_id', $item->id)->latest()->value('status') ?? 'unknown';

            $borderColor = match(strtolower($statusTerakhir)) {
                'disetujui' => '#2ecc71', // Hijau
                'ditolak' => '#e74c3c',   // Merah
                'menunggu' => '#f1c40f',  // Kuning
                default => '#bdc3c7',     // Abu (kosong/unknown)
            };
        @endphp
        <a href="{{ route('requests.index', ['branch' => $item->id]) }}" class="text-decoration-none">
            <div class="stat-box" style="border: 3px solid {{ $borderColor }};">
                <h2>{{ $item->total }}</h2>
                <p>{{ $item->location }}</p>
            </div>
        </a>
    @endforeach
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart Status Pengiriman
    new Chart(document.getElementById('statusChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: [
                'Tepat Waktu',
                'Terlambat',
                'Tidak Valid',
                'Dikirim Besok',
                'Dalam Pengiriman',
                'Menunggu Pengiriman'
            ],
            datasets: [{
                label: 'Jumlah',
                data: [
                    {{ $statusCountsFormatted['Tepat Waktu'] }},
                    {{ $statusCountsFormatted['Terlambat'] }},
                    {{ $statusCountsFormatted['Tidak Valid'] }},
                    {{ $statusCountsFormatted['Dikirim Besok'] }},
                    {{ $statusCountsFormatted['Dalam Pengiriman'] }},
                    {{ $statusCountsFormatted['Menunggu Pengiriman'] }},
                ],
                backgroundColor: [
                    '#27ae60',
                    '#e74c3c',
                    '#9b59b6',
                    '#f39c12',
                    '#3498db',
                    '#7f8c8d'
                ],
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: context => `${context.raw} unit`
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    }
                }
            }
        }
    });

    // Chart Status Permintaan
    new Chart(document.getElementById('permintaanChart').getContext('2d'), {
        type: 'pie',
        data: {
            labels: ['Disetujui', 'Ditolak', 'Menunggu'],
            datasets: [{
                data: [
                    {{ $statusPermintaanFormatted['Disetujui'] }},
                    {{ $statusPermintaanFormatted['Ditolak'] }},
                    {{ $statusPermintaanFormatted['Menunggu'] }}
                ],
                backgroundColor: ['#2ecc71', '#e74c3c', '#f1c40f'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: {
                    callbacks: {
                        label: context => `${context.label}: ${context.raw} permintaan`
                    }
                }
            }
        }
    });

    // Chart Performa Supir
    new Chart(document.getElementById('supirChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: @json($chartSupirLabels),
            datasets: @json($chartSupirData)
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top' },
                tooltip: {
                    callbacks: {
                        label: context => `${context.dataset.label}: ${context.raw}`
                    }
                }
            },
            scales: {
                x: { stacked: true },
                y: { stacked: true, beginAtZero: true }
            }
        }
    });
</script>
@endsection
