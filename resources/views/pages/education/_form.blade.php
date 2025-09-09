@csrf
<div class="form-group">
    <label for="sekolah">Status Pendidikan</label>
    <select name="sekolah" id="sekolah" class="form-control @error('sekolah') is-invalid @enderror">
        <option value="">-- Pilih Status --</option>
        <option value="masih sekolah" {{ old('sekolah', $education->sekolah ?? '') == 'masih sekolah' ? 'selected' : '' }}>Masih Sekolah</option>
        <option value="belum sekolah" {{ old('sekolah', $education->sekolah ?? '') == 'belum sekolah' ? 'selected' : '' }}>belum Sekolah</option>
        <option value="putus sekolah" {{ old('sekolah', $education->sekolah ?? '') == 'putus sekolah' ? 'selected' : '' }}>Putus Sekolah</option>
    </select>
    @error('sekolah')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="jumlah">Jumlah</label>
    <input type="number" name="jumlah" id="jumlah" class="form-control @error('jumlah') is-invalid @enderror"
           value="{{ old('jumlah', $education->jumlah ?? '') }}" placeholder="Masukkan jumlah warga">
    @error('jumlah')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<button type="submit" class="btn btn-primary">{{ $tombol ?? 'Simpan' }}</button>
<a href="{{ route('education.index') }}" class="btn btn-secondary">Batal</a>
