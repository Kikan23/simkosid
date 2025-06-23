{{-- File: resources/views/pembayaran/edit.blade.php --}}
@extends('sidebar')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Pembayaran</h3>
                </div>
                <form method="POST" action="{{ route('pembayaran.update', $pembayaran->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tenant_id">Pilih Penghuni <span class="text-danger">*</span></label>
                                    <select class="form-control @error('tenant_id') is-invalid @enderror" 
                                            id="tenant_id" 
                                            name="tenant_id" 
                                            required>
                                        <option value="">-- Pilih Penghuni --</option>
                                        @foreach($tenants as $tenant)
                                            <option value="{{ $tenant->id }}" 
                                                {{ old('tenant_id', $pembayaran->tenant_id) == $tenant->id ? 'selected' : '' }}>
                                                {{ $tenant->nama_penyewa }} - Kamar {{ $tenant->kamar->nomor_kamar ?? 'N/A' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('tenant_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="bulan_tahun">Periode Tagihan <span class="text-danger">*</span></label>
                                    <input type="month" 
                                           class="form-control @error('bulan_tahun') is-invalid @enderror" 
                                           id="bulan_tahun" 
                                           name="bulan_tahun" 
                                           value="{{ old('bulan_tahun', $pembayaran->bulan_tahun) }}" 
                                           required>
                                    @error('bulan_tahun')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="tanggal_pembayaran">Tanggal Pembayaran <span class="text-danger">*</span></label>
                                    <input type="date" 
                                           class="form-control @error('tanggal_pembayaran') is-invalid @enderror" 
                                           id="tanggal_pembayaran" 
                                           name="tanggal_pembayaran" 
                                           value="{{ old('tanggal_pembayaran', $pembayaran->tanggal_pembayaran->format('Y-m-d')) }}" 
                                           required>
                                    @error('tanggal_pembayaran')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="jumlah_pembayaran">Jumlah Pembayaran <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="number" 
                                               class="form-control @error('jumlah_pembayaran') is-invalid @enderror" 
                                               id="jumlah_pembayaran" 
                                               name="jumlah_pembayaran" 
                                               value="{{ old('jumlah_pembayaran', $pembayaran->jumlah_pembayaran) }}" 
                                               min="0" 
                                               step="1000"
                                               required>
                                    </div>
                                    @error('jumlah_pembayaran')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status_pembayaran">Status Pembayaran <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status_pembayaran') is-invalid @enderror" 
                                            id="status_pembayaran" 
                                            name="status_pembayaran" 
                                            required>
                                        <option value="">Pilih Status</option>
                                        <option value="lunas" {{ old('status_pembayaran', $pembayaran->status_pembayaran) == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                        <option value="belum_bayar" {{ old('status_pembayaran', $pembayaran->status_pembayaran) == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                                        <option value="terlambat" {{ old('status_pembayaran', $pembayaran->status_pembayaran) == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                                    </select>
                                    @error('status_pembayaran')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="metode_pembayaran">Metode Pembayaran <span class="text-danger">*</span></label>
                                    <select class="form-control @error('metode_pembayaran') is-invalid @enderror" 
                                            id="metode_pembayaran" 
                                            name="metode_pembayaran" 
                                            required>
                                        <option value="">Pilih Metode</option>
                                        <option value="cash" {{ old('metode_pembayaran', $pembayaran->metode_pembayaran) == 'cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="transfer" {{ old('metode_pembayaran', $pembayaran->metode_pembayaran) == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                        <option value="e_wallet" {{ old('metode_pembayaran', $pembayaran->metode_pembayaran) == 'e_wallet' ? 'selected' : '' }}>E-Wallet</option>
                                        <option value="lainnya" {{ old('metode_pembayaran', $pembayaran->metode_pembayaran) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    @error('metode_pembayaran')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="catatan">Catatan</label>
                                    <textarea class="form-control @error('catatan') is-invalid @enderror" 
                                              id="catatan" 
                                              name="catatan" 
                                              rows="4"
                                              placeholder="Catatan tambahan (opsional)">{{ old('catatan', $pembayaran->catatan) }}</textarea>
                                    @error('catatan')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Info Update -->
                                <div class="alert alert-info">
                                    <small>
                                        <i class="fas fa-info-circle"></i>
                                        <strong>Info:</strong> Data terakhir diupdate pada {{ $pembayaran->updated_at->format('d F Y, H:i') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Pembayaran
                        </button>
                        <a href="{{ route('pembayaran.show', $pembayaran->id) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                        <a href="{{ route('pembayaran.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection