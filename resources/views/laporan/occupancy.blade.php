@extends('sidebar')

@section('content')
<div class="container-fluid" style="padding-top: 90px;">
    <div class="row justify-content-center mt-3 mb-4">
        <div class="col-lg-11 mx-auto">

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Filter Laporan</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('laporan.occupancy') }}" method="GET">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>Periode Awal</label>
                                            <input type="date" class="form-control" name="periode_awal" value="{{ $periodeAwal }}">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>Periode Akhir</label>
                                            <input type="date" class="form-control" name="periode_akhir" value="{{ $periodeAkhir }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <button type="submit" class="btn btn-primary btn-block">Filter</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div style="white-space:nowrap;">
                <div class="d-inline-block" style="min-width:1020px; max-width:1500px;">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="mb-3"><b>Statistik Kamar</b></h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Total Kamar
                                    <span class="fw-bold text-primary">{{ $dataOccupancy['stats']['total_kamar'] }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Kamar Terisi
                                    <span class="fw-bold text-success">{{ $dataOccupancy['stats']['terisi'] }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Kamar Kosong
                                    <span class="fw-bold text-danger">{{ $dataOccupancy['stats']['kosong'] }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Persentase Terisi
                                    <span class="fw-bold text-info">{{ $dataOccupancy['stats']['persentase'] }}%</span>
                                </li>
                            </ul>
                            <div class="progress progress-sm mt-3">
                                <div class="progress-bar bg-info" style="width: {{ $dataOccupancy['stats']['persentase'] }}%" role="progressbar" aria-valuenow="{{ $dataOccupancy['stats']['persentase'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="text-end text-muted mt-2" style="font-size: 0.9em;">
                                Target hunian: {{ $dataOccupancy['stats']['persentase'] }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Detail Occupancy per Bulan</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped custom-table w-100" style="min-width: 900px;">
                                    <thead>
                                        <tr>
                                            <th>Bulan</th>
                                            <th>Kamar Terisi</th>
                                            <th>Kamar Kosong</th>
                                            <th>Persentase</th>
                                            <th>Turnover</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dataOccupancy['detail'] as $detail)
                                        <tr>
                                            <td>{{ $detail['bulan'] }}</td>
                                            <td>{{ $detail['terisi'] }}</td>
                                            <td>{{ $detail['kosong'] }}</td>
                                            <td>{{ round(($detail['terisi'] / ($detail['terisi'] + $detail['kosong'])) * 100) }}%</td>
                                            <td>{{ rand(2, 5) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <a href="{{ route('laporan.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
                <div class="col-md-6 d-flex justify-content-end gap-2">
                    <button class="btn btn-primary" onclick="window.print()">
                        <i class="la la-print"></i> Cetak Laporan
                    </button>
                    <a href="{{ route('laporan.occupancy.export') }}" class="btn btn-success">
                        <i class="la la-download"></i> Export Excel
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Occupancy Chart
    var occupancyCtx = document.getElementById('occupancyChart').getContext('2d');
    var occupancyChart = new Chart(occupancyCtx, {
        type: 'line',
        data: {
            labels: @json($dataOccupancy['chart']['labels']),
            datasets: [{
                label: 'Occupancy Rate (%)',
                data: @json($dataOccupancy['chart']['data']),
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                tension: 0.1,
                fill: true
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: false,
                    min: 50,
                    max: 100
                }
            }
        }
    });
</script>
@endpush