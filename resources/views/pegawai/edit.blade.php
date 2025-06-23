{{-- File: resources/views/pegawai/edit.blade.php --}}
@extends('sidebar')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center mt-5">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Data Pegawai</h3>
                </div>
                <form method="POST" action="{{ route('pegawai.update', $pegawai->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_pegawai">Nama Pegawai <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('nama_pegawai') is-invalid @enderror" 
                                           id="nama_pegawai" 
                                           name="nama_pegawai" 
                                           value="{{ old('nama_pegawai', $pegawai->nama_pegawai) }}" 
                                           placeholder="Masukkan nama pegawai"
                                           required>
                                    @error('nama_pegawai')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="jobdesk">Jobdesk <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('jobdesk') is-invalid @enderror" 
                                              id="jobdesk" 
                                              name="jobdesk" 
                                              rows="3"
                                              placeholder="Masukkan jobdesk pegawai"
                                              required>{{ old('jobdesk', $pegawai->jobdesk) }}</textarea>
                                    @error('jobdesk')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="no_telepon">No. Telepon <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('no_telepon') is-invalid @enderror" 
                                           id="no_telepon" 
                                           name="no_telepon" 
                                           value="{{ old('no_telepon', $pegawai->no_telepon) }}" 
                                           placeholder="Contoh: 08123456789"
                                           required>
                                    @error('no_telepon')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jadwal_kerja">Jadwal Kerja <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('jadwal_kerja') is-invalid @enderror" 
                                           id="jadwal_kerja" 
                                           name="jadwal_kerja" 
                                           value="{{ old('jadwal_kerja', $pegawai->jadwal_kerja) }}" 
                                           placeholder="Contoh: Senin-Jumat 08:00-17:00"
                                           required>
                                    @error('jadwal_kerja')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="status_pegawai">Status Pegawai <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status_pegawai') is-invalid @enderror" 
                                            id="status_pegawai" 
                                            name="status_pegawai" 
                                            required>
                                        <option value="">Pilih Status Pegawai</option>
                                        <option value="aktif" {{ old('status_pegawai', $pegawai->status_pegawai) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="tidak_aktif" {{ old('status_pegawai', $pegawai->status_pegawai) == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                        <option value="cuti" {{ old('status_pegawai', $pegawai->status_pegawai) == 'cuti' ? 'selected' : '' }}>Cuti</option>
                                    </select>
                                    @error('status_pegawai')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('pegawai.index') }}" class="btn btn-secondary mr-2">
                                <i class="fas fa-times"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection