@extends('layouts.app')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Ubah Data Pendidikan</h1>
    </div>

    <div class="row">
        <div class="col">
            {{-- Form action sudah benar, mengarah ke route update dengan method PUT --}}
            <form action="/education/{{ $education->id }}" method="post">
                @csrf
                @method('PUT')
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Formulir Edit Data</h6>
                    </div>
                    <div class="card-body">
                        {{-- 1. Perbaikan: Container menggunakan form-group --}}
                        <div class="form-group mb-3">
                            <label for="sekolah">Status Sekolah</label>
                            {{-- 2. Perbaikan: Menambahkan class is-invalid untuk error handling --}}
                            <select name="sekolah" id="sekolah" class="form-control @error('sekolah') is-invalid @enderror">
                                <option value="" disabled>-- Pilih Status --</option>
                                {{-- 3. Perbaikan: Menampilkan data lama (selected option) --}}
                                <option value="masih sekolah" {{ old('sekolah', $education->sekolah) == ' masih sekolah' ? 'selected' : '' }}>
                                    Sekolah
                                </option>
                                <option value="tidak sekolah" {{ old('sekolah', $education->sekolah) == 'tidak sekolah' ? 'selected' : '' }}>
                                    Tidak Sekolah
                                </option>
                                <option value="putus sekolah" {{ old('sekolah', $education->sekolah) == 'putus sekolah' ? 'selected' : '' }}>
                                    Putus Sekolah
                                </option>
                            </select>
                            {{-- 4. Perbaikan: Menampilkan pesan error validasi --}}
                            @error('sekolah')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- 5. Perbaikan: Container diubah dari .form-control menjadi .form-group --}}
                        <div class="form-group mb-3">
                            <label for="jumlah">Jumlah</label>
                            {{-- 6. Perbaikan: Typo pada id, value diisi dengan data lama, dan tipe input diubah --}}
                            <input type="number" name="jumlah" id="jumlah"
                                   class="form-control @error('jumlah') is-invalid @enderror"
                                   value="{{ old('jumlah', $education->jumlah) }}"
                                   placeholder="Masukkan jumlah">
                            @error('jumlah')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-end" style="gap: 10px;">
                            <a href="/education" class="btn btn-secondary">
                                Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection