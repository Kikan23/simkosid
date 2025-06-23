{{-- File: resources/views/keluhan/create.blade.php --}}
@extends('sidebar')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Tambah Keluhan</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('keluhan.index') }}">Keluhan</a></li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Formulir Keluhan Baru</h4>
                </div>
                <form method="POST" action="{{ route('keluhan.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="tenant_id">Pilih Penghuni <span class="text-danger">*</span></label>
                                    <select class="form-control @error('tenant_id') is-invalid @enderror" 
                                            id="tenant_id" 
                                            name="tenant_id" 
                                            required>
                                        <option value="">-- Pilih Penghuni --</option>
                                        @foreach($tenants as $tenant)
                                            <option value="{{ $tenant->id }}" {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}>
                                                Kamar {{ $tenant->kamar ? $tenant->kamar->nomor_kamar : 'N/A' }} - {{ $tenant->nama_penyewa }}
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
                                           value="{{ old('tanggal_keluhan', date('Y-m-d')) }}" 
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
                                                   {{ old('status_keluhan', 'pending') == 'pending' ? 'checked' : '' }} 
                                                   required>
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
                                                   {{ old('status_keluhan') == 'diproses' ? 'checked' : '' }} 
                                                   required>
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
                                                   {{ old('status_keluhan') == 'selesai' ? 'checked' : '' }} 
                                                   required>
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
                                              rows="8" 
                                              placeholder="Jelaskan keluhan secara detail..." 
                                              required>{{ old('deskripsi_keluhan') }}</textarea>
                                    @error('deskripsi_keluhan')
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
                                    <i class="fas fa-save"></i> Simpan Keluhan
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
@endsection