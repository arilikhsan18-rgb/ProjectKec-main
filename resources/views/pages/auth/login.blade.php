<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>SIDIUK - Login</title>
    <link href="{{ asset('template/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text-css">
    <link href="https://fonts.googleapis.com/css?family=Inter:400,500,600,700&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --bg-color: #f5f7fa;
            --card-bg-color: #ffffff;
            --text-color: #2d3748;
            --subtext-color: #718096;
            --input-bg-color: #edf2f7;
            --primary-color: #4299e1;
        }
        body.dark-mode {
            --bg-color: #1a202c;
            --card-bg-color: #2d3748;
            --text-color: #edf2f7;
            --subtext-color: #a0aec0;
            --input-bg-color: #4a5568;
        }
        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: 'Inter', sans-serif;
            transition: background-color 0.3s, color 0.3s;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-card {
            background-color: var(--card-bg-color);
            padding: 50px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
            transition: background-color 0.3s;
        }
        .login-card h2 {
            font-weight: 700;
        }
        .form-control {
            background-color: var(--input-bg-color);
            border: none;
            height: 50px;
            border-radius: 10px;
            color: var(--text-color);
        }
        .btn-login {
            background-color: var(--primary-color);
            border: none;
            border-radius: 10px;
            font-weight: 600;
            padding: 12px;
        }
        /* Dark Mode Toggle Switch */
        .theme-switch-wrapper {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .theme-switch {
            display: inline-block;
            height: 34px;
            position: relative;
            width: 60px;
        }
        .theme-switch input { display: none; }
        .slider {
            background-color: #ccc;
            bottom: 0;
            cursor: pointer;
            left: 0;
            position: absolute;
            right: 0;
            top: 0;
            transition: .4s;
            border-radius: 34px;
        }
        .slider:before {
            background-color: #fff;
            bottom: 4px;
            content: "";
            height: 26px;
            left: 4px;
            position: absolute;
            transition: .4s;
            width: 26px;
            border-radius: 50%;
        }
        input:checked + .slider { background-color: var(--primary-color); }
        input:checked + .slider:before { transform: translateX(26px); }

        /* === BAGIAN RESPONSIVE UNTUK HP === */
        @media (max-width: 768px) {
            .login-card {
                /* Kurangi padding agar tidak terlalu lebar di HP */
                padding: 30px; 
                /* Hapus bayangan agar lebih simpel di HP */
                box-shadow: none; 
                /* Buat kartu memenuhi layar dengan sedikit margin */
                width: 90%; 
                max-width: none;
            }

            .theme-switch-wrapper {
                /* Pindahkan tombol dark mode agar tidak terlalu di pojok */
                top: 15px;
                right: 15px;
            }

            body {
                /* Hapus align-items agar kartu tidak selalu di tengah vertikal,
                   berguna jika keyboard muncul */
                align-items: flex-start;
                padding-top: 5vh; /* Beri sedikit jarak dari atas */
            }
        }
    </style>
</head>
<body>
    @if ($errors->any())
     <script>
         Swal.fire({
             title: "Terjadi Kesalahan",
             text: "@foreach($errors->all() as $error) {{ $error }} @endforeach",
             icon: "error",
         });
     </script>
    @endif
    
    <div class="theme-switch-wrapper">
        <label class="theme-switch" for="checkbox">
            <input type="checkbox" id="checkbox" />
            <div class="slider round"></div>
        </label>
    </div>

    <div class="login-card">
        <h2>Login Akun</h2>
        <p style="color: var(--subtext-color);">Selamat datang kembali!</p>
        <form action="/login" method="POST" class="mt-4">
            @csrf
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-login mt-4">Masuk</button>
        </form>
    </div>

    <script>
        const toggleSwitch = document.querySelector('#checkbox');
        const currentTheme = localStorage.getItem('theme');

        if (currentTheme) {
            document.body.classList.add(currentTheme);
            if (currentTheme === 'dark-mode') {
                toggleSwitch.checked = true;
            }
        }

        function switchTheme(e) {
            if (e.target.checked) {
                document.body.classList.add('dark-mode');
                localStorage.setItem('theme', 'dark-mode');
            } else {
                document.body.classList.remove('dark-mode');
                localStorage.setItem('theme', 'light-mode');
            }
        }

        toggleSwitch.addEventListener('change', switchTheme, false);
    </script>
</body>
</html>