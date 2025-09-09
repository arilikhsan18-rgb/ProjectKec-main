@extends('layouts.app')

@section('title', 'Tambah Data Lampid')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Tambah Data Lampid</h1>
    <p class="mb-4">Masukkan informasi data lampid untuk wilayah Anda.</p>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulir Data Lampid</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('lampid.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="kelahiran" {{ old('status') == 'kelahiran' ? 'selected' : '' }}>Kelahiran</option>
                        <option value="kematian" {{ old('status') == 'kematian' ? 'selected' : '' }}>Kematian</option>
                        <option value="pindah" {{ old('status') == 'pindah' ? 'selected' : '' }}>Warga Pindah</option>
                        <option value="baru" {{ old('status') == 'baru' ? 'selected' : '' }}>Warga Baru</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="jumlah">Jumlah</label>
                    <input type="number" class="form-control @error('jumlah') is-invalid @enderror" id="jumlah" name="jumlah" value="{{ old('jumlah') }}" required min="0">
                    @error('jumlah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('lampid.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection