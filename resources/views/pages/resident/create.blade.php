@extends('layouts.app')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Data Warga</h1>
        {{-- Tombol untuk kembali ke halaman index --}}
        <a href="{{ route('resident.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    {{-- Form --}}
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Formulir Tambah Warga</h6>
                </div>
                <div class="card-body">
                    {{-- Arahkan form ke route 'resident.store' dengan method POST --}}
                    <form action="{{ route('resident.store') }}" method="POST">
                        {{-- Token CSRF untuk keamanan --}}
                        @csrf

                        <div class="form-group">
                            <label for="status_tinggal">Status Tinggal</label>
                            <select name="status_tinggal" id="status_tinggal" class="form-control @error('status_tinggal') is-invalid @enderror">
                                <option value="" disabled selected>-- Pilih Status --</option>
                                <option value="tetap" {{ old('status_tinggal') == 'tetap' ? 'selected' : '' }}>Tetap</option>
                                <option value="pindahan" {{ old('status_tinggal') == 'pindahan' ? 'selected' : '' }}>Pindahan</option>
                            </select>
                             {{-- Menampilkan pesan error jika validasi gagal --}}
                            @error('status_tinggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="jumlah">Jumlah</label>
                            <input type="number" class="form-control @error('jumlah') is-invalid @enderror" id="jumlah" name="jumlah" placeholder="Masukkan jumlah warga..." value="{{ old('jumlah') }}">
                            {{-- Menampilkan pesan error jika validasi gagal --}}
                            @error('jumlah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Simpan Data
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection