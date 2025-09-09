@extends('layouts.app')

@section('title', 'Edit Data Lampid')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Edit Data Lampid</h1>
    <p class="mb-4">Perbarui informasi data lampid untuk wilayah Anda.</p>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulir Edit Data Lampid</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('lampid.update', $lampid->id) }}" method="POST">
                @method('PUT')
                @csrf
                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="kelahiran" {{ old('status', $lampid->status) == 'kelahiran' ? 'selected' : '' }}>kelahiran</option>
                        <option value="kematian" {{ old('status', $lampid->status) == 'kematian' ? 'selected' : '' }}>kematian</option>
                        <option value="pindah" {{ old('status', $lampid->status) == 'pindah' ? 'selected' : '' }}> Warga Pindah</option>
                        <option value="baru" {{ old('status', $lampid->status) == 'baru' ? 'selected' : '' }}>Warga baru</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="jumlah">Jumlah</label>
                    <input type="number" class="form-control @error('jumlah') is-invalid @enderror" id="jumlah" name="jumlah" value="{{ old('jumlah', $lampid->jumlah) }}" required min="0">
                    @error('jumlah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('lampid.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection