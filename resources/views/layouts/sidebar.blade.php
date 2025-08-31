@php
    // Ambil nama role user sekali saja untuk efisiensi
    $userRole = auth()->user()->role->name;

    // Mengambil data ruangan (sebaiknya ini dipindahkan ke View Composer nanti)
    $ruangansForSidebar = \App\models\Room::orderBy('name', 'asc')->get();
@endphp

<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-info sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/dashboard') }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('img/tsk.png') }}" alt="Logo" style="width: 40px; border-radius: 60%;">
        </div>
        <div class="sidebar-brand-text mx-3">SIDIUK</div>
    </a>

    <hr class="sidebar-divider my-0">

    <!-- Selalu Tampil Untuk Semua Role -->
    <li class="nav-item {{ request()->is('dashboard*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    {{-- Menu yang bisa di-CRUD RT, dan dilihat oleh semua atasan --}}
    @if(in_array($userRole, ['SUPERADMIN', 'KECAMATAN', 'KELURAHAN', 'RW', 'RT']))
        <li class="nav-item {{ request()->is('resident*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('resident') }}">
                <i class="fas fa-fw fa-table"></i>
                <span>Status Kependudukan</span>
            </a>
        </li>
    @endif

    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        Manajemen Data
    </div>

    {{-- Menu yang bisa di-CRUD RT, dan dilihat oleh semua atasan --}}
    @if(in_array($userRole, ['SUPERADMIN', 'KECAMATAN', 'KELURAHAN', 'RW', 'RT']))
        <li class="nav-item {{ request()->is('year*') ? 'active' : '' }}">
            <a class="nav-link" href="/year">
                <i class="fas fa-regular fa-calendar-check"></i>
                <span>Data Tahun Kelahiran</span>
            </a>
        </li>
        <li class="nav-item {{ request()->is('education*') ? 'active' : '' }}">
            <a class="nav-link" href="/education">
                <i class="fas fa-regular fa-school"></i>
                <span>Data Status Pendidikan</span>
            </a>
        </li>
        <li class="nav-item {{ request()->is('occupation*') ? 'active' : '' }}">
            <a class="nav-link" href="/occupation">
                <i class="fas fa-regular fa-city"></i>
                <span>Data Status Pekerjaan</span>
            </a>
        </li>
    @endif
    
    {{-- Menu yang hanya bisa dilihat oleh Kelurahan ke atas --}}
    @if(in_array($userRole, ['SUPERADMIN', 'KECAMATAN', 'KELURAHAN']))
        <div class="sidebar-heading">
            Kondisi Lingkungan
        </div>

        <li class="nav-item {{ request()->is('infrastruktur*') ? 'active' : '' }}">
            <a class="nav-link" href="/infrastruktur">
                <i class="fas fa-fw fa-landmark"></i>
                <span>Data Infrastrukur</span>
            </a>
        </li>
        
        <div class="sidebar-heading">
            Data Barang
        </div>

        <li class="nav-item {{ request()->is('room*') ? 'active' : '' }}">
            <a class="nav-link" href="/room">
                <i class="fas fa-fw fa-door-open"></i>
                <span>Data Ruangan</span>
            </a>
        </li>

        <li class="nav-item {{ request()->is('inventaris*') ? 'active' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseInventaris"
                aria-expanded="true" aria-controls="collapseInventaris">
                <i class="fas fa-fw fa-boxes"></i>
                <span>Data Inventori Ruangan</span>
            </a>
            <div id="collapseInventaris" class="collapse {{ request()->is('inventaris*') ? 'show' : '' }}" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Pilih Ruangan:</h6>
                    @forelse ($ruangansForSidebar as $ruangan)
                        <a class="collapse-item {{ request('room_id') == $ruangan->id ? 'active' : '' }}" 
                            href="{{ route('inventaris.index', ['room_id' => $ruangan->id]) }}">
                            {{ $ruangan->name }}
                        </a>
                    @empty
                        <a class="collapse-item" href="{{ route('room.create') }}">Tambah Ruangan Dulu</a>
                    @endforelse
                </div>
            </div>
        </li>
    @endif

    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        Data Akun & Laporan
    </div>
    
    {{-- Menu Akun hanya untuk Super Admin --}}
    @if($userRole == 'SUPERADMIN')
        <li class="nav-item {{ request()->is('user*') ? 'active' : '' }}">
            <a class="nav-link" href="/user">
                <i class="fas fa-regular fa-user"></i>
                <span>Account</span>
            </a>
        </li>
    @endif
    
    {{-- Menu Laporan untuk semua --}}
    <li class="nav-item {{ request()->is('report*') ? 'active' : '' }}">
        <a class="nav-link" href="/report">
            <i class="fas fa-regular fa-print"></i>
            <span>Laporan Keseluruhan</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>

