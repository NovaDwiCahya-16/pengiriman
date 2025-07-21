<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Datarekap;
use App\Models\RequestModel as Permintaan;
use App\Models\Branch;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPermintaan = Permintaan::count();
        $totalPengiriman = Datarekap::count();

        // Status pengiriman
        $statusCounts = Datarekap::select('status_pengiriman', DB::raw('count(*) as total'))
            ->whereIn('status_pengiriman', [
                'Tepat Waktu',
                'Terlambat',
                'Tidak Valid',
                'Dikirim Besok',
                'Dalam Pengiriman',
                'Menunggu Pengiriman'
            ])
            ->groupBy('status_pengiriman')
            ->pluck('total', 'status_pengiriman')
            ->toArray();

        $statusCountsFormatted = [
            'Tepat Waktu' => $statusCounts['Tepat Waktu'] ?? 0,
            'Terlambat' => $statusCounts['Terlambat'] ?? 0,
            'Tidak Valid' => $statusCounts['Tidak Valid'] ?? 0,
            'Dikirim Besok' => $statusCounts['Dikirim Besok'] ?? 0,
            'Dalam Pengiriman' => $statusCounts['Dalam Pengiriman'] ?? 0,
            'Menunggu Pengiriman' => $statusCounts['Menunggu Pengiriman'] ?? 0,
        ];

        // Status permintaan
        $statusPermintaan = Permintaan::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $statusPermintaanFormatted = [
            'Disetujui' => $statusPermintaan['Disetujui'] ?? 0,
            'Ditolak' => $statusPermintaan['Ditolak'] ?? 0,
            'Menunggu' => $statusPermintaan['Menunggu'] ?? 0,
        ];

        // Cabang + jumlah permintaan + icon dan warna box
        $cabangPermintaan = Branch::leftJoin('requests', 'branches.id', '=', 'requests.branch_id')
            ->select('branches.id', 'branches.location', DB::raw('count(requests.id) as total'))
            ->groupBy('branches.id', 'branches.location')
            ->get()
            ->map(function ($branch, $index) {
                $icons = ['bi-building', 'bi-shop', 'bi-geo-alt', 'bi-diagram-3', 'bi-house-door', 'bi-flag', 'bi-bank', 'bi-truck', 'bi-box', 'bi-bar-chart'];
                $branch->icon = $icons[$index % count($icons)];

                $hasDitolak = Permintaan::where('branch_id', $branch->id)->where('status', 'Ditolak')->exists();
                $branch->box_color = $hasDitolak ? '#e74c3c' : '#2ecc71';

                return $branch;
            });

        // === Tambahan: Data untuk stacked bar chart performa supir ===
        $supirPerforma = Datarekap::select('supir', 'status_pengiriman', DB::raw('count(*) as total'))
            ->whereNotNull('supir')
            ->where('supir', '!=', '')
            ->whereIn('status_pengiriman', [
                'Tepat Waktu',
                'Terlambat',
                'Tidak Valid',
                'Dikirim Besok',
                'Dalam Pengiriman',
                'Menunggu Pengiriman'
            ])
            ->groupBy('supir', 'status_pengiriman')
            ->get()
            ->groupBy('supir');

        $statusList = [
            'Tepat Waktu',
            'Terlambat',
            'Tidak Valid',
            'Dikirim Besok',
            'Dalam Pengiriman',
            'Menunggu Pengiriman'
        ];

        $chartSupirLabels = $supirPerforma->keys();
        $chartSupirData = [];

        foreach ($statusList as $status) {
            $dataPerStatus = [];

            foreach ($chartSupirLabels as $supir) {
                $jumlah = $supirPerforma[$supir]
                    ->firstWhere('status_pengiriman', $status)
                    ->total ?? 0;

                $dataPerStatus[] = $jumlah;
            }

            $chartSupirData[] = [
                'label' => $status,
                'data' => $dataPerStatus,
                'backgroundColor' => match ($status) {
                    'Tepat Waktu' => '#27ae60',
                    'Terlambat' => '#e74c3c',
                    'Tidak Valid' => '#9b59b6',
                    'Dikirim Besok' => '#f39c12',
                    'Dalam Pengiriman' => '#3498db',
                    'Menunggu Pengiriman' => '#7f8c8d',
                    default => '#bdc3c7',
                }
            ];
        }

        return view('welcome', compact(
            'totalPermintaan',
            'totalPengiriman',
            'statusCountsFormatted',
            'statusPermintaanFormatted',
            'cabangPermintaan',
            'chartSupirLabels',
            'chartSupirData'
        ));
    }
}
