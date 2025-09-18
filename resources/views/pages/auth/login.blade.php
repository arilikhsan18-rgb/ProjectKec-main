<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>SIDAKEP - Login</title>
    
    <!-- Ikon dan Font -->
    <link rel="icon" href="{{ asset('img/tsk.png') }}" type="image/png">
    <link href="{{ asset('template/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Library Eksternal -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --bg-color: #EAF0F6; 
            --card-bg-color: #ffffff;
            --text-color: #1a202c;
            --subtext-color: #718096;
            --input-bg-color: #f7fafc;
            --input-border-color: #e2e8f0;
            --primary-color: #4A90E2;
            --primary-hover-color: #357ABD;
            --primary-gradient: linear-gradient(90deg, #4A90E2 0%, #57a8f5 100%);
            --image-overlay-color: rgba(26, 32, 44, 0.7);
        }
        body.dark-mode {
            --bg-color: #1a202c;
            --card-bg-color: #2d3748;
            --text-color: #e2e8f0;
            --subtext-color: #a0aec0;
            --input-bg-color: #4a5568;
            --input-border-color: #718096;
            --image-overlay-color: rgba(0, 0, 0, 0.75);
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-color);
            transition: background-color 0.3s ease;
            margin: 0;
            height: 100vh;
            overflow: hidden;
        }

        .main-container {
            display: flex;
            width: 100vw;
            height: 100vh;
        }

        .image-side {
            width: 55%;
            background-image: url('https://images.unsplash.com/photo-1549492423-400212a6e5a5?q=80&w=1974&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            position: relative;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 40px;
            animation: bgZoom 25s infinite alternate;
        }
        .image-side::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: var(--image-overlay-color);
            transition: background 0.3s ease;
        }
        .image-content {
            position: relative; z-index: 2;
            animation: fadeIn 1s ease-in-out;
        }
        .image-content img { width: 150px; margin-bottom: 20px; }
        .image-content h1 { font-weight: 700; font-size: 2.8rem; margin-bottom: 10px; }
        .image-content p { font-size: 1.1rem; max-width: 450px; opacity: 0.9; }

        .form-side {
            width: 45%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background-color: var(--card-bg-color);
            transition: background-color 0.3s ease;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            text-align: left;
            animation: fadeIn 0.8s ease-in-out;
        }
        .login-card .logo-mobile {
            display: none;
            text-align: center;
            margin-bottom: 20px;
        }
        .login-card .logo-mobile img { width: 100px; }
        .login-card h2 { font-weight: 600; color: var(--text-color); font-size: 2rem; }
        .login-card p { color: var(--subtext-color); margin-bottom: 30px; }
        
        .input-group-text {
            background-color: var(--input-bg-color);
            border: 1px solid var(--input-border-color);
            border-right: none;
            color: var(--subtext-color);
            border-radius: 0.5rem 0 0 0.5rem;
        }
        .form-control {
            background-color: var(--input-bg-color);
            border: 1px solid var(--input-border-color);
            height: 50px;
            color: var(--text-color);
            border-radius: 0 0.5rem 0.5rem 0;
        }
        .form-control:focus {
            background-color: var(--card-bg-color);
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.25);
        }
        
        .password-wrapper { position: relative; }
        .toggle-password {
            position: absolute;
            right: 15px; top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--subtext-color);
        }

        .btn-login {
            background-image: var(--primary-gradient);
            background-size: 200% auto;
            border: none;
            border-radius: 0.5rem;
            font-weight: 500;
            padding: 12px;
            transition: 0.5s;
            color: white;
        }
        .btn-login:hover {
            background-position: right center;
            box-shadow: 0 4px 15px rgba(74, 144, 226, 0.4);
            color: white;
        }
        
        .divider { display: flex; align-items: center; text-align: center; color: var(--subtext-color); margin: 25px 0; }
        .divider::before, .divider::after { content: ''; flex: 1; border-bottom: 1px solid var(--input-border-color); }
        .divider:not(:empty)::before { margin-right: .5em; }
        .divider:not(:empty)::after { margin-left: .5em; }

        .social-login a {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            width: 45px; height: 45px;
            border-radius: 50%;
            background-color: var(--input-bg-color);
            color: var(--subtext-color);
            margin: 0 10px;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        .social-login a:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        
        /* ======================================================= */
        /* VVV CSS BARU UNTUK TOMBOL TEMA VVV */
        /* ======================================================= */
        .theme-toggle-wrapper {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 10;
            width: 45px;
            height: 45px;
            background-color: var(--card-bg-color);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            overflow: hidden; 
        }
        .theme-toggle-wrapper:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }
        .theme-toggle-wrapper i {
            position: absolute;
            transition: transform 0.4s ease, opacity 0.3s ease;
            color: var(--text-color);
            font-size: 20px;
        }
        /* Saat mode terang (default), bulan tersembunyi di bawah */
        .theme-toggle-wrapper .fa-moon {
            transform: translateY(100%);
            opacity: 0;
        }
        /* Saat mode gelap, matahari tersembunyi di atas */
        body.dark-mode .theme-toggle-wrapper .fa-sun {
            transform: translateY(-100%);
            opacity: 0;
        }
        /* Saat mode gelap, bulan muncul dari bawah */
        body.dark-mode .theme-toggle-wrapper .fa-moon {
            transform: translateY(0);
            opacity: 1;
        }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes bgZoom { 0% { background-size: 100%; } 100% { background-size: 110%; } }
        
        @media (max-width: 992px) {
            body { overflow: auto; }
            .image-side { display: none; }
            .form-side {
                width: 100%;
                padding: 30px; 
                align-items: flex-start;
                padding-top: 10vh;
            }
            .login-card .logo-mobile { display: block; }
        }
    </style>
</head>
<body>

    @if ($errors->any())
     <script>
         Swal.fire({
             title: "Login Gagal",
             text: "@foreach($errors->all() as $error){{ $error }}@endforeach",
             icon: "error",
             confirmButtonColor: '#4A90E2'
         });
     </script>
    @endif
    
    <div class="main-container">
        <!-- Sisi Gambar -->
        <div class="image-side">
            <div class="image-content">
                <img src="{{ asset('img/tsk.png') }}" alt="Logo">
                <h1>SIDAKEP</h1>
                <p>Sistem Informasi Data Analisa Kependudukan Terintegrasi Kecamatan Tawang.</p>
            </div>
        </div>

        <!-- Sisi Form -->
        <div class="form-side">
            <div class="login-card">
                <div class="logo-mobile">
                     <img src="{{ asset('img/tsk.png') }}" alt="Logo Mobile">
                </div>
                <h2>Selamat Datang!</h2>
                <p>Silakan masuk untuk melanjutkan.</p>
                <form action="/login" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="email" class="sr-only">Email</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input type="email" class="form-control" name="email" placeholder="Email" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="sr-only">Password</label>
                         <div class="input-group password-wrapper">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            </div>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                            <i class="fas fa-eye toggle-password" id="togglePassword"></i>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block btn-login mt-4">Masuk</button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- ======================================================= -->
    <!-- VVV TOMBOL DARK MODE BARU VVV -->
    <!-- ======================================================= -->
    <div id="theme-toggle" class="theme-toggle-wrapper">
        <i class="fas fa-sun"></i>
        <i class="fas fa-moon"></i>
    </div>

    <script>
        // --- Skrip untuk Dark Mode Baru ---
        const themeToggle = document.getElementById('theme-toggle');
        const currentTheme = localStorage.getItem('theme');

        if (currentTheme) {
            document.body.classList.add(currentTheme);
        }

        function switchTheme() {
            document.body.classList.toggle('dark-mode');
            let theme = 'light-mode';
            if (document.body.classList.contains('dark-mode')) {
                theme = 'dark-mode';
            }
            localStorage.setItem('theme', theme);
        }
        themeToggle.addEventListener('click', switchTheme);


        // --- Skrip untuk Tampilkan/Sembunyikan Password ---
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function (e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>

