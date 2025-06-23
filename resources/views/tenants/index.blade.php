@extends('sidebar')  

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Data Penghuni</h3>
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
                    <a href="{{ route('tenants.index') }}">Penghuni</a>
                </li>
            </ul>
        </div>

        <div class="card card-round">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="card-title">Daftar Penghuni</h4>
                    <a href="{{ route('tenants.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus"></i>
                        Tambah Penghuni
                    </a>
                </div>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                
                @if($tenants->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>No. KTP</th>
                                    <th>Telepon</th>
                                    <th>Email</th>
                                    <th>No. Kamar</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Tanggal Keluar</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tenants as $index => $tenant)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $tenant->nama_penyewa }}</td>
                                    <td>{{ $tenant->nomor_ktp }}</td>
                                    <td>{{ $tenant->telepon }}</td>
                                    <td>{{ $tenant->email ?? 'N/A' }}</td>
                                    <td>{{ $tenant->kamar->nomor_kamar ?? 'N/A' }}</td>
                                    <td>{{ optional($tenant->tanggal_masuk)->format('d/m/Y') }}</td>
                                    <td>{{ optional($tenant->tanggal_keluar)->format('d/m/Y') ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge {{ $tenant->getStatusBadgeClassAttribute() }}">{{ $tenant->getStatusLabelAttribute() }}</span>
                                    </td>
                                    <td>
                                        <div class="form-button-action">
                                            <a href="#" data-id="{{ $tenant->id }}" class="btn btn-link btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#tenantDetailModal" title="Lihat Detail">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('tenants.edit', $tenant->id) }}" data-toggle="tooltip" title="Edit" class="btn btn-link btn-primary">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{ route('tenants.destroy', $tenant->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" data-toggle="tooltip" title="Hapus" class="btn btn-link btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus penghuni ini?')">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="d-flex align-items-center justify-content-center" style="height: 300px;">
                        <div class="text-center">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada data penghuni</h5>
                            <p class="text-muted">Silakan tambahkan penghuni baru untuk memulai</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Detail Tenant Modal -->
    <div class="modal fade" id="tenantDetailModal" tabindex="-1" role="dialog" aria-labelledby="tenantDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tenantDetailModalLabel">Detail Penghuni</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="tenantDetailModalBody">
                    <!-- Content will be loaded here dynamically -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.datatable').DataTable();

    $('#tenantDetailModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var tenantId = button.data('id'); // Extract info from data-* attributes
        var modal = $(this);
        var modalBody = modal.find('.modal-body');

        // Clear previous content and show a loader
        modalBody.html('<div class="text-center p-4"><i class="fas fa-spinner fa-spin fa-2x"></i><br><br>Memuat data...</div>');

        // AJAX call to get tenant details
        $.ajax({
            url: '/tenants/' + tenantId,
            type: 'GET',
            headers: {
                'Accept': 'application/json'
            },
            success: function(data) {
                // Format dates
                var tglMasuk = new Date(data.tanggal_masuk).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                var tglKeluar = data.tanggal_keluar ? new Date(data.tanggal_keluar).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : '-';
                
                // Status badge
                var statusBadge = `<span class="badge badge-success">Aktif</span>`;
                if (data.status !== 'aktif') {
                    statusBadge = `<span class="badge badge-danger">Non-Aktif</span>`;
                }

                var kamarInfo = data.kamar ? data.kamar.nomor_kamar : 'N/A';
                var fotoKtpUrl = data.foto_ktp_url || '#';
                var kontrakUrl = data.dokumen_kontrak_url || '#';

                var content = `
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Data Personal</h5>
                            <table class="table table-sm table-borderless">
                                <tr><td width="120"><strong>Nama</strong></td><td>: ${data.nama_penyewa}</td></tr>
                                <tr><td><strong>No. KTP</strong></td><td>: ${data.nomor_ktp}</td></tr>
                                <tr><td><strong>Telepon</strong></td><td>: ${data.telepon}</td></tr>
                                <tr><td><strong>Email</strong></td><td>: ${data.email || '-'}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Data Sewa</h5>
                            <table class="table table-sm table-borderless">
                                <tr><td width="120"><strong>No. Kamar</strong></td><td>: ${kamarInfo}</td></tr>
                                <tr><td><strong>Tgl Masuk</strong></td><td>: ${tglMasuk}</td></tr>
                                <tr><td><strong>Tgl Keluar</strong></td><td>: ${tglKeluar}</td></tr>
                                <tr><td><strong>Status</strong></td><td>: ${statusBadge}</td></tr>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <h5>Dokumen</h5>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td width="150"><strong>Foto KTP</strong></td>
                            <td><a href="${fotoKtpUrl}" target="_blank" class="btn btn-sm btn-info ${!data.foto_ktp_url ? 'disabled' : ''}">Lihat Foto</a></td>
                        </tr>
                        <tr>
                            <td><strong>Dokumen Kontrak</strong></td>
                            <td><a href="${kontrakUrl}" target="_blank" class="btn btn-sm btn-info ${!data.dokumen_kontrak_url ? 'disabled' : ''}">Lihat Dokumen</a></td>
                        </tr>
                    </table>
                `;
                
                modalBody.html(content);
            },
            error: function(err) {
                modalBody.html('<div class="text-center p-4 text-danger"><i class="fas fa-exclamation-triangle fa-2x"></i><br><br>Gagal memuat data. Silakan coba lagi.</div>');
                console.error(err);
            }
        });
    });
});
</script>
@endpush