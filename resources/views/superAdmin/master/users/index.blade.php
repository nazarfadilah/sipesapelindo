@extends('superAdmin.layout')

@section('title', 'Data User/Petugas')

@section('content')
<div class="content-area-table">
    <div class="bg-primary text-white p-3 rounded mb-4">
        <h3 class="mb-0">Data Users</h3>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <a href="{{ route('superadmin.master.users.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Data
                </a>
            </div>
            
            <div class="table-responsive">
                <table id="usersTable" class="table table-bordered table-striped">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="25%">Nama</th>
                            <th width="25%">Email</th>
                            <th width="15%" class="text-center">Role</th>
                            <!-- <th width="15%" class="text-center">Tanggal Dibuat</th> -->
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td class="text-center">
                                @if($user->role == 1)
                                    <span class="badge bg-danger">Super Admin</span>
                                @elseif($user->role == 2)
                                    <span class="badge bg-warning">Admin</span>
                                @else
                                    <span class="badge bg-info">Petugas</span>
                                @endif
                            </td>
                            <!-- <td class="text-center">{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}</td> -->
                            <td class="text-center">
                                <a href="{{ route('superadmin.master.users.edit', $user->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $user->id }})">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                                <form id="delete-form-{{ $user->id }}" action="{{ route('superadmin.master.users.destroy', $user->id) }}" method="POST" style="display: none;">
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
        $('#usersTable').DataTable({
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
            text: 'Apakah Anda yakin ingin menghapus user ini?',
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
    .badge {
        padding: 0.5em 0.8em;
        font-size: 0.85em;
    }
</style>
@endpush
