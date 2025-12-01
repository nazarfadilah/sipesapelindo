@extends('superAdmin.layout')

@section('title', 'Data Dokumen')

@section('content')
<div class="content-area-table">
    <div class="bg-primary text-white p-3 rounded mb-4">
        <h3 class="mb-0">Data Dokumen</h3>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <a href="{{ route('superadmin.master.dokumen.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Data
                </a>
            </div>
            
            <div class="table-responsive">
                <table id="dokumenTable" class="table table-bordered table-striped">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="25%">Nama Dokumen</th>
                            <th width="15%" class="text-center">File</th>
                            <th width="25%">Keterangan</th>
                            <th width="15%" class="text-center">Waktu Upload</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dokumens as $index => $dok)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $dok->nama_dokumen }}</td>
                            <td class="text-center">
                                @if($dok->file_dokumen)
                                    <a href="{{ asset('storage/' . $dok->file_dokumen) }}" target="_blank" class="btn btn-sm btn-light">
                                        <i class="fas fa-file-pdf"></i> Lihat File
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $dok->keterangan ?? '-' }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($dok->created_at)->format('d/m/Y H:i') }}</td>
                            <td class="text-center">
                                <a href="{{ route('superadmin.master.dokumen.edit', $dok->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $dok->id }})">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                                <form id="delete-form-{{ $dok->id }}" action="{{ route('superadmin.master.dokumen.destroy', $dok->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data</td>
                        </tr>
                        @endforelse
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
        $('#dokumenTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            },
            "pageLength": 10,
            "ordering": true,
            "searching": true,
            "info": false
        });
    });

    function confirmDelete(id) {
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin menghapus data dokumen ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endpush

@push('styles')
<style>
    .bg-primary {
        background-color: #1E3F8C !important;
    }
    .btn-primary {
        background-color: #0dcaf0;
        border-color: #0dcaf0;
        color: #fff;
    }
    .btn-primary:hover {
        background-color: #0bb5d6;
        border-color: #0bb5d6;
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
