{{-- File: resources/views/pengeluaran/show.blade.php --}}

<div>
    <h3>Detail Pengeluaran</h3>
    <div class="mb-3">
        <a href="{{ route('pengeluaran.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered">
        <tr>
            <th>Tanggal Pengeluaran</th>
            <td>{{ $pengeluaran->formatted_tanggal }}</td>
        </tr>
        <tr>
            <th>Kategori</th>
            <td>
                <span class="badge badge-{{ $pengeluaran->kategori_info['color'] }}">
                    <i class="{{ $pengeluaran->kategori_info['icon'] }}"></i>
                    {{ $pengeluaran->kategori_info['name'] }}
                </span>
            </td>
        </tr>
        <tr>
            <th>Jenis Pengeluaran</th>
            <td>{{ $pengeluaran->jenis_pengeluaran }}</td>
        </tr>
        <tr>
            <th>Nominal</th>
            <td><span class="h5 text-danger">{{ $pengeluaran->formatted_nominal }}</span></td>
        </tr>
        <tr>
            <th>Keterangan Detail</th>
            <td>{{ $pengeluaran->keterangan_detail }}</td>
        </tr>
        @if($pengeluaran->catatan_tambahan)
        <tr>
            <th>Catatan Tambahan</th>
            <td>{{ $pengeluaran->catatan_tambahan }}</td>
        </tr>
        @endif
        <tr>
            <th>Status Approval</th>
            <td>
                <span class="badge badge-{{ $pengeluaran->status_approval == 'approved' ? 'success' : ($pengeluaran->status_approval == 'pending' ? 'warning' : 'danger') }}">
                    {{ ucfirst($pengeluaran->status_approval) }}
                </span>
            </td>
        </tr>
        <tr>
            <th>Pengeluaran Berulang</th>
            <td>
                @if($pengeluaran->is_recurring)
                    <span class="badge badge-info"><i class="fas fa-repeat"></i> Ya</span>
                @else
                    <span class="badge badge-secondary"><i class="fas fa-times"></i> Tidak</span>
                @endif
            </td>
        </tr>
        <tr>
            <th>Dibuat Pada</th>
            <td>{{ $pengeluaran->created_at->format('d F Y H:i') }}</td>
        </tr>
        <tr>
            <th>Terakhir Diupdate</th>
            <td>{{ $pengeluaran->updated_at->format('d F Y H:i') }}</td>
        </tr>
        <tr>
            <th>Bukti Pembayaran</th>
            <td class="text-center">
                @if($pengeluaran->bukti_pembayaran)
                    <img src="{{ asset('uploads/bukti_pengeluaran/' . $pengeluaran->bukti_pembayaran) }}"
                         alt="Bukti Pembayaran"
                         class="img-fluid img-thumbnail"
                         style="max-width: 300px; cursor: pointer;"
                         onclick="showImageModal(this.src)">
                    <br>
                    <a href="{{ asset('uploads/bukti_pengeluaran/' . $pengeluaran->bukti_pembayaran) }}"
                       target="_blank"
                       class="btn btn-sm btn-info mt-2">
                        <i class="fas fa-download"></i> Download
                    </a>
                @else
                    <div class="alert alert-warning mb-0 mt-2">
                        <i class="fas fa-exclamation-triangle"></i> Tidak ada bukti pembayaran
                    </div>
                @endif
            </td>
        </tr>
    </table>

    <!-- Aksi Cepat -->
    <div class="mt-4">
        <form method="POST" action="{{ route('pengeluaran.destroy', $pengeluaran->id) }}" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger"
                    onclick="return confirm('Yakin ingin menghapus pengeluaran ini?')">
                <i class="fas fa-trash"></i> Hapus Pengeluaran
            </button>
        </form>
    </div>

</div>

<!-- Modal untuk preview gambar -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Bukti Pembayaran</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
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
