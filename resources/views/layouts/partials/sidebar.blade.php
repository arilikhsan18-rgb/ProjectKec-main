@php
    // Mengambil data ruangan (sebaiknya ini dipindahkan ke View Composer nanti)
    // Untuk saat ini, kita biarkan agar menu dropdown tetap berfungsi.
    $ruangansForSidebar = \App\Models\Room::orderBy('name', 'asc')->get();
@endphp

<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-info sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('img/tsk.png') }}" alt="Logo" style="width: 40px; border-radius: 60%;">
        </div>
        <div class="sidebar-brand-text mx-3">SIDIUK</div>
    </a>

    <hr class="sidebar-divider my-0">

    <!-- Selalu Tampil Untuk Semua Role -->
    <li class="nav-item {{ request()->is('dashboard*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        Manajemen Data
    </div>

    {{-- Menu Kependudukan: bisa DILIHAT oleh semua, tapi di-CRUD hanya oleh RT/Admin --}}
    @hasanyrole('SUPERADMIN|KECAMATAN|KELURAHAN|RW|RT')
        <li class="nav-item {{ request()->is('resident*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('resident.index') }}">
                <i class="fas fa-fw fa-table"></i>
                <span>Status Kependudukan</span>
            </a>
        </li>
        <li class="nav-item {{ request()->is('year*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('year.index') }}">
                <i class="fas fa-regular fa-calendar-check"></i>
                <span>Data Tahun Kelahiran</span>
            </a>
        </li>
        <li class="nav-item {{ request()->is('education*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('education.index') }}">
                <i class="fas fa-regular fa-school"></i>
                <span>Data Status Pendidikan</span>
            </a>
        </li>
        <li class="nav-item {{ request()->is('occupation*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('occupation.index') }}">
                <i class="fas fa-regular fa-city"></i>
                <span>Data Status Pekerjaan</span>
            </a>
        </li>
    @endhasanyrole
    
    {{-- Menu Lingkungan & Barang: hanya bisa DILIHAT oleh Kelurahan ke atas --}}
    @hasanyrole('SUPERADMIN|KECAMATAN|KELURAHAN')
        <hr class="sidebar-divider">
        <div class="sidebar-heading">
            Lingkungan
        </div>

        <li class="nav-item {{ request()->is('infrastruktur*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('infrastruktur.index') }}">
                <i class="fas fa-fw fa-landmark"></i>
                <span>Data Infrastrukur</span>
            </a>
        </li>
    @endhasanyrole
    
    {{-- Menu Akun: hanya untuk Super Admin --}}
    @hasrole('SUPERADMIN')
    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        Data Akun & Laporan
    </div>
        <li class="nav-item {{ request()->is('user*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('user.index') }}">
                <i class="fas fa-regular fa-user"></i>
                <span>Account</span>
            </a>
        </li>
    @endhasrole
    
    {{-- Menu Laporan: hanya untuk RW ke atas. RT tidak boleh lihat menu ini. --}}
    @hasanyrole('RW|KELURAHAN|KECAMATAN|SUPERADMIN')
        <li class="nav-item {{ request()->is('report*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('report.index') }}">
                <i class="fas fa-regular fa-print"></i>
                <span>Laporan Keseluruhan</span>
            </a>
        </li>
    @endhasanyrole

    <hr class="sidebar-divider d-none d-md-block">
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>

