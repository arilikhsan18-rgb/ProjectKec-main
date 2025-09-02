@csrf
<div class="form-group">
    <label for="status_tinggal">Status Tinggal</label>
    <select name="status_tinggal" id="status_tinggal" class="form-control @error('status_tinggal') is-invalid @enderror">
        <option value="">-- Pilih Status --</option>
        {{-- Logika ini akan memilih opsi yang benar saat create (dari old value) atau edit (dari data model) --}}
        <option value="tetap" {{ old('status_tinggal', $resident->status_tinggal ?? '') == 'tetap' ? 'selected' : '' }}>Tetap</option>
        <option value="pindahan" {{ old('status_tinggal', $resident->status_tinggal ?? '') == 'pindahan' ? 'selected' : '' }}>Pindahan</option>
    </select>
    @error('status_tinggal')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="jumlah">Jumlah</label>
    <input type="number" name="jumlah" id="jumlah" class="form-control @error('jumlah') is-invalid @enderror"
           value="{{ old('jumlah', $resident->jumlah ?? '') }}" placeholder="Masukkan jumlah warga">
    @error('jumlah')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<button type="submit" class="btn btn-primary">{{ $tombol ?? 'Simpan' }}</button>
<a href="{{ route('resident.index') }}" class="btn btn-secondary">Batal</a>

