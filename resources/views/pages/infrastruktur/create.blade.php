@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Data Infrastruktur</h1>
        {{-- Tombol untuk kembali ke halaman index --}}
        <a href="{{ route('infrastruktur.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    {{-- Form --}}
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Formulir Tambah Infrastruktur</h6>
                </div>
                <div class="card-body">
                    {{-- Arahkan action ke route yang benar dan tambahkan enctype untuk upload file --}}
                    <form action="{{ route('infrastruktur.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="gambar">Gambar</label>
                            <input type="file" class="form-control-file @error('gambar') is-invalid @enderror" id="gambar" name="gambar">
                            @error('gambar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" placeholder="Masukkan alamat..." value="{{ old('alamat') }}">
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="ukuran">Ukuran</label>
                            <input type="text" class="form-control @error('ukuran') is-invalid @enderror" id="ukuran" name="ukuran" placeholder="Contoh: 10x20 Meter" value="{{ old('ukuran') }}">
                            @error('ukuran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="4" placeholder="Masukkan keterangan tambahan...">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Data
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection