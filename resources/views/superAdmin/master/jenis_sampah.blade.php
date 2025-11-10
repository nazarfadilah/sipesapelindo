@extends('superAdmin.layout')

@section('title', 'Lihat Semua Data')

@section('content')
<div class="content-area">
    <div class="bg-primary text-white p-3 rounded mb-4">
        <h3 class="mb-0">Semua Data Sampah > Jenis Sampah</h3>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th width="10%" class="text-center">No</th>
                            <th width="90%" class="text-center">Nama Jenis</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jenisSampah as $index => $jenis)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $jenis->nama_jenis }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center">Tidak ada data jenis sampah</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        {{ $jenisSampah->links() }}
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
    .table th {
        vertical-align: middle;
    }
</style>
@endpush