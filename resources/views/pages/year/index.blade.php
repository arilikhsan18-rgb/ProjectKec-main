@extends('layouts.app')

@section('title', 'Data Tahun Kelahiran')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Data Tahun Kelahiran</h1>
    <p class="mb-4">Berikut adalah data tahun kelahiran yang telah diinput.</p>

    @hasanyrole('RT|KECAMATAN|SUPERADMIN')
    <div class="mb-3">
        <a href="{{ route('year.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Data</a>
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
                <th>Tahun Kelahiran</th>
                <th>Jumlah</th>
                @unlessrole('RT')
                <th>Diinput Oleh</th>
                @endunlessrole
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($years as $key => $year)
                <tr>
                    <td>{{ $years->firstItem() + $key }}</td>
                    <td>{{ $year->tahun_lahir }}</td>
                    <td>{{ number_format($year->jumlah, 0, ',', '.') }}</td>
                    @unlessrole('RT')
                    <td>{{ $year->user->name ?? 'N/A' }}</td>
                    @endunlessrole
                    <td class="text-center">
                        @hasanyrole('RT|KECAMATAN|SUPERADMIN')
                        <a href="{{ route('year.edit', $year->id) }}" class="btn btn-warning btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                        <form action="{{ route('year.destroy', $year->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
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
@endsection