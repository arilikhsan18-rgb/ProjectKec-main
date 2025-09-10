@extends('layouts.app')

@section('title', 'Tambah Data Kependudukan')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Tambah Data Kependudukan</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tambah Data</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('penduduk.store') }}" method="POST">
                @csrf
                {{-- Menampilkan Error Validasi --}}
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
                    <label for="year">Tahun Lahir</label>
                    <input type="text" class="form-control" id="year" name="year" value="{{ old('year') }}" required>
                </div>

                <div class="form-group">
                    <label for="gender">Jenis Kelamin</label>
                    <select class="form-control" id="gender" name="gender" required>
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="laki-laki" {{ old('gender') == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="perempuan" {{ old('gender') == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="resident">Status Kependudukan</label>
                    <select class="form-control" id="resident" name="resident" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="tetap" {{ old('resident') == 'tetap' ? 'selected' : '' }}>Tetap</option>
                        <option value="pindahan" {{ old('resident') == 'pindahan' ? 'selected' : '' }}>Pindahan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="religion">Agama</label>
                    <input type="text" class="form-control" id="religion" name="religion" value="{{ old('religion') }}" required>
                </div>

                <div class="form-group">
                    <label for="education">Status Pendidikan</label>
                    <select class="form-control" id="education" name="education" required>
                        <option value="">-- Pilih Pendidikan --</option>
                        <option value="belum sekolah" {{ old('education') == 'belum sekolah' ? 'selected' : '' }}>Belum Sekolah</option>
                        <option value="masih sekolah" {{ old('education') == 'masih sekolah' ? 'selected' : '' }}>Masih Sekolah</option>
                        <option value="putus sekolah" {{ old('education') == 'putus sekolah' ? 'selected' : '' }}>Putus Sekolah</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="occupation">Status Pekerjaan</label>
                    <select class="form-control" id="occupation" name="occupation" required>
                        <option value="">-- Pilih Pekerjaan --</option>
                        <option value="bekerja" {{ old('occupation') == 'bekerja' ? 'selected' : '' }}>Bekerja</option>
                        <option value="tidak bekerja" {{ old('occupation') == 'tidak bekerja' ? 'selected' : '' }}>Tidak Bekerja</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="lampid">Jenis Laporan (Lampid) <span class="text-muted">(Opsional)</span></label>
                    {{-- Atribut "required" dihapus dari select di bawah ini --}}
                    <select class="form-control" id="lampid" name="lampid">
                        <option value="">-- Pilih Jenis Laporan --</option>
                        <option value="kelahiran" {{ old('lampid') == 'kelahiran' ? 'selected' : '' }}>Kelahiran</option>
                        <option value="kematian" {{ old('lampid') == 'kematian' ? 'selected' : '' }}>Kematian</option>
                        <option value="pindah" {{ old('lampid') == 'pindah' ? 'selected' : '' }}>Pindah</option>
                        <option value="datang" {{ old('lampid') == 'datang' ? 'selected' : '' }}>Datang</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary">Simpan Data</button>
                <a href="{{ route('penduduk.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
@endsection

