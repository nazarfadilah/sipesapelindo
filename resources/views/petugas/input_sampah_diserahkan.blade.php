@extends('petugas.layout')

@section('title', 'Input Data Sampah Diserahkan')

@push('styles')
<style>
    body {
        background-color: #f8f9fa;
    }
    
    .rounded-top {
        border-top-left-radius: 8px !important;
        border-top-right-radius: 8px !important;
    }
    
    .rounded-bottom {
        border-bottom-left-radius: 8px !important;
        border-bottom-right-radius: 8px !important;
    }
    
    .bg-primary {
        background-color: #1e3f8c !important;
    }
    .form-container {
        max-width: 100%;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-label {
        font-weight: 600;
        margin-bottom: 8px;
        color: #333;
    }
    
    .form-control {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
    }
    
    .btn-primary {
        background-color: #1e3f8c;
        border: none;
        padding: 8px 20px;
        border-radius: 4px;
        color: #fff;
        font-weight: 600;
        cursor: pointer;
    }
    
    .btn-secondary {
        background-color: #fff;
        border: 1px solid #ddd;
        padding: 8px 20px;
        border-radius: 4px;
        color: #333;
        font-weight: 600;
        cursor: pointer;
        margin-right: 10px;
    }
    
    .btn-primary:hover {
        background-color: #0099cc;
    }
    
    .btn-secondary:hover {
        background-color: #f0f0f0;
    }
    
    .page-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 20px;
    }
    
    .input-group {
        position: relative;
        display: flex;
        flex-wrap: wrap;
        align-items: stretch;
        width: 100%;
    }
    
    .input-group-text {
        display: flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        font-weight: 400;
        line-height: 1.5;
        text-align: center;
        white-space: nowrap;
        background-color: #e9ecef;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
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
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
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
    
    /* Responsive styles */
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
                <form action="{{ route('petugas.store-sampah-diserahkan') }}" method="POST" enctype="multipart/form-data" id="formSampah">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tgl" class="form-label">Tanggal</label>
                        <input type="date" id="tgl" name="tgl" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="id_lokasi" class="form-label">Sumber Sampah</label>
                        <select id="id_lokasi" name="id_lokasi" class="form-control" required>
                            <option value="" selected disabled>-- Pilih Sumber --</option>
                            @foreach($lokasiAsals as $lokasi)
                                <option value="{{ $lokasi->id }}">{{ $lokasi->nama_lokasi }}</option>
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
                            <option value="" selected disabled>-- Pilih Jenis --</option>
                            @foreach($jenisAll as $jenis)
                                <option value="{{ $jenis->id }}">{{ $jenis->nama_jenis }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="jumlah_berat" class="form-label">Berat (Kg)</label>
                        <input type="number" id="jumlah_berat" name="jumlah_berat" class="form-control" step="0.01" min="0" placeholder="0.00" required>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="id_diserahkan" class="form-label">Tujuan Sampah</label>
                        <select id="id_diserahkan" name="id_diserahkan" class="form-control" required>
                            <option value="" selected disabled>-- Pilih Tujuan --</option>
                            @foreach($tujuanSampahs as $tujuan)
                                <option value="{{ $tujuan->id }}">{{ $tujuan->nama_tujuan }}</option>
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
                        <small class="text-white">* Format: JPG, PNG, GIF (Max 2MB)</small>
                    </div>
                </div>
            </div>
            
            <input type="hidden" name="id_user" value="{{ Auth::id() }}">
            
            <div class="form-group" style="margin-top: 30px;">
                <a href="{{ route('petugas.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Simpan
                </button>
            </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@include('components.form-confirmation')

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Update file input label with selected filename
        document.querySelector('.custom-file-input').addEventListener('change', function(e) {
            const fileName = e.target.files[0].name;
            const label = e.target.nextElementSibling;
            label.innerHTML = fileName;
        });
    });
</script>
@endpush