@extends('layouts.app')

@section('title', 'Profil Kependudukan Kecamatan Tawang')

@section('content')

    {{-- Header Halaman yang Sudah Dinamis --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Profil Kependudukan Kecamatan Tawang</h1>
            <!--p class="mb-0 text-muted small">Data statistik agregat dari {{ $jumlahRT ?? 0 }} RT dan {{ $jumlahRW ?? 0 }} RW</p-->
        </div>
        {{-- Tombol Cetak Profil (opsional, bisa diaktifkan kembali) --}}
        <!--a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-download fa-sm text-white-50"></i> Cetak Laporan
        </a-->
    </div>

    {{-- BARIS PERTAMA: Ringkasan Utama & Data LAMPID --}}
    <div class="row">

        <!-- Kartu Ringkasan Utama -->
        <div class="col-lg-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Ringkasan Utama</div>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="40%">Total Warga</td><td width="5%">:</td><td class="font-weight-bold">{{ $totalWarga ?? 0 }} Jiwa</td>
                                </tr>
                                <tr>
                                    <td>Pindahan/Tetap</td><td>:</td><td class="font-weight-bold">{{ $jumlahPindahan ?? 0 }}/{{ $jumlahTetap ?? 0 }} Jiwa</td>
                                </tr>
                                <tr>
                                    <td>Luas Wilayah</td><td>:</td><td class="font-weight-bold">{{ $luas ?? 0 }} h.a</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-info-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kartu LAMPID -->
        <div class="col-lg-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">LAMPID (Tahun {{ date('Y') }})</div>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="40%">Kelahiran</td><td width="5%">:</td><td class="font-weight-bold">{{ $lampid['Lahir'] ?? 0 }} Orang</td>
                                </tr>
                                <tr>
                                    <td>Kematian</td><td>:</td><td class="font-weight-bold">{{ $lampid['Mati'] ?? 0 }} Orang</td>
                                </tr>
                                <tr>
                                    <td>Warga Baru / Keluar</td><td>:</td><td class="font-weight-bold">{{ $lampid['Datang'] ?? 0 }} / {{ $lampid['Pindah'] ?? 0 }} Orang</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-bar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- BARIS KEDUA: Data Demografi & Sosial Ekonomi --}}
    <div class="row">

        <!-- Kartu Kelompok Usia & Jenis Kelamin -->
        <div class="col-lg-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Kelompok Usia & Jenis Kelamin</div>
                            <table class="table table-sm table-borderless">
                                <tr><td width="40%">Balita (0-5)</td><td width="5%">:</td><td class="font-weight-bold">{{ $kelompokUsia['Balita'] ?? 0 }} Orang</td></tr>
                                <tr><td>Anak (6-12)</td><td>:</td><td class="font-weight-bold">{{ $kelompokUsia['Anak'] ?? 0 }} Orang</td></tr>
                                <tr><td>Remaja (13-18)</td><td>:</td><td class="font-weight-bold">{{ $kelompokUsia['Remaja'] ?? 0 }} Orang</td></tr>
                                <tr><td>Dewasa (19-55)</td><td>:</td><td class="font-weight-bold">{{ $kelompokUsia['Dewasa'] ?? 0 }} Orang</td></tr>
                                <tr><td>Lansia (56+)</td><td>:</td><td class="font-weight-bold">{{ $kelompokUsia['Lansia'] ?? 0 }} Orang</td></tr>
                                <tr><td colspan="3"><hr class="my-1"></td></tr>
                                <tr><td>Laki-laki / Perempuan</td><td>:</td><td class="font-weight-bold">{{ $jumlahLaki ?? 0 }} / {{ $jumlahPerempuan ?? 0 }} Orang</td></tr>
                            </table>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kartu Pendidikan & Pekerjaan -->
        <div class="col-lg-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pendidikan & Pekerjaan</div>
                            <table class="table table-sm table-borderless">
                                <tr><td width="40%">Masih Sekolah</td><td width="5%">:</td><td class="font-weight-bold">{{ $pendidikan['Masih Sekolah'] ?? 0 }} Orang</td></tr>
                                <tr><td>Putus Sekolah</td><td>:</td><td class="font-weight-bold">{{ $pendidikan['Putus Sekolah'] ?? 0 }} Orang</td></tr>
                                <tr><td colspan="3"><hr class="my-1"></td></tr>
                                <tr><td>Bekerja</td><td>:</td><td class="font-weight-bold">{{ $pekerjaan['Bekerja'] ?? 0 }} Orang</td></tr>
                                <tr><td>Tidak Bekerja</td><td>:</td><td class="font-weight-bold">{{ $pekerjaan['Tidak Bekerja'] ?? 0 }} Orang</td></tr>
                            </table>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-briefcase fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- BARIS KETIGA: Infrastruktur & Fasilitas Umum (Data Statis) --}}
    <div class="row">
        <!-- Kartu Infrastruktur -->
        <div class="col-lg-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Infrastruktur</div>
                            <table class="table table-sm table-borderless">
                                <tr><td width="40%">Kondisi Jalan</td><td width="5%">:</td><td class="font-weight-bold">Baik (85%)</td></tr>
                                <tr><td>Penerangan Jalan</td><td>:</td><td class="font-weight-bold">15 Titik</td></tr>
                                <tr><td>Sumber Air Bersih</td><td>:</td><td class="font-weight-bold">PDAM (98%)</td></tr>
                            </table>
                        </div>
                        <div class="col-auto"><i class="fas fa-road fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Kartu Fasilitas Umum & Sosial -->
        <div class="col-lg-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Fasilitas Umum & Sosial</div>
                            <table class="table table-sm table-borderless">
                                <tr><td width="40%">Tempat Ibadah</td><td width="5%">:</td><td class="font-weight-bold">1 Unit Masjid</td></tr>
                                <tr><td>Fasilitas Kesehatan</td><td>:</td><td class="font-weight-bold">1 Posyandu (Aktif)</td></tr>
                                <tr><td>Ruang Publik</td><td>:</td><td class="font-weight-bold">Balai Warga & Taman</td></tr>
                            </table>
                        </div>
                        <div class="col-auto"><i class="fas fa-hospital-user fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection