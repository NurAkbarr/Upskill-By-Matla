<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Dasbor - MUSLIM UPSKILL ACADEMY') ?></title>

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
<body class="h-full bg-slate-50 text-slate-900 flex overflow-hidden">

    <!-- ============================================================== -->
    <!-- 1. SIDEBAR NAVIGASI (Kiri)                                    -->
    <!-- ============================================================== -->
    <aside class="w-64 bg-white border-r border-slate-200 flex flex-col justify-between shrink-0 h-screen select-none">
        
        <!-- Bagian Atas: Logo & Menu Utama -->
        <div>
            <!-- Identitas Brand -->
            <div class="h-20 flex items-center px-6 border-b border-slate-100">
                <a href="<?= base_url('/') ?>" class="flex items-center gap-2.5">
                    <img src="<?= base_url('assets/images/logo-removebg.png') ?>" alt="Muslim Upskill Academy" class="h-12 w-auto object-contain">
                </a>
            </div>

            <!-- Daftar Menu Navigasi Sidebar (text-upskill-darkblue) -->
            <nav class="p-4 space-y-1">
                <!-- 1. Ringkasan (Aktif) -->
                <a href="<?= base_url('dashboard') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold bg-pink-50 text-upskill-pink transition-colors">
                    <svg class="w-4 h-4 text-upskill-pink" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Ringkasan
                </a>

                <!-- 2. Kelas Saya -->
                <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold text-upskill-darkblue hover:text-upskill-pink hover:bg-slate-50 transition-colors">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Kelas Saya
                </a>

                <!-- 3. Sertifikat -->
                <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold text-upskill-darkblue hover:text-upskill-pink hover:bg-slate-50 transition-colors">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                    Sertifikat
                </a>
            </nav>
        </div>

        <!-- Bagian Bawah: Profil Singkat & Menu Keluar -->
        <div class="p-4 border-t border-slate-100">
            <!-- Profil Singkat -->
            <div class="px-3.5 py-3 mb-2 rounded-lg bg-slate-50 border border-slate-200/70">
                <p class="text-xs font-bold text-upskill-darkblue truncate">
                    <?= esc($user_name) ?>
                </p>
                <div class="mt-1 flex items-center justify-between">
                    <span class="inline-flex items-center text-[10px] font-semibold text-upskill-pink bg-pink-50 px-2 py-0.5 rounded border border-pink-100">
                        <?= esc($role_label) ?>
                    </span>
                </div>
            </div>

            <!-- 4. Tombol Keluar (Logout) -->
            <a href="<?= base_url('logout') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold text-rose-600 hover:bg-rose-50 transition-colors">
                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Keluar
            </a>
        </div>
    </aside>

    <!-- ============================================================== -->
    <!-- 2. MAIN CONTENT AREA (Kanan)                                  -->
    <!-- ============================================================== -->
    <main class="flex-1 overflow-y-auto bg-slate-50">
        
        <!-- Header Atas Area Konten -->
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 sm:px-10">
            <div>
                <h1 class="text-base font-bold text-upskill-darkblue">Dasbor Pembelajaran</h1>
            </div>
            <div class="flex items-center gap-3">
                <a href="<?= base_url('/#program-kelas') ?>" class="inline-flex items-center justify-center px-3.5 py-1.5 text-xs font-semibold rounded-lg text-upskill-pink bg-pink-50 border border-pink-200 hover:bg-pink-100 transition-colors">
                    Katalog Program &rarr;
                </a>
            </div>
        </header>

        <!-- Wadah Konten Utama dengan Ruang Kosong Luas -->
        <div class="p-6 sm:p-10 max-w-6xl mx-auto space-y-8">
            
            <!-- Kartu Sapaan Pengguna (Clean & Minimalist) -->
            <div class="bg-white border border-slate-200 rounded-xl p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-extrabold text-upskill-darkblue tracking-tight">
                            Selamat datang kembali, <span class="text-upskill-pink"><?= esc($user_name) ?></span>
                        </h2>
                        <p class="text-sm text-slate-500 mt-1 leading-relaxed">
                            Pantau aktivitas belajar, kurikulum materi aktif, dan capaian sertifikasi kompetensi Anda di Muslim Upskill Academy.
                        </p>
                    </div>
                    <div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                            Akun Aktif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Ringkasan Statistik Singkat -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- Kartu 1: Kelas Berjalan -->
                <div class="bg-white border border-slate-200 rounded-xl p-6 hover:border-upskill-pink/30 transition-colors">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Kelas Aktif</p>
                    <div class="flex items-baseline justify-between">
                        <span class="text-2xl font-extrabold text-upskill-darkblue">0</span>
                        <span class="text-xs text-slate-400">Program Terdaftar</span>
                    </div>
                </div>

                <!-- Kartu 2: Materi Selesai -->
                <div class="bg-white border border-slate-200 rounded-xl p-6 hover:border-upskill-pink/30 transition-colors">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Progres Rata-rata</p>
                    <div class="flex items-baseline justify-between">
                        <span class="text-2xl font-extrabold text-upskill-darkblue">0%</span>
                        <span class="text-xs text-slate-400">Penyelesaian</span>
                    </div>
                </div>

                <!-- Kartu 3: Sertifikat -->
                <div class="bg-white border border-slate-200 rounded-xl p-6 hover:border-upskill-pink/30 transition-colors">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Sertifikat Kelulusan</p>
                    <div class="flex items-baseline justify-between">
                        <span class="text-2xl font-extrabold text-upskill-darkblue">0</span>
                        <span class="text-xs text-slate-400">Diterbitkan</span>
                    </div>
                </div>
            </div>

            <!-- Area Konten Materi Belajar Terkini -->
            <div class="bg-white border border-slate-200 rounded-xl p-6 sm:p-8">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <h3 class="text-base font-bold text-upskill-darkblue">Program Belajar Anda</h3>
                    <a href="<?= base_url('/#program-kelas') ?>" class="text-xs font-semibold text-upskill-pink hover:underline">
                        Jelajahi Kelas Baru
                    </a>
                </div>

                <!-- Empty State Bersih & Nyaman -->
                <div class="text-center py-12 px-4">
                    <div class="w-12 h-12 mx-auto rounded-full bg-pink-50 flex items-center justify-center text-upskill-pink mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h4 class="text-sm font-bold text-upskill-darkblue">Belum ada kelas yang diikuti</h4>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1 mb-6 leading-relaxed">
                        Pilih program pelatihan yang tersedia untuk mulai meningkatkan keterampilan praktis Anda.
                    </p>
                    <a href="<?= base_url('/#program-kelas') ?>" class="inline-flex items-center justify-center px-5 py-2.5 text-xs font-semibold rounded-lg text-white bg-upskill-pink hover:bg-upskill-magenta transition-colors shadow-sm">
                        Lihat Katalog Kelas
                    </a>
                </div>
            </div>

        </div>
    </main>

</body>
</html>
