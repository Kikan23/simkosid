<div class="col-md-3 mb-4 room-card-item">
    <div class="card room-card h-100 border-{{ $kamar->status_color }}">
        <div class="card-header bg-{{ $kamar->status_color }} text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Kamar {{ $kamar->nomor_kamar }}</h5>
                <span class="badge badge-light">{{ ucfirst($kamar->status) }}</span>
            </div>
        </div>
        <div class="card-body">
            <p class="card-text"><strong>Tipe:</strong> {{ $kamar->tipe_kamar_label }}</p>
            <p class="card-text"><strong>Tarif:</strong> Rp {{ number_format($kamar->tarif_bulanan, 0, ',', '.') }}/bulan</p>
            <p class="card-text"><strong>Fasilitas:</strong></p>
            <ul class="facility-list">
                @foreach(array_slice(explode(',', $kamar->fasilitas), 0, 3) as $facility)
                    <li>{{ trim($facility) }}</li>
                @endforeach
                @if(count(explode(',', $kamar->fasilitas)) > 3)
                    <li class="text-muted">+{{ count(explode(',', $kamar->fasilitas)) - 3 }} lebih</li>
                @endif
            </ul>
        </div>
        <div class="card-footer bg-transparent">
            <div class="d-flex justify-content-between">
                <a href="{{ route('kamar.show', $kamar->id) }}" class="btn btn-sm btn-info" title="Detail">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="{{ route('kamar.edit', $kamar->id) }}" class="btn btn-sm btn-primary" title="Edit">
                    <i class="fas fa-edit"></i>
                </a>
                <form action="{{ route('kamar.update-status', $kamar->id) }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="status" value="maintenance">
                    <button type="submit" class="btn btn-sm btn-warning" title="Set Maintenance">
                        <i class="fas fa-tools"></i>
                    </button>
                </form>
                <form action="{{ route('kamar.destroy', $kamar->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Hapus kamar ini?')">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>