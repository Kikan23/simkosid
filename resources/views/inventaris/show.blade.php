{{-- File: resources/views/inventaris/show.blade.php --}}
@extends('sidebar')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Inventaris</h3>
                    <div class="card-tools">
                        <a href="{{ route('inventaris.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="30%"><strong>Nama Barang:</strong></td>
                                    <td>{{ $inventaris->nama_barang }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Letak:</strong></td>
                                    <td>{{ $inventaris->letak }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Beli:</strong></td>
                                    <td>{{ $inventaris->tanggal_beli ? $inventaris->tanggal_beli->format('d/m/Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $inventaris->status == 'baik' ? 'success' : ($inventaris->status == 'rusak' ? 'danger' : ($inventaris->status == 'hilang' ? 'dark' : 'warning')) }}">
                                            {{ ucfirst($inventaris->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Dibuat:</strong></td>
                                    <td>{{ $inventaris->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Diupdate:</strong></td>
                                    <td>{{ $inventaris->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Keterangan:</strong></label>
                                <div class="border p-3 rounded">
                                    {{ $inventaris->keterangan ?: 'Tidak ada keterangan' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('inventaris.edit', $inventaris->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form method="POST" action="{{ route('inventaris.destroy', $inventaris->id) }}" class="d-inline">
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