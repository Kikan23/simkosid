@extends('sidebar')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Pusat Laporan</h3>
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
                <a href="{{ route('laporan.index') }}">Laporan</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Galeri Template Laporan</h4>
                    <p class="card-category">Pilih salah satu template di bawah ini untuk memulai membuat laporan.</p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Template Laporan Keuangan -->
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar-sm bg-primary-lighten-2 text-primary rounded-circle me-3">
                                            <i class="fas fa-file-invoice-dollar fs-4"></i>
                                        </div>
                                        <h5 class="card-title mb-0 fw-bold">Laporan Keuangan</h5>
                                    </div>
                                    <p class="card-text text-muted">Laporan komprehensif mengenai semua pemasukan dan pengeluaran dalam periode tertentu.</p>
                                </div>
                                <div class="card-footer bg-white border-0 pt-0">
                                    <a href="{{ route('laporan.keuangan') }}" class="btn btn-primary w-100">
                                        Buat Laporan <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Template Laporan Okupansi Kamar -->
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar-sm bg-success-lighten-2 text-success rounded-circle me-3">
                                            <i class="fas fa-bed fs-4"></i>
                                        </div>
                                        <h5 class="card-title mb-0 fw-bold">Laporan Okupansi</h5>
                                    </div>
                                    <p class="card-text text-muted">Analisis tingkat hunian kamar, data penyewa aktif, dan statistik kekosongan kamar.</p>
                                </div>
                                <div class="card-footer bg-white border-0 pt-0">
                                     <a href="{{ route('laporan.occupancy') }}" class="btn btn-success w-100">
                                        Buat Laporan <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Template Laporan Tunggakan -->
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar-sm bg-warning-lighten-2 text-warning rounded-circle me-3">
                                            <i class="fas fa-file-import fs-4"></i>
                                        </div>
                                        <h5 class="card-title mb-0 fw-bold">Laporan Tunggakan</h5>
                                    </div>
                                    <p class="card-text text-muted">Rincian semua pembayaran yang berstatus belum lunas atau terlambat dari para penyewa.</p>
                                </div>
                                 <div class="card-footer bg-white border-0 pt-0">
                                     <a href="{{ route('laporan.maintenance') }}" class="btn btn-warning w-100">
                                        Buat Laporan <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .avatar-sm {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .bg-primary-lighten-2 { background-color: #e3f2fd !important; }
    .bg-success-lighten-2 { background-color: #e8f5e9 !important; }
    .bg-warning-lighten-2 { background-color: #fff8e1 !important; }
    .card-footer {
        background-color: #fff;
    }
</style>
@endpush