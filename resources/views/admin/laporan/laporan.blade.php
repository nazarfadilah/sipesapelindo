@extends('admin.layout')

@section('title', 'Menu Laporan Neraca Sampah')

@section('content')
<div class="content-area">
    <div class="card">
        <div class="card-body bg-primary text-white">
            <h5 class="mb-0">Laporan Rekapitulasi Neraca Pengelolaan Sampah</h5>
        </div>
        
        <div class="card-body">
            <form method="GET" class="mb-4">
                <div class="row align-items-end mb-3">
                    <div class="col-md-4">
                        <label for="year" class="form-label">Pilih Tahun Laporan:</label>
                        <input type="number" class="form-control" id="year" name="year" 
                               value="{{ request('year', date('Y')) }}" min="2020" max="2030" required>
                    </div>
                    <div class="col-md-8">
                        <div class="btn-group">
                            <button type="submit" formaction="{{ route('admin.laporan.rekap.excel') }}" class="btn btn-success me-2">
                                <i class="fas fa-file-excel"></i> Export Semua Rekap
                            </button>
                            <button type="submit" formaction="{{ route('admin.laporan.rekap.neraca') }}" class="btn btn-primary me-2">
                                <i class="fas fa-balance-scale"></i> Rekap Neraca
                            </button>
                            <button type="submit" formaction="{{ route('admin.laporan.rekap.terkelola') }}" class="btn btn-info me-2">
                                <i class="fas fa-recycle"></i> Rekap Terkelola
                            </button>
                            <button type="submit" formaction="{{ route('admin.laporan.rekap.area') }}" class="btn btn-warning me-2">
                                <i class="fas fa-map-marker-alt"></i> Rekap Area
                            </button>
                            <button type="submit" formaction="{{ route('admin.laporan.rekap.daily') }}" class="btn btn-secondary">
                                <i class="fas fa-calendar-day"></i> Data Harian
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            @if(isset($rekapData))
            <div class="preview-area mt-4">
                <h6 class="mb-3">Preview Laporan Rekapitulasi Tahun {{ request('year', date('Y')) }}</h6>
                
                @if(isset($viewType) && $viewType == 'neraca')
                    @include('admin.laporan.partials.neraca_table')
                @elseif(isset($viewType) && $viewType == 'terkelola')
                    @include('admin.laporan.partials.terkelola_table')
                @elseif(isset($viewType) && $viewType == 'area')
                    @include('admin.laporan.partials.area_table')
                @elseif(isset($viewType) && $viewType == 'daily')
                    @include('admin.laporan.partials.daily_table')
                @else
                    <div class="alert alert-info">
                        Pilih jenis rekap yang ingin ditampilkan menggunakan tombol di atas.
                    </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validasi tahun
    const yearInput = document.getElementById('year');
    yearInput.addEventListener('change', function() {
        const year = parseInt(this.value);
        if (year < 2020) this.value = 2020;
        if (year > 2030) this.value = 2030;
    });
});
</script>
@endpush

@push('styles')
<style>
    .bg-primary {
        background-color: #1E3F8C !important;
    }
    .text-primary {
        color: #1E3F8C !important;
    }
    .btn-primary {
        background-color: #0dcaf0;
        border-color: #0dcaf0;
    }
    .preview-area {
        background-color: #fff;
        border-radius: 0.25rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    .table th {
        vertical-align: middle;
        text-align: center;
    }
    .table .text-end {
        text-align: right !important;
    }
    .table .text-center {
        text-align: center !important;
    }
</style>
@endpush

@push('styles')
<style>
    .bg-primary {
        background-color: #1E3F8C !important;
    }
    .text-primary {
        color: #1E3F8C !important;
    }
    .btn-primary {
        background-color: #0dcaf0;
        border-color: #0dcaf0;
    }
    .preview-area {
        background-color: #fff;
        border-radius: 0.25rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    .table th {
        vertical-align: middle;
        text-align: center;
    }
    .table .text-end {
        text-align: right !important;
    }
    .table .text-center {
        text-align: center !important;
    }
</style>
@endpush