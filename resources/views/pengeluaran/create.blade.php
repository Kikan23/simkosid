{{-- File: resources/views/pengeluaran/create.blade.php --}}
@extends('sidebar')

@section('content')
<div class="container-fluid" style="padding-top: 90px;">
    <div class="row justify-content-center mt-3 mb-4">
        <div class="col-lg-10 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Tambah Pengeluaran Baru</h4>
                    <a href="{{ route('pengeluaran.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('pengeluaran.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="tanggal_pengeluaran">Tanggal Pengeluaran <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('tanggal_pengeluaran') is-invalid @enderror" 
                                           id="tanggal_pengeluaran" name="tanggal_pengeluaran" 
                                           value="{{ old('tanggal_pengeluaran', date('Y-m-d')) }}" required>
                                    @error('tanggal_pengeluaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="kategori">Kategori <span class="text-danger">*</span></label>
                                    <select class="form-control @error('kategori') is-invalid @enderror" 
                                            id="kategori" name="kategori" required>
                                        <option value="">Pilih Kategori</option>
                                        <option value="listrik" {{ old('kategori') == 'listrik' ? 'selected' : '' }}>Listrik</option>
                                        <option value="air" {{ old('kategori') == 'air' ? 'selected' : '' }}>Air</option>
                                        <option value="internet" {{ old('kategori') == 'internet' ? 'selected' : '' }}>Internet</option>
                                        <option value="maintenance" {{ old('kategori') == 'maintenance' ? 'selected' : '' }}>Perawatan</option>
                                        <option value="gaji" {{ old('kategori') == 'gaji' ? 'selected' : '' }}>Gaji</option>
                                        <option value="kebersihan" {{ old('kategori') == 'kebersihan' ? 'selected' : '' }}>Kebersihan</option>
                                        <option value="keamanan" {{ old('kategori') == 'keamanan' ? 'selected' : '' }}>Keamanan</option>
                                        <option value="pajak" {{ old('kategori') == 'pajak' ? 'selected' : '' }}>Pajak</option>
                                        <option value="asuransi" {{ old('kategori') == 'asuransi' ? 'selected' : '' }}>Asuransi</option>
                                        <option value="lainnya" {{ old('kategori') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    @error('kategori')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group mb-3">
                                    <label for="jenis_pengeluaran">Jenis Pengeluaran <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('jenis_pengeluaran') is-invalid @enderror" 
                                           id="jenis_pengeluaran" name="jenis_pengeluaran" 
                                           value="{{ old('jenis_pengeluaran') }}" 
                                           placeholder="Contoh: Bayar Listrik PLN, Gaji Karyawan, dll" required>
                                    @error('jenis_pengeluaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="nominal">Nominal <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="number" class="form-control @error('nominal') is-invalid @enderror" 
                                               id="nominal" name="nominal" 
                                               value="{{ old('nominal') }}" 
                                               placeholder="0" min="0" step="0.01" required>
                                        @error('nominal')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="keterangan_detail">Keterangan Detail <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('keterangan_detail') is-invalid @enderror" 
                                              id="keterangan_detail" name="keterangan_detail" rows="3" 
                                              placeholder="Jelaskan detail pengeluaran ini..." required>{{ old('keterangan_detail') }}</textarea>
                                    @error('keterangan_detail')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="catatan_tambahan">Catatan Tambahan</label>
                                    <textarea class="form-control @error('catatan_tambahan') is-invalid @enderror" 
                                              id="catatan_tambahan" name="catatan_tambahan" rows="3" 
                                              placeholder="Catatan tambahan (opsional)">{{ old('catatan_tambahan') }}</textarea>
                                    @error('catatan_tambahan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="bukti_pembayaran">Bukti Pembayaran</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('bukti_pembayaran') is-invalid @enderror" 
                                                   id="bukti_pembayaran" name="bukti_pembayaran" 
                                                   accept="image/*" onchange="previewImage(this)">
                                            <label class="custom-file-label" for="bukti_pembayaran">Pilih file...</label>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Format: JPG, PNG, GIF. Maksimal 2MB</small>
                                    @error('bukti_pembayaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <!-- Image Preview -->
                                    <div id="imagePreview" class="mt-2" style="display: none;">
                                        <img id="preview" src="#" alt="Preview" class="img-thumbnail border border-secondary p-1" style="max-width: 200px;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Pengaturan Tambahan</label>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="is_recurring" name="is_recurring" value="1" {{ old('is_recurring') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_recurring">
                                            <i class="fas fa-repeat"></i> Pengeluaran Berulang
                                        </label>
                                        <small class="form-text text-muted">Centang jika pengeluaran ini terjadi secara berkala</small>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label for="status_approval">Status Approval <span class="text-danger">*</span></label>
                                        <select class="form-control @error('status_approval') is-invalid @enderror" 
                                                id="status_approval" name="status_approval" required>
                                            <option value="">Pilih Status</option>
                                            <option value="pending" {{ old('status_approval') == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="approved" {{ old('status_approval') == 'approved' ? 'selected' : '' }}>Approved</option>
                                            <option value="rejected" {{ old('status_approval') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                        @error('status_approval')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-4 mb-0 text-right">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Pengeluaran
                            </button>
                            <a href="{{ route('pengeluaran.index') }}" class="btn btn-secondary ml-2">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#preview').attr('src', e.target.result);
            $('#imagePreview').show();
        }
        reader.readAsDataURL(input.files[0]);
        // Update label
        var fileName = input.files[0].name;
        $(input).next('.custom-file-label').html(fileName);
    }
}
</script>
@endsection