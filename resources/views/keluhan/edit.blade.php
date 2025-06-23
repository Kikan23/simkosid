{{-- File: resources/views/keluhan/edit.blade.php --}}
@extends('sidebar')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Edit Keluhan</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('keluhan.index') }}">Keluhan</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Data Keluhan</h4>
                </div>
                <form method="POST" action="{{ route('keluhan.update', $keluhan->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="tenant_id">Penghuni <span class="text-danger">*</span></label>
                                    <select class="form-control @error('tenant_id') is-invalid @enderror" 
                                            id="tenant_id" 
                                            name="tenant_id" 
                                            required>
                                        <option value="">Pilih Penghuni</option>
                                        @foreach($tenants as $tenant)
                                            <option value="{{ $tenant->id }}" 
                                                    {{ old('tenant_id', $keluhan->tenant_id) == $tenant->id ? 'selected' : '' }}>
                                                {{ $tenant->nama_penyewa }} - Kamar {{ $tenant->kamar ? $tenant->kamar->nomor_kamar : 'N/A' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('tenant_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="tanggal_keluhan">Tanggal Keluhan <span class="text-danger">*</span></label>
                                    <input type="date" 
                                           class="form-control @error('tanggal_keluhan') is-invalid @enderror" 
                                           id="tanggal_keluhan" 
                                           name="tanggal_keluhan" 
                                           value="{{ old('tanggal_keluhan', $keluhan->tanggal_keluhan->format('Y-m-d')) }}"
                                           required>
                                    @error('tanggal_keluhan')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label>Status Keluhan <span class="text-danger">*</span></label>
                                    <div class="mt-2">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input @error('status_keluhan') is-invalid @enderror" 
                                                   type="radio" 
                                                   name="status_keluhan" 
                                                   id="status_pending" 
                                                   value="pending"
                                                   {{ old('status_keluhan', $keluhan->status_keluhan) == 'pending' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status_pending">
                                                <span class="badge badge-warning">Pending</span>
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input @error('status_keluhan') is-invalid @enderror" 
                                                   type="radio" 
                                                   name="status_keluhan" 
                                                   id="status_diproses" 
                                                   value="diproses"
                                                   {{ old('status_keluhan', $keluhan->status_keluhan) == 'diproses' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status_diproses">
                                                <span class="badge badge-info">Diproses</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input @error('status_keluhan') is-invalid @enderror" 
                                                   type="radio" 
                                                   name="status_keluhan" 
                                                   id="status_selesai" 
                                                   value="selesai"
                                                   {{ old('status_keluhan', $keluhan->status_keluhan) == 'selesai' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status_selesai">
                                                <span class="badge badge-success">Selesai</span>
                                            </label>
                                        </div>
                                    </div>
                                    @error('status_keluhan')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="deskripsi_keluhan">Deskripsi Keluhan <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('deskripsi_keluhan') is-invalid @enderror" 
                                              id="deskripsi_keluhan" 
                                              name="deskripsi_keluhan" 
                                              rows="4"
                                              placeholder="Jelaskan keluhan secara detail..."
                                              required>{{ old('deskripsi_keluhan', $keluhan->deskripsi_keluhan) }}</textarea>
                                    @error('deskripsi_keluhan')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="tanggal_penyelesaian">Tanggal Penyelesaian</label>
                                    <input type="date" 
                                           class="form-control @error('tanggal_penyelesaian') is-invalid @enderror" 
                                           id="tanggal_penyelesaian" 
                                           name="tanggal_penyelesaian" 
                                           value="{{ old('tanggal_penyelesaian', $keluhan->tanggal_penyelesaian ? $keluhan->tanggal_penyelesaian->format('Y-m-d') : '') }}"
                                           min="{{ $keluhan->tanggal_keluhan->format('Y-m-d') }}">
                                    <small class="form-text text-muted">Diisi jika status keluhan adalah 'Selesai'</small>
                                    @error('tanggal_penyelesaian')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="catatan_penyelesaian">Catatan Penyelesaian</label>
                                    <textarea class="form-control @error('catatan_penyelesaian') is-invalid @enderror" 
                                              id="catatan_penyelesaian" 
                                              name="catatan_penyelesaian" 
                                              rows="3"
                                              placeholder="Catatan tambahan tentang penyelesaian keluhan...">{{ old('catatan_penyelesaian', $keluhan->catatan_penyelesaian) }}</textarea>
                                    <small class="form-text text-muted">Diisi jika status keluhan adalah 'Selesai'</small>
                                    @error('catatan_penyelesaian')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Keluhan
                                </button>
                                <a href="{{ route('keluhan.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-fill tanggal penyelesaian when status is set to 'selesai'
    const statusRadios = document.querySelectorAll('input[name="status_keluhan"]');
    const tanggalPenyelesaian = document.getElementById('tanggal_penyelesaian');
    
    statusRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'selesai' && !tanggalPenyelesaian.value) {
                tanggalPenyelesaian.value = new Date().toISOString().split('T')[0];
            }
        });
    });
});
</script>
@endsection