<?php

namespace App\Exports\Sheets;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RekapAreaSheet implements FromView, WithTitle, ShouldAutoSize
{
    private $data;
    private $totals;
    private $lokasis;

    public function __construct(array $data, array $totals, $lokasis) {
        $this->data = $data;
        $this->totals = $totals;
        $this->lokasis = $lokasis;
    }
    public function view(): View {
        return view('admin.laporan.export_templates.rekap_area', [
            'rekapData' => $this->data,
            'totals' => $this->totals,
            'lokasis' => $this->lokasis
        ]);
    }
    public function title(): string {
        return 'Rekap area';
    }
}