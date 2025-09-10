@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        {{-- Tombol Laporan hanya muncul untuk atasan --}}
        @hasanyrole('SUPERADMIN|KECAMATAN|KELURAHAN|RW')
        <a href="{{ route('report.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-chart-pie fa-sm text-white-50"></i> Lihat Laporan Agregat
        </a>
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

    <!-- Baris untuk Kartu Statistik (KPI) -->
    <div class="row">

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
                                @hasanyrole('SUPERADMIN|KECAMATAN') Di Seluruh Wilayah @else Di Wilayah Anda @endhasanyrole
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
// ==============================================================
// VERSI JAVASCRIPT PALING AMAN DENGAN NULL COALESCING ?? []
// ==============================================================

// 1. Grafik Usia (Bar Chart)
var ctxUsia = document.getElementById("grafikUsia");
var myBarChart = new Chart(ctxUsia, {
  type: 'bar',
  data: {
    labels: @json($labelsUsia ?? []), // <-- Jaring Pengaman
    datasets: [{
      label: "Jumlah Warga",
      backgroundColor: "#4e73df",
      hoverBackgroundColor: "#2e59d9",
      borderColor: "#4e73df",
      data: @json($dataUsia ?? []), // <-- Jaring Pengaman
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
    labels: @json($labelsPendidikan ?? []), // <-- Jaring Pengaman
    datasets: [{
      data: @json($dataPendidikan ?? []), // <-- Jaring Pengaman
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
    labels: @json($labelsPekerjaan ?? []), // <-- Jaring Pengaman
    datasets: [{
      data: @json($dataPekerjaan ?? []), // <-- Jaring Pengaman
      backgroundColor: ['#4e73df', '#1cc88a', '#f6c23e'],
      hoverBackgroundColor: ['#2e59d9', '#17a673', '#dda20a'],
    }],
  },
  options: { maintainAspectRatio: false, legend: { position: 'bottom' } },
});

// 4. Grafik Kependudukan (Pie Chart)
var ctxKependudukan = document.getElementById("grafikKependudukan");
var myPieChartKependudukan = new Chart(ctxKependudukan, {
  type: 'doughnut',
  data: {
    labels: @json($labelsKependudukan ?? []), // <-- Jaring Pengaman
    datasets: [{
      data: @json($dataKependudukan ?? []), // <-- Jaring Pengaman
      backgroundColor: ['#4e73df', '#36b9cc'],
      hoverBackgroundColor: ['#2e59d9', '#2c9faf'],
    }],
  },
  options: { maintainAspectRatio: false, legend: { position: 'bottom' } },
});

</script>
@endpush
