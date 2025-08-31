@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Pendidikan</h1>
        <div>
        <a href="{{ route('education.cetak') }}" target="_blank" class="d-none d-sm-inline-block btn btn-sm btn-danger btn-success shadow-sm mr-2">
                <i class="fas fa-print fa-sm text-white-50"></i> Cetak PDF
            </a>
        <a href="{{ route('education.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah
        </a>
        </div>
    </div>

    {{-- tabel --}}
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-body">
                    <table class="table table-bordered table-hovered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Status Sekolah</th>
                                <th>Jumlah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($educations as $education)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $education->sekolah }}</td>
                                    <td>{{ $education->jumlah }}</td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="{{ route('education.edit', $education->id) }}" class="d-inline-block mr-2 btn btn-sm btn-warning">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#confirmationDelete-{{ $education->id }}">
                                                <i class="fas fa-eraser"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @include('pages.education.confirmation-delete')
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">
                                        <p class="pt-3">Tidak Ada Data</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end">
                        <strong>Total Data: {{ $total_jumlah }} Warga</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
