@extends('superAdmin.layout')

@section('title', 'Edit Sampah Terkelola')

@section('content')
<div class="content-area-form">
    
    <div class="card">
        <div class="card-body bg-primary text-white">
            <h5 class="mb-0">Edit Sampah Terkelola</h5>
        </div>
        
        <div class="card-body">
            <form action="{{ route('superadmin.master.sampah-terkelola.update', $sampahTerkelola->id_sampah) }}" method="POST" id="sampahTerkelolaForm">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="tgl" class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('tgl') is-invalid @enderror" id="tgl" name="tgl" value="{{ old('tgl', \Carbon\Carbon::parse($sampahTerkelola->tgl)->format('Y-m-d')) }}" required>
                        @error('tgl')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="id_user" class="form-label">User <span class="text-danger">*</span></label>
                        <select class="form-select @error('id_user') is-invalid @enderror" id="id_user" name="id_user" required>
                            <option value="">-- Pilih User --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('id_user', $sampahTerkelola->id_user) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('id_user')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="id_lokasi" class="form-label">Lokasi Asal <span class="text-danger">*</span></label>
                        <select class="form-select @error('id_lokasi') is-invalid @enderror" id="id_lokasi" name="id_lokasi" required>
                            <option value="">-- Pilih Lokasi Asal --</option>
                            @foreach($lokasiAsals as $lokasi)
                                <option value="{{ $lokasi->id }}" {{ old('id_lokasi', $sampahTerkelola->id_lokasi) == $lokasi->id ? 'selected' : '' }}>{{ $lokasi->nama_lokasi }}</option>
                            @endforeach
                        </select>
                        @error('id_lokasi')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="id_jenis" class="form-label">Jenis Sampah <span class="text-danger">*</span></label>
                        <select class="form-select @error('id_jenis') is-invalid @enderror" id="id_jenis" name="id_jenis" required>
                            <option value="">-- Pilih Jenis Sampah --</option>
                            @foreach($jenises as $jen)
                                <option value="{{ $jen->id }}" {{ old('id_jenis', $sampahTerkelola->id_jenis) == $jen->id ? 'selected' : '' }}>{{ $jen->nama_jenis }}</option>
                            @endforeach
                        </select>
                        @error('id_jenis')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="jumlah_berat" class="form-label">Jumlah Berat (Kg) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control @error('jumlah_berat') is-invalid @enderror" id="jumlah_berat" name="jumlah_berat" value="{{ old('jumlah_berat', $sampahTerkelola->jumlah_berat) }}" required>
                        @error('jumlah_berat')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                
                <div class="d-flex">
                    <button type="button" class="btn btn-secondary me-2" onclick="confirmBack('{{ route('superadmin.master.sampah-terkelola') }}')">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    setupFormConfirmation('sampahTerkelolaForm', 'Apakah Anda yakin ingin menyimpan perubahan data sampah terkelola ini?');
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
    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }
</style>
@endpush
