@extends('superadmin.layout')

@section('title', 'Menu Laporan')

@section('content')
<div class="content-area">
    <div class="card">
        <div class="card-body bg-primary text-white">
            <h5 class="mb-0">Menu Laporan</h5>
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
                            <button type="submit" formaction="{{ route('superadmin.laporan.rekap.excel') }}" class="btn btn-success me-2">
                                <i class="fas fa-file-excel"></i> Export Semua Rekap
                            </button>
                            <button type="submit" formaction="{{ route('superadmin.laporan.rekap.neraca') }}" class="btn btn-primary me-2">
                                <i class="fas fa-balance-scale"></i> Rekap Neraca
                            </button>
                            <button type="submit" formaction="{{ route('superadmin.laporan.rekap.terkelola') }}" class="btn btn-info me-2">
                                <i class="fas fa-recycle"></i> Rekap Terkelola
                            </button>
                            <button type="submit" formaction="{{ route('superadmin.laporan.rekap.area') }}" class="btn btn-warning me-2">
                                <i class="fas fa-map-marker-alt"></i> Rekap Area
                            </button>
                            <button type="submit" formaction="{{ route('superadmin.laporan.rekap.daily') }}" class="btn btn-secondary">
                                <i class="fas fa-calendar-day"></i> Data Harian
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Data yang ditampilkan merupakan rekapitulasi selama satu tahun penuh.
                            Pilih tahun di atas dan klik salah satu tombol untuk melihat atau mengunduh laporan yang diinginkan.
                        </div>
                    </div>
                </div>
            </form>

            @if(isset($rekapData))
            <div class="preview-area mt-4">
                <h6 class="mb-3">Preview Laporan Rekapitulasi Tahun {{ request('year', date('Y')) }}</h6>
                
                @if(isset($viewType) && $viewType == 'neraca')
                    @include('superadmin.laporan.partials.neraca_table')
                @elseif(isset($viewType) && $viewType == 'terkelola')
                    @include('superadmin.laporan.partials.terkelola_table')
                @elseif(isset($viewType) && $viewType == 'area')
                    @include('superadmin.laporan.partials.area_table')
                @elseif(isset($viewType) && $viewType == 'daily')
                    @include('superadmin.laporan.partials.daily_table')
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