@extends('superAdmin.layout')

@section('title', 'Lihat Semua Data')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div style="background-color: #1E3F8C" class="p-4 rounded-top">
                <h4 class="text-white mb-0">Semua Data Sampah > Jenis Sampah</h4>
            </div>
            <div class="bg-white p-4 rounded-bottom shadow">
                <div class="table-responsive">
                    <table id="jenis-sampah-table" class="table table-striped table-bordered w-100">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th width="10%" class="text-center">No</th>
                            <th width="90%" class="text-center">Nama Jenis</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jenisSampah as $index => $jenis)
                        <tr>
                            <td class="text-center">{{ ($jenisSampah->currentPage() - 1) * $jenisSampah->perPage() + $index + 1 }}</td>
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
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#jenis-sampah-table').DataTable({
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