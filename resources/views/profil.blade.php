@extends('layouts.app')

@section('title', 'Profil Lengkap RT 01 / RW 05')

@section('content')

    {{-- Header utama tetap sama --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Profil Rukun Tetangga (RT) 01 / RW 05</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Cetak Profil</a>
    </div>

    {{-- Menggunakan satu kartu utama untuk membungkus semuanya --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Kelurahan Sukamaju, Kecamatan Kertajaya, Kota Harapan</h6>
        </div>
        <div class="card-body">
            
            <div class="profile-body">

                <div class="profile-sidebar">
                    <h5>Peta Wilayah</h5>
                    <div class="map-placeholder">[ Area untuk Peta Wilayah RT ]</div>

                    <ul class="profile-summary">
                        <li>
                            <strong>Total Warga</strong>
                            <span>185 Jiwa</span>
                        </li>
                        <li>
                            <strong>Jumlah KK</strong>
                            <span>52 KK</span>
                        </li>
                         <li>
                            <strong>Luas Wilayah</strong>
                            <span>1.5 Ha</span>
                        </li>
                        <li>
                            <strong>Kondisi Jalan</strong>
                            <span>Baik (85%)</span>
                        </li>
                    </ul>
                </div>

                <div class="profile-main">
                    <table class="data-table">
                        <tbody>
                            <tr class="section-header">
                                <td colspan="2">📊 Data Demografi Kependudukan</td>
                            </tr>
                            <tr>
                                <td>Laki-laki</td>
                                <td>90 Jiwa</td>
                            </tr>
                            <tr>
                                <td>Perempuan</td>
                                <td>95 Jiwa</td>
                            </tr>
                            <tr>
                                <td>Kelahiran (2025)</td>
                                <td>3 Jiwa</td>
                            </tr>
                            <tr>
                                <td>Kematian (2025)</td>
                                <td>1 Jiwa</td>
                            </tr>
                            <tr>
                                <td>Pendatang (2025)</td>
                                <td>8 Jiwa</td>
                            </tr>
                            <tr>
                                <td>Pindah (2025)</td>
                                <td>5 Jiwa</td>
                            </tr>

                            <tr class="section-header">
                                <td colspan="2">🎓 Tingkat Pendidikan</td>
                            </tr>
                            <tr>
                                <td>Sarjana (S1/S2/S3)</td>
                                <td>25 Orang</td>
                            </tr>
                            <tr>
                                <td>Tamat SMA / Sederajat</td>
                                <td>80 Orang</td>
                            </tr>
                            <tr>
                                <td>Tamat SMP / Sederajat</td>
                                <td>40 Orang</td>
                            </tr>
                             <tr>
                                <td>Putus Sekolah</td>
                                <td>5 Orang</td>
                            </tr>

                            <tr class="section-header">
                                <td colspan="2">💼 Jenis Pekerjaan</td>
                            </tr>
                            <tr>
                                <td>Karyawan Swasta/BUMN</td>
                                <td>70 Orang</td>
                            </tr>
                            <tr>
                                <td>Wiraswasta/Pengusaha</td>
                                <td>20 Orang</td>
                            </tr>
                            <tr>
                                <td>Aparatur Sipil Negara (ASN)</td>
                                <td>10 Orang</td>
                            </tr>
                            <tr>
                                <td>Pengangguran</td>
                                <td>85 Orang</td>
                            </tr>

                            <tr class="section-header">
                                <td colspan="2">🏡 Fasilitas, Potensi & Permasalahan</td>
                            </tr>
                             <tr>
                                <td>Fasilitas Utama</td>
                                <td>Masjid, Posyandu, Balai Warga</td>
                            </tr>
                             <tr>
                                <td>Potensi Unggulan</td>
                                <td>Usaha Katering, PKK & Karang Taruna Aktif</td>
                            </tr>
                            <tr>
                                <td>Permasalahan Utama</td>
                                <td>Penerangan kurang, Drainase meluap</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

@endsection