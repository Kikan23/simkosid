@extends('sidebar')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <style>
        .summary-card {
            text-align: center;
            padding: 1.5rem;
            border-radius: 0.5rem;
            color: #fff;
            margin-bottom: 1rem;
        }
        .summary-card.bg-success { background-color: #28a745 !important; }
        .summary-card.bg-danger { background-color: #dc3545 !important; }
        .summary-card.bg-info { background-color: #17a2b8 !important; }
        .summary-card .value { font-size: 2rem; font-weight: 700; }
        .summary-card .title { font-size: 1rem; opacity: 0.9; }

        @media print {
            body, .wrapper, .page-inner {
                background-color: #fff !important;
            }
            .sidebar, .main-header, .page-header .breadcrumbs, .card-header .d-flex .btn, .card-footer {
                display: none !important;
            }
            .main-panel {
                width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .card {
                box-shadow: none !important;
                border: 1px solid #dee2e6 !important;
            }
        }
    </style>
@endpush

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Hasil Laporan: {{ $title }}</h3>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('dashboard') }}"><i class="icon-home"></i></a>
            </li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item">
                <a href="{{ route('laporan.index') }}">Laporan</a>
            </li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item">
                <a href="#">Hasil Laporan</a>
            </li>
        </ul>
    </div>

    <!-- Executive Summary -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Executive Summary</h4>
                    <p class="card-category">Ringkasan utama untuk periode {{ $tanggalMulai->format('d M Y') }} - {{ $tanggalSelesai->format('d M Y') }}</p>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($summary as $metric)
                        <div class="col-md-4">
                            <div class="summary-card {{ $metric['class'] }}">
                                <div class="value">{{ $metric['value'] }}</div>
                                <div class="title">{{ $metric['title'] }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Interactive Charts -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Grafik Laporan</h4>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="reportChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Tables -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Rincian Data</h4>
                        <div>
                            <a href="{{ url()->previous() }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                             <button id="printButton" class="btn btn-secondary btn-sm"><i class="fas fa-print"></i> Cetak</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="reportTable" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    @foreach($table['headers'] as $header)
                                        <th>{{ $header }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($table['rows'] as $row)
                                <tr>
                                    @foreach($row as $cell)
                                        <td>{!! $cell !!}</td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('assets/js/plugin/chart.js/chart.min.js') }}"></script>
    
    <script>
        $(document).ready(function() {
            $('#reportTable').DataTable();

            const ctx = document.getElementById('reportChart').getContext('2d');
            const chartData = @json($chart);

            new Chart(ctx, {
                type: chartData.type,
                data: chartData.data,
                options: chartData.options
            });

            $('#printButton').on('click', function() {
                window.print();
            });
        });
    </script>
@endpush 