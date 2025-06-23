@extends('sidebar')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Pembangun Laporan: {{ ucfirst(request('template', 'Tidak Dikenal')) }}</h3>
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
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Pembangun Laporan</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Langkah 1: Atur Parameter Laporan</h4>
                    <p class="card-category">Tentukan kriteria untuk laporan yang ingin Anda buat.</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('laporan.generate') }}" method="POST">
                        @csrf
                        <input type="hidden" name="template" value="{{ request('template') }}">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_mulai">Tanggal Mulai <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="{{ date('Y-m-01') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_selesai">Tanggal Selesai <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" value="{{ date('Y-m-t') }}" required>
                                </div>
                            </div>
                        </div>

                        {{-- Parameter spesifik berdasarkan template --}}
                        @if(request('template') == 'tunggakan')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Status Tunggakan</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="status_tunggakan[]" value="belum_bayar" id="belum_bayar" checked>
                                        <label class="form-check-label" for="belum_bayar">
                                            Belum Bayar
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="status_tunggakan[]" value="terlambat" id="terlambat" checked>
                                        <label class="form-check-label" for="terlambat">
                                            Terlambat
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="card-footer text-end">
                            <a href="{{ route('laporan.index') }}" class="btn btn-danger">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-cogs"></i> Buat Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 