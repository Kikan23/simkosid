@extends('sidebar')

@section('content')    
    <div class="container-fluid">
        <div class="page-inner d-flex flex-column" style="padding-top: 100px;">
            
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center mb-3">
                                <div class="col-md-6">
                                    <h5 class="card-title mb-0">Data Kamar</h5>
                                </div>
                                <div class="col-md-6 d-flex justify-content-end">
                                    <a href="{{ route('kamar.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Tambah Kamar
                                    </a>
                                </div>
                            </div>
                            <div class="table-responsive">
                                @if($kamars->count() > 0)
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No. Kamar</th>
                                            <th>Tipe</th>
                                            <th>Tarif Bulanan</th>
                                            <th>Status</th>
                                            <th>Fasilitas</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($kamars as $index => $kamar)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $kamar->nomor_kamar }}</td>
                                            <td>{{ $kamar->tipe_kamar }}</td>
                                            <td>Rp {{ number_format($kamar->tarif_bulanan, 0, ',', '.') }}</td>
                                            <td>
                                                @if($kamar->status == 'kosong')
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check-circle me-1"></i> Tersedia
                                                    </span>
                                                @elseif($kamar->status == 'dihuni')
                                                    <span class="badge bg-primary">
                                                        <i class="fas fa-user me-1"></i> Dihuni
                                                    </span>
                                                @elseif($kamar->status == 'maintenance')
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fas fa-tools me-1"></i> Perbaikan
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        {{ ucfirst($kamar->status) }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>{{ Str::limit($kamar->fasilitas, 50) }}</td>
                                            <td>
                                                <div class="form-button-action">
                                                    <a href="{{ route('kamar.show', $kamar->id) }}" 
                                                       class="btn btn-info btn-sm" 
                                                       title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                    <a href="{{ route('kamar.edit', $kamar->id) }}" class="btn btn-link btn-primary btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                    <form action="{{ route('kamar.destroy', $kamar->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                        <button type="submit" class="btn btn-link btn-danger btn-sm" title="Hapus" onclick="return confirm('Hapus kamar ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @else
                                <div class="d-flex align-items-center justify-content-center" style="height: 300px;">
                                    <div class="text-center">
                                        <i class="fas fa-bed fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">Belum ada data kamar</h5>
                                        <p class="text-muted">Silakan tambahkan kamar baru untuk memulai</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Filter Kamar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('kamar.partials.filter-panel')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="applyFilter">Terapkan Filter</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.datatable').DataTable({
        responsive: true,
        dom: 't',
        searching: false,
        lengthChange: false,
        info: false,
        language: {
            emptyTable: '' // Hilangkan pesan default bawaan DataTables
        }
    });
    
    $('#applyFilter').click(function() {
        // Implement filter logic here
        alert('Filter akan diterapkan');
        $('#filterModal').modal('hide');
    });
});
</script>
@endpush