{{-- File: resources/views/inventaris/index.blade.php --}}
@extends('sidebar')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Manajemen Inventaris</h3>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('dashboard') }}">
                    <i class="icon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Inventaris</a>
            </li>
        </ul>
    </div>

    <!-- Inventory Card Wrapper -->
    <div class="card">
        <div class="card-body">
            <div class="row align-items-center mb-3">
                <div class="col-md-6">
                    <h5 class="card-title mb-0">Data Inventaris</h5>
                </div>
                <div class="col-md-6 d-flex justify-content-end">
                    <a href="{{ route('inventaris.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus"></i>
                        Tambah Aset
                    </a>
                </div>
            </div>
            <!-- Inventory Grid -->
            <div class="row" id="inventory-grid">
                @forelse($inventaris as $item)
                    <div class="col-md-4 col-lg-3 inventory-card" data-nama="{{ strtolower($item->nama_barang) }}" data-status="{{ $item->status }}" data-lokasi="{{ $item->lokasi }}">
                        <div class="card">
                            <img class="card-img-top" src="{{ $item->foto ? asset('storage/' . $item->foto) : 'https://via.placeholder.com/300x200.png?text=Tidak+Ada+Foto' }}" alt="Foto {{ $item->nama_barang }}" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title fw-bold">{{ $item->nama_barang }}</h5>
                                
                                <div>
                                    @php
                                        $statusClass = '';
                                        if ($item->status == 'baik') $statusClass = 'badge-success';
                                        elseif ($item->status == 'rusak') $statusClass = 'badge-danger';
                                        elseif ($item->status == 'hilang') $statusClass = 'badge-dark';
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ ucfirst($item->status) }}</span>
                                    <span class="badge badge-info">{{ $item->lokasi }}</span>
                                </div>

                                <p class="card-text mt-2">{{ Str::limit($item->catatan, 50) }}</p>

                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('inventaris.edit', $item->id) }}" class="btn btn-link btn-warning btn-sm">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                    <form method="POST" action="{{ route('inventaris.destroy', $item->id) }}" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus aset ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link btn-danger btn-sm">
                                            <i class="fa fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="d-flex align-items-center justify-content-center" style="height: 300px; width: 100%;">
                        <div class="text-center">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum Ada Aset Inventaris</h5>
                            <p class="text-muted">Silakan tambahkan aset baru untuk memulai manajemen inventaris.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search-input');
    const statusFilter = document.getElementById('filter-status');
    const lokasiFilter = document.getElementById('filter-lokasi');
    const inventoryGrid = document.getElementById('inventory-grid');
    const cards = inventoryGrid.querySelectorAll('.inventory-card');

    function filterCards() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;
        const lokasiValue = lokasiFilter.value;

        cards.forEach(card => {
            const nama = card.dataset.nama;
            const status = card.dataset.status;
            const lokasi = card.dataset.lokasi;

            const matchesSearch = nama.includes(searchTerm);
            const matchesStatus = statusValue === 'all' || status === statusValue;
            const matchesLokasi = lokasiValue === 'all' || lokasi === lokasiValue;

            if (matchesSearch && matchesStatus && matchesLokasi) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('keyup', filterCards);
    statusFilter.addEventListener('change', filterCards);
    lokasiFilter.addEventListener('change', filterCards);
});
</script>
@endpush