{{-- File: resources/views/pegawai/index.blade.php --}}
@extends('sidebar')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Manajemen Pegawai</h3>
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
                <a href="{{ route('pegawai.index') }}">Pegawai</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title mb-0">Data Pegawai</h4>
                        <a href="{{ route('pegawai.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i>
                            Tambah Pegawai
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($pegawais->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Nama Pegawai</th>
                                        <th>Jobdesk</th>
                                        <th>No. Telepon</th>
                                        <th>Jadwal Kerja</th>
                                        <th>Status</th>
                                        <th width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pegawais as $index => $pegawai)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $pegawai->nama_pegawai }}</td>
                                        <td>{{ Str::limit($pegawai->jobdesk, 50) }}</td>
                                        <td>{{ $pegawai->no_telepon }}</td>
                                        <td>{{ $pegawai->jadwal_kerja }}</td>
                                        <td>
                                            <span class="badge badge-{{ $pegawai->status_pegawai == 'aktif' ? 'success' : ($pegawai->status_pegawai == 'tidak_aktif' ? 'danger' : 'warning') }}">
                                                {{ ucfirst(str_replace('_', ' ', $pegawai->status_pegawai)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group d-flex align-items-center gap-1">
                                                <a href="{{ route('pegawai.show', $pegawai->id) }}" class="btn btn-info btn-sm" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('pegawai.edit', $pegawai->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" action="{{ route('pegawai.destroy', $pegawai->id) }}" class="d-inline mb-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus" onclick="return confirm('Yakin ingin menghapus data pegawai ini?')">
                                                    <i class="fas fa-trash"></i>
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
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle"></i> Belum ada data pegawai
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Pegawai -->
<div class="modal fade" id="modalDetailPegawai" tabindex="-1" role="dialog" aria-labelledby="modalDetailPegawaiLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalDetailPegawaiLabel">Detail Pegawai</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modalDetailPegawaiContent">
        <div class="text-center py-5">
          <div class="spinner-border text-primary"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
$(document).on('click', '.btn-detail-pegawai', function() {
    var id = $(this).data('id');
    $('#modalDetailPegawaiContent').html('<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>');
    $('#modalDetailPegawai').modal('show');
    $.get('/pegawai/' + id + '/modal', function(data) {
        $('#modalDetailPegawaiContent').html(data);
    }).fail(function() {
        $('#modalDetailPegawaiContent').html('<div class="alert alert-danger">Gagal memuat detail pegawai.</div>');
    });
});
</script>
@endsection