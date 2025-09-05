@extends('layouts.app')

@section('title', 'Tambah Data Fasilitas Umum')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Tambah Data Fasilitas Umum</h1>
    <p class="mb-4">Masukkan informasi fasilitas umum untuk wilayah Anda.</p>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulir Data Fasum</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('fasum.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="nama">Nama Fasilitas Umum</label>
                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" required>
                    @error('nama')
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
                    <a href="{{ route('fasum.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection