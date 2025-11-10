<?php

namespace App\Http\Controllers;

use App\Models\SampahTerkelola;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardStatsController extends Controller
{
    public function getStats(Request $request)
    {
        $period = $request->get('period', 'daily');
        $query = SampahTerkelola::query();

        // Set date range based on period
        switch ($period) {
            case 'daily':
                $query->whereDate('tgl', Carbon::today());
                $groupBy = 'HOUR(tgl)';
                $labels = range(0, 23);
                $format = 'H:i';
                break;
            case 'weekly':
                $query->whereBetween('tgl', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                $groupBy = 'DATE(tgl)';
                $labels = range(0, 6);
                $format = 'D';
                break;
            case 'monthly':
                $query->whereMonth('tgl', Carbon::now()->month)
                      ->whereYear('tgl', Carbon::now()->year);
                $groupBy = 'DATE(tgl)';
                $labels = range(1, Carbon::now()->daysInMonth);
                $format = 'd M';
                break;
        }

        // Get distribution by type
        $distribution = $query->clone()
            ->join('jenis', 'sampah_terkelolas.id_jenis', '=', 'jenis.id')
            ->select('jenis.nama_jenis', DB::raw('SUM(jumlah_berat) as total'))
            ->groupBy('jenis.id', 'jenis.nama_jenis')
            ->pluck('total', 'nama_jenis')
            ->toArray();

        // Get trend data
        $trend = [
            'labels' => [],
            'values' => array_fill(0, count($labels), 0)
        ];

        $trendData = $query->clone()
            ->select(DB::raw("$groupBy as date"), DB::raw('SUM(jumlah_berat) as total'))
            ->groupBy(DB::raw($groupBy))
            ->get();

        foreach ($labels as $i => $label) {
            if ($period === 'daily') {
                $trend['labels'][] = sprintf("%02d:00", $label);
                foreach ($trendData as $data) {
                    if ((int)$data->date === $label) {
                        $trend['values'][$i] = $data->total;
                    }
                }
            } elseif ($period === 'weekly') {
                $date = Carbon::now()->startOfWeek()->addDays($label);
                $trend['labels'][] = $date->format('D');
                foreach ($trendData as $data) {
                    if (Carbon::parse($data->date)->dayOfWeek === $date->dayOfWeek) {
                        $trend['values'][$i] = $data->total;
                    }
                }
            } else {
                $date = Carbon::now()->startOfMonth()->addDays($label - 1);
                $trend['labels'][] = $date->format('d M');
                foreach ($trendData as $data) {
                    if (Carbon::parse($data->date)->day === $date->day) {
                        $trend['values'][$i] = $data->total;
                    }
                }
            }
        }

        return response()->json([
            'distribution' => $distribution,
            'trend' => $trend
        ]);
    }
}