@php
    // Mengambil data ruangan (sebaiknya ini dipindahkan ke View Composer nanti)
    // Untuk saat ini, kita biarkan agar menu dropdown tetap berfungsi.
    $ruangansForSidebar = \App\Models\Room::orderBy('name', 'asc')->get();
@endphp

<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-info sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('img/tsk.png') }}" alt="Logo" style="width: 40px; border-radius: 60%;">
        </div>
        <div class="sidebar-brand-text mx-3">SIDAKEP</div>
    </a>


    <!-- Selalu Tampil Untuk Semua Role -->
    <li class="nav-item {{ request()->is('dashboard*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    
    
    <div class="sidebar-heading">
        Data Profil
    </div>

    {{-- Menu Kependudukan: bisa DILIHAT oleh semua, tapi di-CRUD hanya oleh RT/Admin --}}
    @hasanyrole('SUPERADMIN|KECAMATAN|KELURAHAN|RW|RT')
        <li class="nav-item {{ request()->is('profil*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('profil') }}">
                <i class="fas fa-fw fa-table"></i>
                <span>Profil</span>
            </a>
        </li>
        <li class="nav-item {{ request()->is('data*') ? 'active' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsedata" aria-expanded="true" aria-controls="collapsedata">
            <i class="fas fa-fw fa-archive"></i>
            <span>Data Profil</span>
            </a>
            <div id="collapsedata" class="collapse {{ request()->is('data*') ? 'show' : '' }}" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Pilih Data:</h6>
                    <a class="collapse-item" href="{{ route('geografis.index') }}">Geografis</a>
                    <a class="collapse-item" href="{{ route('fasilitas.index') }}">Fasilitas </a>
                    <a class="collapse-item" href="{{ route('infrastruktur.index') }}">Infrastrukur</a>
                    <a class="collapse-item" href="{{ route('penduduk.index') }}">Penduduk</a>
                </div>
            </div>
        </li>
    @endhasanyrole
    
    {{-- Menu Akun: hanya untuk Super Admin --}}
    @hasrole('SUPERADMIN')
   
    
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

    
    
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>

