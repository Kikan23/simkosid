@extends('sidebar')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <h2>Detail Kamar {{ $kamar->nomor_kamar }}</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('kamar.index') }}">Daftar Kamar</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail Kamar</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Informasi Kamar</span>
                        <span class="badge badge-{{ $kamar->status_color }}">
                            {{ ucfirst($kamar->status) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Data Dasar</h5>
                            <table class="table table-sm">
                                <tr>
                                    <th>Nomor Kamar</th>
                                    <td>{{ $kamar->nomor_kamar }}</td>
                                </tr>
                                <tr>
                                    <th>Tipe Kamar</th>
                                    <td>{{ $kamar->tipe_kamar_label }}</td>
                                </tr>
                                <tr>
                                    <th>Tarif Bulanan</th>
                                    <td>Rp {{ number_format($kamar->tarif_bulanan, 0, ',', '.') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Status</h5>
                            <form action="{{ route('kamar.update-status', $kamar->id) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <select class="form-control" name="status">
                                        <option value="kosong" {{ $kamar->status == 'kosong' ? 'selected' : '' }}>Kosong</option>
                                        <option value="dihuni" {{ $kamar->status == 'dihuni' ? 'selected' : '' }}>Dihuni</option>
                                        <option value="maintenance" {{ $kamar->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm">Update Status</button>
                            </form>
                        </div>
                    </div>

                    <hr>

                    <h5>Fasilitas</h5>
                    <div class="facilities-list">
                        @php
                            $facilities = explode(',', $kamar->fasilitas);
                        @endphp
                        <ul class="list-group">
                            @foreach($facilities as $facility)
                                <li class="list-group-item">{{ trim($facility) }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('kamar.edit', $kamar->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form method="POST" action="{{ route('kamar.destroy', $kamar->id) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus kamar ini?')">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Statistik Kamar
                </div>
                <div class="card-body">
                    <h6 class="mb-3">Riwayat Pendapatan</h6>
                    <table class="table table-sm mb-0">
                        <tr>
                            <th>Bulan Ini</th>
                            <td>Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Bulan Lalu</th>
                            <td>Rp {{ number_format($pendapatanBulanLalu, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Tahun Ini</th>
                            <td>Rp {{ number_format($pendapatanTahunIni, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Riwayat Penghuni
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Nama Penghuni</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Tanggal Keluar</th>
                                    <th>Durasi</th>
                                    <th>Total Pembayaran</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($kamar->tenants as $tenant)
                                <tr>
                                    <td>{{ $tenant->nama_penyewa }}</td>
                                    <td>{{ $tenant->tanggal_masuk ? \Carbon\Carbon::parse($tenant->tanggal_masuk)->format('d/m/Y') : '-' }}</td>
                                    <td>{{ $tenant->tanggal_keluar ? \Carbon\Carbon::parse($tenant->tanggal_keluar)->format('d/m/Y') : '-' }}</td>
                                    <td>
                                        @if($tenant->tanggal_masuk && $tenant->tanggal_keluar)
                                            {{ \Carbon\Carbon::parse($tenant->tanggal_masuk)->diffInMonths(\Carbon\Carbon::parse($tenant->tanggal_keluar)) }} bulan
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        Rp {{ number_format($tenant->pembayarans->sum('jumlah_pembayaran'), 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada data penghuni</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="d-flex justify-content-end">
                <a href="{{ route('kamar.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Occupancy Chart
    var ctx = document.getElementById('occupancyChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Terisi', 'Kosong', 'Maintenance'],
            datasets: [{
                data: [60, 30, 10],
                backgroundColor: [
                    '#dc3545',
                    '#28a745',
                    '#ffc107'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            legend: {
                position: 'bottom'
            }
        }
    });
});
</script>
@endpush