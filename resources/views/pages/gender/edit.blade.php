@extends('layouts.app')

@section('title', 'Edit Data Gender')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Edit Data Gender</h1>
    <p class="mb-4">Gunakan formulir di bawah ini untuk mengubah data yang ada.</p>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulir Edit Data</h6>
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

            {{-- Formulir mengarah ke route update dengan method PUT --}}
            <form action="{{ route('gender.update', $gender->id) }}" method="POST">
                @csrf
                @method('PUT') {{-- Method Spoofing untuk HTTP PUT --}}
                
                <div class="form-group">
                    <label for="gender">Jenis Kelamin</label>
                    {{-- Opsi dropdown akan terpilih otomatis sesuai data yang ada --}}
                    <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror" required>
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="laki-laki" {{ old('gender', $gender->gender) == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="perempuan" {{ old('gender', $gender->gender) == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('gender')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="jumlah">Jumlah</label>
                    {{-- Input akan terisi otomatis dengan data jumlah yang ada --}}
                    <input type="number" name="jumlah" id="jumlah" class="form-control @error('jumlah') is-invalid @enderror" value="{{ old('jumlah', $gender->jumlah) }}" required min="0">
                    @error('jumlah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update</button>
                    <a href="{{ route('gender.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>

        </div>
    </div>
@endsection
