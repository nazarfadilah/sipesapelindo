@extends('superAdmin.layout')

@section('title', 'Lihat Semua Data')

@section('content')
<div class="content-area">
    <div class="bg-primary text-white p-3 rounded mb-4">
        <h3 class="mb-0">Semua Data Sampah > Sampah Terkelola</h3>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Jenis</th>
                            <th class="text-center">Sumber</th>
                            <th class="text-center">Berat (kg)</th>
                            <th class="text-center">Foto</th>
                            <th class="text-center">Tanggal</th>
                        </tr>
                    </thead>
            <tbody>
                @forelse($sampahTerkelolas as $index => $sampah)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $sampah->jenis->nama_jenis ?? 'Unknown' }}</td>
                    <td>{{ $sampah->lokasi->nama_lokasi ?? 'Unknown' }}</td>
                    <td class="text-center">{{ number_format($sampah->jumlah_berat, 2) }}</td>
                    <td class="text-center">
                        @if($sampah->foto)
                            <a href="{{ asset('storage/' . $sampah->foto) }}" target="_blank" class="btn btn-sm btn-light">
                                Lihat Foto
                            </a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($sampah->tgl)->format('d-m-Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data sampah terkelola</td>
                </tr>
                @endforelse
            </tbody>
        </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item active"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">4</a></li>
                        <li class="page-item"><a class="page-link" href="#">5</a></li>
                        <li class="page-item"><a class="page-link" href="#">6</a></li>
                        <li class="page-item"><a class="page-link" href="#">7</a></li>
                        <li class="page-item"><a class="page-link" href="#">8</a></li>
                        <li class="page-item"><a class="page-link" href="#">9</a></li>
                        <li class="page-item"><a class="page-link" href="#">10</a></li>
                        <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                    </ul>
                </nav>
            </div>
            
            <div class="dropdown d-flex justify-content-center mt-2">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    10 Baris
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="#">10 Baris</a></li>
                    <li><a class="dropdown-item" href="#">25 Baris</a></li>
                    <li><a class="dropdown-item" href="#">50 Baris</a></li>
                    <li><a class="dropdown-item" href="#">100 Baris</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .pagination {
        justify-content: center;
    }
    .bg-primary {
        background-color: #1E3F8C !important;
    }
    .btn-light {
        background-color: #f8f9fa;
        border-color: #ddd;
    }
    .table th {
        vertical-align: middle;
    }
</style>
@endpush