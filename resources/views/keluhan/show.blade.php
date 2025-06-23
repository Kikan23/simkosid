{{-- File: resources/views/keluhan/show.blade.php --}}
@extends('sidebar')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center mt-5">
        <div class="col-12 mt-5">
            <div class="card shadow-sm">
                <div class="card-header border-bottom pb-2 d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Detail Keluhan</h3>
                </div>
                <div class="card-body pt-3">
                    <div class="row">
                        <div class="col-md-8">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td width="35%"><strong>Nama Penghuni:</strong></td>
                                    <td>{{ $keluhan->nama_penghuni }}</td>
                                </tr>
                                <tr>
                                    <td><strong>No. Kamar:</strong></td>
                                    <td>{{ $keluhan->no_kamar }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Keluhan:</strong></td>
                                    <td>{{ $keluhan->tanggal_keluhan->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status Keluhan:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $keluhan->status_keluhan == 'selesai' ? 'success' : 'warning' }}">
                                            {{ ucfirst($keluhan->status_keluhan) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Dibuat:</strong></td>
                                    <td>{{ $keluhan->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Diupdate:</strong></td>
                                    <td>{{ $keluhan->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <div class="border p-3 rounded bg-light" style="min-height: 150px;">
                                <strong>Deskripsi Keluhan:</strong><br>
                                {{ $keluhan->deskripsi_keluhan }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-top d-flex gap-2 justify-content-end">
                    <div class="card-tools">
                        <a href="{{ route('keluhan.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                    <a href="{{ route('keluhan.edit', $keluhan->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form method="POST" action="{{ route('keluhan.destroy', $keluhan->id) }}" class="d-inline mb-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" 
                                onclick="return confirm('Yakin ingin menghapus data ini?')">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection