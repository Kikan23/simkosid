{{-- File: resources/views/inventaris/edit.blade.php --}}
@extends('sidebar')

@section('content')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Edit Aset Inventaris</h3>
            <h6 class="op-7 mb-2">Perbarui detail aset di bawah ini.</h6>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Formulir Edit Aset: {{ $inventaris->nama_barang }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('inventaris.update', $inventaris->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="nama_barang" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_barang') is-invalid @enderror" id="nama_barang" name="nama_barang" value="{{ old('nama_barang', $inventaris->nama_barang) }}" required>
                                    @error('nama_barang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="lokasi" class="form-label">Lokasi <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('lokasi') is-invalid @enderror" id="lokasi" name="lokasi" value="{{ old('lokasi', $inventaris->lokasi) }}" required>
                                        @error('lokasi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="status" class="form-label">Status Kondisi <span class="text-danger">*</span></label>
                                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                            <option value="baik" {{ old('status', $inventaris->status) == 'baik' ? 'selected' : '' }}>Baik</option>
                                            <option value="rusak" {{ old('status', $inventaris->status) == 'rusak' ? 'selected' : '' }}>Rusak</option>
                                            <option value="hilang" {{ old('status', $inventaris->status) == 'hilang' ? 'selected' : '' }}>Hilang</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="catatan" class="form-label">Catatan</label>
                                    <textarea class="form-control" id="catatan" name="catatan" rows="5">{{ old('catatan', $inventaris->catatan) }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="foto" class="form-label">Ganti Foto Barang</label>
                                    <input class="form-control @error('foto') is-invalid @enderror" type="file" id="foto" name="foto" accept="image/*" onchange="previewImage()">
                                    @error('foto')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <img id="img-preview" src="{{ $inventaris->foto ? asset('storage/' . $inventaris->foto) : 'https://via.placeholder.com/300x200.png?text=Tidak+Ada+Foto' }}" class="img-fluid mt-3 rounded">
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 d-flex justify-content-end">
                            <a href="{{ route('inventaris.index') }}" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewImage() {
    const image = document.querySelector('#foto');
    const imgPreview = document.querySelector('#img-preview');

    const oFReader = new FileReader();
    oFReader.readAsDataURL(image.files[0]);

    oFReader.onload = function(oFREvent) {
        imgPreview.src = oFREvent.target.result;
    }
}
</script>
@endpush