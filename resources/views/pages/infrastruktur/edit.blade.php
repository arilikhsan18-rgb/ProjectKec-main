@extends('layouts.app')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"> Edit Data Infrastruktur</h1>
    </div>
    <div class="row">
        <div class="col">
            {{-- Tambahkan enctype untuk upload file --}}
            <form action="/infrastruktur/{{ $infrastruktur->id }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') 
                <div class="card">
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="gambar">Gambar</label>
                            <input type="file" name="gambar" id="gambar" class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label for="alamat">Alamat</label>
                            <input type="text" name="alamat" id="alamat" class="form-control">
                        </div>
                        {{-- Class-nya seharusnya form-group, bukan form-control --}}
                        <div class="form-group mb-3">
                            <label for="ukuran">Ukuran</label>
                            <input type="text" name="ukuran" id="ukuran" class="form-control">
                        </div>
                        {{-- Class-nya seharusnya form-group, bukan form-control --}}
                        <div class="form-group mb-3">
                            <label for="keterangan">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control" cols="20" rows="10"></textarea>
                        </div>
                    </div>
                    {{-- Tambahkan tombol submit di card-footer agar rapi --}}
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection