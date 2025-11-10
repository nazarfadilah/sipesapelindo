<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\SampahTerkelola;
use App\Models\SampahDiserahkan;
use App\Models\LokasiAsal;
use App\Exports\LaporanMultiSheetExport;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman Laporan
     */
    public function index(Request $request)
    {
        return view('admin.laporan.laporan');
    }

    /**
     * Generate dan download Excel report
     */
    public function generateExcel(Request $request)
    {
        $year = $request->input('year', date('Y'));
        
        // Get all lokasi
        $lokasis = LokasiAsal::all();

        // Prepare data arrays
        $rekapNeracaData = $this->prepareRekapNeracaData($year);
        $rekapTerkelolaData = $this->prepareRekapTerkelolaData($year);
        $rekapAreaData = $this->prepareRekapAreaData($year, $lokasis);
        $dailyData = $this->prepareDailyData($year, $lokasis);

        return Excel::download(
            new LaporanMultiSheetExport(
                $rekapNeracaData,
                $rekapTerkelolaData,
                $rekapAreaData,
                $dailyData
            ),
            'Laporan_Pengelolaan_Sampah_' . $year . '.xlsx'
        );
    }

    /**
     * Tampilkan preview rekap neraca
     */
    public function rekapNeraca(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $rekapData = $this->prepareRekapNeracaData($year);
        
        return view('admin.laporan.laporan', [
            'rekapData' => $rekapData['data'],
            'totals' => $rekapData['totals'],
            'viewType' => 'neraca'
        ]);
    }

    /**
     * Tampilkan preview rekap terkelola
     */
    public function rekapTerkelola(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $rekapData = $this->prepareRekapTerkelolaData($year);
        
        return view('admin.laporan.laporan', [
            'rekapData' => $rekapData['data'],
            'totals' => $rekapData['totals'],
            'viewType' => 'terkelola'
        ]);
    }

    /**
     * Tampilkan preview rekap area
     */
    public function rekapArea(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $lokasis = LokasiAsal::all();
        $rekapData = $this->prepareRekapAreaData($year, $lokasis);
        
        return view('admin.laporan.laporan', [
            'rekapData' => $rekapData['data'],
            'totals' => $rekapData['totals'],
            'lokasis' => $lokasis,
            'viewType' => 'area'
        ]);
    }

    /**
     * Tampilkan preview data harian
     */
    public function rekapDaily(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $lokasis = LokasiAsal::all();
        $rekapData = $this->prepareDailyData($year, $lokasis);
        
        return view('admin.laporan.laporan', [
            'rekapData' => $rekapData['dailyData'],
            'lokasis' => $lokasis,
            'viewType' => 'daily'
        ]);
    }
    /**
     * Prepare data for Rekap Neraca sheet
     */
    private function prepareRekapNeracaData($year)
    {
        $data = [];
        $totals = [
            'total_diserahkan' => 0,
            'total_terkelola' => 0
        ];

        $diserahkanData = SampahDiserahkan::whereYear('tgl', $year)
            ->selectRaw('MONTH(tgl) as month, sum(jumlah_berat) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $terkelolaData = SampahTerkelola::whereYear('tgl', $year)
            ->selectRaw('MONTH(tgl) as month, sum(jumlah_berat) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        for ($month = 1; $month <= 12; $month++) {
            $diserahkan = $diserahkanData->where('month', $month)->first();
            $terkelola = $terkelolaData->where('month', $month)->first();

            $data[] = [
                'bulan' => Carbon::create($year, $month, 1)->format('F'),
                'diserahkan' => $diserahkan ? $diserahkan->total : 0,
                'terkelola' => $terkelola ? $terkelola->total : 0
            ];

            $totals['total_diserahkan'] += $diserahkan ? $diserahkan->total : 0;
            $totals['total_terkelola'] += $terkelola ? $terkelola->total : 0;
        }

        return [
            'data' => $data,
            'totals' => $totals
        ];
    }

    /**
     * Prepare data for Rekap Terkelola sheet
     */
    private function prepareRekapTerkelolaData($year)
    {
        $data = [];
        $totals = [
            'total_recycling' => 0,
            'total_reuse' => 0,
            'total_reduce' => 0,
            'total_tpa' => 0
        ];

        $monthlyData = SampahTerkelola::with('tujuan_sampah')
            ->whereYear('tgl', $year)
            ->get()
            ->groupBy(function($item) {
                return Carbon::parse($item->tgl)->format('n');
            });

        for ($month = 1; $month <= 12; $month++) {
            $monthData = $monthlyData->get($month, collect([]));
            
            $recycling = $monthData->sum(function($item) {
                return $item->tujuan_sampah && $item->tujuan_sampah->kategori === 'recycling' ? $item->jumlah_berat : 0;
            });
            $reuse = $monthData->sum(function($item) {
                return $item->tujuan_sampah && $item->tujuan_sampah->kategori === 'reuse' ? $item->jumlah_berat : 0;
            });
            $reduce = $monthData->sum(function($item) {
                return $item->tujuan_sampah && $item->tujuan_sampah->kategori === 'reduce' ? $item->jumlah_berat : 0;
            });
            $tpa = $monthData->sum(function($item) {
                return $item->tujuan_sampah && $item->tujuan_sampah->kategori === 'tpa' ? $item->jumlah_berat : 0;
            });

            $data[] = [
                'bulan' => Carbon::create($year, $month, 1)->format('F'),
                'recycling' => $recycling,
                'reuse' => $reuse,
                'reduce' => $reduce,
                'tpa' => $tpa
            ];

            $totals['total_recycling'] += $recycling;
            $totals['total_reuse'] += $reuse;
            $totals['total_reduce'] += $reduce;
            $totals['total_tpa'] += $tpa;
        }

        return [
            'data' => $data,
            'totals' => $totals
        ];
    }

    /**
     * Prepare data for Rekap Area sheet
     */
    private function prepareRekapAreaData($year, $lokasis)
    {
        $data = [];
        $totals = [];

        foreach ($lokasis as $lokasi) {
            $totals[$lokasi->id] = 0;
        }

        $monthlyData = SampahDiserahkan::with('lokasi_asal')
            ->whereYear('tgl', $year)
            ->get()
            ->groupBy(function($item) {
                return Carbon::parse($item->tgl)->format('n');
            });

        for ($month = 1; $month <= 12; $month++) {
            $monthData = [
                'bulan' => Carbon::create($year, $month, 1)->format('F')
            ];

            $monthRecords = $monthlyData->get($month, collect([]));

            foreach ($lokasis as $lokasi) {
                $amount = $monthRecords->sum(function($item) use ($lokasi) {
                    return $item->lokasi_asal && $item->lokasi_asal->id === $lokasi->id ? $item->jumlah_berat : 0;
                });
                $monthData[$lokasi->id] = $amount;
                $totals[$lokasi->id] += $amount;
            }

            $data[] = $monthData;
        }

        return [
            'data' => $data,
            'totals' => $totals,
            'lokasis' => $lokasis
        ];
    }

    /**
     * Prepare data for daily sheets (one per month)
     */
    private function prepareDailyData($year, $lokasis)
    {
        $dailyData = [];

        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::create($year, $month, 1);
            $endDate = $startDate->copy()->endOfMonth();
            
            // Get daily data for the month
            $sampahDiserahkan = SampahDiserahkan::with(['lokasi_asal'])
            ->whereBetween('tgl', [$startDate, $endDate])
            ->get()
            ->groupBy('tgl');

            $sampahTerkelola = SampahTerkelola::with(['tujuan_sampah'])
                ->whereBetween('tgl', [$startDate, $endDate])
                ->get()
                ->groupBy('tgl');            $monthData = [];
            $totalsNeraca = [
                'total_diserahkan' => 0,
                'total_terkelola' => 0
            ];
            $totalsArea = [];

            foreach ($lokasis as $lokasi) {
                $totalsArea[$lokasi->id] = 0;
            }

            // Process each day of the month
            for ($day = 1; $day <= $endDate->day; $day++) {
                $currentDate = Carbon::create($year, $month, $day);
                $dateStr = $currentDate->format('Y-m-d');
                
                $dayData = [
                    'tanggal' => $currentDate->format('d F Y'),
                    'diserahkan' => 0,
                    'terkelola' => 0
                ];

                // Add area data
                foreach ($lokasis as $lokasi) {
                    $dayData['area_' . $lokasi->id] = 0;
                }

                // Process diserahkan data
                if ($sampahDiserahkan->has($dateStr)) {
                    $diserahkanAmount = $sampahDiserahkan[$dateStr]->sum('jumlah');
                    $dayData['diserahkan'] = $diserahkanAmount;
                    $totalsNeraca['total_diserahkan'] += $diserahkanAmount;

                    // Process area data
                    foreach ($lokasis as $lokasi) {
                        $areaAmount = $sampahDiserahkan[$dateStr]
                            ->where('lokasi_asal_id', $lokasi->id)
                            ->sum('jumlah');
                        $dayData['area_' . $lokasi->id] = $areaAmount;
                        $totalsArea[$lokasi->id] += $areaAmount;
                    }
                }

                // Process terkelola data
                if ($sampahTerkelola->has($dateStr)) {
                    $terkelolaAmount = $sampahTerkelola[$dateStr]->sum('jumlah');
                    $dayData['terkelola'] = $terkelolaAmount;
                    $totalsNeraca['total_terkelola'] += $terkelolaAmount;
                }

                $monthData[] = $dayData;
            }

            $dailyData[Carbon::create($year, $month, 1)->format('F')] = [
                'data' => $monthData,
                'totals_neraca' => $totalsNeraca,
                'totals_area' => $totalsArea
            ];
        }

        return [
            'dailyData' => $dailyData,
            'lokasis' => $lokasis
        ];
    }

    /**
     * Fungsi helper untuk mengambil data rekap 12 bulan.
     * @param Carbon $startDate (contoh: 2024-07-01)
     * @return array
     */
    private function getRekapDataTahunan(Carbon $startDate)
    {
        $dataRekap = [];
        $grandTotals = [
            'timbulan_organik' => 0, 'timbulan_anorganik' => 0, 'timbulan_residu' => 0, 'total_timbulan' => 0,
            'terkelola_organik' => 0, 'terkelola_anorganik' => 0, 'terkelola_residu' => 0, 'total_terkelola' => 0,
            'diserahkan_organik' => 0, 'diserahkan_anorganik' => 0, 'diserahkan_residu' => 0, 'total_diserahkan' => 0,
        ];

        // Dapatkan nama jenis sampah dari database Anda
        // Asumsi: 1=Organik, 2=Anorganik, 3=Residu. Sesuaikan jika ID-nya berbeda.
        // Anda bisa ganti 'id' dengan 'nama_jenis' jika lebih mudah
        $jenisMapping = [
            'organik' => 1, // Ganti 1 dengan ID 'Organik' di tabel 'jenis'
            'anorganik' => 2, // Ganti 2 dengan ID 'Anorganik'
            'residu' => 3, // Ganti 3 dengan ID 'Residu'
        ];

        // Loop 12 kali (12 bulan)
        for ($i = 0; $i < 12; $i++) {
            $currentMonthStart = $startDate->copy()->addMonths($i);
            $currentMonthEnd = $currentMonthStart->copy()->endOfMonth();
            $monthName = $currentMonthStart->format('F Y');

            // 1. Ambil data 'sampah_terkelolas' (Terkelola)
            $terkelola = DB::table('sampah_terkelolas')
                ->whereBetween('tgl', [$currentMonthStart, $currentMonthEnd])
                ->select('id_jenis', DB::raw('SUM(jumlah_berat) as total'))
                ->groupBy('id_jenis')
                ->get()
                ->pluck('total', 'id_jenis');

            // 2. Ambil data 'sampah_diserahkans' (Tidak Terkelola/Diserahkan)
            $diserahkan = DB::table('sampah_diserahkans')
                ->whereBetween('tgl', [$currentMonthStart, $currentMonthEnd])
                ->select('id_jenis', DB::raw('SUM(jumlah_berat) as total'))
                ->groupBy('id_jenis')
                ->get()
                ->pluck('total', 'id_jenis');

            // 3. Siapkan data per baris (per bulan)
            $rowData = ['bulan' => $monthName];

            $totalTimbulanBulan = 0;
            $totalTerkelolaBulan = 0;
            $totalDiserahkanBulan = 0;

            foreach ($jenisMapping as $key => $id) {
                $terkelolaBulan = (float)($terkelola[$id] ?? 0);
                $diserahkanBulan = (float)($diserahkan[$id] ?? 0);
                $timbulanBulan = $terkelolaBulan + $diserahkanBulan;

                $rowData["timbulan_$key"] = $timbulanBulan;
                $rowData["terkelola_$key"] = $terkelolaBulan;
                $rowData["diserahkan_$key"] = $diserahkanBulan;

                $totalTimbulanBulan += $timbulanBulan;
                $totalTerkelolaBulan += $terkelolaBulan;
                $totalDiserahkanBulan += $diserahkanBulan;

                // Akumulasi ke Grand Total
                $grandTotals["timbulan_$key"] += $timbulanBulan;
                $grandTotals["terkelola_$key"] += $terkelolaBulan;
                $grandTotals["diserahkan_$key"] += $diserahkanBulan;
            }

            $rowData['total_timbulan'] = $totalTimbulanBulan;
            $rowData['total_terkelola'] = $totalTerkelolaBulan;
            $rowData['total_diserahkan'] = $totalDiserahkanBulan;

            $dataRekap[] = $rowData;
        }

        // Hitung Grand Total keseluruhan
        $grandTotals['total_timbulan'] = $grandTotals['timbulan_organik'] + $grandTotals['timbulan_anorganik'] + $grandTotals['timbulan_residu'];
        $grandTotals['total_terkelola'] = $grandTotals['terkelola_organik'] + $grandTotals['terkelola_anorganik'] + $grandTotals['terkelola_residu'];
        $grandTotals['total_diserahkan'] = $grandTotals['diserahkan_organik'] + $grandTotals['diserahkan_anorganik'] + $grandTotals['diserahkan_residu'];

        return ['data' => $dataRekap, 'totals' => $grandTotals];
    }
}