@csrf
<div class="form-group">
    <label for="tahun_lahir">Tahun Kelahiran</label>
    <input type="number" name="tahun_lahir" id="tahun_lahir" class="form-control @error('tahun_lahir') is-invalid @enderror"
           value="{{ old('tahun_lahir', $year->tahun_lahir ?? '') }}" placeholder="Contoh: 1995">
    @error('tahun_lahir')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="jumlah">Jumlah</label>
    <input type="number" name="jumlah" id="jumlah" class="form-control @error('jumlah') is-invalid @enderror"
           value="{{ old('jumlah', $year->jumlah ?? '') }}" placeholder="Masukkan jumlah warga">
    @error('jumlah')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<button type="submit" class="btn btn-primary">{{ $tombol ?? 'Simpan' }}</button>
<a href="{{ route('year.index') }}" class="btn btn-secondary">Batal</a>
