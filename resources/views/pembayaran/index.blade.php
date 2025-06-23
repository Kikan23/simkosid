{{-- File: resources/views/pembayaran/index.blade.php --}}
@extends('sidebar')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Manajemen Pembayaran</h3>
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
                <a href="{{ route('pembayaran.index') }}">Pembayaran</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Data Pembayaran</h4>
                        <a href="{{ route('pembayaran.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i>
                            Catat Pembayaran
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($pembayarans->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Penghuni</th>
                                        <th>No. Kamar</th>
                                        <th>Periode</th>
                                        <th>Jumlah</th>
                                        <th>Tgl Bayar</th>
                                        <th>Status</th>
                                        <th>Metode</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pembayarans as $index => $pembayaran)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $pembayaran->tenant->nama_penyewa }}</td>
                                        <td>{{ $pembayaran->no_kamar }}</td>
                                        <td>{{ $pembayaran->formatted_bulan_tahun }}</td>
                                        <td>{{ $pembayaran->formatted_jumlah }}</td>
                                        <td>{{ $pembayaran->tanggal_pembayaran->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="badge {{ $pembayaran->status_pembayaran == 'lunas' ? 'bg-success' : ($pembayaran->status_pembayaran == 'belum_bayar' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                                {{ ucfirst(str_replace('_', ' ', $pembayaran->status_pembayaran)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ ucfirst(str_replace('_', ' ', $pembayaran->metode_pembayaran)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-1">
                                                <a href="{{ route('pembayaran.show', $pembayaran->id) }}" class="btn btn-info btn-sm" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('pembayaran.edit', $pembayaran->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" action="{{ route('pembayaran.destroy', $pembayaran->id) }}" class="d-inline mb-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data pembayaran ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $pembayarans->links() }}
                        </div>
                    @else
                        <div class="d-flex align-items-center justify-content-center" style="height: 300px;">
                            <div class="text-center">
                                <i class="fas fa-money-bill-wave fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Belum ada data pembayaran</h5>
                                <p class="text-muted">Silakan catat pembayaran baru untuk memulai</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection