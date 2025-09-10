@extends('layouts.app')

@section('title', 'Laporan Penduduk Per Tahun')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Laporan Penduduk Per Tahun</h1>
    <p class="mb-4">Silakan pilih tahun untuk menampilkan data kependudukan yang sesuai.</p>

    <!-- Card untuk Form Filter -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Data</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('penduduk.year') }}" method="GET">
                <div class="form-row align-items-end">
                    <div class="form-group col-md-4">
                        <label for="year_filter">Pilih Tahun</label>
                        <select name="year_filter" id="year_filter" class="form-control">
                            <option value="">-- Pilih Tahun --</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-filter"></i> Tampilkan Data
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data (hanya ditampilkan jika tahun sudah dipilih) -->
    @if($selectedYear)
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Hasil Laporan untuk Tahun: {{ $selectedYear }}</h6>
                {{-- Anda bisa menambahkan tombol cetak di sini nanti --}}
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tahun Lahir</th>
                                <th>Jenis Kelamin</th>
                                <th>Status Kependudukan</th>
                                <th>Agama</th>
                                <th>Pendidikan</th>
                                <th>Pekerjaan</th>
                                <th>Lampid</th>
                                @unlessrole('RT')
                                <th>Diinput Oleh</th>
                                @endunlessrole
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($penduduks as $key => $penduduk)
                                <tr>
                                    <td>{{ $penduduks->firstItem() + $key }}</td>
                                    <td>{{ ucwords($penduduk->year) }}</td>
                                    <td>{{ ucwords($penduduk->gender) }}</td>
                                    <td>{{ ucwords($penduduk->resident) }}</td>
                                    <td>{{ ucwords($penduduk->religion) }}</td>
                                    <td>{{ ucwords($penduduk->education) }}</td>
                                    <td>{{ ucwords($penduduk->occupation) }}</td>
                                    <td>{{ ucwords($penduduk->lampid) }}</td>
                                    @unlessrole('RT')
                                    <td>{{ $penduduk->user->name ?? 'N/A' }}</td>
                                    @endunlessrole
                                    <td class="text-center">
                                        <a href="{{ route('penduduk.edit', $penduduk->id) }}" class="btn btn-warning btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                                        <form action="{{ route('penduduk.destroy', $penduduk->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" title="Hapus"><i class="fa fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                @php
                                    $colspan = Auth::user()->hasRole('RT') ? 9 : 10;
                                @endphp
                                <tr>
                                    <td colspan="{{ $colspan }}" class="text-center">Tidak ada data yang ditemukan untuk tahun {{ $selectedYear }}.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($penduduks->hasPages())
                <div class="mt-3">
                    {{ $penduduks->links() }}
                </div>
                @endif
            </div>
        </div>
    @endif
@endsection