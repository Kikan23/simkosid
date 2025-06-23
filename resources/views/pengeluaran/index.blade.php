{{-- File: resources/views/pengeluaran/index.blade.php --}}
@extends('sidebar')

@section('content')
<div class="page-inner">
    <div class="page-header">
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
                <a href="{{ route('pengeluaran.index') }}">Pengeluaran</a>
            </li>
        </ul>
    </div>
    <!-- Analytics Cards -->
    <div class="row mb-4 align-items-stretch">
        <div class="col-lg-3 col-6 mb-3">
            <div class="card card-stats h-100">
                <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                    <p class="card-category mb-1 font-weight-bold">Total Bulan Ini</p>
                    <h4 class="card-title mb-0">{{ 'Rp ' . number_format($analytics['total_bulan_ini'], 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6 mb-3">
            <div class="card card-stats h-100">
                <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                    <p class="card-category mb-1 font-weight-bold">Total Bulan Lalu</p>
                    <h4 class="card-title mb-0">{{ 'Rp ' . number_format($analytics['total_bulan_lalu'], 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6 mb-3">
            <div class="card card-stats h-100">
                <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                    <p class="card-category mb-1 font-weight-bold">{{ $analytics['persentase_perubahan'] >= 0 ? 'Naik' : 'Turun' }}</p>
                    <h4 class="card-title mb-0">{{ number_format(abs($analytics['persentase_perubahan']), 1) }}%</h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6 mb-3">
            <div class="card card-stats h-100">
                <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                    <p class="card-category mb-1 font-weight-bold">Kategori Aktif</p>
                    <h4 class="card-title mb-0">{{ $analytics['pengeluaran_kategori']->count() }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center mb-3">
                        <div class="col-md-6">
                            <h5 class="card-title mb-0">Data Pengeluaran</h5>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end">
                            <a href="{{ route('pengeluaran.create') }}" class="btn btn-primary">
                                <i class="fa fa-plus"></i> Tambah Pengeluaran
                            </a>
                        </div>
                    </div>
                    <div style="border-bottom: 2px solid #eee; margin-bottom: 18px; position: relative; left: -1.25rem; width: calc(100% + 2.5rem);"></div>
                    <div class="table-responsive">
                        @if($pengeluarans->count() > 0)
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Tanggal</th>
                                    <th>Jenis Pengeluaran</th>
                                    <th>Kategori</th>
                                    <th>Nominal</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pengeluarans as $index => $pengeluaran)
                                <tr>
                                    <td>{{ $pengeluarans->firstItem() + $index }}</td>
                                    <td>{{ $pengeluaran->tanggal_pengeluaran->format('d/m/Y') }}</td>
                                    <td>
                                        <strong>{{ $pengeluaran->jenis_pengeluaran }}</strong>
                                        @if($pengeluaran->is_recurring)
                                            <span class="badge badge-info badge-sm ml-1">Recurring</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $pengeluaran->kategori_info['color'] }}">
                                            <i class="{{ $pengeluaran->kategori_info['icon'] }}"></i>
                                            {{ $pengeluaran->kategori_info['name'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong class="text-danger">{{ $pengeluaran->formatted_nominal }}</strong>
                                    </td>
                                    <td>{{ Str::limit($pengeluaran->keterangan_detail, 50) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $pengeluaran->status_approval == 'approved' ? 'success' : ($pengeluaran->status_approval == 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($pengeluaran->status_approval) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-1">
                                            <a href="{{ route('pengeluaran.show', $pengeluaran->id) }}" class="btn btn-info btn-sm" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('pengeluaran.edit', $pengeluaran->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('pengeluaran.destroy', $pengeluaran->id) }}" class="d-inline mb-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" 
                                                        onclick="return confirm('Yakin ingin menghapus data pengeluaran ini?')" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $pengeluarans->withQueryString()->links() }}
                        </div>
                        @else
                        <div class="d-flex align-items-center justify-content-center" style="height: 300px;">
                            <div class="text-center">
                                <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Belum ada data pengeluaran</h5>
                                <p class="text-muted">Silakan tambahkan pengeluaran baru untuk memulai</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Pengeluaran -->
    <div class="modal fade" id="modalDetailPengeluaran" tabindex="-1" role="dialog" aria-labelledby="modalDetailLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetailLabel">Detail Pengeluaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modalDetailContent">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .icon-big {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .icon-big i {
        font-size: 2.5rem;
        line-height: 1;
    }
</style>
@endpush

<script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
<script>
$(document).on('click', '.btn-detail-pengeluaran', function() {
    var id = $(this).data('id');
    $('#modalDetailContent').html('<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>');
    $('#modalDetailPengeluaran').modal('show');
    $.get('/pengeluaran/' + id + '/modal', function(data) {
        $('#modalDetailContent').html(data);
    }).fail(function() {
        $('#modalDetailContent').html('<div class="alert alert-danger">Gagal memuat detail.</div>');
    });
});
</script>