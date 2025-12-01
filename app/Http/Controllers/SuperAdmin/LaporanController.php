<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Exports\LaporanMultiSheetExport;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    // === ATURAN BISNIS ===
    
    // 1. Mapping 6 Jenis Sampah ke 3 Kategori Excel
    const JENIS_ORGANIK_IDS = [1];
    const JENIS_ANORGANIK_IDS = [2];
    const JENIS_RESIDU_IDS = [3, 4, 5, 6];
    
    // 2. Nama Kolom Berat (sesuai Model - keduanya sama)
    const COL_TERKELOLA = 'jumlah_berat';
    const COL_DISERAHKAN = 'jumlah_berat';

    /**
     * Menampilkan halaman filter laporan
     */
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        return view('superadmin.laporan.laporan', ['tahun' => $tahun]);
    }

    /**
     * Fungsi utama untuk mengekspor file 15-sheet atau hanya 12-sheet bulanan
     */
    public function export(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $tipe = $request->get('tipe', 'lengkap'); // 'lengkap' atau 'bulanan'
        
        $startDate = Carbon::createFromDate($tahun, 7, 1)->startOfDay();
        $endDate = $startDate->copy()->addYear()->subDay()->endOfDay();
        
        $lokasis = DB::table('lokasi_asals')->pluck('nama_lokasi', 'id');

        // Ambil data mentah 12 bulan
        $dataTerkelola = $this->queryFullData('sampah_terkelolas', $startDate, $endDate);
        $dataDiserahkan = $this->queryFullData('sampah_diserahkans', $startDate, $endDate);

        if ($tipe === 'bulanan') {
            // Export hanya 12 sheet data harian per bulan
            $dailyData = $this->getDailySheetsData($startDate, $dataTerkelola, $dataDiserahkan, $lokasis);
            $fileName = "Laporan_Bulanan_SIPESA_{$tahun}-" . ($tahun + 1) . ".xlsx";
            
            return Excel::download(new LaporanMultiSheetExport(
                null,  // tidak ada rekap neraca
                null,  // tidak ada rekap terkelola
                null,  // tidak ada rekap area
                $dailyData
            ), $fileName);
        } else {
            // Export lengkap 15 sheet
            $dailyData = $this->getDailySheetsData($startDate, $dataTerkelola, $dataDiserahkan, $lokasis);
            $rekapTerkelolaData = $this->getRekapTerkelolaData($dataTerkelola, $startDate);
            $rekapAreaData = $this->getRekapAreaData($dataTerkelola, $dataDiserahkan, $startDate, $lokasis);
            $rekapNeracaData = $this->getRekapNeracaData($dataTerkelola, $dataDiserahkan, $lokasis);

            $fileName = "Laporan_Logbook_SIPESA_{$tahun}-" . ($tahun + 1) . ".xlsx";

            return Excel::download(new LaporanMultiSheetExport(
                $rekapNeracaData,
                $rekapTerkelolaData,
                $rekapAreaData,
                $dailyData,
                $tahun
            ), $fileName);
        }
    }

    // ===================================================================
    // === HELPER PENGOLAH DATA ===
    // ===================================================================

    /**
     * Sheet 1: Rekap Neraca Pengelolaan Sampah
     */
    private function getRekapNeracaData($dataTerkelola, $dataDiserahkan, $lokasis)
    {
        $dataRekap = [];
        $grandTotals = [
            'timbulan' => 0,
            'terkelola_organik' => 0,
            'terkelola_anorganik' => 0,
            'total_terkelola' => 0,
            'residu_dll' => 0
        ];

        foreach ($lokasis as $id => $nama) {
            $terkelola_O = $dataTerkelola->where('id_lokasi', $id)->whereIn('id_jenis', self::JENIS_ORGANIK_IDS)->sum('jumlah_berat');
            $terkelola_A = $dataTerkelola->where('id_lokasi', $id)->whereIn('id_jenis', self::JENIS_ANORGANIK_IDS)->sum('jumlah_berat');
            $terkelola_R = $dataTerkelola->where('id_lokasi', $id)->whereIn('id_jenis', self::JENIS_RESIDU_IDS)->sum('jumlah_berat');
            $diserahkan_Total = $dataDiserahkan->where('id_lokasi', $id)->sum('jumlah_berat');
            
            $residu_dll = $terkelola_R + $diserahkan_Total;
            $total_terkelola = $terkelola_O + $terkelola_A;
            $timbulan = $total_terkelola + $residu_dll;
            
            $dataRekap[] = [
                'lokasi' => $nama,
                'timbulan' => $timbulan,
                'terkelola_organik' => $terkelola_O,
                'terkelola_anorganik' => $terkelola_A,
                'total_terkelola' => $total_terkelola,
                'residu_dll' => $residu_dll,
            ];

            $grandTotals['timbulan'] += $timbulan;
            $grandTotals['terkelola_organik'] += $terkelola_O;
            $grandTotals['terkelola_anorganik'] += $terkelola_A;
            $grandTotals['total_terkelola'] += $total_terkelola;
            $grandTotals['residu_dll'] += $residu_dll;
        }
        
        return ['data' => $dataRekap, 'totals' => $grandTotals, 'lokasis' => $lokasis];
    }

    /**
     * Sheet 2: Rekap Sampah Terkelola (HANYA dari sampah_terkelolas)
     */
    private function getRekapTerkelolaData($dataTerkelola, $startDate)
    {
        $dataRekap = [];
        $grandTotals = [
            'organik' => 0,
            'anorganik' => 0,
            'residu_dll' => 0,
            'timbulan_terkelola' => 0
        ];

        for ($i = 0; $i < 12; $i++) {
            $currentMonth = $startDate->copy()->addMonths($i);
            $monthKey = $currentMonth->format('Y-m');
            
            $dataBulanan = $dataTerkelola->where('month_year', $monthKey);
            
            $terkelola_O = $dataBulanan->whereIn('id_jenis', self::JENIS_ORGANIK_IDS)->sum('jumlah_berat');
            $terkelola_A = $dataBulanan->whereIn('id_jenis', self::JENIS_ANORGANIK_IDS)->sum('jumlah_berat');
            $terkelola_R = $dataBulanan->whereIn('id_jenis', self::JENIS_RESIDU_IDS)->sum('jumlah_berat');
            $timbulan_terkelola = $terkelola_O + $terkelola_A + $terkelola_R;
            
            $dataRekap[] = [
                'bulan' => $this->translateMonth($currentMonth->format('F')),
                'tahun' => $currentMonth->format('Y'),
                'organik' => $terkelola_O,
                'anorganik' => $terkelola_A,
                'residu_dll' => $terkelola_R,
                'timbulan_terkelola' => $timbulan_terkelola,
            ];
            
            $grandTotals['organik'] += $terkelola_O;
            $grandTotals['anorganik'] += $terkelola_A;
            $grandTotals['residu_dll'] += $terkelola_R;
            $grandTotals['timbulan_terkelola'] += $timbulan_terkelola;
        }
        
        return ['data' => $dataRekap, 'totals' => $grandTotals];
    }

    /**
     * Sheet 3: Rekap Area (Pivot Area-Jenis)
     */
    private function getRekapAreaData($dataTerkelola, $dataDiserahkan, $startDate, $lokasis)
    {
        $dataRekap = [];
        $grandTotals = ['total_tahunan' => 0];
        
        for ($i = 0; $i < 12; $i++) {
            $currentMonth = $startDate->copy()->addMonths($i);
            $monthKey = $currentMonth->format('Y-m');
            
            $dataTerkelolaBulan = $dataTerkelola->where('month_year', $monthKey);
            $dataDiserahkanBulan = $dataDiserahkan->where('month_year', $monthKey);

            $rowData = [
                'bulan' => $this->translateMonth($currentMonth->format('F')),
                'tahun' => $currentMonth->format('Y')
            ];
            $totalBulanan = 0;
            
            foreach ($lokasis as $lokasiId => $lokasiNama) {
                $timbulan_O = $dataTerkelolaBulan->where('id_lokasi', $lokasiId)->whereIn('id_jenis', self::JENIS_ORGANIK_IDS)->sum('jumlah_berat') +
                              $dataDiserahkanBulan->where('id_lokasi', $lokasiId)->whereIn('id_jenis', self::JENIS_ORGANIK_IDS)->sum('jumlah_berat');
                
                $timbulan_A = $dataTerkelolaBulan->where('id_lokasi', $lokasiId)->whereIn('id_jenis', self::JENIS_ANORGANIK_IDS)->sum('jumlah_berat') +
                              $dataDiserahkanBulan->where('id_lokasi', $lokasiId)->whereIn('id_jenis', self::JENIS_ANORGANIK_IDS)->sum('jumlah_berat');
                
                $timbulan_R = $dataTerkelolaBulan->where('id_lokasi', $lokasiId)->whereIn('id_jenis', self::JENIS_RESIDU_IDS)->sum('jumlah_berat') +
                              $dataDiserahkanBulan->where('id_lokasi', $lokasiId)->whereIn('id_jenis', self::JENIS_RESIDU_IDS)->sum('jumlah_berat');

                $timbulan_Lokasi = $timbulan_O + $timbulan_A + $timbulan_R;
                
                $rowData["lokasi_{$lokasiId}_O"] = $timbulan_O;
                $rowData["lokasi_{$lokasiId}_A"] = $timbulan_A;
                $rowData["lokasi_{$lokasiId}_R"] = $timbulan_R;
                $rowData["lokasi_{$lokasiId}_Total"] = $timbulan_Lokasi;

                $totalBulanan += $timbulan_Lokasi;
                
                if (!isset($grandTotals["lokasi_{$lokasiId}_O"])) $grandTotals["lokasi_{$lokasiId}_O"] = 0;
                if (!isset($grandTotals["lokasi_{$lokasiId}_A"])) $grandTotals["lokasi_{$lokasiId}_A"] = 0;
                if (!isset($grandTotals["lokasi_{$lokasiId}_R"])) $grandTotals["lokasi_{$lokasiId}_R"] = 0;
                if (!isset($grandTotals["lokasi_{$lokasiId}_Total"])) $grandTotals["lokasi_{$lokasiId}_Total"] = 0;
                
                $grandTotals["lokasi_{$lokasiId}_O"] += $timbulan_O;
                $grandTotals["lokasi_{$lokasiId}_A"] += $timbulan_A;
                $grandTotals["lokasi_{$lokasiId}_R"] += $timbulan_R;
                $grandTotals["lokasi_{$lokasiId}_Total"] += $timbulan_Lokasi;
            }
            
            $rowData['total_bulanan'] = $totalBulanan;
            $grandTotals['total_tahunan'] += $totalBulanan;
            $dataRekap[] = $rowData;
        }
        
        return ['data' => $dataRekap, 'totals' => $grandTotals, 'lokasis' => $lokasis];
    }

    /**
     * Sheet 4-15: Data Harian (12 bulan)
     */
    private function getDailySheetsData($startDate, $dataTerkelola, $dataDiserahkan, $lokasis)
    {
        $dailyDataPerMonth = [];
        
        for ($i = 0; $i < 12; $i++) {
            $startOfMonth = $startDate->copy()->addMonths($i);
            $monthName = $startOfMonth->format('F');
            $monthKey = $startOfMonth->format('Y-m');
            $tahunBulan = $startOfMonth->format('Y');
            $bulanIndo = $this->translateMonth($monthName);

            $dataTerkelolaBulan = $dataTerkelola->where('month_year', $monthKey);
            $dataDiserahkanBulan = $dataDiserahkan->where('month_year', $monthKey);

            $monthData = [];
            $monthTotals = $this->getEmptyPivotTotals($lokasis);

            for ($day = 1; $day <= $startOfMonth->daysInMonth; $day++) {
                $currentDate = $startOfMonth->copy()->addDays($day - 1);
                $dateString = $currentDate->toDateString();
                
                $dataTerkelolaHari = $dataTerkelolaBulan->where('tgl', $dateString);
                $dataDiserahkanHari = $dataDiserahkanBulan->where('tgl', $dateString);

                $rowData = ['tanggal' => $currentDate->format('d-m-Y')];
                $totalHarian = 0;

                foreach ($lokasis as $lokasiId => $lokasiNama) {
                    $timbulan_O = $dataTerkelolaHari->where('id_lokasi', $lokasiId)->whereIn('id_jenis', self::JENIS_ORGANIK_IDS)->sum('jumlah_berat') +
                                  $dataDiserahkanHari->where('id_lokasi', $lokasiId)->whereIn('id_jenis', self::JENIS_ORGANIK_IDS)->sum('jumlah_berat');
                    
                    $timbulan_A = $dataTerkelolaHari->where('id_lokasi', $lokasiId)->whereIn('id_jenis', self::JENIS_ANORGANIK_IDS)->sum('jumlah_berat') +
                                  $dataDiserahkanHari->where('id_lokasi', $lokasiId)->whereIn('id_jenis', self::JENIS_ANORGANIK_IDS)->sum('jumlah_berat');
                    
                    $timbulan_R = $dataTerkelolaHari->where('id_lokasi', $lokasiId)->whereIn('id_jenis', self::JENIS_RESIDU_IDS)->sum('jumlah_berat') +
                                  $dataDiserahkanHari->where('id_lokasi', $lokasiId)->whereIn('id_jenis', self::JENIS_RESIDU_IDS)->sum('jumlah_berat');

                    $timbulan_Lokasi = $timbulan_O + $timbulan_A + $timbulan_R;

                    $rowData["lokasi_{$lokasiId}_O"] = $timbulan_O;
                    $rowData["lokasi_{$lokasiId}_A"] = $timbulan_A;
                    $rowData["lokasi_{$lokasiId}_R"] = $timbulan_R;
                    $rowData["lokasi_{$lokasiId}_Total"] = $timbulan_Lokasi;

                    $totalHarian += $timbulan_Lokasi;

                    $monthTotals["lokasi_{$lokasiId}_O"] += $timbulan_O;
                    $monthTotals["lokasi_{$lokasiId}_A"] += $timbulan_A;
                    $monthTotals["lokasi_{$lokasiId}_R"] += $timbulan_R;
                    $monthTotals["lokasi_{$lokasiId}_Total"] += $timbulan_Lokasi;
                }
                
                $rowData['total_harian'] = $totalHarian;
                $monthTotals['total_bulanan'] += $totalHarian;
                $monthData[] = $rowData;
            }
            
            $dailyDataPerMonth[$monthName] = [
                'data' => $monthData, 
                'totals' => $monthTotals,
                'tahun' => $tahunBulan,
                'bulan' => $bulanIndo
            ];
        }
        
        return ['dailyData' => $dailyDataPerMonth, 'lokasis' => $lokasis];
    }

    // ===================================================================
    // === HELPER QUERY DATABASE ===
    // ===================================================================

    /**
     * Mengambil semua data mentah dari DB untuk 12 bulan
     */
    private function queryFullData($table, $start, $end)
    {
        // Kedua table menggunakan kolom jumlah_berat yang sama
        return DB::table($table)
            ->whereBetween('tgl', [$start, $end])
            ->select('tgl', 'id_jenis', 'id_lokasi', 'jumlah_berat')
            ->get()->map(function ($item) {
                $date = Carbon::parse($item->tgl);
                $item->tgl = $date->toDateString();
                $item->month_year = $date->format('Y-m');
                return $item;
            });
    }

    /**
     * Helper untuk membuat array total pivot yang kosong
     */
    private function getEmptyPivotTotals($lokasis)
    {
        $totals = ['total_bulanan' => 0];
        foreach ($lokasis as $lokasiId => $lokasiNama) {
            $totals["lokasi_{$lokasiId}_O"] = 0;
            $totals["lokasi_{$lokasiId}_A"] = 0;
            $totals["lokasi_{$lokasiId}_R"] = 0;
            $totals["lokasi_{$lokasiId}_Total"] = 0;
        }
        return $totals;
    }

    /**
     * Helper untuk translate nama bulan ke Indonesia
     */
    private function translateMonth($monthName)
    {
        $months = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        ];
        return $months[$monthName] ?? $monthName;
    }
}