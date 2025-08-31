@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4 border-left-warning">
        {{-- Card Header --}}
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h4 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-pencil-alt me-2"></i>Edit Data Status Pekerjaan
            </h4>
        </div>

        {{-- Card Body --}}
        <div class="card-body">
            {{-- Form menunjuk ke route 'occupation.update' --}}
            {{-- Variabel $occupation harus dikirim dari controller --}}
            <form action="{{ route('occupation.update', $occupation->id) }}" method="POST">
                @csrf
                @method('PUT') {{-- Method spoofing untuk request UPDATE --}}

                <div class="row">
                    {{-- Input untuk Status Pekerjaan --}}
                    <div class="col-md-6 mb-3">
                        <label for="pekerjaan" class="form-label fw-bold">Status Pekerjaan <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('pekerjaan') is-invalid @enderror" 
                               id="pekerjaan" 
                               name="pekerjaan" 
                               value="{{ old('pekerjaan', $occupation->pekerjaan) }}" 
                               placeholder="Contoh: Pegawai Swasta" 
                               required>
                        @error('pekerjaan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Input untuk Jumlah --}}
                    <div class="col-md-6 mb-3">
                        <label for="jumlah" class="form-label fw-bold">Jumlah <span class="text-danger">*</span></label>
                        <input type="number" 
                               class="form-control @error('jumlah') is-invalid @enderror" 
                               id="jumlah" 
                               name="jumlah" 
                               value="{{ old('jumlah', $occupation->jumlah) }}" 
                               placeholder="Masukkan jumlah warga" 
                               required>
                        @error('jumlah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                {{-- Tombol Aksi --}}
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('occupation.index') }}" class="btn btn-secondary mr-1">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
