@extends('superAdmin.layout')

@section('title', 'Lihat Semua Data Dokumen')

@section('content')
<div class="content-area">
    <div class="card">
        <div class="card-body bg-primary text-white">
            <h5 class="mb-0">Semua Data Dokumen</h5>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="25%" class="text-center">Judul</th>
                            <th width="15%" class="text-center">File</th>
                            <th width="15%" class="text-center">Tanggal Mulai</th>
                            <th width="15%" class="text-center">Tanggal Akhir</th>
                            <th width="15%" class="text-center">Waktu Upload</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dokumen as $index => $dok)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $dok->judul_dokumen }}</td>
                            <td class="text-center">
                                @if($dok->file_dokumen)
                                    <a href="{{ asset('storage/' . $dok->file_dokumen) }}" target="_blank" class="btn btn-sm btn-light">
                                        <i class="fas fa-file-pdf"></i> Lihat File
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($dok->berlaku)->format('Y-m-d') }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($dok->berakhir)->format('Y-m-d') }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($dok->created_at)->format('H:i:s') }}</td>
                        </tr>
                        @empty
                        <!-- Data contoh jika tidak ada data dari database -->
                        <tr>
                            <td class="text-center">1</td>
                            <td>Rekap Pengelolaan Sampah</td>
                            <td class="text-center">
                                <a href="#" class="btn btn-sm btn-light">
                                    <i class="fas fa-file-pdf"></i> Lihat File
                                </a>
                            </td>
                            <td class="text-center">2025-09-23</td>
                            <td class="text-center">2025-09-23</td>
                            <td class="text-center">16:11:07</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(isset($dokumen) && $dokumen->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $dokumen->links() }}
            </div>
            @endif
            
            <div class="d-flex justify-content-center mt-3">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="rowsPerPageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        10 Baris
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="rowsPerPageDropdown">
                        <li><a class="dropdown-item" href="{{ request()->url() }}?per_page=10">10 Baris</a></li>
                        <li><a class="dropdown-item" href="{{ request()->url() }}?per_page=25">25 Baris</a></li>
                        <li><a class="dropdown-item" href="{{ request()->url() }}?per_page=50">50 Baris</a></li>
                        <li><a class="dropdown-item" href="{{ request()->url() }}?per_page=100">100 Baris</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .bg-primary {
        background-color: #1E3F8C !important;
    }
    .text-primary {
        color: #1E3F8C !important;
    }
    .table th {
        vertical-align: middle;
    }
    .btn-light {
        background-color: #f8f9fa;
        border-color: #ddd;
    }
    .pagination {
        margin-bottom: 0;
    }
    .page-item.active .page-link {
        background-color: #1E3F8C;
        border-color: #1E3F8C;
    }
    .page-link {
        color: #1E3F8C;
    }
</style>
@endpush
