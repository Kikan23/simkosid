{{-- File: resources/views/keluhan/index.blade.php --}}
@extends('sidebar')

@section('content')
<div class="page-inner">
    <div class="page-header">
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
                <a href="{{ route('keluhan.index') }}">Keluhan</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Data Keluhan</h4>
                        <a href="{{ route('keluhan.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i>
                            Tambah Keluhan
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($keluhans->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Nama Penghuni</th>
                                        <th>No. Kamar</th>
                                        <th>Tanggal Keluhan</th>
                                        <th>Deskripsi</th>
                                        <th>Status</th>
                                        <th width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($keluhans as $keluhan)
                                    <tr>
                                        <td>{{ $loop->iteration + ($keluhans->currentPage() - 1) * $keluhans->perPage() }}</td>
                                        <td>{{ $keluhan->nama_penghuni }}</td>
                                        <td>{{ $keluhan->no_kamar }}</td>
                                        <td>{{ $keluhan->tanggal_keluhan->format('d/m/Y') }}</td>
                                        <td>{{ Str::limit($keluhan->deskripsi_keluhan, 50) }}</td>
                                        <td>
                                            <span class="badge badge-{{ $keluhan->status_keluhan == 'selesai' ? 'success' : 'warning' }}">
                                                {{ ucfirst($keluhan->status_keluhan) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('keluhan.show', $keluhan->id) }}" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('keluhan.edit', $keluhan->id) }}" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('keluhan.destroy', $keluhan->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" 
                                                            onclick="return confirm('Yakin ingin menghapus data ini?')">
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
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $keluhans->links() }}
                        </div>
                    @else
                        <div class="d-flex align-items-center justify-content-center" style="height: 300px;">
                            <div class="text-center">
                                <i class="fas fa-comment-dots fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Belum ada data keluhan</h5>
                                <p class="text-muted">Silakan tambahkan keluhan baru untuk memulai</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection