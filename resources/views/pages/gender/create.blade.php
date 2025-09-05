@extends('layouts.app')

@section('title', 'Tambah Data Gender')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Tambah Data Gender</h1>
    <p class="mb-4">Gunakan formulir di bawah ini untuk menambahkan data baru.</p>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulir Tambah Data</h6>
        </div>
        <div class="card-body">

            {{-- Tampilkan Notifikasi Error --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('gender.store') }}" method="POST">
                @csrf {{-- Token Keamanan Laravel --}}
                
                <div class="form-group">
                    <label for="gender">Jenis Kelamin</label>
                    {{-- Menggunakan dropdown untuk membatasi input --}}
                    <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror" required>
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="laki-laki" {{ old('gender') == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="perempuan" {{ old('gender') == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('gender')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="jumlah">Jumlah</label>
                    <input type="number" name="jumlah" id="jumlah" class="form-control @error('jumlah') is-invalid @enderror" value="{{ old('jumlah') }}" required min="0">
                    @error('jumlah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                    <a href="{{ route('gender.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>

        </div>
    </div>
@endsection
