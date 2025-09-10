@extends('layouts.app')

@section('title', 'Data Kependudukan')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Data Kependudukan</h1>

    {{-- Pesan Sukses --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    {{-- Card untuk Fitur Pencarian --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-search"></i> Cari Berdasarkan Tahun Kelahiran</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('penduduk.index') }}" method="GET">
                <div class="form-row align-items-end">
                    <div class="form-group col-md-3">
                        <label for="year_start">Dari Tahun</label>
                        <input type="number" name="year_start" id="year_start" class="form-control" placeholder="Contoh: 1990" value="{{ $yearStart ?? '' }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="year_end">Sampai Tahun</label>
                        <input type="number" name="year_end" id="year_end" class="form-control" placeholder="Contoh: 2000" value="{{ $yearEnd ?? '' }}">
                    </div>
                    <div class="form-group col-md-6">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Terapkan Filter</button>
                        <a href="{{ route('penduduk.index') }}" class="btn btn-secondary"><i class="fa fa-redo"></i> Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ======================================================= --}}
    {{-- VVV BAGIAN RINGKASAN DATA YANG BARU VVV --}}
    {{-- ======================================================= --}}
    <div class="row">
        <!-- Total Penduduk Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Penduduk (Hasil Filter)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $total }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jenis Kelamin Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Jenis Kelamin</div>
                            <div class="h6 mb-0 text-gray-800">
                                <span class="font-weight-bold">Laki-laki:</span> {{ $genderTotals['laki-laki'] ?? 0 }}<br>
                                <span class="font-weight-bold">Perempuan:</span> {{ $genderTotals['perempuan'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-venus-mars fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kelompok Usia Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Kelompok Usia</div>
                            <div class="mb-0 text-gray-800" style="font-size: 0.8rem;">
                                <div class="d-flex justify-content-between"><span>Balita (0-5 Thn):</span> <strong>{{ $ageGroupTotals['balita'] ?? 0 }}</strong></div>
                                <div class="d-flex justify-content-between"><span>Anak (6-12 Thn):</span> <strong>{{ $ageGroupTotals['anak'] ?? 0 }}</strong></div>
                                <div class="d-flex justify-content-between"><span>Remaja (13-18 Thn):</span> <strong>{{ $ageGroupTotals['remaja'] ?? 0 }}</strong></div>
                                <div class="d-flex justify-content-between"><span>Dewasa (19-55 Thn):</span> <strong>{{ $ageGroupTotals['dewasa'] ?? 0 }}</strong></div>
                                <div class="d-flex justify-content-between"><span>Lansia (56+ Thn):</span> <strong>{{ $ageGroupTotals['lansia'] ?? 0 }}</strong></div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-birthday-cake fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Status Pendidikan Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pendidikan</div>
                            <div class="mb-0 text-gray-800" style="font-size: 0.9rem;">
                                <div class="d-flex justify-content-between"><span>Belum Sekolah:</span> <strong>{{ $educationTotals['belum sekolah'] ?? 0 }}</strong></div>
                                <div class="d-flex justify-content-between"><span>Masih Sekolah:</span> <strong>{{ $educationTotals['masih sekolah'] ?? 0 }}</strong></div>
                                <div class="d-flex justify-content-between"><span>Putus Sekolah:</span> <strong>{{ $educationTotals['putus sekolah'] ?? 0 }}</strong></div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Pekerjaan Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Pekerjaan</div>
                            <div class="mb-0 text-gray-800" style="font-size: 0.9rem;">
                                <div class="d-flex justify-content-between"><span>Bekerja:</span> <strong>{{ $occupationTotals['bekerja'] ?? 0 }}</strong></div>
                                <div class="d-flex justify-content-between"><span>Tidak Bekerja:</span> <strong>{{ $occupationTotals['tidak bekerja'] ?? 0 }}</strong></div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-briefcase fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lampid Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Laporan Lampid</div>
                            <div class="mb-0 text-gray-800" style="font-size: 0.9rem;">
                                <div class="d-flex justify-content-between"><span>Kelahiran:</span> <strong>{{ $lampidTotals['kelahiran'] ?? 0 }}</strong></div>
                                <div class="d-flex justify-content-between"><span>Kematian:</span> <strong>{{ $lampidTotals['kematian'] ?? 0 }}</strong></div>
                                <div class="d-flex justify-content-between"><span>Pindah:</span> <strong>{{ $lampidTotals['pindah'] ?? 0 }}</strong></div>
                                <div class="d-flex justify-content-between"><span>Datang:</span> <strong>{{ $lampidTotals['datang'] ?? 0 }}</strong></div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- Card untuk Daftar Data --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Data</h6>
            @hasanyrole('RT|KECAMATAN|SUPERADMIN')
                <a href="{{ route('penduduk.create') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah Data</a>
            @endhasanyrole
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tahun Lahir</th>
                            <th>Jenis Kelamin</th>
                            <th>Status Kependudukan</th>
                            <th>Agama</th>
                            <th>Status Pendidikan</th>
                            <th>Status Pekerjaan</th>
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
                                <td>{{ ucfirst($penduduk->lampid) ?: '-' }}</td>
                                @unlessrole('RT')
                                <td>{{ $penduduk->user->name ?? 'N/A' }}</td>
                                @endunlessrole
                                <td class="text-center">
                                    @hasanyrole('RT|KECAMATAN|SUPERADMIN')
                                        <div class="d-inline-flex">
                                            <a href="{{ route('penduduk.edit', $penduduk->id) }}" class="btn btn-sm btn-warning mr-1" title="Edit"><i class="fa fa-edit"></i></a>
                                            <form action="{{ route('penduduk.destroy', $penduduk->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger" title="Hapus"><i class="fa fa-trash"></i></button>
                                            </form>
                                        </div>
                                    @else
                                        -
                                    @endhasanyrole
                                </td>
                            </tr>
                        @empty
                            @php
                                $colspan = Auth::user()->hasRole('RT') ? 9 : 10;
                            @endphp
                            <tr>
                                <td colspan="{{ $colspan }}" class="text-center">Data tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Menampilkan Pagination --}}
            <div class="d-flex justify-content-end mt-3">
                {{ $penduduks->links() }}
            </div>
        </div>
    </div>
@endsection

