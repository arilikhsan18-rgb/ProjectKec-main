<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    {{-- 
        FITUR PENCARIAN (BELUM DIAKTIFKAN)
        Anda bisa mengaktifkan dan membuat fungsionalitasnya nanti jika diperlukan.
    --}}
    <!--
    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
        <div class="input-group">
            <input type="text" class="form-control bg-light border-0 small" placeholder="Cari data..." aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-primary" type="button">
                    <i class="fas fa-search fa-sm"></i>
                </button>
            </div>
        </div>
    </form>
    -->

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <div class="topbar-divider d-none d-sm-block"></div>

        @auth
        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="mr-2 d-none d-lg-inline text-right">
                    {{-- Menampilkan Nama Pengguna --}}
                    <div class="text-gray-800 font-weight-bold">{{ auth()->user()->name }}</div>
                    {{-- Menampilkan Role Pengguna (mengambil role pertama jika ada banyak) --}}
                    <div class="text-gray-500 small">{{ auth()->user()->getRoleNames()->first() }}</div>
                </div>
                <img class="img-profile rounded-circle"
                    src="{{ asset('template/img/undraw_profile.svg')}}">
            </a>
            
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                 <!--a class="dropdown-item" href="#">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                    Settings
                </a-->
                <div class="dropdown-divider"></div>
                
                {{-- PERBAIKAN UTAMA: Tombol Logout yang Fungsional --}}
                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>

                {{-- Form ini disembunyikan dan hanya digunakan untuk proses logout yang aman --}}
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>

            </div>
        </li>
        @endauth

    </ul>

</nav>
