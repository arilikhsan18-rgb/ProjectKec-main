@extends('layouts.app')

@section('title', 'Tambah Data Fasilitas')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Tambah Data Fasilitas</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tambah Fasilitas</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('fasilitas.store') }}" method="POST">
                @csrf
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-group">
                    <label for="nama_fasilitas">Nama Fasilitas <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nama_fasilitas" name="nama_fasilitas" value="{{ old('nama_fasilitas') }}" required>
                </div>

                <div class="form-group">
                    <label for="jenis_fasilitas">Jenis Fasilitas <span class="text-danger">*</span></label>
                    <select class="form-control" id="jenis_fasilitas" name="jenis_fasilitas" required>
                        <option value="">-- Pilih Jenis --</option>
                        <option value="Tempat Ibadah" {{ old('jenis_fasilitas') == 'Tempat Ibadah' ? 'selected' : '' }}>Tempat Ibadah</option>
                        <option value="Pendidikan" {{ old('jenis_fasilitas') == 'Pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                        <option value="Kesehatan" {{ old('jenis_fasilitas') == 'Kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                        <option value="Umum" {{ old('jenis_fasilitas') == 'Umum' ? 'selected' : '' }}>Umum</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="3">{{ old('alamat') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <textarea class="form-control" id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">Simpan Data</button>
                <a href="{{ route('fasilitas.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
@endsection
