<!DOCTYPE html>
<html lang="id" class="h-full bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Autentikasi - MUSLIM UPSKILL ACADEMY') ?></title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/logo.png') ?>">
    <link rel="shortcut icon" href="<?= base_url('favicon.ico') ?>">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: {
              sans: ['Inter', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
            },
            colors: {
              'upskill-darkblue': '#1C21AC',
              'upskill-blue': '#0101FF',
              'upskill-pink': '#FA1886',
              'upskill-magenta': '#B103C5',
            }
          }
        }
      }
    </script>
    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            -webkit-font-smoothing: antialiased;
        }
    </style>
</head>
<body class="min-h-full bg-white text-slate-900 flex flex-col justify-between">

    <!-- Navbar Header Khusus Auth -->
    <header class="w-full bg-white border-b border-slate-100 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">
                <!-- Logo Brand -->
                <a href="<?= base_url('/') ?>" class="flex items-center gap-2 group">
                    <img 
                        src="<?= base_url('assets/images/logo-removebg.png') ?>" 
                        alt="Muslim Upskill Academy" 
                        class="h-9 sm:h-12 w-auto object-contain transition-transform duration-200 group-hover:scale-105"
                    >
                </a>

                <!-- Tautan Kembali / Navigasi Cepat -->
                <div class="flex items-center gap-3">
                    <a href="<?= base_url('/') ?>" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-slate-600 hover:text-upskill-blue transition-colors duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span>Beranda</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Konten Dinamis Halaman Autentikasi (Tanpa Footer) -->
    <main class="flex-1 flex items-center justify-center bg-slate-50/50">
        <?= $this->renderSection('content') ?>
    </main>

    <script>
        function togglePasswordVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            if (!input) return;
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            
            const eyeIcon = btn.querySelector('.eye-icon');
            const eyeSlashIcon = btn.querySelector('.eye-slash-icon');
            if (eyeIcon && eyeSlashIcon) {
                if (isPassword) {
                    eyeIcon.classList.add('hidden');
                    eyeSlashIcon.classList.remove('hidden');
                } else {
                    eyeIcon.classList.remove('hidden');
                    eyeSlashIcon.classList.add('hidden');
                }
            }
        }
    </script>
</body>
</html>
