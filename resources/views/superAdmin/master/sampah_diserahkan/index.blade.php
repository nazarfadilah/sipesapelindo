@extends('superAdmin.layout')

@section('title', 'Data Sampah Diserahkan')

@section('content')
<div class="content-area-table">
    <div class="bg-primary text-white p-3 rounded mb-4">
        <h3 class="mb-0">Data Sampah Diserahkan</h3>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <a href="{{ route('superadmin.master.sampah-diserahkan.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Data
                </a>
            </div>
            
            <div class="table-responsive">
                <table id="sampahDiserahkanTable" class="table table-bordered table-striped">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="10%" class="text-center">Tanggal</th>
                            <th width="15%">User</th>
                            <th width="15%">Lokasi Asal</th>
                            <th width="12%">Jenis Sampah</th>
                            <th width="15%">Tujuan</th>
                            <th width="13%" class="text-center">Jumlah Berat (Kg)</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sampahDiserahkans as $index => $sampah)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($sampah->tgl)->format('d/m/Y') }}</td>
                            <td>{{ $sampah->user->name ?? '-' }}</td>
                            <td>{{ $sampah->lokasiAsal->nama_lokasi ?? '-' }}</td>
                            <td>{{ $sampah->jenis->nama_jenis ?? '-' }}</td>
                            <td>{{ $sampah->tujuanSampah->nama_tujuan ?? '-' }}</td>
                            <td class="text-center">{{ number_format($sampah->jumlah_berat, 2) }}</td>
                            <td class="text-center">
                                <a href="{{ route('superadmin.master.sampah-diserahkan.edit', $sampah->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $sampah->id }})">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                                <form id="delete-form-{{ $sampah->id }}" action="{{ route('superadmin.master.sampah-diserahkan.destroy', $sampah->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data</td>
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
        $('#sampahDiserahkanTable').DataTable({
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
            text: 'Apakah Anda yakin ingin menghapus data sampah diserahkan ini?',
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
    .table th {
        vertical-align: middle;
    }
</style>
@endpush
