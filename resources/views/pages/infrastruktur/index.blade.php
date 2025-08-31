@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Infrastruktur</h1>
        <div>
            {{-- TOMBOL CETAK PDF BARU --}}
            <a href="{{ route('infrastruktur.cetak') }}" target="_blank" class="d-none d-sm-inline-block btn btn-sm btn-danger btn-success shadow-sm mr-2">
                <i class="fas fa-print fa-sm text-white-50"></i> Cetak PDF
            </a>
            {{-- TOMBOL TAMBAH DATA --}}
            <a href="/infrastruktur/create" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Tambah
            </a>
            </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Gambar</th>
                                <th>Alamat</th>
                                <th>Ukuran</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Gunakan variabel $infrastrukturs untuk pengecekan, bukan $residents --}}
                            @if (count($infrastrukturs) < 1)
                                <tr>
                                    {{-- Sesuaikan colspan dengan jumlah kolom di thead (ada 5) --}}
                                    <td colspan="6" class="text-center">
                                        <p class="pt-3">Tidak Ada Data</p>
                                    </td>
                                </tr>
                            @else
                                {{-- Loop setiap data infrastruktur --}}
                                @foreach($infrastrukturs as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{-- Menampilkan gambar, bukan hanya teks path-nya --}}
                                        <img src="{{ asset('storage/' . $data->gambar) }}" width="100">
                                    </td>
                                    <td>{{ $data->alamat }}</td>
                                    <td>{{ $data->ukuran }}</td>
                                    <td>{{ $data->keterangan }}</td>
                                    <td>
                                        
                                        {{-- Tambahkan tombol aksi di sini, contoh: --}}
                                        <a href="/infrastruktur/{{ $data->id }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#confirmationDelete-{{ $data->id }}">
                                        <i class="fas fa-eraser"></i>
                                        </button>
                                    </td>
                                </tr>
                                @include('pages.infrastruktur.confirmation-delete')
                                @endforeach {{-- @foreach harus ditutup --}}
                            @endif {{-- @if harus ditutup --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection