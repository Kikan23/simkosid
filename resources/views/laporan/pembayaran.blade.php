@extends('sidebar')

@section('content')
<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h3 class="page-title">Laporan Pembayaran</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('laporan.index') }}">Laporan</a></li>
                <li class="breadcrumb-item active">Pembayaran</li>
            </ul>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Filter Laporan</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('laporan.pembayaran') }}" method="GET">
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

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Collection Rate</h4>
            </div>
            <div class="card-body">
                <canvas id="collectionChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Status Pembayaran</h4>
            </div>
            <div class="card-body">
                <canvas id="paymentStatusChart"></canvas>
                <div class="mt-4">
                    <div class="stats-info">
                        <div class="stats-info-left">
                            <h6>Lunas</h6>
                        </div>
                        <div class="stats-info-right">
                            <span class="badge badge-success">{{ $dataPembayaran['status']['lunas'] }}</span>
                        </div>
                    </div>
                    <div class="stats-info">
                        <div class="stats-info-left">
                            <h6>Pending</h6>
                        </div>
                        <div class="stats-info-right">
                            <span class="badge badge-warning">{{ $dataPembayaran['status']['pending'] }}</span>
                        </div>
                    </div>
                    <div class="stats-info">
                        <div class="stats-info-left">
                            <h6>Overdue</h6>
                        </div>
                        <div class="stats-info-right">
                            <span class="badge badge-danger">{{ $dataPembayaran['status']['overdue'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Outstanding Payments</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped custom-table">
                        <thead>
                            <tr>
                                <th>Nama Penghuni</th>
                                <th>Kamar</th>
                                <th>Jumlah</th>
                                <th>Jatuh Tempo</th>
                                <th>Status</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dataPembayaran['outstanding'] as $payment)
                            <tr>
                                <td>{{ $payment['nama'] }}</td>
                                <td>{{ $payment['kamar'] }}</td>
                                <td>Rp {{ number_format($payment['jumlah'], 0, ',', '.') }}</td>
                                <td>{{ $payment['jatuh_tempo'] }}</td>
                                <td>
                                    <span class="badge badge-{{ strtotime($payment['jatuh_tempo']) < time() ? 'danger' : 'warning' }}">
                                        {{ strtotime($payment['jatuh_tempo']) < time() ? 'Overdue' : 'Pending' }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <button class="btn btn-sm btn-primary">
                                        <i class="la la-envelope"></i> Reminder
                                    </button>
                                </td>
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
    <div class="col-md-12 text-right">
        <button class="btn btn-primary">
            <i class="la la-print"></i> Cetak Laporan
        </button>
        <button class="btn btn-success">
            <i class="la la-download"></i> Export Excel
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Collection Chart
    var collectionCtx = document.getElementById('collectionChart').getContext('2d');
    var collectionChart = new Chart(collectionCtx, {
        type: 'line',
        data: {
            labels: @json($dataPembayaran['chart']['labels']),
            datasets: [{
                label: 'Collection Rate (%)',
                data: @json($dataPembayaran['chart']['data']),
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                tension: 0.1,
                fill: true
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: false,
                    min: 80,
                    max: 100
                }
            }
        }
    });

    // Payment Status Chart
    var paymentStatusCtx = document.getElementById('paymentStatusChart').getContext('2d');
    var paymentStatusChart = new Chart(paymentStatusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Lunas', 'Pending', 'Overdue'],
            datasets: [{
                data: [
                    {{ $dataPembayaran['status']['lunas'] }},
                    {{ $dataPembayaran['status']['pending'] }},
                    {{ $dataPembayaran['status']['overdue'] }}
                ],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(255, 99, 132, 0.7)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        }
    });
</script>
@endpush