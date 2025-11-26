@extends('superAdmin.layout')

@section('title', 'Lihat Semua Data')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div style="background-color: #1E3F8C" class="p-4 rounded-top">
                <h4 class="text-white mb-0">Semua Data Sampah > Sampah Terkelola</h4>
            </div>
            <div class="bg-white p-4 rounded-bottom shadow">
                <div class="table-responsive">
                    <table id="sampah-terkelola-table" class="table table-striped table-bordered w-100">
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
                    <td>{{ $sampah->lokasiAsal->nama_lokasi ?? 'Unknown' }}</td>
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
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#sampah-terkelola-table').DataTable({
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