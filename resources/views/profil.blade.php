@extends('layouts.app')

@section('title', 'Profil Lengkap RT 01 / RW 05')

@section('content')

    {{-- Header Halaman --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Profil Rukun Tetangga (RT) 01 / RW 05</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-download fa-sm text-white-50"></i> Cetak Profil
        </a>
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
                                    <td width="40%">Total Warga</td>
                                    <td width="5%">:</td>
                                    <td class="font-weight-bold">185 Jiwa</td>
                                </tr>
                                <tr>
                                    <td>Luas Wilayah</td>
                                    <td>:</td>
                                    <td class="font-weight-bold">1.5 Ha</td>
                                </tr>
                                <tr>
                                    <td>Pindahan/Tetap</td>
                                    <td>:</td>
                                    <td class="font-weight-bold">15/170 Jiwa</td>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">LAMPID (Lahir, Mati, Pindah, Datang)</div>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="40%">Kelahiran</td>
                                    <td width="5%">:</td>
                                    <td class="font-weight-bold">3 Orang</td>
                                </tr>
                                <tr>
                                    <td>Kematian</td>
                                    <td>:</td>
                                    <td class="font-weight-bold">1 Orang</td>
                                </tr>
                                <tr>
                                    <td>Warga Baru / Keluar</td>
                                    <td>:</td>
                                    <td class="font-weight-bold">3 / 3 Orang</td>
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
                                <tr><td width="40%">Balita (0-5)</td><td width="5%">:</td><td class="font-weight-bold">3 Orang</td></tr>
                                <tr><td>Anak (6-12)</td><td>:</td><td class="font-weight-bold">1 Orang</td></tr>
                                <tr><td>Remaja (13-18)</td><td>:</td><td class="font-weight-bold">3 Orang</td></tr>
                                <tr><td>Dewasa (19-55)</td><td>:</td><td class="font-weight-bold">3 Orang</td></tr>
                                <tr><td colspan="3"><hr class="my-1"></td></tr>
                                <tr><td>Laki-laki / Perempuan</td><td>:</td><td class="font-weight-bold">3 / 3 Orang</td></tr>
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
                                <tr><td width="40%">Masih Sekolah</td><td width="5%">:</td><td class="font-weight-bold">3 Orang</td></tr>
                                <tr><td>Belum Sekolah</td><td>:</td><td class="font-weight-bold">3 Orang</td></tr>
                                <tr><td>Putus Sekolah</td><td>:</td><td class="font-weight-bold">3 Orang</td></tr>
                                <tr><td colspan="3"><hr class="my-1"></td></tr>
                                <tr><td>Bekerja</td><td>:</td><td class="font-weight-bold">3 Orang</td></tr>
                                <tr><td>Tidak Bekerja</td><td>:</td><td class="font-weight-bold">1 Orang</td></tr>
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

    {{-- BARIS KETIGA: Infrastruktur & Fasilitas Umum --}}
    <div class="row">

        <div class="col-lg-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Infrastruktur</div>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="40%">Kondisi Jalan</td>
                                    <td width="5%">:</td>
                                    <td class="font-weight-bold">Baik (85%)</td>
                                </tr>
                                <tr>
                                    <td>Penerangan Jalan</td>
                                    <td>:</td>
                                    <td class="font-weight-bold">15 Titik</td>
                                </tr>
                                <tr>
                                    <td>Sumber Air Bersih</td>
                                    <td>:</td>
                                    <td class="font-weight-bold">PDAM (98%)</td>
                                </tr>
                                <tr>
                                    <td>Pengelolaan Sampah</td>
                                    <td>:</td>
                                    <td class="font-weight-bold">Ada</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-road fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Fasilitas Umum & Sosial</div>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="40%">Tempat Ibadah</td>
                                    <td width="5%">:</td>
                                    <td class="font-weight-bold">1 Unit Masjid</td>
                                </tr>
                                <tr>
                                    <td>Fasilitas Kesehatan</td>
                                    <td>:</td>
                                    <td class="font-weight-bold">1 Posyandu (Aktif)</td>
                                </tr>
                                <tr>
                                    <td>Fasilitas Keamanan</td>
                                    <td>:</td>
                                    <td class="font-weight-bold">1 Pos Kamling</td>
                                </tr>
                                <tr>
                                    <td>Ruang Publik</td>
                                    <td>:</td>
                                    <td class="font-weight-bold">Balai Warga & Taman</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hospital-user fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection