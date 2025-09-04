@extends('layouts.app')

@section('title', 'Data geografis')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Data geografis</h1>
    <p class="mb-4">Berikut adalah data geografis yang telah diinput.</p>

    {{-- Tombol Tambah Data, hanya muncul untuk role yang berhak --}}
    @hasanyrole('RT|KECAMATAN|SUPERADMIN')
    <div class="mb-3">
        <a href="{{ route('geografis.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Data</a>
        {{-- Tombol Cetak PDF, muncul untuk semua yang bisa melihat --}}
    </div>
    @endhasanyrole

    {{-- Notifikasi Sukses --}}
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
                            <th>Luas</th>
                            
                            {{-- ======================================================= --}}
                            {{-- VVV PERBAIKAN 1: TAMBAHKAN HEADER KOLOM INI VVV --}}
                            {{-- Kolom ini hanya ditampilkan jika user BUKAN RT --}}
                            @unlessrole('RT')
                            <th>Diinput Oleh</th>
                            @endunlessrole
                            {{-- ======================================================= --}}
                            
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($geografiss as $key => $geografis)
                            <tr>
                                <td>{{ $geografiss->firstItem() + $key }}</td>
                                <td>{{ $geografis->luas }}</td>
                                
                                {{-- ======================================================= --}}
                                {{-- VVV PERBAIKAN 2: TAMBAHKAN DATA KOLOM INI VVV --}}
                                @unlessrole('RT')
                                <td>{{ $geografis->user->name ?? 'N/A' }}</td>
                                @endunlessrole
                                {{-- ======================================================= --}}
                                
                                <td class="text-center">
                                    @hasanyrole('RT|KECAMATAN|SUPERADMIN')
                                    <a href="{{ route('geografis.edit', $geografis->id) }}" class="btn btn-warning btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                                    <form action="{{ route('geografis.destroy', $geografis->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
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
                                {{-- Sesuaikan colspan agar pas dengan jumlah kolom --}}
                                <td colspan="5" class="text-center">Data kosong.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    {{-- Tambahkan TFOOT untuk menampilkan total --}}
                </table>
            </div>
            {{-- Tambahkan link paginasi --}}
            <div class="mt-3">
                {{ $geografiss->links() }}
            </div>
        </div>
    </div>
@endsection