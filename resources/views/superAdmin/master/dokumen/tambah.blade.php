@extends('superAdmin.layout')

@section('title', 'Tambah Dokumen')

@section('content')
<div class="content-area-form">
    
    <div class="card">
        <div class="card-body bg-primary text-white">
            <h5 class="mb-0">Tambah Dokumen</h5>
        </div>
        
        <div class="card-body">
            <form action="{{ route('superadmin.master.dokumen.store') }}" method="POST" enctype="multipart/form-data" id="dokumenForm">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="nama_dokumen" class="form-label">Nama Dokumen <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_dokumen') is-invalid @enderror" id="nama_dokumen" name="nama_dokumen" value="{{ old('nama_dokumen') }}" required>
                        @error('nama_dokumen')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="file_dokumen" class="form-label">File Dokumen <span class="text-danger">*</span></label>
                        <input type="file" class="form-control @error('file_dokumen') is-invalid @enderror" id="file_dokumen" name="file_dokumen" required>
                        <small class="text-muted">Format: PDF, DOC, DOCX (Max: 5MB)</small>
                        @error('file_dokumen')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                
                <div class="d-flex">
                    <button type="button" class="btn btn-secondary me-2" onclick="confirmBack('{{ route('superadmin.master.dokumen') }}')">
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
    setupFormConfirmation('dokumenForm', 'Apakah Anda yakin ingin menyimpan data dokumen ini?');
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
