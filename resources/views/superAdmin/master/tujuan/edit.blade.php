@extends('superAdmin.layout')

@section('title', 'Edit Tujuan Sampah')

@section('content')
<div class="content-area-form">
    
    <div class="card">
        <div class="card-body bg-primary text-white">
            <h5 class="mb-0">Edit Tujuan Sampah</h5>
        </div>
        
        <div class="card-body">
            <form action="{{ route('superadmin.master.tujuan.update', $tujuanSampah->id) }}" method="POST" id="tujuanForm">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="nama_tujuan" class="form-label">Nama Tujuan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_tujuan') is-invalid @enderror" id="nama_tujuan" name="nama_tujuan" value="{{ old('nama_tujuan', $tujuanSampah->nama_tujuan) }}" required>
                        @error('nama_tujuan')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                
                <div class="d-flex">
                    <button type="button" class="btn btn-secondary me-2" onclick="confirmBack('{{ route('superadmin.master.tujuan-sampah') }}')">
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
    setupFormConfirmation('tujuanForm', 'Apakah Anda yakin ingin menyimpan perubahan data tujuan sampah ini?');
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
