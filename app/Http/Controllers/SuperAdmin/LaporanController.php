<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SampahTerkelola;
use App\Models\SampahDiserahkan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $periode = $request->get('periode', date('Y-m'));
        $startDate = Carbon::createFromFormat('Y-m', $periode)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $periode)->endOfMonth();

        $preview = $this->getLaporanData($startDate, $endDate);
        $totals = $this->calculateTotals($preview);

        return view('superadmin.laporan.laporan', compact('preview', 'totals'));
    }

    public function export(Request $request)
    {
        $periode = $request->get('periode', date('Y-m'));
        $startDate = Carbon::createFromFormat('Y-m', $periode)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $periode)->endOfMonth();

        $data = $this->getLaporanData($startDate, $endDate);
        $totals = $this->calculateTotals($data);

        $fileName = 'Laporan_Pengelolaan_Sampah_' . $periode . '.xlsx';
        return Excel::download(new LaporanExport($data, $totals), $fileName);
    }

    private function getLaporanData($startDate, $endDate)
    {
        $data = [];
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            $terkelola = SampahTerkelola::whereDate('created_at', $currentDate->format('Y-m-d'))
                ->selectRaw('jenis_id, SUM(berat) as total')
                ->groupBy('jenis_id')
                ->pluck('total', 'jenis_id')
                ->toArray();

            $diserahkan = SampahDiserahkan::whereDate('created_at', $currentDate->format('Y-m-d'))
                ->selectRaw('jenis_id, SUM(berat) as total')
                ->groupBy('jenis_id')
                ->pluck('total', 'jenis_id')
                ->toArray();

            // Calculate timbunan (total waste) as terkelola + diserahkan
            $timbunan = [];
            foreach ([1, 2, 3] as $jenisId) {
                $timbunan[$jenisId] = ($terkelola[$jenisId] ?? 0) + ($diserahkan[$jenisId] ?? 0);
            }

            $row = [
                'tanggal' => $currentDate->format('Y-m-d'),
                'timbunan_organik' => $timbunan[1] ?? 0,
                'timbunan_anorganik' => $timbunan[2] ?? 0,
                'timbunan_residu' => $timbunan[3] ?? 0,
                'terkelola_organik' => $terkelola[1] ?? 0,
                'terkelola_anorganik' => $terkelola[2] ?? 0,
                'terkelola_residu' => $terkelola[3] ?? 0,
                'diserahkan_organik' => $diserahkan[1] ?? 0,
                'diserahkan_anorganik' => $diserahkan[2] ?? 0,
                'diserahkan_residu' => $diserahkan[3] ?? 0,
            ];

            $row['total_per_hari'] = array_sum($timbunan);
            $data[] = $row;

            $currentDate->addDay();
        }

        return $data;
    }

    private function calculateTotals($data)
    {
        $totals = [
            'timbunan_organik' => 0,
            'timbunan_anorganik' => 0,
            'timbunan_residu' => 0,
            'terkelola_organik' => 0,
            'terkelola_anorganik' => 0,
            'terkelola_residu' => 0,
            'diserahkan_organik' => 0,
            'diserahkan_anorganik' => 0,
            'diserahkan_residu' => 0,
            'total' => 0,
        ];

        foreach ($data as $row) {
            foreach ($totals as $key => &$value) {
                if ($key !== 'total') {
                    $value += $row[$key];
                }
            }
        }

        $totals['total'] = $totals['timbunan_organik'] + 
                          $totals['timbunan_anorganik'] + 
                          $totals['timbunan_residu'];

        return $totals;
    }
}