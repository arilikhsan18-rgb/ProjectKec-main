@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        {{-- Tombol Laporan hanya muncul untuk atasan --}}
        @hasanyrole('SUPERADMIN|KECAMATAN|KELURAHAN|RW')
        <!--a href="{{ route('report.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-chart-pie fa-sm text-white-50"></i> Lihat Laporan Agregat
        </a-->
        @endhasanyrole
    </div>

    <!-- Pesan Selamat Datang (BISA DITUTUP) -->
    <div class="alert alert-info shadow alert-dismissible fade show" role="alert">
        <h4 class="alert-heading">Selamat Datang, {{ $namaUser ?? 'Pengguna' }}!</h4>
        <p>Anda login sebagai **{{ $roleUser ?? 'Tidak Dikenal' }}**. Semua data yang ditampilkan di bawah ini telah disesuaikan dengan hak akses dan wilayah Anda.</p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    {{-- ======================================================= --}}
    {{-- VVV FORMULIR FILTER DENGAN DESAIN BARU (COLLAPSIBLE) VVV --}}
    {{-- ======================================================= --}}
    @hasanyrole('SUPERADMIN|KECAMATAN')
    <div class="card shadow mb-4">
        <!-- Card Header - Accordion -->
        <a href="#collapseFilterCard" class="d-block card-header py-3" data-toggle="collapse"
            role="button" aria-expanded="true" aria-controls="collapseFilterCard">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-filter fa-fw"></i> Filter Data Dashboard</h6>
        </a>
        <!-- Card Content - Collapse -->
        <div class="collapse show" id="collapseFilterCard">
            <div class="card-body">
                <form action="{{ route('dashboard') }}" method="GET">
                    <div class="row align-items-end">
                        <div class="col-lg-3 col-md-6 mb-3">
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
                        <div class="col-lg-3 col-md-6 mb-3">
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
                        <div class="col-lg-3 col-md-6 mb-3">
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
                        <div class="col-lg-3 col-md-6 mb-3 d-flex">
                            <button type="submit" class="btn btn-primary btn-sm flex-grow-1 mr-2"><i class="fas fa-search fa-sm"></i> Terapkan</button>
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm" title="Reset Filter"><i class="fas fa-sync-alt fa-sm"></i></a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endhasanyrole

    <!-- Baris untuk Kartu Statistik (KPI) -->
    <div class="row">
        {{-- Konten kartu statistik tetap sama --}}
        <!-- Card Total Warga -->
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Warga Tercatat</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalWarga ?? 0, 0, ',', '.') }}</div>
                            <small class="text-muted">
                                @hasanyrole('SUPERADMIN|KECAMATAN') Di Wilayah Terfilter @else Di Wilayah Anda @endhasanyrole
                            </small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Total Pengguna Bawahan -->
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Pengguna Bawahan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPengguna ?? 0 }}</div>
                             <small class="text-muted">
                                @if(isset($roleUser) && ($roleUser == 'SUPERADMIN' || $roleUser == 'KECAMATAN')) Total KEL/RW/RT
                                @elseif(isset($roleUser) && $roleUser == 'KELURAHAN') Total RW & RT
                                @elseif(isset($roleUser) && $roleUser == 'RW') Total RT
                                @else - @endif
                            </small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-shield fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Baris untuk Grafik -->
    <div class="row">
        {{-- Konten grafik tetap sama --}}
        <!-- Grafik Rentang Usia -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Grafik Rentang Usia</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar"><canvas id="grafikUsia"></canvas></div>
                </div>
            </div>
        </div>
        <!-- Grafik Status Pendidikan -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Grafik Status Pendidikan</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie"><canvas id="grafikPendidikan"></canvas></div>
                </div>
            </div>
        </div>
         <!-- Grafik Status Pekerjaan -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Grafik Status Pekerjaan</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie"><canvas id="grafikPekerjaan"></canvas></div>
                </div>
            </div>
        </div>
        <!-- Grafik Status Kependudukan -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Grafik Status Kependudukan</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie"><canvas id="grafikKependudukan"></canvas></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
{{-- Sertakan script Chart.js jika belum ada di layout utama --}}
<script src="{{ asset('template/vendor/chart.js/Chart.min.js')}}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ==============================================================
    // BAGIAN UNTUK FILTER DROPDOWN (HANYA UNTUK ADMIN)
    // ==============================================================
    const kelurahanSelect = document.getElementById('kelurahan_id');
    const rwSelect = document.getElementById('rw_id');
    const rtSelect = document.getElementById('rt_id');

    // Cek apakah elemen select ada di halaman ini (karena hanya admin yang punya)
    if (kelurahanSelect) {
        function filterDropdown(parentSelect, childSelect, parentIdAttribute) {
            if (!parentSelect || !childSelect) return;
            const parentId = parentSelect.value;

            for (let option of childSelect.options) {
                if (option.value === "") continue; // Jangan sembunyikan opsi default
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


    // ==============================================================
    // BAGIAN UNTUK GRAFIK (AMAN UNTUK SEMUA USER)
    // ==============================================================

    // 1. Grafik Usia (Bar Chart)
    var ctxUsia = document.getElementById("grafikUsia");
    var myBarChart = new Chart(ctxUsia, {
      type: 'bar',
      data: {
        labels: @json($labelsUsia ?? []),
        datasets: [{
          label: "Jumlah Warga",
          backgroundColor: "#4e73df",
          hoverBackgroundColor: "#2e59d9",
          borderColor: "#4e73df",
          data: @json($dataUsia ?? []),
        }],
      },
      options: {
        maintainAspectRatio: false,
        legend: { display: false },
        scales: {
            yAxes: [{ ticks: { beginAtZero: true, precision: 0 } }]
        }
      }
    });

    // 2. Grafik Pendidikan (Pie Chart)
    var ctxPendidikan = document.getElementById("grafikPendidikan");
    var myPieChartPendidikan = new Chart(ctxPendidikan, {
      type: 'doughnut',
      data: {
        labels: @json($labelsPendidikan ?? []),
        datasets: [{
          data: @json($dataPendidikan ?? []),
          backgroundColor: ['#4e73df', '#1cc88a', '#e74a3b'],
          hoverBackgroundColor: ['#2e59d9', '#17a673', '#d02c1d'],
        }],
      },
      options: { maintainAspectRatio: false, legend: { position: 'bottom' } },
    });

    // 3. Grafik Pekerjaan (Pie Chart)
    var ctxPekerjaan = document.getElementById("grafikPekerjaan");
    var myPieChartPekerjaan = new Chart(ctxPekerjaan, {
      type: 'doughnut',
      data: {
        labels: @json($labelsPekerjaan ?? []),
        datasets: [{
          data: @json($dataPekerjaan ?? []),
          backgroundColor: ['#4e73df', '#1cc88a', '#f6c23e', '#e74a3b'], // Tambah warna
          hoverBackgroundColor: ['#2e59d9', '#17a673', '#dda20a', '#d02c1d'],
        }],
      },
      options: { maintainAspectRatio: false, legend: { position: 'bottom' } },
    });

    // 4. Grafik Kependudukan (Pie Chart)
    var ctxKependudukan = document.getElementById("grafikKependudukan");
    var myPieChartKependudukan = new Chart(ctxKependudukan, {
      type: 'doughnut',
      data: {
        labels: @json($labelsKependudukan ?? []),
        datasets: [{
          data: @json($dataKependudukan ?? []),
          backgroundColor: ['#4e73df', '#36b9cc'],
          hoverBackgroundColor: ['#2e59d9', '#2c9faf'],
        }],
      },
      options: { maintainAspectRatio: false, legend: { position: 'bottom' } },
    });
});
</script>
@endpush

