<div>
    <h4>{{ $pegawai->nama }}</h4>
    <table class="table table-bordered">
                                <tr>
            <th>NIP</th>
            <td>{{ $pegawai->nip }}</td>
                                </tr>
                                <tr>
            <th>Jabatan</th>
            <td>{{ $pegawai->jabatan }}</td>
                                </tr>
                                <tr>
            <th>Email</th>
            <td>{{ $pegawai->email }}</td>
                                </tr>
        <!-- Tambahkan field lain sesuai kebutuhan -->
                            </table>
</div>

<script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
<script>
  // script custom kamu di sini
  $(document).on('click', '.btn-detail-pegawai', function() { ... });
</script>