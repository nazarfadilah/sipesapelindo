<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SampahTerkelola;
use App\Models\SampahDiserahkan;
use App\Models\Jenis;
use App\Models\LokasiAsal;
use App\Models\TujuanSampah;
use App\Models\User;
use App\Models\Dokumen;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SuperAdminController extends Controller
{
    /**
     * Menampilkan dashboard super admin dengan data statistik
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function dashboard(Request $request)
    {
        // Mengambil parameter filter
        $filterType = $request->get('filter_type', 'year');
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        $week = $request->get('week', 1);
        $day = $request->get('day', date('Y-m-d'));
        $dataType = $request->get('data_type', 'both'); // both, terkelola, diserahkan
        
        // Query dasar untuk sampah terkelola dan diserahkan
        $queryTerkelola = SampahTerkelola::query();
        $queryDiserahkan = SampahDiserahkan::query();
        
        // Determine date range: support explicit start_date/end_date, fiscal (Jul-Jun), or existing filters
        $useDateRange = false;
        $startDate = null;
        $endDate = null;

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->get('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->get('end_date'))->endOfDay();
            $useDateRange = true;
        } elseif ($filterType === 'fiscal') {
            // fiscal_year param expected to be the ending year (e.g., 2025 means Jul 2024 - Jun 2025)
            $fiscalEnd = $request->get('fiscal_year', (Carbon::now()->month >= 7 ? Carbon::now()->year : Carbon::now()->year - 1));
            $startDate = Carbon::create($fiscalEnd - 1, 7, 1)->startOfDay();
            $endDate = Carbon::create($fiscalEnd, 6, 30)->endOfDay();
            $useDateRange = true;
        } elseif (!$request->filled('filter_type')) {
            // Default: when page first accessed with no filters, show previous fiscal year
            $now = Carbon::now();
            // previous fiscal end year: if current month is July or after, previous fiscal end is current year,
            // otherwise it's last year. Example: Nov 2025 -> previous fiscal is Jul 2024 - Jun 2025 (fiscalEnd=2025)
            $fiscalEnd = ($now->month >= 7) ? $now->year : $now->year - 1;
            $startDate = Carbon::create($fiscalEnd - 1, 7, 1)->startOfDay();
            $endDate = Carbon::create($fiscalEnd, 6, 30)->endOfDay();
            $useDateRange = true;
            // also mark filterType so other UI can reflect it if needed
            $filterType = 'fiscal';
        } else {
            // existing granular filters (year/month/week/day) will be applied later
        }

        // Apply date range to base queries if needed
        if ($useDateRange) {
            $queryTerkelola->whereBetween('tgl', [$startDate, $endDate]);
            $queryDiserahkan->whereBetween('tgl', [$startDate, $endDate]);
        } else {
            // Menerapkan filter berdasarkan pilihan (year/month/week/day)
            if ($filterType == 'year') {
                $queryTerkelola->whereYear('tgl', $year);
                $queryDiserahkan->whereYear('tgl', $year);
            } elseif ($filterType == 'month') {
                $queryTerkelola->whereYear('tgl', $year)->whereMonth('tgl', $month);
                $queryDiserahkan->whereYear('tgl', $year)->whereMonth('tgl', $month);
            } elseif ($filterType == 'week') {
                $startWeek = Carbon::create($year, $month, 1)->startOfMonth();
                $weekStart = $startWeek->copy()->addDays(($week - 1) * 7);
                $weekEnd = $weekStart->copy()->addDays(6);
                $queryTerkelola->whereBetween('tgl', [$weekStart, $weekEnd]);
                $queryDiserahkan->whereBetween('tgl', [$weekStart, $weekEnd]);
            } elseif ($filterType == 'day') {
                $queryTerkelola->whereDate('tgl', $day);
                $queryDiserahkan->whereDate('tgl', $day);
            }
        }
        
        // helper to apply either date range or granular filters
        $applyDateFilter = function($query) use ($useDateRange, $startDate, $endDate, $filterType, $year, $month, $week, $day) {
            if ($useDateRange) {
                $query->whereBetween('tgl', [$startDate, $endDate]);
                return;
            }
            if ($filterType == 'year') {
                $query->whereYear('tgl', $year);
            } elseif ($filterType == 'month') {
                $query->whereYear('tgl', $year)->whereMonth('tgl', $month);
            } elseif ($filterType == 'week') {
                $startDateW = Carbon::create($year, $month, 1)->startOfMonth();
                $weekStart = $startDateW->copy()->addDays(($week - 1) * 7);
                $weekEnd = $weekStart->copy()->addDays(6);
                $query->whereBetween('tgl', [$weekStart, $weekEnd]);
            } elseif ($filterType == 'day') {
                $query->whereDate('tgl', $day);
            }
        };

        // Mendapatkan semua jenis sampah untuk pie chart
        $jenisSampah = Jenis::all();
        $jenisColors = ['#FF0000', '#00FF00', '#FFFF00', '#0000FF', '#FF00FF', '#00FFFF', '#FF9900', '#9900FF', '#009900'];
        $jenisTotals = [];
        
        // Hitung total berdasarkan tipe data yang dipilih
        $totalSampah = 0;
        if ($dataType == 'both' || $dataType == 'terkelola') {
            $totalSampah += $queryTerkelola->sum('jumlah_berat');
        }
        if ($dataType == 'both' || $dataType == 'diserahkan') {
            $totalSampah += $queryDiserahkan->sum('jumlah_berat');
        }
        
        foreach ($jenisSampah as $index => $jenis) {
            $totalJenis = 0;
            
            if ($dataType == 'both' || $dataType == 'terkelola') {
                $totalJenis += SampahTerkelola::where('id_jenis', $jenis->id)
                    ->where(function($query) use ($applyDateFilter) {
                        $applyDateFilter($query);
                    })
                    ->sum('jumlah_berat');
            }

            if ($dataType == 'both' || $dataType == 'diserahkan') {
                $totalJenis += SampahDiserahkan::where('id_jenis', $jenis->id)
                    ->where(function($query) use ($applyDateFilter) {
                        $applyDateFilter($query);
                    })
                    ->sum('jumlah_berat');
            }
            
            $jenisTotals[] = $totalSampah > 0 ? round(($totalJenis / $totalSampah) * 100, 1) : 0;
        }
        
        // Mendapatkan semua lokasi asal untuk bar chart
        $lokasiAsals = LokasiAsal::all();
        $lokasiTotals = [];
        
        foreach ($lokasiAsals as $lokasi) {
            $totalLokasi = 0;
            
            if ($dataType == 'both' || $dataType == 'terkelola') {
                $totalLokasi += SampahTerkelola::where('id_lokasi', $lokasi->id)
                    ->where(function($query) use ($applyDateFilter) {
                        $applyDateFilter($query);
                    })
                    ->sum('jumlah_berat');
            }

            if ($dataType == 'both' || $dataType == 'diserahkan') {
                $totalLokasi += SampahDiserahkan::where('id_lokasi', $lokasi->id)
                    ->where(function($query) use ($applyDateFilter) {
                        $applyDateFilter($query);
                    })
                    ->sum('jumlah_berat');
            }

            $lokasiTotals[] = $totalLokasi;
        }
        
        // Data untuk tabel rekap
        $neraca = [];
        $totals = [
            'sampah_kg' => 0,
            'lb3_kg' => 0,
            'total_kg' => 0,
            'terkelola_kg' => 0,
            'persen_terkelola' => 0,
            'diserahkan_kg' => 0,
            'diserahkan_lb3_kg' => 0,
            'persen_diserahkan' => 0
        ];
        
        foreach ($lokasiAsals as $lokasi) {
            // Menghitung total sampah reguler dan LB3 berdasarkan jenis
            $sampahKg = SampahTerkelola::where('id_lokasi', $lokasi->id)
                ->whereHas('jenis', function ($query) {
                    $query->whereNotIn('nama_jenis', ['LB3']);
                })
                ->where(function($q) use ($applyDateFilter) {
                    $applyDateFilter($q);
                })
                ->sum('jumlah_berat');

            $lb3Kg = SampahTerkelola::where('id_lokasi', $lokasi->id)
                ->whereHas('jenis', function ($query) {
                    $query->where('nama_jenis', 'LB3');
                })
                ->where(function($q) use ($applyDateFilter) {
                    $applyDateFilter($q);
                })
                ->sum('jumlah_berat');

            $totalKg = $sampahKg + $lb3Kg;

            // Sampah terkelola
            $terkelolaKg = SampahTerkelola::where('id_lokasi', $lokasi->id)
                ->where(function($q) use ($applyDateFilter) {
                    $applyDateFilter($q);
                })
                ->sum('jumlah_berat');

            // Sampah diserahkan
            $diserahkanKg = SampahDiserahkan::where('id_lokasi', $lokasi->id)
                ->whereHas('jenis', function ($query) {
                    $query->whereNotIn('nama_jenis', ['LB3']);
                })
                ->where(function($q) use ($applyDateFilter) {
                    $applyDateFilter($q);
                })
                ->sum('jumlah_berat');

            $diserahkanLb3Kg = SampahDiserahkan::where('id_lokasi', $lokasi->id)
                ->whereHas('jenis', function ($query) {
                    $query->where('nama_jenis', 'LB3');
                })
                ->where(function($q) use ($applyDateFilter) {
                    $applyDateFilter($q);
                })
                ->sum('jumlah_berat');

            $totalDiserahkan = $diserahkanKg + $diserahkanLb3Kg;
            $totalKeseluruhan = $terkelolaKg + $totalDiserahkan;
            
            // Persentase dari total keseluruhan (terkelola + diserahkan)
            $persenTerkelolaFromTotal = $totalKeseluruhan > 0 ? ($terkelolaKg / $totalKeseluruhan) * 100 : 0;
            $persenDiserahkanFromTotal = $totalKeseluruhan > 0 ? ($totalDiserahkan / $totalKeseluruhan) * 100 : 0;
            
            $neraca[] = [
                'sumber' => $lokasi->nama_lokasi,
                'sampah_kg' => $sampahKg,
                'lb3_kg' => $lb3Kg,
                'total_kg' => $totalKg,
                'terkelola_kg' => $terkelolaKg,
                'persen_terkelola' => $totalKg > 0 ? ($terkelolaKg / $totalKg) * 100 : 0,
                'diserahkan_kg' => $diserahkanKg,
                'diserahkan_lb3_kg' => $diserahkanLb3Kg,
                'persen_diserahkan' => $totalKg > 0 ? ($totalDiserahkan / $totalKg) * 100 : 0,
                'total_keseluruhan' => $totalKeseluruhan,
                'persen_terkelola_from_total' => $persenTerkelolaFromTotal,
                'persen_diserahkan_from_total' => $persenDiserahkanFromTotal
            ];
            
            // Menambahkan ke total
            $totals['sampah_kg'] += $sampahKg;
            $totals['lb3_kg'] += $lb3Kg;
            $totals['total_kg'] += $totalKg;
            $totals['terkelola_kg'] += $terkelolaKg;
            $totals['diserahkan_kg'] += $diserahkanKg;
            $totals['diserahkan_lb3_kg'] += $diserahkanLb3Kg;
        }
        
        // Menghitung total keseluruhan dan persentase total
        $totalKeseluruhanAll = $totals['terkelola_kg'] + $totals['diserahkan_kg'] + $totals['diserahkan_lb3_kg'];
        $totals['total_keseluruhan'] = $totalKeseluruhanAll;
        $totals['persen_terkelola'] = $totals['total_kg'] > 0 ? ($totals['terkelola_kg'] / $totals['total_kg']) * 100 : 0;
        $totals['persen_diserahkan'] = $totals['total_kg'] > 0 ? (($totals['diserahkan_kg'] + $totals['diserahkan_lb3_kg']) / $totals['total_kg']) * 100 : 0;
        $totals['persen_terkelola_from_total'] = $totalKeseluruhanAll > 0 ? ($totals['terkelola_kg'] / $totalKeseluruhanAll) * 100 : 0;
        $totals['persen_diserahkan_from_total'] = $totalKeseluruhanAll > 0 ? (($totals['diserahkan_kg'] + $totals['diserahkan_lb3_kg']) / $totalKeseluruhanAll) * 100 : 0;
        
        return view('superAdmin.dashboard', compact(
            'jenisSampah', 
            'jenisColors', 
            'jenisTotals', 
            'lokasiAsals', 
            'lokasiTotals', 
            'neraca', 
            'totals',
            'startDate',
            'endDate',
            'filterType'
        ));
    }

    /**
     * Menampilkan data pengguna/petugas
     *
     * @return \Illuminate\View\View
     */
    public function masterUsers(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $users = User::where('role', '!=', 'superadmin')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
        return view('superAdmin.master.users', compact('users'));
    }

    /**
     * Menampilkan data sampah terkelola
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function masterSampahTerkelola(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $sampahTerkelolas = SampahTerkelola::with(['user', 'lokasiAsal', 'jenis'])
            ->orderBy('tgl', 'desc')
            ->paginate($perPage);
        
        return view('superAdmin.master.sampah_terkelola', compact('sampahTerkelolas'));
    }

    /**
     * Menampilkan data sampah diserahkan
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function masterSampahDiserahkan(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $sampahDiserahkans = SampahDiserahkan::with(['user', 'lokasiAsal', 'jenis', 'tujuanSampah'])
            ->orderBy('tgl', 'desc')
            ->paginate($perPage);
        
        return view('superAdmin.master.sampah_diserahkan', compact('sampahDiserahkans'));
    }

    /**
     * Menampilkan data lokasi asal sampah
     *
     * @return \Illuminate\View\View
     */
    public function masterLokasiAsal()
    {
        $lokasiAsals = LokasiAsal::all();
        return view('superAdmin.master.lokasi_asal', compact('lokasiAsals'));
    }

    /**
     * Menampilkan data jenis sampah
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function masterJenisSampah(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $jenisSampah = Jenis::orderBy('created_at', 'desc')
            ->paginate($perPage);
        return view('superAdmin.master.jenis_sampah', compact('jenisSampah'));
    }

    /**
     * Menampilkan data tujuan sampah
     *
     * @return \Illuminate\View\View
     */
    public function masterTujuanSampah()
    {
        $tujuanSampah = TujuanSampah::all();
        return view('superAdmin.master.tujuan_sampah', compact('tujuanSampah'));
    }
    
    /**
     * Menampilkan data dokumen
     *
     * @return \Illuminate\View\View
     */
    public function masterDokumen(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $dokumen = Dokumen::orderBy('created_at', 'desc')->paginate($perPage);
        return view('superAdmin.master.dokumen', compact('dokumen'));
    }
    
    /**
     * Menampilkan laporan harian
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function laporanHarian(Request $request)
    {
        $tanggal = $request->get('tanggal', date('Y-m-d'));
        
        // Query untuk mendapatkan data laporan harian
        $sampahTerkelolas = SampahTerkelola::with(['jenis', 'lokasiAsal', 'user'])
            ->whereDate('tgl', $tanggal)
            ->get();
            
        $sampahDiserahkans = SampahDiserahkan::with(['jenis', 'lokasiAsal', 'tujuanSampah', 'user'])
            ->whereDate('tgl', $tanggal)
            ->get();
        
        return view('superAdmin.laporan.harian', compact('sampahTerkelolas', 'sampahDiserahkans', 'tanggal'));
    }
    
    /**
     * Menampilkan laporan mingguan
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function laporanMingguan(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        $week = $request->get('week', 1);
        
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $weekStart = $startDate->copy()->addDays(($week - 1) * 7);
        $weekEnd = $weekStart->copy()->addDays(6);
        
        // Query untuk mendapatkan data laporan mingguan
        $sampahTerkelolas = SampahTerkelola::with(['jenis', 'lokasiAsal', 'user'])
            ->whereBetween('tgl', [$weekStart, $weekEnd])
            ->get();
            
        $sampahDiserahkans = SampahDiserahkan::with(['jenis', 'lokasiAsal', 'tujuanSampah', 'user'])
            ->whereBetween('tgl', [$weekStart, $weekEnd])
            ->get();
        
        return view('superAdmin.laporan.mingguan', compact(
            'sampahTerkelolas', 
            'sampahDiserahkans', 
            'year', 
            'month', 
            'week', 
            'weekStart', 
            'weekEnd'
        ));
    }
    
    /**
     * Menampilkan laporan bulanan
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function laporanBulanan(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        
        // Query untuk mendapatkan data laporan bulanan
        $sampahTerkelolas = SampahTerkelola::with(['jenis', 'lokasiAsal', 'user'])
            ->whereYear('tgl', $year)
            ->whereMonth('tgl', $month)
            ->get();
            
        $sampahDiserahkans = SampahDiserahkan::with(['jenis', 'lokasiAsal', 'tujuanSampah', 'user'])
            ->whereYear('tgl', $year)
            ->whereMonth('tgl', $month)
            ->get();
        
        return view('superAdmin.laporan.bulanan', compact(
            'sampahTerkelolas', 
            'sampahDiserahkans', 
            'year', 
            'month'
        ));
    }
    
    /**
     * Menampilkan laporan tahunan
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function laporanTahunan(Request $request)
    {
        $year = $request->get('year', date('Y'));
        
        // Query untuk mendapatkan data laporan tahunan
        $sampahTerkelolas = SampahTerkelola::with(['jenis', 'lokasiAsal', 'user'])
            ->whereYear('tgl', $year)
            ->get();
            
        $sampahDiserahkans = SampahDiserahkan::with(['jenis', 'lokasiAsal', 'tujuanSampah', 'user'])
            ->whereYear('tgl', $year)
            ->get();
        
        // Mengelompokkan data berdasarkan bulan
        $monthlyData = [];
        
        for ($m = 1; $m <= 12; $m++) {
            $monthlyData[$m] = [
                'terkelola' => [
                    'total' => 0,
                    'sampah' => 0,
                    'lb3' => 0
                ],
                'diserahkan' => [
                    'total' => 0,
                    'sampah' => 0,
                    'lb3' => 0
                ]
            ];
        }
        
        foreach ($sampahTerkelolas as $terkelola) {
            $month = Carbon::parse($terkelola->tgl)->month;
            $monthlyData[$month]['terkelola']['total'] += $terkelola->jumlah_berat;
            
            if ($terkelola->jenis && $terkelola->jenis->nama_jenis === 'LB3') {
                $monthlyData[$month]['terkelola']['lb3'] += $terkelola->jumlah_berat;
            } else {
                $monthlyData[$month]['terkelola']['sampah'] += $terkelola->jumlah_berat;
            }
        }
        
        foreach ($sampahDiserahkans as $diserahkan) {
            $month = Carbon::parse($diserahkan->tgl)->month;
            $monthlyData[$month]['diserahkan']['total'] += $diserahkan->jumlah_berat;
            
            if ($diserahkan->jenis && $diserahkan->jenis->nama_jenis === 'LB3') {
                $monthlyData[$month]['diserahkan']['lb3'] += $diserahkan->jumlah_berat;
            } else {
                $monthlyData[$month]['diserahkan']['sampah'] += $diserahkan->jumlah_berat;
            }
        }
        
        return view('superAdmin.laporan.tahunan', compact(
            'year', 
            'monthlyData'
        ));
    }
}
