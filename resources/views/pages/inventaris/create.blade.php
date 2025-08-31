@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4 border-left-primary">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h4 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-plus-circle me-2"></i>Tambah Data Inventaris Baru
            </h4>
        </div>
        <div class="card-body">
            <form action="{{ route('inventaris.store') }}" method="POST">
                @csrf

                {{-- Bagian 1: Informasi Utama Barang --}}
                <h5 class="mb-3 text-gray-800 border-bottom pb-2">1. Informasi Utama Barang</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nama_barang" class="form-label fw-bold">Nama Barang / Jenis Barang <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_barang') is-invalid @enderror" id="nama_barang" name="nama_barang" value="{{ old('nama_barang') }}" placeholder="Contoh: Meja Komputer Kayu" required>
                        @error('nama_barang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="kode_barang" class="form-label fw-bold">No. Kode Barang <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('kode_barang') is-invalid @enderror" id="kode_barang" name="kode_barang" value="{{ old('kode_barang') }}" placeholder="Contoh: A.101.01" required>
                        @error('kode_barang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="merk_model" class="form-label fw-bold">Merk / Model</label>
                        <input type="text" class="form-control @error('merk_model') is-invalid @enderror" id="merk_model" name="merk_model" value="{{ old('merk_model') }}" placeholder="Contoh: Olympic / Minimalis">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="bahan" class="form-label fw-bold">Bahan</label>
                        <input type="text" class="form-control @error('bahan') is-invalid @enderror" id="bahan" name="bahan" value="{{ old('bahan') }}" placeholder="Contoh: Kayu Jati">
                    </div>
                </div>

                {{-- Bagian 2: Detail Perolehan --}}
                <h5 class="mt-4 mb-3 text-gray-800 border-bottom pb-2">2. Detail Perolehan</h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="tahun_pembelian" class="form-label fw-bold">Tahun Pembelian <span class="text-danger">*</span></label>
                        <div class="input-group">
                             <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                            <input type="number" class="form-control @error('tahun_pembelian') is-invalid @enderror" id="tahun_pembelian" name="tahun_pembelian" value="{{ old('tahun_pembelian') }}" placeholder="Contoh: 2023" required>
                        </div>
                        @error('tahun_pembelian')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="jumlah" class="form-label fw-bold">Jumlah <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('jumlah') is-invalid @enderror" id="jumlah" name="jumlah" value="{{ old('jumlah', 1) }}" required>
                        @error('jumlah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="harga_perolehan" class="form-label fw-bold">Harga Satuan (Rp) <span class="text-danger">*</span></label>
                         <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control @error('harga_perolehan') is-invalid @enderror" id="harga_perolehan" name="harga_perolehan" value="{{ old('harga_perolehan') }}" placeholder="Contoh: 500000" required>
                        </div>
                        @error('harga_perolehan')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Bagian 3: Kondisi & Lokasi --}}
                <h5 class="mt-4 mb-3 text-gray-800 border-bottom pb-2">3. Kondisi & Lokasi</h5>
                <div class="row">
                     <div class="col-md-6 mb-3">
                        <label for="kondisi" class="form-label fw-bold">Keadaan Barang <span class="text-danger">*</span></label>
                        <select class="form-select @error('kondisi') is-invalid @enderror" id="kondisi" name="kondisi" required>
                            <option value="" disabled selected>-- Pilih Kondisi --</option>
                            <option value="B" {{ old('kondisi') == 'B' ? 'selected' : '' }}>Baik (B)</option>
                            <option value="KB" {{ old('kondisi') == 'KB' ? 'selected' : '' }}>Kurang Baik (KB)</option>
                            <option value="RB" {{ old('kondisi') == 'RB' ? 'selected' : '' }}>Rusak Berat (RB)</option>
                        </select>
                        @error('kondisi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 mb-3">
                        <label for="keterangan" class="form-label fw-bold">Keterangan</label>
                        <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="3" placeholder="Tambahkan catatan jika ada (contoh: perlu perbaikan kecil pada kaki meja)...">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                {{-- Tombol Aksi --}}
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('inventaris.index') }}" class="btn btn-secondary mr-1">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

