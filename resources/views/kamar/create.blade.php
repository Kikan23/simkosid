@extends('sidebar')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Manajemen Kamar</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href="#">
                    <i class="icon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="{{ route('kamar.index') }}">Kamar</a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Tambah Kamar Baru</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form Tambah Kamar</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('kamar.store') }}" method="POST">
                        @csrf
                        <!-- Nomor Kamar -->
                        <div class="mb-3">
                            <label for="nomor_kamar" class="form-label">Nomor Kamar</label>
                            <input type="text" class="form-control @error('nomor_kamar') is-invalid @enderror" id="nomor_kamar" name="nomor_kamar" 
                                    value="{{ old('nomor_kamar') }}" placeholder="Contoh: A-101" required>
                            @error('nomor_kamar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
 
                        <!-- Tipe Kamar -->
                        <div class="mb-3">
                            <label for="tipe_kamar" class="form-label">Tipe Kamar</label>
                            <select class="form-select @error('tipe_kamar') is-invalid @enderror" id="tipe_kamar" name="tipe_kamar" required>
                                <option value="">Pilih Tipe Kamar</option>
                                @foreach ($tipeKamarData as $tipe => $data)
                                    <option value="{{ $tipe }}" {{ old('tipe_kamar') == $tipe ? 'selected' : '' }}>{{ $tipe }}</option>
                                @endforeach
                            </select>
                            @error('tipe_kamar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tarif Bulanan -->
                        <div class="mb-3">
                            <label for="tarif_bulanan" class="form-label">Tarif Bulanan (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control bg-light @error('tarif_bulanan') is-invalid @enderror" id="tarif_bulanan" name="tarif_bulanan" 
                                       value="{{ old('tarif_bulanan') }}" readonly required>
                            </div>
                             @error('tarif_bulanan')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Fasilitas -->
                        <div class="mb-3">
                            <label for="fasilitas" class="form-label">Fasilitas</label>
                            <textarea class="form-control bg-light @error('fasilitas') is-invalid @enderror" id="fasilitas" name="fasilitas" rows="4" readonly required>{{ old('fasilitas') }}</textarea>
                             @error('fasilitas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
 
                        <!-- Status -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Status Awal</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="kosong" @selected(old('status', 'kosong') == 'kosong')>Kosong</option>
                                <option value="maintenance" @selected(old('status') == 'maintenance')>Maintenance</option>
                            </select>
                             @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="card-action">
                            <button type="submit" class="btn btn-success">Simpan</button>
                            <a href="{{ route('kamar.index') }}" class="btn btn-danger">Batal</a>
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
    document.addEventListener('DOMContentLoaded', function () {
        const tipeKamarSelect = document.getElementById('tipe_kamar');
        const tarifInput = document.getElementById('tarif_bulanan');
        const fasilitasTextarea = document.getElementById('fasilitas');

        // Data dari controller
        const tipeKamarData = @json($tipeKamarData);

        function updateFormFields() {
            const selectedTipe = tipeKamarSelect.value;
            if (selectedTipe && tipeKamarData[selectedTipe]) {
                const data = tipeKamarData[selectedTipe];
                tarifInput.value = data.tarif_bulanan;
                fasilitasTextarea.value = data.fasilitas;
            } else {
                tarifInput.value = '';
                fasilitasTextarea.value = '';
            }
        }

        tipeKamarSelect.addEventListener('change', updateFormFields);

        // Panggil saat halaman dimuat jika ada old value
        if (tipeKamarSelect.value) {
            updateFormFields();
        }
    });
</script>
@endpush