@extends('superAdmin.layout')

@section('title', 'Lihat Semua Data')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div style="background-color: #1E3F8C" class="p-4 rounded-top">
                <h4 class="text-white mb-0">Semua Data Sampah > Tujuan Sampah</h4>
            </div>
            <div class="bg-white p-4 rounded-bottom shadow">
                <div class="table-responsive">
                    <table id="tujuan-sampah-table" class="table table-striped table-bordered w-100">
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
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#tujuan-sampah-table').DataTable({
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