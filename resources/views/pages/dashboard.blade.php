@extends('layouts.app')

@section('content')
{{-- Menambahkan Font Awesome untuk Ikon --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

{{-- CSS Kustom untuk mempercantik kartu --}}
<style>
    .card {
        border: none;
        border-radius: 0.75rem;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        transition: all 0.3s ease-in-out;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -2px rgb(0 0 0 / 0.1);
    }
    .card-header {
        background-color: transparent;
        border-bottom: 1px solid #e3e6f0;
        font-weight: 600;
        color: #4a5568;
    }
    .kpi-card .card-body {
        padding: 1.5rem;
    }
    .border-left-primary { border-left: .25rem solid #4e73df !important; }
    .border-left-success { border-left: .25rem solid #1cc88a !important; }
    .border-left-info { border-left: .25rem solid #36b9cc !important; }
</style>

<div class="container">
    {{-- BARIS UNTUK STAT CARD (KPI) --}}
    <div class="row mb-4">
        <div class="col-md-4 mb-4">
            <div class="card border-left-primary kpi-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Warga</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalWarga ?? 'N/A' }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Anda bisa tambahkan Stat Card lain di sini jika ada datanya --}}
    </div>

    {{-- BARIS UNTUK GRAFIK --}}
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">Grafik Rentang Usia</div>
                <div class="card-body"><canvas id="grafikUsia"></canvas></div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">Grafik Status Pendidikan</div>
                <div class="card-body"><canvas id="grafikPendidikan"></canvas></div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">Grafik Status Pekerjaan</div>
                <div class="card-body"><canvas id="grafikPekerjaan"></canvas></div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">Grafik Status Kependudukan</div>
                <div class="card-body"><canvas id="grafikKependudukan"></canvas></div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Definisikan palet warna Anda di satu tempat untuk konsistensi
    const appColors = {
        blue: 'rgba(54, 162, 235, 0.7)',
        green: 'rgba(75, 192, 192, 0.7)',
        orange: 'rgba(255, 159, 64, 0.7)',
        purple: 'rgba(153, 102, 255, 0.7)',
        red: 'rgba(255, 99, 132, 0.7)',
    };

    // Opsi default untuk semua chart agar tidak mengulang kode
    const defaultOptions = {
        responsive: true,
        scales: { y: { beginAtZero: true } },
        plugins: {
            legend: { display: false } // Sembunyikan legenda default, label sudah cukup
        }
    };

    // GRAFIK USIA (Bar)
    const labelsUsia = @json($labelsUsia);
    const dataUsia = @json($dataUsia);
    new Chart(document.getElementById('grafikUsia'), {
        type: 'bar',
        data: {
            labels: labelsUsia,
            datasets: [{ label: 'Jumlah', data: dataUsia, backgroundColor: appColors.blue }]
        },
        options: defaultOptions
    });

    // GRAFIK PENDIDIKAN (Bar)
    const labelsPendidikan = @json($labelsPendidikan);
    const dataPendidikan = @json($dataPendidikan);
    new Chart(document.getElementById('grafikPendidikan'), {
        type: 'bar',
        data: {
            labels: labelsPendidikan,
            datasets: [{ label: 'Jumlah', data: dataPendidikan, backgroundColor: appColors.green }]
        },
        options: defaultOptions
    });

    // GRAFIK PEKERJAAN (Bar)
    const labelsPekerjaan = @json($labelsPekerjaan);
    const dataPekerjaan = @json($dataPekerjaan);
    new Chart(document.getElementById('grafikPekerjaan'), {
        type: 'bar',
        data: {
            labels: labelsPekerjaan,
            datasets: [{ label: 'Jumlah', data: dataPekerjaan, backgroundColor: appColors.orange }]
        },
        options: defaultOptions
    });

    // GRAFIK KEPENDUDUKAN (Donat)
    const labelsKependudukan = @json($labelsKependudukan);
    const dataKependudukan = @json($dataKependudukan);
    new Chart(document.getElementById('grafikKependudukan'), {
        type: 'doughnut',
        data: {
            labels: labelsKependudukan,
            datasets: [{
                label: 'Jumlah',
                data: dataKependudukan,
                backgroundColor: [appColors.purple, appColors.red],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top' } } // Tampilkan legenda untuk donat
        }
    });
</script>
@endsection