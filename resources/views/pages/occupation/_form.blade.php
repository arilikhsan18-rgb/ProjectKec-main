@csrf
<div class="form-group">
    <label for="pekerjaan">Status Pekerjaan</label>
    <select name="pekerjaan" id="pekerjaan" class="form-control @error('pekerjaan') is-invalid @enderror">
        <option value="">-- Pilih Status --</option>
        <option value="bekerja" {{ old('pekerjaan', $occupation->pekerjaan ?? '') == 'bekerja' ? 'selected' : '' }}>Bekerja</option>
        <option value="tidak bekerja" {{ old('pekerjaan', $occupation->pekerjaan ?? '') == 'tidak bekerja' ? 'selected' : '' }}>Tidak Bekerja</option>
        <option value="usaha" {{ old('pekerjaan', $occupation->pekerjaan ?? '') == 'usaha' ? 'selected' : '' }}>Usaha</option>
    </select>
    @error('pekerjaan')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="jumlah">Jumlah</label>
    <input type="number" name="jumlah" id="jumlah" class="form-control @error('jumlah') is-invalid @enderror"
           value="{{ old('jumlah', $occupation->jumlah ?? '') }}" placeholder="Masukkan jumlah warga">
    @error('jumlah')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<button type="submit" class="btn btn-primary">{{ $tombol ?? 'Simpan' }}</button>
<a href="{{ route('occupation.index') }}" class="btn btn-secondary">Batal</a>
