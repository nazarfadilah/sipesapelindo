@extends('superAdmin.layout')

@section('title', 'Lihat Semua Data')

@section('content')
<div class="content-area">
    <div class="bg-primary text-white p-3 rounded mb-4">
        <h3 class="mb-0">Semua Data Sampah > Tujuan Sampah</h3>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="20%" class="text-center">Kategori</th>
                            <th width="30%" class="text-center">Tujuan</th>
                            <th width="35%" class="text-center">Alamat</th>
                            <th width="10%" class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">1</td>
                            <td>Organik</td>
                            <td>Daur Ulang</td>
                            <td>Jl. Veteran No.17</td>
                            <td class="text-center">Aktif</td>
                        </tr>
                        <tr>
                            <td class="text-center">2</td>
                            <td>Anorganik</td>
                            <td>Bank Sampah</td>
                            <td>Jl. Kayu Tangi No.12</td>
                            <td class="text-center">Aktif</td>
                        </tr>
                        <tr>
                            <td class="text-center">3</td>
                            <td>Residu</td>
                            <td>TPA</td>
                            <td>Jl. Hasan Basri No.10</td>
                            <td class="text-center">Aktif</td>
                        </tr>
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
    .table th {
        vertical-align: middle;
    }
</style>
@endpush