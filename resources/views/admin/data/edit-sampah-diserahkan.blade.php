@extends('admin.layout')

@section('title', 'Edit Sampah Diserahkan')

@push('styles')
<style>
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: inline-block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #000;
    }
    
    .form-control, .form-select {
        display: block;
        width: 100%;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    
    .form-control:focus, .form-select:focus {
        color: #495057;
        background-color: #fff;
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    
    .custom-file {
        position: relative;
        display: inline-block;
        width: 100%;
        height: calc(1.5em + 0.75rem + 2px);
        margin-bottom: 0;
    }
    
    .custom-file-input {
        position: relative;
        z-index: 2;
        width: 100%;
        height: calc(1.5em + 0.75rem + 2px);
        margin: 0;
        opacity: 0;
    }
    
    .custom-file-label {
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        z-index: 1;
        height: calc(1.5em + 0.75rem + 2px);
        padding: 0.375rem 0.75rem;
        font-weight: 400;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }
    
    .row {
        display: flex;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px;
    }
    
    .col-md-6 {
        flex: 0 0 50%;
        max-width: 50%;
        padding-right: 15px;
        padding-left: 15px;
    }
    
    @media (max-width: 768px) {
        .col-md-6 {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="bg-primary p-4 rounded-top">
                <h4 class="text-white mb-0">Input Data Sampah</h4>
            </div>
            <div class="bg-white p-4 rounded-bottom shadow">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.data.sampah-diserahkan.update', $sampah->id) }}" method="POST" enctype="multipart/form-data" id="formEditSampah">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tgl" class="form-label">Tanggal</label>
                                <input type="date" id="tgl" name="tgl" class="form-control" 
                                    value="{{ old('tgl', $sampah->tgl ? $sampah->tgl->format('Y-m-d') : '') }}" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_lokasi" class="form-label">Sumber Sampah</label>
                                <select id="id_lokasi" name="id_lokasi" class="form-control" required>
                                    <option value="">-- Pilih Sumber --</option>
                                    @foreach($lokasiAsals as $lokasi)
                                        <option value="{{ $lokasi->id }}" {{ $sampah->id_lokasi == $lokasi->id ? 'selected' : '' }}>
                                            {{ $lokasi->nama_lokasi }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_jenis" class="form-label">Jenis Sampah</label>
                                <select id="id_jenis" name="id_jenis" class="form-control" required>
                                    <option value="">-- Pilih Jenis --</option>
                                    @foreach($jenisList as $jenis)
                                        <option value="{{ $jenis->id }}" {{ $sampah->id_jenis == $jenis->id ? 'selected' : '' }}>
                                            {{ $jenis->nama_jenis }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jumlah_berat" class="form-label">Berat (Kg)</label>
                                <input type="number" id="jumlah_berat" name="jumlah_berat" class="form-control" 
                                    step="0.01" min="0" placeholder="0.00" 
                                    value="{{ old('jumlah_berat', $sampah->jumlah_berat) }}" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_diserahkan" class="form-label">Tujuan Sampah</label>
                                <select id="id_diserahkan" name="id_diserahkan" class="form-control" required>
                                    <option value="">-- Pilih Tujuan --</option>
                                    @foreach($tujuanSampahs as $tujuan)
                                        <option value="{{ $tujuan->id }}" {{ $sampah->id_diserahkan == $tujuan->id ? 'selected' : '' }}>
                                            {{ $tujuan->nama_tujuan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="foto" class="form-label">Foto</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="foto" name="foto" accept="image/*">
                                        <label class="custom-file-label" for="foto">Choose file</label>
                                    </div>
                                </div>
                                <small class="text-muted">* Format: JPG, PNG, GIF (Max 2MB)</small>
                                @if($sampah->foto)
                                <div class="mt-2">
                                    <a href="{{ asset('storage/' . $sampah->foto) }}" target="_blank" class="btn btn-sm btn-info">
                                        <i class="fas fa-image"></i> Lihat Foto Saat Ini
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-start mt-4">
                        <button type="button" class="btn btn-secondary me-2" id="btnBack">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </button>
                        <button type="button" class="btn btn-primary" id="btnSubmit">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('formEditSampah');
    const btnSubmit = document.getElementById('btnSubmit');
    const btnBack = document.getElementById('btnBack');

    let formChanged = false;
    window.isSubmitting = false;

    // Custom file input
    const fileInput = document.getElementById('foto');
    const fileLabel = document.querySelector('.custom-file-label');
    
    if (fileInput && fileLabel) {
        fileInput.addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'Choose file';
            fileLabel.textContent = fileName;
        });
    }

    // Deteksi perubahan form
    form.addEventListener('input', () => {
        formChanged = true;
    });

    // ✅ Fungsi reusable SweetAlert
    function showConfirm(title, text, confirmText, callback) {
        Swal.fire({
            title,
            text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: confirmText,
            cancelButtonText: 'Batal',
            confirmButtonColor: '#0dcaf0',
            cancelButtonColor: '#6c757d'
        }).then(result => {
            if (result.isConfirmed) callback();
        });
    }

    // ✅ Tombol SIMPAN
    btnSubmit.addEventListener('click', function () {
        showConfirm(
            'Simpan Perubahan?',
            'Pastikan data sudah benar sebelum melanjutkan.',
            'Ya, Simpan',
            () => {
                window.isSubmitting = true;
                formChanged = false;
                form.submit();
            }
        );
    });

    // ✅ Tombol KEMBALI
    btnBack.addEventListener('click', function (e) {
        e.preventDefault();
        if (formChanged) {
            showConfirm(
                'Konfirmasi',
                'Apakah anda yakin ingin kembali? Perubahan yang belum disimpan akan hilang.',
                'Ya, Kembali',
                () => {
                    formChanged = false;
                    window.location.href = "{{ route('admin.data.sampah-diserahkan') }}";
                }
            );
        } else {
            window.location.href = "{{ route('admin.data.sampah-diserahkan') }}";
        }
    });

    // ✅ Proteksi saat menutup tab (hanya jika ada perubahan & belum submit)
    window.addEventListener('beforeunload', function (e) {
        if (formChanged && !window.isSubmitting) {
            e.preventDefault();
            e.returnValue = '';
        }
    });
});
</script>
@endpush
