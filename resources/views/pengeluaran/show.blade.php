{{-- File: resources/views/pengeluaran/show.blade.php --}}
@extends('sidebar')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center mt-5">
        <div class="col-12 mt-5">
            <div class="card shadow-sm">
                <div class="card-header border-bottom pb-2 d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Detail Pengeluaran</h3>
                </div>
                <div class="card-body pt-3">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-8">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td width="35%"><strong>Tanggal Pengeluaran:</strong></td>
                                    <td>{{ $pengeluaran->formatted_tanggal }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Kategori:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $pengeluaran->kategori_info['color'] }}">
                                            <i class="{{ $pengeluaran->kategori_info['icon'] }}"></i>
                                            {{ $pengeluaran->kategori_info['name'] }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Jenis Pengeluaran:</strong></td>
                                    <td>{{ $pengeluaran->jenis_pengeluaran }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nominal:</strong></td>
                                    <td><span class="h5 text-danger">{{ $pengeluaran->formatted_nominal }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Keterangan Detail:</strong></td>
                                    <td>{{ $pengeluaran->keterangan_detail }}</td>
                                </tr>
                                @if($pengeluaran->catatan_tambahan)
                                <tr>
                                    <td><strong>Catatan Tambahan:</strong></td>
                                    <td>{{ $pengeluaran->catatan_tambahan }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td><strong>Status Approval:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $pengeluaran->status_approval == 'approved' ? 'success' : ($pengeluaran->status_approval == 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($pengeluaran->status_approval) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Pengeluaran Berulang:</strong></td>
                                    <td>
                                        @if($pengeluaran->is_recurring)
                                            <span class="badge badge-info"><i class="fas fa-repeat"></i> Ya</span>
                                        @else
                                            <span class="badge badge-secondary"><i class="fas fa-times"></i> Tidak</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Dibuat pada:</strong></td>
                                    <td>{{ $pengeluaran->created_at->format('d F Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Terakhir diupdate:</strong></td>
                                    <td>{{ $pengeluaran->updated_at->format('d F Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4 text-center">
                            @if($pengeluaran->bukti_pembayaran)
                                <div class="mb-2">
                                    <img src="{{ asset('uploads/bukti_pengeluaran/' . $pengeluaran->bukti_pembayaran) }}" 
                                         alt="Bukti Pembayaran" class="img-fluid img-thumbnail mb-2" 
                                         style="max-width: 300px; cursor: pointer;"
                                         onclick="showImageModal(this.src)">
                                </div>
                                <a href="{{ asset('uploads/bukti_pengeluaran/' . $pengeluaran->bukti_pembayaran) }}" 
                                   target="_blank" class="btn btn-sm btn-info mb-2">
                                    <i class="fas fa-download"></i> Download
                                </a>
                            @else
                                <div class="alert alert-warning mt-2">
                                    <i class="fas fa-exclamation-triangle"></i> Tidak ada bukti pembayaran
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-top d-flex gap-2 justify-content-end">
                    <div class="card-tools">
                        <a href="{{ route('pengeluaran.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                    <a href="{{ route('pengeluaran.edit', $pengeluaran->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form method="POST" action="{{ route('pengeluaran.destroy', $pengeluaran->id) }}" class="d-inline mb-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" 
                                onclick="return confirm('Yakin ingin menghapus pengeluaran ini?')">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk preview gambar -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Bukti Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Bukti Pembayaran" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<script>
function showImageModal(src) {
    $('#modalImage').attr('src', src);
    $('#imageModal').modal('show');
}
</script>
@endsection