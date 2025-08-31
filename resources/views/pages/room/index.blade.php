@extends('layouts.app')

@section('content')
            <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Data Ruangan</h1>
            <div>
            {{-- TOMBOL CETAK PDF BARU --}}
            <!-- <a href="{{ route('room.cetak') }}" target="_blank" class="d-none d-sm-inline-block btn btn-sm btn-danger btn-success shadow-sm mr-2">
                <i class="fas fa-print fa-sm text-white-50"></i> Cetak PDF
            </a>-->
            {{-- TOMBOL TAMBAH DATA --}}
            <a href="/room/create" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Tambah
            </a>
        </div>
        </div>
    {{-- tabel--}}
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-body">
                    <table class="table table-bordered table-hovered">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Ruangan</th>
                            <th>Kode Ruangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    @if (count($rooms) <1 )
                        <tbody>
                            <tr>
                                <td colspan="9">
                                    <p class="pt-3 text-center">Tidak Ada Data</p>
                                </td>
                            </tr>
                        </tbody>
                    @else
                    <tbody>
                        @foreach ($rooms as $r)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $r->name }}</td>
                            <td>{{ $r->code}}</td>
                            <td>

                                <div class="d-flex">
                                    {{-- GANTI TOMBOL LAMA ANDA DENGAN INI --}}
                                    <a href="{{ route('room.edit', $r->id) }}" class="d-inline-block mr-2 btn btn-sm btn-warning">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#confirmationDelete-{{ $r->id }}">
                                        <i class="fas fa-eraser"></i>
                                    </button>
                                </div>
                            </td>
                        </tr> 
                        @include('pages.room.confirmation-delete')
                        @endforeach
                    </tbody>
                    @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection