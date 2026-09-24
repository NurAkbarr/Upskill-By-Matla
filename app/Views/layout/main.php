<!DOCTYPE html>
<html lang="id" class="h-full bg-white scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'MUSLIM UPSKILL ACADEMY - Platform Peningkatan Kompetensi') ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/logo.png') ?>">
    <link rel="shortcut icon" href="<?= base_url('favicon.ico') ?>">
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
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
<body class="min-h-full flex flex-col bg-white text-slate-900 selection:bg-pink-100 selection:text-upskill-pink">

    <!-- Header & Navigasi -->
    <header class="border-b border-slate-100 bg-white/95 backdrop-blur-sm sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20 sm:h-24">
                <!-- Logo Muslim Upskill Academy (Sesuaikan nama file gambar jika berbeda) -->
                <div class="flex items-center">
                    <a href="<?= base_url('/') ?>" class="flex items-center gap-3 group py-2">
                        <img src="<?= base_url('assets/images/logo-removebg.png') ?>" alt="Muslim Upskill Academy" class="h-12 sm:h-14 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
                    </a>
                </div>

                <!-- Desktop Navigation Menu (text-upskill-blue) -->
                <nav class="hidden md:flex items-center gap-8 text-sm font-bold text-upskill-blue">
                    <a href="<?= base_url('/#program-kelas') ?>" class="hover:text-upskill-pink transition-colors duration-300">Program</a>
                    <a href="<?= base_url('/#target-peserta') ?>" class="hover:text-upskill-pink transition-colors duration-300">Target Peserta</a>
                    <a href="<?= base_url('/#tentang-kami') ?>" class="hover:text-upskill-pink transition-colors duration-300">Tentang Kami</a>
                </nav>

                <!-- Auth Action Button (Dynamic based on Session) -->
                <div class="flex items-center gap-3">
                    <?php if (session()->get('isLoggedIn')): ?>
                        <span class="text-xs font-semibold text-upskill-darkblue hidden sm:inline-block">
                            Halo, <strong class="text-upskill-blue"><?= esc(session()->get('full_name')) ?></strong>
                        </span>
                        <a href="<?= session()->get('role') == 'super_admin' ? base_url('/admin/dashboard') : base_url('/dashboard') ?>" class="inline-flex items-center justify-center px-4 py-2 text-xs font-bold rounded-lg text-white bg-upskill-pink hover:bg-upskill-magenta transition-colors duration-300 shadow-sm">
                            Dasbor
                        </a>
                        <a href="<?= base_url('logout') ?>" class="inline-flex items-center justify-center px-3.5 py-1.5 text-xs font-semibold rounded-lg text-rose-700 bg-rose-50 border border-rose-200 hover:bg-rose-100 transition-colors duration-300">
                            Keluar
                        </a>
                    <?php else: ?>
                        <a href="<?= base_url('login') ?>" class="inline-flex items-center justify-center px-4 py-2 text-sm font-bold rounded-lg text-upskill-darkblue bg-white border border-slate-200 hover:border-upskill-blue hover:text-upskill-blue transition-colors duration-300">
                            Masuk
                        </a>
                        <a href="<?= base_url('register') ?>" class="hidden sm:inline-flex items-center justify-center px-5 py-2.5 text-sm font-bold rounded-lg text-white bg-upskill-pink hover:bg-upskill-magenta transition-colors duration-300 shadow-sm">
                            Daftar Sekarang
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Konten Dinamis Halaman -->
    <main class="flex-1 bg-white">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer Bersih & Profesional -->
    <footer id="tentang-kami" class="border-t border-slate-100 bg-slate-50/70 mt-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 lg:gap-12">
                <div class="md:col-span-2 space-y-3">
                    <div class="flex items-center gap-2">
                        <img src="<?= base_url('assets/images/logo-removebg.png') ?>" alt="Muslim Upskill Academy" class="h-10 w-auto object-contain">
                    </div>
                    <p class="text-sm text-slate-500 leading-relaxed max-w-sm">
                        Inisiatif pendidikan dan pelatihan kompetensi profesional berbasis nilai integritas, keilmuan praktis, dan kebermanfaatan nyata bagi kemajuan umat dan bangsa.
                    </p>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-upskill-darkblue uppercase tracking-wider mb-3">Navigasi</h4>
                    <ul class="space-y-2 text-sm font-medium text-slate-600">
                        <li><a href="<?= base_url('/#program-kelas') ?>" class="hover:text-upskill-pink transition-colors duration-300">Semua Program</a></li>
                        <li><a href="<?= base_url('login') ?>" class="hover:text-upskill-pink transition-colors duration-300">Portal Peserta</a></li>
                        <li><a href="<?= base_url('/#target-peserta') ?>" class="hover:text-upskill-pink transition-colors duration-300">Target Belajar</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-upskill-darkblue uppercase tracking-wider mb-3">Informasi</h4>
                    <ul class="space-y-2 text-sm text-slate-600">
                        <li><span class="text-slate-500">Program B2B Instansi</span></li>
                        <li><span class="text-slate-500">Kemitraan Mentor</span></li>
                        <li><span class="text-slate-500">Bantuan & FAQ</span></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-slate-200/60 mt-10 pt-6 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
                <p>&copy; <?= date('Y') ?> MUSLIM UPSKILL ACADEMY by MATLA. Seluruh hak cipta dilindungi.</p>
                <p class="text-slate-400">Pendidikan Berkelanjutan & Keterampilan Praktis</p>
            </div>
        </div>
    </footer>

</body>
</html>
