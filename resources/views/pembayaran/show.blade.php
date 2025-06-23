{{-- File: resources/views/pembayaran/show.blade.php --}}
@extends('sidebar')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center mt-5">
        <div class="col-12 mt-5">
            <div class="card shadow-sm">
                <div class="card-header border-bottom pb-2 d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Detail Pembayaran</h3>
                </div>
                <div class="card-body pt-3">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-8">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td width="35%"><strong>Nama Lengkap</strong></td>
                                    <td>{{ $pembayaran->tenant->nama_penyewa }}</td>
                                </tr>
                                <tr>
                                    <td><strong>No. Kamar</strong></td>
                                    <td>{{ $pembayaran->no_kamar }}</td>
                                </tr>
                                <tr>
                                    <td><strong>No. HP</strong></td>
                                    <td>{{ $pembayaran->tenant->telepon }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email</strong></td>
                                    <td>{{ $pembayaran->tenant->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Periode</strong></td>
                                    <td>{{ $pembayaran->formatted_bulan_tahun }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Jumlah Bayar</strong></td>
                                    <td><span class="text-success font-weight-bold">{{ $pembayaran->formatted_jumlah }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Bayar</strong></td>
                                    <td>{{ $pembayaran->tanggal_pembayaran->format('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $pembayaran->status_pembayaran == 'lunas' ? 'success' : ($pembayaran->status_pembayaran == 'belum_bayar' ? 'warning' : 'danger') }} p-2">
                                            {{ ucfirst(str_replace('_', ' ', $pembayaran->status_pembayaran)) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Metode Bayar</strong></td>
                                    <td>
                                        <span class="badge badge-info p-2">
                                            {{ ucfirst(str_replace('_', ' ', $pembayaran->metode_pembayaran)) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @if($pembayaran->catatan)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="border p-3 rounded bg-light">
                                <strong>Catatan:</strong><br>
                                {{ $pembayaran->catatan }}
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="border p-3 rounded bg-white">
                                <div class="row">
                                    <div class="col-md-6">
                                        <small class="text-muted">
                                            <strong>Dibuat:</strong> {{ $pembayaran->created_at->format('d F Y, H:i') }}
                                        </small>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">
                                            <strong>Diupdate:</strong> {{ $pembayaran->updated_at->format('d F Y, H:i') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-top d-flex gap-2 justify-content-end">
                    <div class="card-tools">
                        <a href="{{ route('pembayaran.index') }}" class="btn btn-secondary">
                            <i class="fas fa-list"></i> Daftar Pembayaran
                        </a>
                    </div>
                    <a href="{{ route('pembayaran.edit', $pembayaran->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form method="POST" action="{{ route('pembayaran.destroy', $pembayaran->id) }}" class="d-inline mb-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" 
                                onclick="return confirm('Yakin ingin menghapus data pembayaran ini?')">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection