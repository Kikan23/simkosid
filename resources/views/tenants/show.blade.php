@extends('sidebar')

@section('content')
<div class="container-fluid">
    <div class="page-inner d-flex flex-column" style="padding-top: 100px;">
        
        <!-- Page Header -->
        <div class="page-header">
            <h4 class="page-title">Detail Penghuni</h4>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('home') }}">
                        <i class="flaticon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tenants.index') }}">Penghuni</a>
                </li>
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <span>Detail</span>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="card-title">Informasi Penghuni</h4>
                            <div>
                                <a href="{{ route('tenants.edit', $tenant->id) }}" class="btn btn-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="{{ route('tenants.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="150"><strong>Nama Penghuni:</strong></td>
                                        <td>{{ $tenant->nama_penyewa }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nomor KTP:</strong></td>
                                        <td>{{ $tenant->nomor_ktp }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Telepon:</strong></td>
                                        <td>{{ $tenant->telepon }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email:</strong></td>
                                        <td>{{ $tenant->email }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="150"><strong>Kamar:</strong></td>
                                        <td>
                                            @if($tenant->kamar)
                                                <span class="badge bg-primary">{{ $tenant->kamar->nomor_kamar }}</span>
                                            @else
                                                <span class="text-muted">Tidak ada kamar</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal Masuk:</strong></td>
                                        <td>{{ \Carbon\Carbon::parse($tenant->tanggal_masuk)->format('d/m/Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal Keluar:</strong></td>
                                        <td>{{ \Carbon\Carbon::parse($tenant->tanggal_keluar)->format('d/m/Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            @if($tenant->status == 'aktif')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle me-1"></i> Aktif
                                                </span>
                                            @elseif($tenant->status == 'nonaktif')
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times-circle me-1"></i> Nonaktif
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($tenant->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        @if($tenant->catatan)
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <h5>Catatan:</h5>
                                <div class="alert alert-info">
                                    {{ $tenant->catatan }}
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($tenant->kamar)
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h5>Informasi Kamar:</h5>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Tipe Kamar:</strong> {{ $tenant->kamar->tipe_kamar }}</p>
                                                <p><strong>Tarif Bulanan:</strong> Rp {{ number_format($tenant->kamar->tarif_bulanan, 0, ',', '.') }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Status Kamar:</strong> 
                                                    @if($tenant->kamar->status == 'dihuni')
                                                        <span class="badge bg-primary">Dihuni</span>
                                                    @else
                                                        <span class="badge bg-{{ $tenant->kamar->status == 'kosong' ? 'success' : 'warning' }}">
                                                            {{ ucfirst($tenant->kamar->status) }}
                                                        </span>
                                                    @endif
                                                </p>
                                                <p><strong>Fasilitas:</strong> {{ $tenant->kamar->fasilitas }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 