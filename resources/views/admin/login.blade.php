<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JENDELA - Login Admin Panel</title>
    
    <!-- Google Fonts: Playfair Display, Source Serif 4, DM Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:ital,wght@0,300;0,400;0,500;1,400&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Source+Serif+4:ital,opsz,wght@0,8..60,400;0,8..60,600;1,8..60,400&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/index.css') }}" rel="stylesheet">
</head>
<body class="tubuh-halaman d-flex flex-column min-vh-100 justify-content-center align-items-center">

    <!-- Tombol Tema Kanan Atas -->
    <div class="position-fixed top-0 end-0 p-3">
        <button id="btnToggleTema" class="tombol-tema" title="Ubah Tema Visual">
            <i class="bi bi-moon-stars" id="ikonTema"></i>
        </button>
    </div>

    <!-- Container Utama Form Login -->
    <div class="container py-4" style="max-width: 440px;">
        
        <!-- Header Branding JENDELA -->
        <div class="text-center mb-4">
            <a href="/" class="text-decoration-none d-inline-block mb-1">
                <span class="logo-pustaka fs-2">JENDELA<span class="logo-titik-kayu">.</span></span>
            </a>
            <h3 class="fw-bold mt-1 mb-1" style="font-family: var(--font-display);">Login Administrator</h3>
            <p class="text-muted small" style="font-family: var(--font-body); font-style: italic;">Panel Khusus Karang Taruna & Perangkat Desa</p>
        </div>

        <!-- Bingkai Kartu Login Kertas Desa -->
        <div class="bingkai-login-admin">
            @if ($errors->any())
                <div class="alert alert-danger border-0 rounded-3 small mb-4">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $errors->first() }}
                </div>
            @endif

            <form action="/admin/login" method="POST">
                @csrf
                
                <!-- Input Email Admin -->
                <div class="mb-3">
                    <label for="email" class="form-label small fw-semibold text-secondary" style="font-family: var(--font-utility);">EMAIL ADMIN</label>
                    <div class="wadah-input-ikon">
                        <i class="bi bi-envelope ikon-input-login"></i>
                        <input type="email" 
                               class="input-login-admin" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus 
                               placeholder="admin@jendela.desa.id">
                    </div>
                </div>

                <!-- Input Kata Sandi -->
                <div class="mb-4">
                    <label for="password" class="form-label small fw-semibold text-secondary" style="font-family: var(--font-utility);">KATA SANDI</label>
                    <div class="wadah-input-ikon">
                        <i class="bi bi-lock ikon-input-login"></i>
                        <input type="password" 
                               class="input-login-admin" 
                               id="password" 
                               name="password" 
                               required 
                               placeholder="••••••••">
                    </div>
                </div>

                <!-- Opsi Ingat Saya -->
                <div class="mb-4 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                        <label class="form-check-label small text-muted ms-1" for="remember" style="font-family: var(--font-utility);">Ingat Saya</label>
                    </div>
                </div>

                <!-- Tombol Submit Login -->
                <button type="submit" class="tombol-masuk-admin">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Masuk ke Panel Admin
                </button>
            </form>

            <!-- Tautan Kembali -->
            <div class="text-center mt-4">
                <a href="/" class="text-decoration-none text-muted small" style="font-family: var(--font-utility);">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Portal Publik
                </a>
            </div>
        </div>
    </div>

    <!-- Script Tema -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const body = document.body;
            const btnToggleTema = document.getElementById('btnToggleTema');
            const ikonTema = document.getElementById('ikonTema');

            const muatTemaPreferensi = () => {
                const temaTersimpan = localStorage.getItem('theme');
                const preferensiGelapSystem = window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (temaTersimpan === 'dark' || (!temaTersimpan && preferensiGelapSystem)) {
                    body.classList.add('theme-dark');
                    ikonTema.className = 'bi bi-sun';
                } else {
                    body.classList.remove('theme-dark');
                    ikonTema.className = 'bi bi-moon-stars';
                }
            };

            btnToggleTema.addEventListener('click', () => {
                if (body.classList.contains('theme-dark')) {
                    body.classList.remove('theme-dark');
                    ikonTema.className = 'bi bi-moon-stars';
                    localStorage.setItem('theme', 'light');
                } else {
                    body.classList.add('theme-dark');
                    ikonTema.className = 'bi bi-sun';
                    localStorage.setItem('theme', 'dark');
                }
            });

            muatTemaPreferensi();
        });
    </script>
</body>
</html>
