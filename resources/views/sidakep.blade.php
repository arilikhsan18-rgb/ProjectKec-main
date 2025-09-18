<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang SIDAKEP - Sistem Informasi Data Kependudukan</title>

    <!-- Ikon dan Font -->
    <link rel="icon" href="{{ asset('img/tsk.png') }}" type="image/png">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            color: #333;
            background-color: #f8f9fa;
        }

        /* Navbar */
        .navbar {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,.05);
            transition: all 0.3s;
        }
        .navbar-brand img {
            height: 40px;
        }
        .navbar-brand span {
            font-weight: 700;
            color: #4A90E2;
        }
        .nav-link {
            font-weight: 500;
        }
        .btn-login {
            background-color: #4A90E2;
            border-color: #4A90E2;
            color: white;
            border-radius: 50px;
            padding: 8px 25px;
            transition: all 0.3s;
        }
        .btn-login:hover {
            background-color: #357ABD;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(rgba(74, 144, 226, 0.85), rgba(74, 144, 226, 0.85)), url('https://images.unsplash.com/photo-1614994268238-a735e8a71225?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            padding: 120px 0;
            color: white;
            text-align: center;
        }
        .hero-section h1 {
            font-weight: 700;
            font-size: 3.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .hero-section p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 20px auto;
            opacity: 0.9;
        }
        .btn-hero {
            background-color: #fff;
            color: #4A90E2;
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s;
        }
         .btn-hero:hover {
            background-color: #f1f1f1;
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.15);
        }

        /* Sections */
        .section {
            padding: 80px 0;
        }
        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }
        .section-title h2 {
            font-weight: 700;
            color: #4A90E2;
        }
        .section-title p {
            color: #777;
        }

        /* Feature Card */
        .feature-card {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            text-align: center;
            transition: all 0.3s;
            height: 100%;
        }
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 25px rgba(0,0,0,0.1);
        }
        .feature-icon {
            font-size: 40px;
            color: #4A90E2;
            margin-bottom: 20px;
        }
        .feature-card h5 {
            font-weight: 600;
            margin-bottom: 15px;
        }

        /* Team Card */
        .team-card {
            text-align: center;
        }
        .team-card img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #fff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .team-card h5 {
            font-weight: 600;
        }
        .team-card p {
            color: #777;
            font-style: italic;
        }

        /* Footer */
        .footer {
            background-color: #343a40;
            color: #f8f9fa;
            padding: 40px 0 20px 0;
            text-align: center;
        }
        .footer .logo-footer {
            height: 50px;
            margin-bottom: 15px;
        }
        .footer p {
            margin-bottom: 20px;
        }
        .footer .social-icons a {
            color: white;
            margin: 0 10px;
            font-size: 20px;
            transition: all 0.3s;
        }
        .footer .social-icons a:hover {
            color: #4A90E2;
            transform: scale(1.2);
        }
        .footer .copyright {
            margin-top: 20px;
            font-size: 0.9rem;
            border-top: 1px solid #495057;
            padding-top: 20px;
        }

        /* ======================================================= */
        /* VVV CSS BARU UNTUK TAMPILAN RESPONSIVE VVV */
        /* ======================================================= */
        @media (max-width: 768px) {
            .hero-section {
                padding: 80px 0;
            }
            .hero-section h1 {
                font-size: 2.5rem; /* Perkecil judul utama di HP */
            }
            .hero-section p {
                font-size: 1rem; /* Perkecil subjudul di HP */
            }
            .section {
                padding: 60px 0; /* Kurangi padding di semua section */
            }
            .section-title h2 {
                font-size: 1.8rem;
            }
        }

    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('img/tsk.png') }}" alt="SIDAKEP Logo">
                <span>SIDAKEP</span>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="#tentang">Tentang Kami</a></li>
                    <li class="nav-item"><a class="nav-link" href="#fitur">Fitur Utama</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tim">Tim Kami</a></li>
                    <li class="nav-item ml-lg-3"><a href="/login" class="btn btn-login">Login Aplikasi</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1>Digitalisasi Data, Membangun Komunitas</h1>
            <p>SIDAKEP adalah platform terintegrasi untuk manajemen data kependudukan di Kecamatan Tawang, dirancang untuk efisiensi, akurasi, dan kemudahan akses.</p>
            <a href="/login" class="btn btn-hero">Mulai Gunakan</a>
        </div>
    </section>

    <!-- Tentang Kami -->
    <section id="tentang" class="section">
        <div class="container">
            <div class="section-title">
                <h2>Tentang SIDAKEP</h2>
                <p>Memahami Visi dan Misi Kami</p>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <img src="https://images.unsplash.com/photo-1521791136064-7986c2920216?q=80&w=2069&auto=format&fit=crop" class="img-fluid rounded shadow" alt="Diskusi Tim">
                </div>
                <div class="col-lg-6 mt-4 mt-lg-0">
                    <h3>Visi Kami</h3>
                    <p>Menjadi platform digital terdepan dalam pengelolaan data kependudukan yang akurat, transparan, dan terintegrasi di tingkat kecamatan untuk mendukung pengambilan kebijakan yang lebih baik dan pelayanan publik yang prima.</p>
                    <h3 class="mt-4">Misi Kami</h3>
                    <ul>
                        <li>Menyediakan sistem yang mudah digunakan bagi aparat RT, RW, hingga Kecamatan.</li>
                        <li>Mendigitalkan proses pencatatan data kependudukan, fasilitas, dan laporan penting lainnya.</li>
                        <li>Menghasilkan data agregat yang akurat untuk analisis dan perencanaan pembangunan wilayah.</li>
                        <li>Meningkatkan efisiensi dan transparansi dalam administrasi kependudukan.</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Fitur Utama -->
    <section id="fitur" class="section bg-light">
        <div class="container">
            <div class="section-title">
                <h2>Fitur Unggulan</h2>
                <p>Apa Saja yang Bisa Anda Lakukan dengan SIDAKEP?</p>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-users"></i></div>
                        <h5>Manajemen Penduduk</h5>
                        <p>Kelola data penduduk, mulai dari kelahiran, kematian, hingga perpindahan dengan mudah dan terstruktur.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-hospital-alt"></i></div>
                        <h5>Pendataan Fasilitas</h5>
                        <p>Catat dan kelola data fasilitas umum di setiap wilayah, dari tempat ibadah, sekolah, hingga fasilitas kesehatan.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-chart-pie"></i></div>
                        <h5>Dasbor Analitik</h5>
                        <p>Visualisasikan data dalam bentuk grafik interaktif. Pahami demografi wilayah Anda dalam sekejap.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tim Kami -->
    <section id="tim" class="section">
        <div class="container">
            <div class="section-title">
                <h2>Tim Pengembang</h2>
                <p>Orang-orang di Balik Inovasi SIDAKEP</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="team-card">
                        <img src="https://placehold.co/150x150/4A90E2/FFFFFF?text=Foto" alt="Foto Tim 1">
                        <h5>Ivan Taufiq A P</h5>
                        <p>Project Manager</p>
                    </div>
                </div>
                 <div class="col-lg-3 col-md-6 mb-4">
                    <div class="team-card">
                        <img src="https://placehold.co/150x150/34495E/FFFFFF?text=Foto" alt="Foto Tim 2">
                        <h5>Sit Nur</h5>
                        <p>beban</p>
                    </div>
                </div>
                 <div class="col-lg-3 col-md-6 mb-4">
                    <div class="team-card">
                        <img src="https://placehold.co/150x150/E74C3C/FFFFFF?text=Foto" alt="Foto Tim 3">
                        <h5>Aril Kece</h5>
                        <p>UI/UX Designer </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <img src="{{ asset('img/tsk.png') }}" alt="Logo Footer" class="logo-footer">
            <h4>SIDAKEP</h4>
            <p>Kecamatan Tawang, Kota Tasikmalaya</p>
            <div class="social-icons">
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
            <p class="copyright">
                &copy; Copyright <strong><span>SIDAKEP 2025</span></strong>. All Rights Reserved
            </p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

