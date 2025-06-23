@extends('sidebar')

@section('content')
<div class="container-fluid" style="padding-top: 90px;">
    <div class="row justify-content-center mt-3 mb-4">
        <div class="col-lg-11 mx-auto" style="margin-left: 100px;">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
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
                            <form action="{{ route('laporan.keuangan') }}" method="GET">
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
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Statistik Keuangan</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title">Pemasukan</h5>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="revenueChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title">Pengeluaran</h5>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="expensesChart"></canvas>
                                        </div>
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
                            <h4 class="card-title">Detail Transaksi</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped custom-table">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Deskripsi</th>
                                            <th>Jumlah</th>
                                            <th>Tipe</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dataKeuangan['transactions'] as $transaksi)
                                        <tr>
                                            <td>{{ $transaksi['tanggal'] }}</td>
                                            <td>{{ $transaksi['deskripsi'] }}</td>
                                            <td>Rp {{ number_format($transaksi['jumlah'], 0, ',', '.') }}</td>
                                            <td>
                                                <span class="badge badge-{{ $transaksi['tipe'] == 'pemasukan' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($transaksi['tipe']) }}
                                                </span>
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
                <div class="col-md-6">
                    <a href="{{ route('laporan.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
                <div class="col-md-6 d-flex justify-content-end gap-2">
                    <button class="btn btn-primary" onclick="window.print()">
                        <i class="la la-print"></i> Cetak Laporan
                    </button>
                    <a href="{{ route('laporan.keuangan.export') }}" class="btn btn-success">
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
    // Revenue Chart
    var revenueCtx = document.getElementById('revenueChart').getContext('2d');
    var revenueChart = new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: @json($dataKeuangan['revenue']['labels']),
            datasets: [{
                label: 'Pemasukan',
                data: @json($dataKeuangan['revenue']['data']),
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Expenses Chart
    var expensesCtx = document.getElementById('expensesChart').getContext('2d');
    var expensesChart = new Chart(expensesCtx, {
        type: 'bar',
        data: {
            labels: @json($dataKeuangan['expenses']['labels']),
            datasets: [{
                label: 'Pengeluaran',
                data: @json($dataKeuangan['expenses']['data']),
                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush