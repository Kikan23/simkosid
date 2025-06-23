                            <div class="form-group">
                                <label for="catatan">Catatan</label>
                                <textarea class="form-control" id="catatan" name="catatan" rows="3">{{ old('catatan', $tenant->catatan) }}</textarea>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <hr>
                            <h5>Dokumen</h5>
                            <div class="form-group">
                                <label for="foto_ktp">Foto KTP</label>
                                <input type="file" class="form-control" name="foto_ktp" accept="image/*,.pdf">
                                @if ($tenant->foto_ktp)
                                    <small class="d-block mt-1">File saat ini: 
                                        <a href="{{ $tenant->foto_ktp_url }}" target="_blank">Lihat KTP</a>
                                    </small>
                                @endif
                                @error('foto_ktp')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="dokumen_kontrak">Dokumen Kontrak</label>
                                <input type="file" class="form-control" name="dokumen_kontrak" accept="image/*,.pdf">
                                @if ($tenant->dokumen_kontrak)
                                    <small class="d-block mt-1">File saat ini: 
                                        <a href="{{ $tenant->dokumen_kontrak_url }}" target="_blank">Lihat Kontrak</a>
                                    </small>
                                @endif
                                @error('dokumen_kontrak')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card-action">
                    <button type="submit" class="btn btn-success">Update</button>
                    <a href="{{ route('tenants.index') }}" class="btn btn-danger">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 