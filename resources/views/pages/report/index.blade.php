@extends('layouts.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Laporan Keseluruhan</h1>
    <div>
        <a href="{{ route('report.cetak') }}" target="_blank" class="d-none d-sm-inline-block btn btn-sm btn-danger btn-success shadow-sm mr-2">
            <i class="fas fa-print fa-sm text-white-50"></i> Cetak PDF
        </a>
    </div>
</div>

<div class="row">
    <div class="col">
        <div class="card shadow">
            <div class="card-body">
                <table class="table table-bordered table-hovered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Status Kependudukan</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($residents as $res)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $res->status_tinggal }}</td>
                            <td>{{ $res->jumlah }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">
                                <p class="pt-3">Tidak Ada Data</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-end">
                    <strong>Total Data: {{ $total_residents }} Warga</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<hr>

<div class="row">
    <div class="col">
        <div class="card shadow">
            <div class="card-body">
                <table class="table table-bordered table-hovered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tahun Kelahiran</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- PERBAIKAN: @foreach sekarang membungkus seluruh <tr> --}}
                        @forelse ($years as $y)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $y->tahun_lahir }}</td>
                            <td>{{ $y->jumlah}}</td>
                            {{-- PERBAIKAN: Menambahkan <td> kosong untuk kolom Aksi --}}
                            <td>
                                </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">
                                <p class="pt-3">Tidak Ada Data</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-end">
                    <strong>Total Data: {{ $total_years }} Warga</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<hr>

<div class="row">
    <div class="col">
        <div class="card shadow">
            <div class="card-body">
                <table class="table table-bordered table-hovered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Status Pekerjaan</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($occupations as $oc)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $oc->pekerjaan }}</td>
                            <td>{{ $oc->jumlah }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">
                                <p class="pt-3">Tidak Ada Data</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-end">
                    <strong>Total Data: {{ $total_occupations }} Warga</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<hr>

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
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($educations as $education)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $education->sekolah }}</td>
                            <td>{{ $education->jumlah }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">
                                <p class="pt-3">Tidak Ada Data</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-end">
                    <strong>Total Data: {{ $total_educations }} Warga</strong>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- 
    PERBAIKAN: Modal konfirmasi diletakkan di luar loop, cukup satu kali.
    Anda perlu menggunakan JavaScript untuk melewatkan ID atau data lain ke modal ini
    saat tombol hapus di dalam tabel di klik.
--}}
@endsection