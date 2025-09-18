<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistem Informasi Kependudukan Kecamatan Tawang">
    <meta name="author" content="Tim Pengembang SIDAKEP">

    <title>@yield('title', 'Dashboard') | SIDAKEP</title>

    <link rel="icon" href="{{ asset('img/tsk.png') }}" type="image/png">
    <link href="{{ asset('template/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="{{ asset('template/css/sb-admin-2.min.css')}}" rel="stylesheet">
    
    @stack('styles')

    {{-- ======================================================= --}}
    {{-- VVV CSS BARU UNTUK LAYOUT FIXED VVV --}}
    {{-- ======================================================= --}}
    <style>
        /* 1. Atur html, body, dan #wrapper untuk mengisi seluruh layar */
        html, body, #wrapper {
            height: 100%;
            overflow: hidden; /* Mencegah scrollbar di body utama */
        }

        /* 2. Atur #content-wrapper (kolom kanan) untuk mengisi tinggi layar juga */
        #content-wrapper {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        /* 3. INI KUNCINYA: Jadikan #content sebagai satu-satunya area yang bisa di-scroll */
        #content {
            flex: 1; /* Membuat #content mengisi semua ruang kosong yang tersisa */
            overflow-y: auto; /* Menambahkan scrollbar HANYA di area ini */
        }
    </style>
</head>

<body id="page-top">

    <div id="wrapper">

        @include('layouts.partials.sidebar')

        <div id="content-wrapper" class="d-flex flex-column">

            @include('layouts.partials.topbar')

            <div id="content">
                <div class="container-fluid pt-4"> 
                    @yield('content')
                </div>
            </div>

            @include('layouts.partials.footer')

        </div>
    </div>

    {{-- Tombol Scroll to Top --}}
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    {{-- Modal Logout --}}
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Logout?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Apakah Anda yakin akan keluar dari aplikasi?</div>
                <div class="modal-footer">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn btn-primary">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Script JavaScript Inti --}}
    <script src="{{ asset('template/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js')}}"></script>
    <script src="{{ asset('template/js/sb-admin-2.min.js')}}"></script>
    
    @stack('scripts')

</body>

</html>

