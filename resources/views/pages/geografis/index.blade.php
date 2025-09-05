@extends('layouts.app')

@section('title', 'Data Geografis')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Data Geografis</h1>
    <p class="mb-4">Berikut adalah data kondisi geografis yang telah diinput.</p>

    @hasanyrole('RT|KECAMATAN|SUPERADMIN')
    <div class="mb-3">
        @if($geografis->isEmpty())
            <a href="{{ route('geografis.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Data</a>
        @endif
    </div>
    @endhasanyrole

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
                            <th>Luas Wilayah</th>
                            <th>Batas Utara</th>
                            <th>Batas Selatan</th>
                            <th>Batas Barat</th>
                            <th>Batas Timur</th>
                            @unlessrole('RT')
                            <th>Diinput Oleh</th>
                            @endunlessrole
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- ======================================================= --}}
                        {{-- VVV PERBAIKAN: Gunakan '$geografis' (bukan '$geografiss') VVV --}}
                        @forelse ($geografis as $key => $data)
                            <tr>
                                <td>{{ $geografis->firstItem() + $key }}</td>
                                <td>{{ $data->luas_wilayah }}</td>
                                <td>{{ $data->batas_wilayah_utara }}</td>
                                <td>{{ $data->batas_wilayah_selatan }}</td>
                                <td>{{ $data->batas_wilayah_barat }}</td>
                                <td>{{ $data->batas_wilayah_timur }}</td>
                                @unlessrole('RT')
                                <td>{{ $data->user->name ?? 'N/A' }}</td>
                                @endunlessrole
                                <td class="text-center">
                                    @hasanyrole('RT|KECAMATAN|SUPERADMIN')
                                    <a href="{{ route('geografis.edit', $data->id) }}" class="btn btn-warning btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                                    <form action="{{ route('geografis.destroy', $data->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
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
                                <td colspan="{{ auth()->user()->hasRole('RT') ? '7' : '8' }}" class="text-center">Data kosong. Silakan tambah data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $geografis->links() }}
            </div>
        </div>
    </div>
@endsection