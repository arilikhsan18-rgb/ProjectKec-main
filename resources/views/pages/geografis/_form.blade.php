@csrf
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="luas_wilayah">Luas Wilayah (contoh: 500 Ha)</label>
            <input type="text" class="form-control @error('luas_wilayah') is-invalid @enderror" id="luas_wilayah" name="luas_wilayah" value="{{ old('luas_wilayah', $geografis->luas_wilayah ?? '') }}" required>
            @error('luas_wilayah')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="batas_wilayah_utara">Batas Wilayah Utara</label>
            <input type="text" class="form-control @error('batas_wilayah_utara') is-invalid @enderror" id="batas_wilayah_utara" name="batas_wilayah_utara" value="{{ old('batas_wilayah_utara', $geografis->batas_wilayah_utara ?? '') }}">
            @error('batas_wilayah_utara')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="batas_wilayah_selatan">Batas Wilayah Selatan</label>
            <input type="text" class="form-control @error('batas_wilayah_selatan') is-invalid @enderror" id="batas_wilayah_selatan" name="batas_wilayah_selatan" value="{{ old('batas_wilayah_selatan', $geografis->batas_wilayah_selatan ?? '') }}">
            @error('batas_wilayah_selatan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="batas_wilayah_barat">Batas Wilayah Barat</label>
            <input type="text" class="form-control @error('batas_wilayah_barat') is-invalid @enderror" id="batas_wilayah_barat" name="batas_wilayah_barat" value="{{ old('batas_wilayah_barat', $geografis->batas_wilayah_barat ?? '') }}">
            @error('batas_wilayah_barat')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="batas_wilayah_timur">Batas Wilayah Timur</label>
            <input type="text" class="form-control @error('batas_wilayah_timur') is-invalid @enderror" id="batas_wilayah_timur" name="batas_wilayah_timur" value="{{ old('batas_wilayah_timur', $geografis->batas_wilayah_timur ?? '') }}">
            @error('batas_wilayah_timur')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="form-group mt-3">
    <button type="submit" class="btn btn-primary">{{ $tombol }}</button>
    <a href="{{ route('geografis.index') }}" class="btn btn-secondary">Batal</a>
</div>