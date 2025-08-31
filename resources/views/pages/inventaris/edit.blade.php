@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h4 class="m-0 font-weight-bold text-primary">Edit Data Inventaris</h4>
        </div>
        <div class="card-body">
            {{-- Form untuk mengubah data --}}
            {{-- Pastikan variabel $item dikirim dari controller --}}
            <form action="{{ route('inventaris.update', $item->id) }}" method="POST">
                @csrf {{-- Token keamanan Laravel --}}
                @method('PUT') {{-- Metode HTTP untuk update --}}
                
                <div class="row">
                    {{-- Kolom Kiri --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nama_barang" class="form-label">Nama Barang / Jenis Barang</label>
                            <input type="text" class="form-control @error('nama_barang') is-invalid @enderror" id="nama_barang" name="nama_barang" value="{{ old('nama_barang', $item->nama_barang) }}" required>
                            @error('nama_barang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="kode_barang" class="form-label">No. Kode Barang</label>
                            <input type="text" class="form-control @error('kode_barang') is-invalid @enderror" id="kode_barang" name="kode_barang" value="{{ old('kode_barang', $item->kode_barang) }}" required>
                            @error('kode_barang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="merk_model" class="form-label">Merk / Model</label>
                            <input type="text" class="form-control @error('merk_model') is-invalid @enderror" id="merk_model" name="merk_model" value="{{ old('merk_model', $item->merk_model) }}">
                            @error('merk_model')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="bahan" class="form-label">Bahan</label>
                            <input type="text" class="form-control @error('bahan') is-invalid @enderror" id="bahan" name="bahan" value="{{ old('bahan', $item->bahan) }}">
                             @error('bahan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Kolom Kanan --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tahun_pembelian" class="form-label">Tahun Pembelian</label>
                            <input type="number" class="form-control @error('tahun_pembelian') is-invalid @enderror" id="tahun_pembelian" name="tahun_pembelian" value="{{ old('tahun_pembelian', $item->tahun_pembelian) }}" placeholder="Contoh: 2023" required>
                            @error('tahun_pembelian')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="jumlah" class="form-label">Jumlah</label>
                                    <input type="number" class="form-control @error('jumlah') is-invalid @enderror" id="jumlah" name="jumlah" value="{{ old('jumlah', $item->jumlah) }}" required>
                                     @error('jumlah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="harga_perolehan" class="form-label">Harga (Rp)</label>
                                    <input type="number" class="form-control @error('harga_perolehan') is-invalid @enderror" id="harga_perolehan" name="harga_perolehan" value="{{ old('harga_perolehan', $item->harga_perolehan) }}" required>
                                     @error('harga_perolehan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="kondisi" class="form-label">Keadaan Barang</label>
                            <select class="form-select @error('kondisi') is-invalid @enderror" id="kondisi" name="kondisi" required>
                                <option value="" disabled>-- Pilih Kondisi --</option>
                                <option value="B" {{ old('kondisi', $item->kondisi) == 'B' ? 'selected' : '' }}>Baik (B)</option>
                                <option value="KB" {{ old('kondisi', $item->kondisi) == 'KB' ? 'selected' : '' }}>Kurang Baik (KB)</option>
                                <option value="RB" {{ old('kondisi', $item->kondisi) == 'RB' ? 'selected' : '' }}>Rusak Berat (RB)</option>
                            </select>
                             @error('kondisi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="2">{{ old('keterangan', $item->keterangan) }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr>

                {{-- Tombol Aksi --}}
                <div class="d-flex justify-content-end">
                    <a href="{{ route('inventaris.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
