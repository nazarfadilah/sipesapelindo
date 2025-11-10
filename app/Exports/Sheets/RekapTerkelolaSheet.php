<?php

namespace App\Exports\Sheets;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RekapTerkelolaSheet implements FromView, WithTitle, ShouldAutoSize
{
    private $data;
    private $totals;

    public function __construct(array $data, array $totals) {
        $this->data = $data;
        $this->totals = $totals;
    }
    public function view(): View {
        return view('admin.laporan.export_templates.rekap_terkelola', [
            'rekapData' => $this->data,
            'totals' => $this->totals
        ]);
    }
    public function title(): string {
        return 'Rekap Sampah terkelola';
    }
}