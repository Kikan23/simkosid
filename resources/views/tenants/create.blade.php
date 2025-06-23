@extends('sidebar')

@section('content')
<!-- Add padding-top to account for fixed header -->
<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4" style="padding-top: 83px !important;">

  <!-- Tetap di dalam page-inner tapi dengan override CSS -->
  <div class="page-inner" style="max-width: none !important; width: 100% !important; padding: 0 15px !important;">
    
    
    <!-- Form Container -->
    <div class="row">
      <div class="col-12">
        <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); min-height: 500px; position: relative;">
          <h2 style="color: #333 !important; font-size: 24px !important; margin-bottom: 30px !important; font-weight: 600 !important; border-bottom: 2px solid #eee; padding-bottom: 10px;">
            Form Tambah Penghuni
          </h2>
          
          <form method="POST" action="{{ route('tenants.store') }}" enctype="multipart/form-data">
            @csrf
            
            <!-- Data Personal Section -->
            <!-- Nama Lengkap - Full Width -->
            <div class="row">
              <div class="col-12 mb-3">
                <label class="form-label" style="color: #495057; font-weight: 500;">
                  Nama Lengkap
                </label>
                <input
                  type="text"
                  name="nama_penyewa"
                  class="form-control"
                  placeholder="Masukkan nama lengkap"
                  value="{{ old('nama_penyewa') }}"
                  required
                />
                @error('nama_penyewa')
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>
            </div>

            <!-- Nomor KTP dan Telepon -->
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label" style="color: #495057; font-weight: 500;">
                  Nomor KTP
                </label>
                <input
                  type="text"
                  name="nomor_ktp"
                  class="form-control"
                  placeholder="Masukkan nomor KTP"
                  value="{{ old('nomor_ktp') }}"
                  maxlength="20"
                  required
                />
                @error('nomor_ktp')
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label" style="color: #495057; font-weight: 500;">
                  Nomor Telepon
                </label>
                <input
                  type="tel"
                  name="telepon"
                  class="form-control"
                  placeholder="Contoh: 08123456789"
                  value="{{ old('telepon') }}"
                  required
                />
                @error('telepon')
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>
            </div>

            <!-- Email dan Nomor Kamar -->
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label" style="color: #495057; font-weight: 500;">
                  Email
                </label>
                <input
                  type="email"
                  name="email"
                  class="form-control"
                  placeholder="contoh@email.com"
                  value="{{ old('email') }}"
                />
                @error('email')
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label" style="color: #495057; font-weight: 500;">
                  Nomor Kamar
                </label>
                <select name="kamar_id" class="form-select" required>
                    <option value="">Pilih Nomor Kamar</option>
                    @foreach($availableRooms as $room)
                        <option value="{{ $room->id }}" 
                            data-tipe="{{ $room->tipe_kamar }}"
                            {{ old('kamar_id') == $room->id ? 'selected' : '' }}>
                            {{ $room->nomor_kamar }} ({{ ucfirst($room->tipe_kamar) }})
                        </option>
                    @endforeach
                </select>
                @error('kamar_id')
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>
            </div>

            <!-- Data Kontrak Section -->
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label" style="color: #495057; font-weight: 500;">
                  Tanggal Masuk
                </label>
                <input
                  type="date"
                  name="tanggal_masuk"
                  class="form-control"
                  value="{{ old('tanggal_masuk') }}"
                  required
                />
                @error('tanggal_masuk')
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>
              
              <div class="col-md-6 mb-3">
                <label class="form-label" style="color: #495057; font-weight: 500;">
                  Tanggal Keluar (Opsional)
                </label>
                <input
                  type="date"
                  name="tanggal_keluar"
                  class="form-control"
                  value="{{ old('tanggal_keluar') }}"
                />
                @error('tanggal_keluar')
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>
            </div>

            <!-- Data Dokumen Section -->
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label" style="color: #495057; font-weight: 500;">
                  Foto KTP (Opsional)
                </label>
                <input
                  type="file"
                  name="foto_ktp"
                  accept="image/*,.pdf"
                  class="form-control"
                />
                <small class="text-muted">
                  Format yang didukung: JPG, JPEG, PNG, PDF. Maksimal 2MB.
                </small>
                @error('foto_ktp')
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label" style="color: #495057; font-weight: 500;">
                  Dokumen Kontrak (Opsional)
                </label>
                <input
                  type="file"
                  name="dokumen_kontrak"
                  accept="image/*,.pdf"
                  class="form-control"
                />
                <small class="text-muted">
                  Format yang didukung: JPG, JPEG, PNG, PDF. Maksimal 5MB.
                </small>
                @error('dokumen_kontrak')
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>
            </div>

            <!-- Informasi Status -->
            <div class="row">
              <div class="col-12 mb-3">
                <div class="alert alert-info" style="background-color: #e8f4fd; border-color: #bee5eb; color: #0c5460;">
                  <i class="fas fa-info-circle me-2"></i>
                  <strong>Info:</strong> Status kamar akan otomatis menjadi "Dihuni" jika status penghuni "Aktif".
                </div>
              </div>
            </div>

            <!-- Submit Buttons -->
            <div class="row">
              <div class="col-12">
                <div class="d-flex justify-content-end gap-3 mt-4 pt-3" style="border-top: 1px solid #eee;">
                    <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-2"></i>
                    Simpan
                    </button>
                  
                  <a href="{{ route('tenants.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>
                    Batal
                  </a>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    
  </div>
</div>
@endsection