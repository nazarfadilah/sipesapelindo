@extends('superAdmin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="content-area-dashboard">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Dashboard Statistik</h4>
        <div>
            <form id="filterForm" method="GET" action="{{ route('superadmin.dashboard') }}" class="d-flex align-items-center">
                <select name="data_type" class="form-select form-select-sm me-2" style="width: 150px;">
                    <option value="both" {{ request('data_type', 'both') == 'both' ? 'selected' : '' }}>Semua</option>
                    <option value="terkelola" {{ request('data_type') == 'terkelola' ? 'selected' : '' }}>Sampah Terkelola</option>
                    <option value="diserahkan" {{ request('data_type') == 'diserahkan' ? 'selected' : '' }}>Sampah Diserahkan</option>
                </select>
                
                <select id="filter_type" name="filter_type" class="form-select form-select-sm me-2" style="width: 120px;" onchange="updateFilterOptions()">
                    <option value="year" {{ request('filter_type') == 'year' ? 'selected' : '' }}>Tahunan</option>
                    <option value="month" {{ request('filter_type') == 'month' ? 'selected' : '' }}>Bulanan</option>
                    <option value="week" {{ request('filter_type') == 'week' ? 'selected' : '' }}>Mingguan</option>
                    <option value="day" {{ request('filter_type') == 'day' ? 'selected' : '' }}>Harian</option>
                </select>
                    <option value="year" {{ request('filter_type') == 'year' ? 'selected' : '' }}>Tahunan</option>
                    <option value="month" {{ request('filter_type') == 'month' ? 'selected' : '' }}>Bulanan</option>
                    <option value="week" {{ request('filter_type') == 'week' ? 'selected' : '' }}>Mingguan</option>
                    <option value="day" {{ request('filter_type') == 'day' ? 'selected' : '' }}>Harian</option>
                </select>
                
                <div id="yearFilter" class="{{ request('filter_type') != 'year' && request('filter_type') ? 'd-none' : '' }}">
                    <select id="year" name="year" class="form-select form-select-sm me-2" style="width: 100px;">
                        @for ($y = date('Y'); $y >= 2023; $y--)
                            <option value="{{ $y }}" {{ request('year', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                
                <div id="monthFilter" class="{{ request('filter_type') != 'month' ? 'd-none' : '' }}">
                    <select id="month" name="month" class="form-select form-select-sm me-2" style="width: 100px;">
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ request('month', date('m')) == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                        @endfor
                    </select>
                </div>
                
                <div id="weekFilter" class="{{ request('filter_type') != 'week' ? 'd-none' : '' }}">
                    <select id="week" name="week" class="form-select form-select-sm me-2" style="width: 100px;">
                        @for ($w = 1; $w <= 5; $w++)
                            <option value="{{ $w }}" {{ request('week', 1) == $w ? 'selected' : '' }}>Minggu {{ $w }}</option>
                        @endfor
                    </select>
                </div>
                
                <div id="dayFilter" class="{{ request('filter_type') != 'day' ? 'd-none' : '' }}">
                    <input type="date" id="day" name="day" class="form-control form-control-sm me-2" style="width: 150px;" value="{{ request('day', date('Y-m-d')) }}">
                </div>
                
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
            </form>
        </div>
    </div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card bg-white text-dark p-3 shadow-sm">
            <h5 class="mb-3 text-dark">Distribusi Sampah Berdasarkan Jenis</h5>
            <div class="d-flex align-items-center">
                <div style="flex:1;">
                    <canvas id="pieChart" style="width: 100%; height: 250px;"></canvas>
                </div>
                <div class="pie-chart-legend ms-3" style="min-width:140px;">
                    @foreach ($jenisSampah as $index => $jenis)
                    <div class="legend-item d-flex align-items-center mb-2">
                        <div class="legend-color me-2" style="width:16px; height:16px; background-color: {{ $jenisColors[$index] }}; border-radius:3px;"></div>
                        <div class="text-dark small">{{ $jenis->nama_jenis }} ({{ $jenisTotals[$index] }}%)</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card bg-white text-dark p-3 shadow-sm">
            <h5 class="mb-3 text-dark">Jumlah Sampah Berdasarkan Area Asal</h5>
            <div style="height:250px;">
                <canvas id="barChart" style="width: 100%; height: 100%;"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="content-area-dashboard">
    @if(isset($startDate) && isset($endDate))
    <h4 class="mb-4">Rekap Neraca Pengelolaan Sampah ({{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }})</h4>
    @else
    <h4 class="mb-4">Rekap Neraca Pengelolaan Sampah</h4>
    @endif
    
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Sumber Sampah</th>
                    <th colspan="3">Jumlah Sampah</th>
                    <th colspan="2">Pengelolaan Sampah (kg/bulan)</th>
                    <th colspan="2">Jumlah Sampah Diserahkan (kg/bulan)</th>
                </tr>
                <tr>
                    <th>Total Sampah Terkelola (kg/bulan)</th>
                    <th>Total Sampah Diserahkan (kg/bulan)</th>
                    <th>Total Keseluruhan (kg/bulan)</th>
                    <th>Sampah Terkelola (kg/bulan)</th>
                    <th>Persentase (%)</th>
                    <th>Sampah Diserahkan (kg/bulan)</th>
                    <th>Persentase (%)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($neraca as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item['sumber'] }}</td>
                    <td>{{ number_format($item['terkelola_kg'], 2) }}</td>
                    <td>{{ number_format($item['diserahkan_kg'] + $item['diserahkan_lb3_kg'], 2) }}</td>
                    <td>{{ number_format($item['terkelola_kg'] + $item['diserahkan_kg'] + $item['diserahkan_lb3_kg'], 2) }}</td>
                    <td>{{ number_format($item['terkelola_kg'], 2) }}</td>
                    <td>{{ number_format($item['persen_terkelola_from_total'], 2) }}%</td>
                    <td>{{ number_format($item['diserahkan_kg'] + $item['diserahkan_lb3_kg'], 2) }}</td>
                    <td>{{ number_format($item['persen_diserahkan_from_total'], 2) }}%</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="2" class="text-center">Total</td>
                    <td>{{ number_format($totals['terkelola_kg'], 2) }}</td>
                    <td>{{ number_format($totals['diserahkan_kg'] + $totals['diserahkan_lb3_kg'], 2) }}</td>
                    <td>{{ number_format($totals['total_keseluruhan'], 2) }}</td>
                    <td>{{ number_format($totals['terkelola_kg'], 2) }}</td>
                    <td>{{ number_format($totals['persen_terkelola_from_total'], 2) }}%</td>
                    <td>{{ number_format($totals['diserahkan_kg'] + $totals['diserahkan_lb3_kg'], 2) }}</td>
                    <td>{{ number_format($totals['persen_diserahkan_from_total'], 2) }}%</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Data dari server
    const jenisLabels = @json($jenisSampah->pluck('nama_jenis')->toArray());
    const jenisTotalsData = @json($jenisTotals);
    const jenisColorsData = @json($jenisColors);
    const lokasiLabels = @json($lokasiAsals->pluck('nama_lokasi')->toArray());
    const lokasiTotalsData = @json($lokasiTotals);
    
    function updateFilterOptions() {
        const filterType = document.getElementById('filter_type').value;
        
        document.getElementById('yearFilter').classList.add('d-none');
        document.getElementById('monthFilter').classList.add('d-none');
        document.getElementById('weekFilter').classList.add('d-none');
        document.getElementById('dayFilter').classList.add('d-none');
        
        if (filterType === 'year' || filterType === 'all') {
            document.getElementById('yearFilter').classList.remove('d-none');
        } else if (filterType === 'month') {
            document.getElementById('yearFilter').classList.remove('d-none');
            document.getElementById('monthFilter').classList.remove('d-none');
        } else if (filterType === 'week') {
            document.getElementById('yearFilter').classList.remove('d-none');
            document.getElementById('monthFilter').classList.remove('d-none');
            document.getElementById('weekFilter').classList.remove('d-none');
        } else if (filterType === 'day') {
            document.getElementById('dayFilter').classList.remove('d-none');
        }
    }
    
    // Render Pie Chart
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    const pieChart = new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: jenisLabels,
            datasets: [{
                data: jenisTotalsData,
                backgroundColor: jenisColorsData,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
    
    // Render Bar Chart
    const barCtx = document.getElementById('barChart').getContext('2d');
    const barChart = new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: lokasiLabels,
            datasets: [{
                label: 'Jumlah Sampah (kg)',
                data: lokasiTotalsData,
                backgroundColor: 'rgba(0, 191, 255, 0.7)',
                borderColor: 'rgba(0, 191, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#333'
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    ticks: {
                        color: '#333'
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.03)'
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        color: '#333'
                    }
                }
            }
        }
    });
</script>
@endpush
