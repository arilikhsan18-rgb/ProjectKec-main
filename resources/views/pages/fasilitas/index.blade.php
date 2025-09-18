@extends('layouts.app')

@section('title', 'Data Fasilitas')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Data Fasilitas Wilayah</h1>
    <p class="mb-4">Berikut adalah daftar fasilitas umum yang tercatat di wilayah Anda.</p>

    {{-- Pesan Sukses --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    {{-- Card untuk Daftar Data --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Fasilitas</h6>
            @hasanyrole('RT|SUPERADMIN')
                <a href="{{ route('fasilitas.create') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah Data</a>
            @endhasanyrole
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Fasilitas</th>
                            <th>Jenis</th>
                            <th>Alamat</th>
                            <th>Keterangan</th>
                            @unlessrole('RT')
                            <th>Diinput Oleh</th>
                            @endunlessrole
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Perulangan dimulai di sini. Variabel $fasilitas diubah menjadi $item --}}
                        @forelse ($fasilitas as $key => $item)
                            <tr>
                                {{-- BENAR: Menggunakan $fasilitas->firstItem() untuk penomoran --}}
                                <td>{{ $fasilitas->firstItem() + $key }}</td>
                                
                                {{-- BENAR: Menggunakan $item->... untuk mengakses data --}}
                                <td>{{ $item->nama_fasilitas }}</td>
                                <td><span class="badge badge-info">{{ $item->jenis_fasilitas }}</span></td>
                                <td>{{ $item->alamat ?: '-' }}</td>
                                <td>{{ $item->keterangan ?: '-' }}</td>
                                @unlessrole('RT')
                                <td>{{ $item->user->name ?? 'N/A' }}</td>
                                @endunlessrole
                                <td class="text-center">
                                    @if(Auth::id() == $item->user_id || Auth::user()->hasRole('SUPERADMIN'|'RT'|'KECAMATAN'))
                                    <div class="d-inline-flex">
                                        <a href="{{ route('fasilitas.edit', $item->id) }}" class="btn btn-sm btn-warning mr-1" title="Edit"><i class="fa fa-edit"></i></a>
                                        <form action="{{ route('fasilitas.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" title="Hapus"><i class="fa fa-trash"></i></button>
                                        </form>
                                    </div>
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            @php
                                $colspan = Auth::user()->hasRole('RT') ? 6 : 7;
                            @endphp
                            <tr>
                                <td colspan="{{ $colspan }}" class="text-center">Data fasilitas kosong.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- BENAR: Menggunakan $fasilitas->links() untuk pagination --}}
            <div class="d-flex justify-content-end mt-3">
                {{ $fasilitas->links() }}
            </div>
        </div>
    </div>
@endsection

