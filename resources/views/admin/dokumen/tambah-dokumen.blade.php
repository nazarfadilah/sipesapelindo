@extends('admin.layout')

@section('title', 'Tambah Dokumen')

@section('content')
<div class="content-area">
    
    <div class="card">
        <div class="card-body bg-primary text-white">
            <h5 class="mb-0">Tambah Dokumen</h5>
        </div>
        
        <div class="card-body">
            <form action="{{ route('admin.dokumen.store') }}" method="POST" enctype="multipart/form-data" id="dokumenForm">
                @csrf
                <input type="hidden" name="id_user" value="{{ auth()->id() }}">
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="judul_dokumen" class="form-label">Judul</label>
                        <input type="text" class="form-control @error('judul_dokumen') is-invalid @enderror" id="judul_dokumen" name="judul_dokumen" value="{{ old('judul_dokumen') }}" required>
                        @error('judul_dokumen')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="file_dokumen" class="form-label">File</label>
                        <input type="file" class="form-control @error('file_dokumen') is-invalid @enderror" id="file_dokumen" name="file_dokumen" required>
                        @error('file_dokumen')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="berlaku" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control @error('berlaku') is-invalid @enderror" id="berlaku" name="berlaku" value="{{ old('berlaku') }}" required>
                        @error('berlaku')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="berakhir" class="form-label">Tanggal Akhir</label>
                        <input type="date" class="form-control @error('berakhir') is-invalid @enderror" id="berakhir" name="berakhir" value="{{ old('berakhir') }}" required>
                        @error('berakhir')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="keterangan_dokumen" class="form-label">Keterangan</label>
                        <textarea class="form-control @error('keterangan_dokumen') is-invalid @enderror" id="keterangan_dokumen" name="keterangan_dokumen" rows="3">{{ old('keterangan_dokumen') }}</textarea>
                        @error('keterangan_dokumen')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                
                <div class="d-flex">
                    <button type="button" class="btn btn-secondary me-2" onclick="confirmBack()">
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
    let isSubmitting = false;

    document.getElementById('dokumenForm').addEventListener('submit', function(e) {
        if (isSubmitting) {
            e.preventDefault();
            return;
        }

        e.preventDefault();
        
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin menyimpan data dokumen ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                isSubmitting = true;
                this.submit();
            }
        });
    });

    function confirmBack() {
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin kembali? Data yang telah dimasukkan tidak akan tersimpan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Kembali',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('admin.dokumen.index') }}";
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
    .text-primary {
        color: #1E3F8C !important;
    }
    .nav-tabs-container {
        background-color: #1E3F8C;
        border-radius: 5px;
        padding: 5px;
    }
    .nav-pills .nav-link {
        border-radius: 5px;
        padding: 10px 15px;
        margin-right: 2px;
    }
    .nav-pills .nav-link.active {
        background-color: white;
        color: #ffffff !important;
        font-weight: bold;
    }
    .btn-primary {
        background-color: #0dcaf0;
        border-color: #0dcaf0;
        color: #fff;
    }
    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }
</style>
@endpush
