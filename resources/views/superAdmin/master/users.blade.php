@extends('superAdmin.layout')

@section('title', 'Lihat Semua Data')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div style="background-color: #1E3F8C" class="p-4 rounded-top">
                <h4 class="text-white mb-0">Semua Data Petugas</h4>
            </div>
            <div class="bg-white p-4 rounded-bottom shadow">
                <div class="table-responsive">
                    <table id="users-table" class="table table-striped table-bordered w-100">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="30%">Email</th>
                                <th width="30%">Nama Lengkap</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->name }}</td>
                            <td class="text-center">
                                <a href="#" class="btn btn-sm btn-info">
                                    Edit
                                </a>
                                <a href="#" class="btn btn-sm btn-danger">
                                    Hapus
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data petugas</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    $(document).ready(function() {
        $('#users-table').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
            pagingType: "simple_numbers",
            info: false,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ baris",
                zeroRecords: "Tidak ditemukan data yang sesuai",
                paginate: {
                    first: "Awal",
                    last: "Akhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            }
        });
    });
</script>
@endpush

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<style>
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .data-table th {
        background-color: #1E3F8C;
        color: white;
        padding: 12px;
        text-align: center;
        font-weight: 600;
    }
    
    .data-table td {
        background-color: white;
        padding: 10px;
        border: 0.5px solid #ddd;
    }
    
    .data-table tr:hover td {
        background-color: #f5f5f5;
    }
    
    .btn-info, .btn-danger {
        padding: 5px 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        margin: 0 2px;
    }
    
    .btn-info {
        background-color: #17a2b8;
        color: white;
    }
    
    .btn-danger {
        background-color: #dc3545;
        color: white;
    }
    
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 15px;
    }
    
    .dataTables_wrapper .dataTables_paginate {
        margin-top: 15px;
    }
    
    @media (max-width: 768px) {
        .data-table {
            font-size: 0.8rem;
        }
        .data-table th,
        .data-table td {
            padding: 8px;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('.data-table').DataTable({
            "language": {
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Tidak ada data yang ditemukan",
                "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data yang tersedia",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "search": "Cari:",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            },
            "pageLength": 10,
            "order": [[0, 'asc']],
        });
    });
</script>
@endpush