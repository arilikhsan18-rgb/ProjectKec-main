@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        {{-- Judul disesuaikan dengan konten form --}}
        <h1 class="h3 mb-0 text-gray-800">Tambah Data Pekerjaan</h1>
        {{-- Tombol untuk kembali ke halaman index pekerjaan --}}
        <a href="{{ route('occupation.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    {{-- Form --}}
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Formulir Tambah Pekerjaan</h6>
                </div>
                <div class="card-body">
                    {{-- Arahkan form ke route 'occupation.store' dengan method POST --}}
                    <form action="{{ route('occupation.store') }}" method="POST">
                        {{-- Token CSRF untuk keamanan --}}
                        @csrf

                        <div class="form-group">
                            <label for="pekerjaan">Status Pekerjaan</label>
                            <select name="pekerjaan" id="pekerjaan" class="form-control @error('pekerjaan') is-invalid @enderror">
                                <option value="" disabled selected>-- Pilih Status --</option>
                                {{-- PERBAIKAN: value 'bekerja' harus sama dengan kondisi old() --}}
                                <option value="bekerja" {{ old('pekerjaan') == 'bekerja' ? 'selected' : '' }}>Bekerja</option>
                                <option value="tidak bekerja" {{ old('pekerjaan') == 'tidak bekerja' ? 'selected' : '' }}>Tidak Bekerja</option>
                                <option value="usaha" {{ old('pekerjaan') == 'usaha' ? 'selected' : '' }}>Punya Usaha</option>
                            </select>
                             {{-- Menampilkan pesan error jika validasi gagal --}}
                            @error('pekerjaan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="jumlah">Jumlah</label>
                            <input type="number" class="form-control @error('jumlah') is-invalid @enderror" id="jumlah" name="jumlah" placeholder="Masukkan jumlah..." value="{{ old('jumlah') }}">
                            {{-- Menampilkan pesan error jika validasi gagal --}}
                            @error('jumlah')
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