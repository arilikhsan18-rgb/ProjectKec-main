@extends('layouts.app')

@section('title', $headerTitle ?? 'Profil Kependudukan')

@section('content')

    {{-- Header Halaman Dinamis --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">{{ $headerTitle ?? 'Profil Kependudukan' }}</h1>
            @if(!empty($headerSubtitle))
                <p class="mb-0 text-muted small">{{ $headerSubtitle }}</p>
            @endif
        </div>
    </div>

    {{-- FORMULIR FILTER --}}
    @hasanyrole('SUPERADMIN|KECAMATAN')
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('profil') }}" method="GET">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label for="kelurahan_id" class="small font-weight-bold">Pilih Kelurahan</label>
                        <select name="kelurahan_id" id="kelurahan_id" class="form-control form-control-sm">
                            <option value="">-- Semua Kelurahan --</option>
                            @foreach ($kelurahans as $kelurahan)
                                <option value="{{ $kelurahan->id }}" {{ ($filters['kelurahan_id'] ?? '') == $kelurahan->id ? 'selected' : '' }}>
                                    {{ $kelurahan->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="rw_id" class="small font-weight-bold">Pilih RW</label>
                        <select name="rw_id" id="rw_id" class="form-control form-control-sm">
                            <option value="">-- Semua RW --</option>
                            @foreach ($rws as $rw)
                                <option value="{{ $rw->id }}" {{ ($filters['rw_id'] ?? '') == $rw->id ? 'selected' : '' }} data-kelurahan="{{ $rw->parent_id }}">
                                    {{ $rw->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="rt_id" class="small font-weight-bold">Pilih RT</label>
                        <select name="rt_id" id="rt_id" class="form-control form-control-sm">
                            <option value="">-- Semua RT --</option>
                            @foreach ($rts as $rt)
                                <option value="{{ $rt->id }}" {{ ($filters['rt_id'] ?? '') == $rt->id ? 'selected' : '' }} data-rw="{{ $rt->parent_id }}">
                                    {{ $rt->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex mt-3 mt-md-0">
                        <button type="submit" class="btn btn-primary btn-sm flex-grow-1 mr-2"><i class="fas fa-filter fa-sm"></i> Terapkan</button>
                        <a href="{{ route('profil') }}" class="btn btn-secondary btn-sm" title="Reset Filter"><i class="fas fa-sync-alt fa-sm"></i></a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endhasanyrole

    {{-- BARIS PERTAMA: Ringkasan Utama & Data LAMPID --}}
    <div class="row">
        <!-- Kartu Ringkasan Utama -->
        <div class="col-lg-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Ringkasan Utama</div>
                            <table class="table table-sm table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td width="40%">Total Warga</td><td width="5%">:</td><td class="font-weight-bold">{{ $totalWarga ?? 0 }} Jiwa</td>
                                    </tr>
                                    <tr>
                                        <td>Pindahan / Tetap</td><td>:</td><td class="font-weight-bold">{{ $jumlahPindahan ?? 0 }} / {{ $jumlahTetap ?? 0 }} Jiwa</td>
                                    </tr>
                                </tbody>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">LAMPID (Laporan Kependudukan)</div>
                            <table class="table table-sm table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td width="40%">Kelahiran</td><td width="5%">:</td><td class="font-weight-bold">{{ $lampid['kelahiran'] ?? 0 }} Orang</td>
                                    </tr>
                                    <tr>
                                        <td>Kematian</td><td>:</td><td class="font-weight-bold">{{ $lampid['kematian'] ?? 0 }} Orang</td>
                                    </tr>
                                    <tr>
                                        <td>Pindah / Datang</td><td>:</td><td class="font-weight-bold">{{ $lampid['pindah'] ?? 0 }} / {{ $lampid['datang'] ?? 0 }} Orang</td>
                                    </tr>
                                </tbody>
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
                            <table class="table table-sm table-borderless mb-0">
                                <tbody>
                                    <tr><td width="40%">Balita (0-5)</td><td width="5%">:</td><td class="font-weight-bold">{{ $kelompokUsia['Balita'] ?? 0 }} Orang</td></tr>
                                    <tr><td>Anak (6-12)</td><td>:</td><td class="font-weight-bold">{{ $kelompokUsia['Anak'] ?? 0 }} Orang</td></tr>
                                    <tr><td>Remaja (13-18)</td><td>:</td><td class="font-weight-bold">{{ $kelompokUsia['Remaja'] ?? 0 }} Orang</td></tr>
                                    <tr><td>Dewasa (19-55)</td><td>:</td><td class="font-weight-bold">{{ $kelompokUsia['Dewasa'] ?? 0 }} Orang</td></tr>
                                    <tr><td>Lansia (56+)</td><td>:</td><td class="font-weight-bold">{{ $kelompokUsia['Lansia'] ?? 0 }} Orang</td></tr>
                                    <tr><td colspan="3"><hr class="my-1"></td></tr>
                                    <tr><td>Laki-laki</td><td>:</td><td class="font-weight-bold">{{ $jumlahLaki ?? 0 }} Orang</td></tr>
                                    <tr><td>Perempuan</td><td>:</td><td class="font-weight-bold">{{ $jumlahPerempuan ?? 0 }} Orang</td></tr>
                                </tbody>
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
                            <table class="table table-sm table-borderless mb-0">
                                <tbody>
                                    <tr><td width="40%">Belum Sekolah</td><td width="5%">:</td><td class="font-weight-bold">{{ $belumsekolah ?? 0 }} Orang</td></tr>
                                    <tr><td>Masih Sekolah</td><td>:</td><td class="font-weight-bold">{{ $masihsekolah ?? 0 }} Orang</td></tr>
                                    <tr><td>Putus Sekolah</td><td>:</td><td class="font-weight-bold">{{ $putussekolah ?? 0 }} Orang</td></tr>
                                    <tr><td colspan="3"><hr class="my-1"></td></tr>
                                    <tr><td>Bekerja</td><td>:</td><td class="font-weight-bold">{{ $bekerja ?? 0 }} Orang</td></tr>
                                    <tr><td>Tidak Bekerja</td><td>:</td><td class="font-weight-bold">{{ $tidakbekerja ?? 0 }} Orang</td></tr>
                                    <tr><td>Usaha</td><td>:</td><td class="font-weight-bold">{{ $usaha ?? 0 }} Orang</td></tr>
                                </tbody>
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
    
    {{-- BARIS BARU UNTUK DATA FASILITAS --}}
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card border-left-dark shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                Ringkasan Fasilitas (Total: {{ array_sum($fasilitasTotals->toArray()) }})
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-3">
                                    <div class="h6 mb-0 text-gray-800">
                                        <i class="fas fa-mosque text-gray-500 mr-2"></i><span class="font-weight-bold">Ibadah:</span> {{ $fasilitasTotals['Tempat Ibadah'] ?? 0 }}
                                    </div>
                                </div>
                                 <div class="col-md-3">
                                    <div class="h6 mb-0 text-gray-800">
                                        <i class="fas fa-school text-gray-500 mr-2"></i><span class="font-weight-bold">Pendidikan:</span> {{ $fasilitasTotals['Pendidikan'] ?? 0 }}
                                    </div>
                                </div>
                                 <div class="col-md-3">
                                    <div class="h6 mb-0 text-gray-800">
                                        <i class="fas fa-clinic-medical text-gray-500 mr-2"></i><span class="font-weight-bold">Kesehatan:</span> {{ $fasilitasTotals['Kesehatan'] ?? 0 }}
                                    </div>
                                </div>
                                 <div class="col-md-3">
                                    <div class="h6 mb-0 text-gray-800">
                                        <i class="fas fa-store text-gray-500 mr-2"></i><span class="font-weight-bold">Umum:</span> {{ $fasilitasTotals['Umum'] ?? 0 }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hospital-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
{{-- Script untuk membuat filter dropdown dependen (cascading) --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const kelurahanSelect = document.getElementById('kelurahan_id');
        const rwSelect = document.getElementById('rw_id');
        const rtSelect = document.getElementById('rt_id');

        if (kelurahanSelect) {
            function filterDropdown(parentSelect, childSelect, parentIdAttribute) {
                if (!parentSelect || !childSelect) return;
                const parentId = parentSelect.value;

                for (let option of childSelect.options) {
                    if (option.value === "") continue;
                    const attributeValue = option.getAttribute(parentIdAttribute);
                    if (parentId === "" || attributeValue === parentId) {
                        option.style.display = "block";
                    } else {
                        option.style.display = "none";
                    }
                }
            }
            
            function resetAndFilter(parentSelect, childSelect, childAttribute, grandChildSelect = null, grandChildAttribute = null) {
                if (childSelect) {
                    childSelect.value = "";
                    filterDropdown(parentSelect, childSelect, childAttribute);
                }
                if (grandChildSelect) {
                    grandChildSelect.value = "";
                    filterDropdown(childSelect, grandChildSelect, grandChildAttribute);
                }
            }

            kelurahanSelect.addEventListener('change', function() {
                resetAndFilter(this, rwSelect, 'data-kelurahan', rtSelect, 'data-rw');
            });

            rwSelect.addEventListener('change', function() {
                resetAndFilter(this, rtSelect, 'data-rw');
            });

            // Jalankan saat halaman dimuat untuk memastikan filter awal sudah benar
            filterDropdown(kelurahanSelect, rwSelect, 'data-kelurahan');
            filterDropdown(rwSelect, rtSelect, 'data-rw');
        }
    });
</script>
@endpush

