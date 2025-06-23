<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="{{ asset('assets/css/register.css') }}">
  <!-- Tambahkan ikon (misal pakai Font Awesome atau Feather Icons) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <title>Daftar Akun</title>
  <style>
    .password-wrapper {
      position: relative;
    }
    .password-toggle {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: #888;
    }
  </style>
</head>
<body>
<div class="register-wrapper">
  <div class="form-section">
    <div class="form-header">
      <h1>Buat Akun Baru</h1>
    </div>

    @if ($errors->any())
      <div style="background: #fee; padding: 10px;">
        @foreach ($errors->all() as $error)
          <p style="color: #c33;">{{ $error }}</p>
        @endforeach
      </div>
    @endif

    <form method="POST" action="{{ route('register.post') }}">
      @csrf
      <div class="form-group">
        <label for="name">Nama Lengkap</label>
        <input type="text" id="name" name="name" required placeholder="Nama lengkap">
      </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required placeholder="Email aktif">
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <div class="password-wrapper">
          <input type="password" id="password" name="password" required placeholder="Password">
          <span class="password-toggle" onclick="togglePassword()">
            <i class="fa-solid fa-eye-slash" id="toggleIcon"></i>
          </span>
        </div>
      </div>

      <button type="submit" class="register-button">Daftar</button>
      <div class="signup-link">
        Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
      </div>
    </form>
  </div>
</div>

<script>
  function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');

    if (passwordInput.type === 'password') {
      passwordInput.type = 'text';
      toggleIcon.classList.remove('fa-eye-slash');
      toggleIcon.classList.add('fa-eye');
    } else {
      passwordInput.type = 'password';
      toggleIcon.classList.remove('fa-eye');
      toggleIcon.classList.add('fa-eye-slash');
    }
  }
</script>
</body>
</html>
