@extends('petugas.layout')

@section('title', 'Lihat Riwayat Inputan - Sampah Diserahkan')

@push('styles')
<style>
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .data-table th {
        background-color: #1E3F8C;
        color: white;
        padding: 8px;
        text-align: center;
        font-weight: 600;
    }
    
    .data-table td {
        background-color: white;
        padding: 8px;
        text-align: center;
        border: 0.5px solid #ddd;
    }
    
    .data-table tr:hover td {
        background-color: #f5f5f5;
    }
    
    .btn-lihat-foto {
        background-color: #1E3F8C;
        color: white;
        padding: 5px 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
    }
    
    .btn-lihat-foto:hover {
        background-color: #15306e;
        color: white;
    }
    
    .page-title {
        margin-bottom: 20px;
        font-size: 1.5rem;
        font-weight: 600;
    }
    
    /* Adjustments for mobile */
    @media (max-width: 768px) {
        .data-table {
            font-size: 0.8rem;
        }
        
        .data-table th,
        .data-table td {
            padding: 5px;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div style="background-color: #1E3F8C" class="p-4 rounded-top">
                <h4 class="text-white mb-0">Lihat Riwayat Inputan > Sampah Diserahkan</h4>
            </div>
            <div class="bg-white p-4 rounded-bottom shadow">
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>User</th>
                                <th>Sumber</th>
                                <th>Jenis</th>
                                <th>Berat (Kg)</th>
                                <th>Tujuan</th>
                                <th>Foto</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
            <tbody>
                @forelse ($sampahDiserahkans as $index => $sampah)
                <tr>
                    <td style="color: #000;">{{ $index + 1 }}</td>
                    <td style="color: #000;">{{ $sampah->user->name ?? '-' }}</td>
                    <td style="color: #000;">{{ $sampah->lokasiAsal->nama_lokasi ?? 'Perkantoran' }}</td>
                    <td style="color: #000;">{{ $sampah->jenis->nama_jenis ?? '-' }}</td>
                    <td style="color: #000;">{{ $sampah->jumlah_berat ?? 0 }} kg</td>
                    <td style="color: #000;">{{ $sampah->tujuanSampah->nama_tujuan ?? 'Bank Sampah' }}</td>
                    <td style="color: #000;">
                        @if ($sampah->foto)
                            <a href="{{ asset('storage/' . $sampah->foto) }}" target="_blank" class="btn-lihat-foto" style="color: #000;">
                                <i class="fas fa-image me-1"></i> Lihat Foto
                            </a>
                        @else
                            <span class="text-muted" style="color: #000;">Tidak ada foto</span>
                        @endif
                    </td>
                    <td style="color: #000;">{{ \Carbon\Carbon::parse($sampah->tgl)->format('d-m-Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-3">Tidak ada data sampah diserahkan</td>
                </tr>
                @endforelse
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
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