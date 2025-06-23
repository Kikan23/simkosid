<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Icon dan CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/loginn.css') }}" />
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login</title>
</head>
<body>
    <div class="logo-section">
        <img src="{{ asset('assets/img/logo.png') }}" alt="Logo SIMKOS">
    </div>
    
    <div class="form-section">
        <div class="form-header">
            <h1>Sistem Informasi Manajemen Kos</h1>
            <h4>Selamat Datang Diwebsite Manajemen Kos</h4>
            </br>
        </div>

        <!-- Tampilkan pesan error -->
        @if ($errors->any())
            <div style="background: #fee; border: 1px solid #fcc; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                @foreach ($errors->all() as $error)
                    <p style="color: #c33; margin: 0; font-size: 14px;">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Tampilkan pesan sukses jika ada -->
        @if (session('message'))
            <div style="background: #efe; border: 1px solid #cfc; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                <p style="color: #3c3; margin: 0; font-size: 14px;">{{ session('message') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    placeholder="Masukkan email Anda"
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-container">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required 
                        placeholder="Masukkan password Anda"
                    >
                    <button type="button" class="password-toggle" onclick="togglePassword()">
                        <i id="eye-icon" class="fi fi-rr-eye-crossed"></i>
                    </button>
                </div>
            </div>

            <div class="checkbox-container">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Ingat saya</label>
            </div>

            <button type="submit" class="login-button">Masuk</button>

            <div class="signup-link">
                Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a>
            </div>
        </form>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const icon = document.querySelector('.password-toggle i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fi-rr-eye-crossed');
                icon.classList.add('fi-rr-eye');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fi-rr-eye');
                icon.classList.add('fi-rr-eye-crossed');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const token = document.querySelector('meta[name="csrf-token"]');
            if (token) {
                window.Laravel = {
                    csrfToken: token.getAttribute('content')
                };
            }
        });
    </script>
</body>
</html>
