@extends('sidebar')

@section('content')
<div class="container-fluid" style="padding-top: 90px;">
    <div class="row justify-content-center mt-3 mb-4">
        <div class="col-lg-11 mx-auto">
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
                            <form action="{{ route('laporan.maintenance') }}" method="GET">
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

            <div class="row" style="margin-left:0;">
                <div class="col-12" style="padding-left:0;">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Status Aset</h4>
                        </div>
                        <div class="card-body" style="padding-left:0;">
                            <div class="table-responsive" style="margin-left:0;">
                                <table class="table table-striped custom-table">
                                    <thead>
                                        <tr>
                                            <th>Nama Barang</th>
                                            <th>Letak</th>
                                            <th>Tanggal Beli</th>
                                            <th>Status</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dataMaintenance['status_aset'] as $aset)
                                        <tr>
                                            <td class="align-middle">{{ $aset['nama_barang'] }}</td>
                                            <td class="align-middle">{{ $aset['letak'] }}</td>
                                            <td class="align-middle">{{ $aset['tanggal_beli'] }}</td>
                                            <td class="align-middle text-capitalize">{{ $aset['status'] }}</td>
                                            <td class="align-middle">{{ $aset['keterangan'] }}</td>
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
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Jadwal Maintenance</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped custom-table">
                                    <thead>
                                        <tr>
                                            <th>Aset</th>
                                            <th>Jenis Maintenance</th>
                                            <th>Tanggal</th>
                                            <th>Status</th>
                                            <th>Biaya</th>
                                            <th class="text-right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dataMaintenance['jadwal'] as $jadwal)
                                        <tr>
                                            <td>{{ $jadwal['aset'] }}</td>
                                            <td>{{ $jadwal['jenis'] }}</td>
                                            <td>{{ $jadwal['tanggal'] }}</td>
                                            <td>
                                                <span class="badge badge-{{ $jadwal['status'] == 'selesai' ? 'success' : 'info' }}">
                                                    {{ ucfirst($jadwal['status']) }}
                                                </span>
                                            </td>
                                            <td>Rp {{ $jadwal['status'] == 'selesai' ? number_format(rand(500000, 2000000), 0, ',', '.') : '-' }}</td>
                                            <td class="text-right">
                                                <button class="btn btn-sm btn-primary">
                                                    <i class="la la-eye"></i> Detail
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
                <div class="col-md-6">
                    <a href="{{ route('laporan.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
                <div class="col-md-6 d-flex justify-content-end gap-2">
                    <button class="btn btn-primary" onclick="window.print()">
                        <i class="la la-print"></i> Cetak Laporan
                    </button>
                    <a href="{{ route('laporan.maintenance.export') }}" class="btn btn-success">
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
    // Maintenance Cost Chart
    var maintenanceCostCtx = document.getElementById('maintenanceCostChart').getContext('2d');
    var maintenanceCostChart = new Chart(maintenanceCostCtx, {
        type: 'bar',
        data: {
            labels: @json($dataMaintenance['biaya']['labels']),
            datasets: [{
                label: 'Biaya Maintenance',
                data: @json($dataMaintenance['biaya']['data']),
                backgroundColor: 'rgba(153, 102, 255, 0.5)',
                borderColor: 'rgba(153, 102, 255, 1)',
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