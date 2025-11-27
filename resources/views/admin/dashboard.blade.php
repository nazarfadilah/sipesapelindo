@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="content-area">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Statistik Data {{ $periodText ?? '' }}</h4>
        <div>
            <form action="{{ route('admin.dashboard') }}" method="GET" class="d-flex">
                <select name="filter_type" class="form-select form-select-sm me-2" style="width: 120px;">
                    <option value="year" {{ request('filter_type') == 'year' ? 'selected' : '' }}>Tahunan</option>
                    <option value="month" {{ request('filter_type') == 'month' ? 'selected' : '' }}>Bulanan</option>
                    <option value="week" {{ request('filter_type') == 'week' ? 'selected' : '' }}>Mingguan</option>
                    <option value="day" {{ request('filter_type') == 'day' ? 'selected' : '' }}>Harian</option>
                </select>
                
                @if(request('filter_type') == 'year' || request('filter_type') == null)
                <select name="year" class="form-select form-select-sm me-2" style="width: 100px;">
                    @for($y = date('Y'); $y >= 2020; $y--)
                    <option value="{{ $y }}" {{ request('year', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
                @endif
                
                @if(request('filter_type') == 'month')
                <select name="month" class="form-select form-select-sm me-2" style="width: 100px;">
                    @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ request('month', date('m')) == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                    @endfor
                </select>
                <select name="year" class="form-select form-select-sm me-2" style="width: 100px;">
                    @for($y = date('Y'); $y >= 2020; $y--)
                    <option value="{{ $y }}" {{ request('year', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
                @endif
                
                @if(request('filter_type') == 'week')
                <select name="week" class="form-select form-select-sm me-2" style="width: 100px;">
                    @for($w = 1; $w <= 5; $w++)
                    <option value="{{ $w }}" {{ request('week', 1) == $w ? 'selected' : '' }}>Minggu {{ $w }}</option>
                    @endfor
                </select>
                <select name="month" class="form-select form-select-sm me-2" style="width: 100px;">
                    @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ request('month', date('m')) == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                    @endfor
                </select>
                <select name="year" class="form-select form-select-sm me-2" style="width: 100px;">
                    @for($y = date('Y'); $y >= 2020; $y--)
                    <option value="{{ $y }}" {{ request('year', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
                @endif
                
                @if(request('filter_type') == 'day')
                <input type="date" name="day" class="form-control form-control-sm me-2" value="{{ request('day', date('Y-m-d')) }}" style="width: 150px;">
                @endif
                
                <button type="submit" class="btn btn-sm btn-primary">Filter</button>
            </form>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-6 d-flex">
            <div class="card shadow w-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Distribusi Jenis Sampah</h5>
                        <select id="jenisTypeFilter" class="form-select form-select-sm" style="width: 180px;">
                            <option value="both">Semua Sampah</option>
                            <option value="terkelola">Sampah Terkelola</option>
                            <option value="diserahkan">Sampah Diserahkan</option>
                        </select>
                    </div>
                    <div class="chart-container flex-grow-1">
                        <canvas id="pieChart"></canvas>
                    </div>
                    <div class="chart-legend mt-3" id="pieChartLegend">
                        <div class="d-flex flex-wrap justify-content-around">
                            <!-- Legend will be populated by JS -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 d-flex">
            <div class="card shadow w-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Total Sampah per Lokasi</h5>
                        <select id="lokasiTypeFilter" class="form-select form-select-sm" style="width: 180px;">
                            <option value="both">Semua Sampah</option>
                            <option value="terkelola">Sampah Terkelola</option>
                            <option value="diserahkan">Sampah Diserahkan</option>
                        </select>
                    </div>
                    <div class="chart-container flex-grow-1">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">Rekap Neraca Pengelolaan Sampah ({{ $periodText ?? 'Data Terbaru' }})</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-dashboard">
                    <thead class="bg-warning">
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Sumber Sampah</th>
                            <th colspan="3" class="text-center">Jumlah Timbulan Sampah</th>
                            <th colspan="2" class="text-center">Pengelolaan Sampah (kg/bulan)</th>
                            <th colspan="3" class="text-center">Jumlah Sampah Diserahkan (kg/bulan)</th>
                        </tr>
                        <tr>
                            <th>Sampah (kg/bulan)</th>
                            <th>LB3 (kg/bulan)</th>
                            <th>Total Sampah (kg/bulan)</th>
                            <th>Terkelola (kg/bulan)</th>
                            <th>Persentase (%)</th>
                            <th>Sampah (kg/bulan)</th>
                            <th>LB3 (kg/bulan)</th>
                            <th>Persentase (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rekapData as $index => $data)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $data['lokasi'] }}</td>
                            <td>{{ number_format($data['sampah_biasa'], 2) }}</td>
                            <td>{{ number_format($data['sampah_lb3'], 2) }}</td>
                            <td>{{ number_format($data['total_sampah'], 2) }}</td>
                            <td>{{ number_format($data['terkelola'], 2) }}</td>
                            <td>{{ number_format($data['persen_terkelola'], 2) }}%</td>
                            <td>{{ number_format($data['diserahkan_biasa'], 2) }}</td>
                            <td>{{ number_format($data['diserahkan_lb3'], 2) }}</td>
                            <td>{{ number_format($data['persen_diserahkan'], 2) }}%</td>
                        </tr>
                        @endforeach
                        <tr class="bg-light fw-bold">
                            <td colspan="2">Total</td>
                            <td>{{ number_format($totalSampahBiasa, 2) }}</td>
                            <td>{{ number_format($totalSampahLB3, 2) }}</td>
                            <td>{{ number_format($totalSampahAll, 2) }}</td>
                            <td>{{ number_format($totalTerkelola, 2) }}</td>
                            <td>{{ number_format($persenTerkelolaTotal, 2) }}%</td>
                            <td>{{ number_format($totalDiserahkanBiasa, 2) }}</td>
                            <td>{{ number_format($totalDiserahkanLB3, 2) }}</td>
                            <td>{{ number_format($persenDiserahkanTotal, 2) }}%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Filter change handler
        $('select[name="filter_type"]').change(function() {
            const filterType = $(this).val();
            
            // Hide all filter options first
            $('select[name="year"], select[name="month"], select[name="week"], input[name="day"]').parent().hide();
            
            // Show relevant filter options based on selection
            if (filterType === 'year') {
                $('select[name="year"]').parent().show();
            } else if (filterType === 'month') {
                $('select[name="year"]').parent().show();
                $('select[name="month"]').parent().show();
            } else if (filterType === 'week') {
                $('select[name="year"]').parent().show();
                $('select[name="month"]').parent().show();
                $('select[name="week"]').parent().show();
            } else if (filterType === 'day') {
                $('input[name="day"]').parent().show();
            }
        });
        
        // Trigger the change event to set initial state
        $('select[name="filter_type"]').trigger('change');
        
        // Get data from PHP variables
        const jenisSampahLabels = {!! $jenisSampahLabels !!};
        const jenisSampahDataTerkelola = {!! $jenisSampahDataTerkelola !!};
        const jenisSampahDataDiserahkan = {!! $jenisSampahDataDiserahkan !!};
        const jenisSampahColors = {!! $jenisSampahColors !!};
        const lokasiSampahLabels = {!! $lokasiSampahLabels !!};
        const lokasiSampahData = {!! $lokasiSampahData !!};
        const lokasiSampahDataDiserahkan = {!! $lokasiSampahDataDiserahkan !!};
        
        // Calculate combined data (terkelola + diserahkan)
        const jenisSampahDataCombined = jenisSampahDataTerkelola.map((val, idx) => val + jenisSampahDataDiserahkan[idx]);
        const lokasiSampahDataCombined = lokasiSampahData.map((val, idx) => val + lokasiSampahDataDiserahkan[idx]);
        
        // Pie Chart
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        let pieChart = new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: jenisSampahLabels,
                datasets: [{
                    data: jenisSampahDataCombined,
                    backgroundColor: jenisSampahColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                        position: 'bottom'
                    }
                }
            }
        });
        
        // Function to update pie chart legend
        function updatePieChartLegend(dataToUse) {
            const totalJenisSampah = dataToUse.reduce((a, b) => a + b, 0);
            const legendContainer = document.querySelector('#pieChartLegend .d-flex');
            legendContainer.innerHTML = '';
            
            jenisSampahLabels.forEach((label, index) => {
                const percentage = totalJenisSampah > 0 ? ((dataToUse[index] / totalJenisSampah) * 100).toFixed(2) : '0.00';
                const color = jenisSampahColors[index];
                
                const legendItem = document.createElement('div');
                legendItem.classList.add('d-flex', 'align-items-center', 'me-3', 'mb-2');
                legendItem.innerHTML = `
                    <div style="width: 15px; height: 15px; background-color: ${color}; margin-right: 5px;"></div>
                    <small>${label} (${percentage}%)</small>
                `;
                
                legendContainer.appendChild(legendItem);
            });
        }
        
        // Initial pie chart legend
        updatePieChartLegend(jenisSampahDataCombined);
        
        // Pie chart filter handler
        $('#jenisTypeFilter').on('change', function() {
            const filterType = $(this).val();
            let dataToUse;
            
            if (filterType === 'terkelola') {
                dataToUse = jenisSampahDataTerkelola;
            } else if (filterType === 'diserahkan') {
                dataToUse = jenisSampahDataDiserahkan;
            } else {
                dataToUse = jenisSampahDataCombined;
            }
            
            pieChart.data.datasets[0].data = dataToUse;
            pieChart.update();
            updatePieChartLegend(dataToUse);
        });
        
        // Bar Chart for Total Sampah per Lokasi
        const barCtx = document.getElementById('barChart').getContext('2d');
        let barChart = new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: lokasiSampahLabels,
                datasets: [{
                    label: 'Total Sampah (kg)',
                    data: lokasiSampahDataCombined,
                    backgroundColor: '#1E3F8C',
                    borderColor: '#1E3F8C',
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
                            callback: function(value) {
                                return value + ' kg';
                            }
                        }
                    },
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Total Sampah (kg): ' + context.parsed.y.toFixed(2);
                            }
                        }
                    }
                }
            }
        });
        
        // Bar chart filter handler
        $('#lokasiTypeFilter').on('change', function() {
            const filterType = $(this).val();
            let dataToUse;
            
            if (filterType === 'terkelola') {
                dataToUse = lokasiSampahData;
            } else if (filterType === 'diserahkan') {
                dataToUse = lokasiSampahDataDiserahkan;
            } else {
                dataToUse = lokasiSampahDataCombined;
            }
            
            barChart.data.datasets[0].data = dataToUse;
            barChart.update();
        });
    });
</script>
@endpush
