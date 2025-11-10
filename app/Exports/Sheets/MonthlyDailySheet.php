<?php

namespace App\Exports\Sheets;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MonthlyDailySheet implements FromView, WithTitle, ShouldAutoSize
{
    private $monthName;
    private $data;
    private $totalsNeraca;
    private $totalsArea;
    private $lokasis;

    public function __construct(string $monthName, array $data, array $totalsNeraca, array $totalsArea, $lokasis)
    {
        $this->monthName = $monthName;
        $this->data = $data;
        $this->totalsNeraca = $totalsNeraca;
        $this->totalsArea = $totalsArea;
        $this->lokasis = $lokasis;
    }

    public function view(): View
    {
        return view('admin.laporan.export_templates.daily_month', [
            'dailyData' => $this->data,
            'totalsNeraca' => $this->totalsNeraca,
            'totalsArea' => $this->totalsArea,
            'lokasis' => $this->lokasis
        ]);
    }

    public function title(): string
    {
        // Judul sheet-nya (cth: "Juli 2024" menjadi "Juli")
        return explode(' ', $this->monthName)[0];
    }
}