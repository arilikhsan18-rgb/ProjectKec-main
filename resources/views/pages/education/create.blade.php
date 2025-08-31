@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        {{-- KESALAHAN 1: Judul diperbaiki agar sesuai dengan form --}}
        <h1 class="h3 mb-0 text-gray-800">Tambah Data Pendidikan</h1>
    </div>
    <div class="row">
        <div class="col">
            {{-- Action sudah benar mengarah ke /education --}}
            <form action="{{ route('education.store') }}" method="POST">
                @csrf
                {{-- @method('POST') tidak diperlukan untuk create, bisa dihapus --}}
                <div class="card">
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="sekolah">Status Sekolah</label>
                            <select name="sekolah" id="sekolah" class="form-control @error('sekolah') is-invalid @enderror">
                                <option value="" disabled selected>-- Pilih Status --</option>
                                <option value="masih sekolah" {{ old('sekolah') == 'masih sekolah' ? 'selected' : '' }}>Sekolah</option>
                                <option value="tidak sekolah" {{ old('sekolah') == 'tidak sekolah' ? 'selected' : '' }}>Tidak Sekolah</option>
                                <option value="putus sekolah" {{ old('sekolah') == 'putus sekolah' ? 'selected' : '' }}>Putus Sekolah</option>
                            </select>
                            @error('sekolah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- KESALAHAN 3: Class div dan id input diperbaiki --}}
                        <div class="form-group mb-3">
                            <label for="jumlah">Jumlah</label>
                            <input type="number" name="jumlah" id="jumlah" class="form-control @error('jumlah') is-invalid @enderror" value="{{ old('jumlah') }}">
                             @error('jumlah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-end" style="gap: 10px;">
                            {{-- KESALAHAN 2: Link tombol kembali diperbaiki --}}
                            <a href="{{ route('education.index') }}" class="btn btn-outline-secondary">
                                Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
