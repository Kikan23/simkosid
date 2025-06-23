@extends('sidebar')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <h2>Edit Kamar {{ $kamar->nomor_kamar }}</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Form Edit Kamar</div>
                <div class="card-body">
                    <form action="{{ route('kamars.update', $kamar->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="nomor_kamar">Nomor Kamar</label>
                            <input type="text" class="form-control" id="nomor_kamar" name="nomor_kamar" 
                                value="{{ $kamar->nomor_kamar }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="tipe_kamar">Tipe Kamar</label>
                            <select class="form-control" id="tipe_kamar" name="tipe_kamar" required>
                                <option value="standard" {{ $kamar->tipe_kamar == 'standard' ? 'selected' : '' }}>Standard</option>
                                <option value="premium" {{ $kamar->tipe_kamar == 'premium' ? 'selected' : '' }}>Premium</option>
                                <option value="vip" {{ $kamar->tipe_kamar == 'vip' ? 'selected' : '' }}>VIP</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="tarif_bulanan">Tarif Bulanan (Rp)</label>
                            <input type="number" class="form-control" id="tarif_bulanan" name="tarif_bulanan" 
                                value="{{ $kamar->tarif_bulanan }}" min="0" step="100000" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="fasilitas">Fasilitas</label>
                            <textarea class="form-control" id="fasilitas" name="fasilitas" rows="3" required>{{ $kamar->fasilitas }}</textarea>
                            <small class="form-text text-muted">
                                Pisahkan dengan koma, contoh: AC, Kamar Mandi Dalam, TV, Lemari, Meja
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="kosong" {{ $kamar->status == 'kosong' ? 'selected' : '' }}>Kosong</option>
                                <option value="dihuni" {{ $kamar->status == 'dihuni' ? 'selected' : '' }}>Dihuni</option>
                                <option value="maintenance" {{ $kamar->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('kamars.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Preview Kamar</div>
                <div class="card-body">
                    <div class="room-preview">
                        <div class="room-card">
                            <div class="room-header bg-{{ $kamar->status_color }}">
                                <h5>Kamar <span id="preview-nomor">{{ $kamar->nomor_kamar }}</span></h5>
                                <span class="badge badge-light">{{ ucfirst($kamar->status) }}</span>
                            </div>
                            <div class="room-body">
                                <p><strong>Tipe:</strong> <span id="preview-tipe">{{ $kamar->tipe_kamar_label }}</span></p>
                                <p><strong>Tarif:</strong> Rp <span id="preview-tarif">{{ number_format($kamar->tarif_bulanan, 0, ',', '.') }}</span>/bulan</p>
                                <p><strong>Fasilitas:</strong></p>
                                <ul id="preview-fasilitas">
                                    @foreach(explode(',', $kamar->fasilitas) as $facility)
                                        <li>{{ trim($facility) }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Update preview when form changes
    $('#nomor_kamar, #tipe_kamar, #tarif_bulanan, #fasilitas, #status').on('input change', function() {
        updatePreview();
    });
    
    function updatePreview() {
        $('#preview-nomor').text($('#nomor_kamar').val());
        $('#preview-tipe').text($('#tipe_kamar option:selected').text());
        $('#preview-tarif').text(parseInt($('#tarif_bulanan').val()).toLocaleString('id-ID'));
        
        // Update facilities list
        const facilities = $('#fasilitas').val().split(',').map(item => item.trim()).filter(item => item);
        const facilitiesList = $('#preview-fasilitas');
        facilitiesList.empty();
        
        if (facilities.length > 0) {
            facilities.forEach(facility => {
                facilitiesList.append(`<li>${facility}</li>`);
            });
        } else {
            facilitiesList.append('<li>Belum ada fasilitas</li>');
        }
        
        // Update status color
        const status = $('#status').val();
        const statusText = status === 'kosong' ? 'Kosong' : 
                        status === 'dihuni' ? 'Dihuni' : 'Maintenance';
        const statusColor = status === 'kosong' ? 'success' : 
                        status === 'dihuni' ? 'danger' : 'warning';
        
        $('.room-header').removeClass('bg-success bg-danger bg-warning').addClass('bg-' + statusColor);
        $('.room-header .badge').text(statusText);
    }
});
</script>
@endpush