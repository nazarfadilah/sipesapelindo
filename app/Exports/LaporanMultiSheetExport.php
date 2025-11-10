<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\RekapNeracaSheet;
use App\Exports\Sheets\RekapTerkelolaSheet;
use App\Exports\Sheets\RekapAreaSheet;
use App\Exports\Sheets\MonthlyDailySheet;

class LaporanMultiSheetExport implements WithMultipleSheets
{
    protected $rekapNeracaData;
    protected $rekapTerkelolaData;
    protected $rekapAreaData;
    protected $dailyData;

    public function __construct(array $d1, array $d2, array $d3, array $d4)
    {
        $this->rekapNeracaData = $d1;
        $this->rekapTerkelolaData = $d2;
        $this->rekapAreaData = $d3;
        $this->dailyData = $d4;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        // Sheet 1: Rekap Neraca
        $sheets[] = new RekapNeracaSheet($this->rekapNeracaData['data'], $this->rekapNeracaData['totals']);

        // Sheet 2: Rekap Sampah Terkelola
        $sheets[] = new RekapTerkelolaSheet($this->rekapTerkelolaData['data'], $this->rekapTerkelolaData['totals']);
        
        // Sheet 3: Rekap Area
        $sheets[] = new RekapAreaSheet($this->rekapAreaData['data'], $this->rekapAreaData['totals'], $this->rekapAreaData['lokasis']);

        // Sheet 4-15: Data Harian per Bulan
        foreach ($this->dailyData['dailyData'] as $monthName => $data) {
            $sheets[] = new MonthlyDailySheet(
                $monthName, 
                $data['data'], 
                $data['totals_neraca'], 
                $data['totals_area'],
                $this->dailyData['lokasis']
            );
        }
        
        return $sheets;
    }
}