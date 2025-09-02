@extends('layouts.app')

@section('title', 'Data Status Pekerjaan')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Data Status Pekerjaan</h1>
    <p class="mb-4">Berikut adalah data status pekerjaan yang telah diinput.</p>

    {{-- Tombol Tambah Data, hanya muncul untuk role yang berhak --}}
    @hasanyrole('RT|KECAMATAN|SUPERADMIN')
    <div class="mb-3">
        <a href="{{ route('occupation.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Data</a>
        {{-- TAMBAHAN: Tombol Cetak PDF --}}
        <a href="{{ route('occupation.cetak') }}" class="btn btn-info"><i class="fa fa-print"></i> Cetak PDF</a>
    </div>
    @endhasanyrole

    {{-- Pesan Sukses --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Data</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Status Pekerjaan</th>
                            <th>Jumlah</th>
                            @unlessrole('RT')
                            <th>Diinput Oleh</th>
                            @endunlessrole
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($occupations as $key => $occupation)
                            <tr>
                                <td>{{ $occupations->firstItem() + $key }}</td>
                                <td>{{ ucwords($occupation->pekerjaan) }}</td>
                                {{-- PERBAIKAN: Menambahkan number_format --}}
                                <td>{{ number_format($occupation->jumlah, 0, ',', '.') }}</td>
                                @unlessrole('RT')
                                <td>{{ $occupation->user->name ?? 'N/A' }}</td>
                                @endunlessrole
                                <td class="text-center">
                                    @hasanyrole('RT|KECAMATAN|SUPERADMIN')
                                    <a href="{{ route('occupation.edit', $occupation->id) }}" class="btn btn-warning btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                                    <form action="{{ route('occupation.destroy', $occupation->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" title="Hapus"><i class="fa fa-trash"></i></button>
                                    </form>
                                    @else
                                    -
                                    @endhasanyrole
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Data kosong.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    {{-- ======================================================= --}}
                    {{-- VVV TAMBAHAN: Baris untuk menampilkan total VVV --}}
                    <tfoot>
                        <tr>
                            <th colspan="{{ auth()->user()->hasRole('RT') ? '2' : '3' }}" class="text-right">Total Keseluruhan:</th>
                            <th>{{ number_format($total_jumlah, 0, ',', '.') }}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                    {{-- ======================================================= --}}
                </table>
            </div>
            <div class="mt-3">
                {{ $occupations->links() }}
            </div>
        </div>
    </div>
@endsection