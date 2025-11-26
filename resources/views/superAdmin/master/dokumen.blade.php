@extends('superAdmin.layout')

@section('title', 'Lihat Semua Data Dokumen')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div style="background-color: #1E3F8C" class="p-4 rounded-top">
                <h4 class="text-white mb-0">Semua Data Dokumen</h4>
            </div>
            <div class="bg-white p-4 rounded-bottom shadow">
                <div class="table-responsive">
                    <table id="dokumen-table" class="table table-striped table-bordered w-100">
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
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#dokumen-table').DataTable({
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
