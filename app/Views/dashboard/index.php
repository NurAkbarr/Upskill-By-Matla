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
    <!-- 1. SIDEBAR NAVIGASI (Desktop - Kiri)                           -->
    <!-- ============================================================== -->
    <aside class="hidden md:flex w-64 bg-white border-r border-slate-200 flex-col justify-between shrink-0 h-screen select-none">
        
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

                <!-- 2. Katalog Program -->
                <a href="#katalog-program" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold text-upskill-darkblue hover:text-upskill-pink hover:bg-slate-50 transition-colors">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Katalog Program
                </a>

                <!-- 3. Pengaturan Akun -->
                <a href="<?= base_url('dashboard/account') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold text-upskill-darkblue hover:text-upskill-pink hover:bg-slate-50 transition-colors">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Pengaturan Akun
                </a>
            </nav>
        </div>

        <!-- Bagian Bawah: Profil Singkat & Menu Keluar -->
        <div class="p-4 border-t border-slate-100">
            <!-- Profil Singkat -->
            <a href="<?= base_url('dashboard/account') ?>" class="block px-3.5 py-3 mb-2 rounded-lg bg-slate-50 hover:bg-slate-100 border border-slate-200/70 transition-colors">
                <p class="text-xs font-bold text-upskill-darkblue truncate">
                    <?= esc($user_name) ?>
                </p>
                <div class="mt-1 flex items-center justify-between">
                    <span class="inline-flex items-center text-[10px] font-semibold text-upskill-pink bg-pink-50 px-2 py-0.5 rounded border border-pink-100">
                        <?= esc($role_label) ?>
                    </span>
                    <span class="text-[10px] text-slate-400 font-medium">Ubah &rarr;</span>
                </div>
            </a>

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
        <div class="p-6 sm:p-10 max-w-6xl mx-auto space-y-8 pb-24 md:pb-10">

            <!-- Flashdata Alert -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2.5 shadow-xs">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="font-medium"><?= esc(session()->getFlashdata('success')) ?></span>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-2.5 shadow-xs">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span class="font-medium"><?= esc(session()->getFlashdata('error')) ?></span>
                </div>
            <?php endif; ?>
            
            <!-- Kartu Sapaan Pengguna (Clean & Minimalist) -->
            <div class="bg-white border border-slate-200 rounded-xl p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-extrabold text-upskill-darkblue tracking-tight">
                            Ahlan wa sahlan, <span class="text-upskill-pink"><?= esc($user_name) ?></span>
                        </h2>
                        <p class="text-sm text-slate-500 mt-1 leading-relaxed">
                            Pantau aktivitas belajar, kurikulum materi aktif, dan capaian evaluasi kompetensi Anda di Muslim Upskill Academy.
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

            <!-- Ringkasan Statistik Singkat (Dinamis dari Controller) -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- Kartu 1: Kelas Berjalan -->
                <div class="bg-white border border-slate-200 rounded-xl p-6 hover:border-upskill-pink/30 transition-colors shadow-2xs">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Kelas Aktif</p>
                    <div class="flex items-baseline justify-between">
                        <span class="text-2xl font-extrabold text-upskill-darkblue"><?= (int) $total_active ?></span>
                        <span class="text-xs text-slate-400">Program Terdaftar</span>
                    </div>
                </div>

                <!-- Kartu 2: Progres Rata-Rata -->
                <div class="bg-white border border-slate-200 rounded-xl p-6 hover:border-upskill-pink/30 transition-colors shadow-2xs">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Progres Rata-rata</p>
                    <div class="flex items-baseline justify-between">
                        <span class="text-2xl font-extrabold text-upskill-darkblue"><?= (int) $avg_progress ?>%</span>
                        <span class="text-xs text-slate-400">Penyelesaian</span>
                    </div>
                </div>

                <!-- Kartu 3: Total Kuis (Tahap 21) -->
                <div class="bg-white border border-slate-200 rounded-xl p-6 hover:border-upskill-pink/30 transition-colors shadow-2xs">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Kuis</p>
                        <span class="text-[11px] font-mono font-bold text-upskill-darkblue bg-slate-100 px-2 py-0.5 rounded">
                            <?= (int) ($quizzes_completed ?? 0) + (int) ($quizzes_pending ?? 0) ?> Sesi
                        </span>
                    </div>
                    <div class="space-y-1.5 pt-0.5">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-emerald-700 font-semibold flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                Kuis Dikerjakan:
                            </span>
                            <strong class="text-slate-800 font-bold"><?= (int) ($quizzes_completed ?? 0) ?></strong>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-amber-700 font-semibold flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                                Kuis Belum Dikerjakan:
                            </span>
                            <strong class="text-slate-800 font-bold"><?= (int) ($quizzes_pending ?? 0) ?></strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Area Konten Materi Belajar Terkini -->
            <div id="katalog-program" class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-xs scroll-mt-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <div class="flex items-center gap-2.5">
                        <h3 class="text-base font-bold text-upskill-darkblue">Katalog Program Belajar</h3>
                        <?php if (!empty($enrolled_courses)): ?>
                            <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-pink-50 text-upskill-pink border border-pink-100">
                                <?= count($enrolled_courses) ?> Kelas
                            </span>
                        <?php endif; ?>
                    </div>
                    <a href="<?= base_url('/#program-kelas') ?>" class="text-xs font-semibold text-upskill-pink hover:underline flex items-center gap-1">
                        <span>Lihat Semua Katalog</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>

                <?php if (empty($enrolled_courses)): ?>
                    <!-- Empty State saat Katalog Belum Ada Kelas -->
                    <div class="text-center py-12 px-4">
                        <div class="w-12 h-12 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <h4 class="text-sm font-bold text-upskill-darkblue">Belum ada kelas yang tersedia</h4>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1 mb-2 leading-relaxed">
                            Katalog kelas saat ini belum memiliki program pelatihan yang ditambahkan oleh admin.
                        </p>
                    </div>
                <?php else: ?>
                    <!-- Grid Kartu Kelas dari Katalog Admin -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($enrolled_courses as $c): ?>
                            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xs hover:shadow-md hover:border-slate-300 transition-all flex flex-col group">
                                
                                <!-- Header macOS 3 Titik & Status -->
                                <div class="flex items-center justify-between px-4 py-2.5 bg-slate-50/80 border-b border-slate-100">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500 inline-block"></span>
                                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500 inline-block"></span>
                                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span>
                                    </div>
                                    <div>
                                        <?php if (!empty($c['is_enrolled']) && (int)($c['progress_percentage'] ?? 0) > 0): ?>
                                            <span class="text-[10px] font-mono font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200 uppercase tracking-wider">
                                                Sedang Belajar
                                            </span>
                                        <?php elseif (!empty($c['is_enrolled'])): ?>
                                            <span class="text-[10px] font-mono font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded border border-blue-200 uppercase tracking-wider">
                                                Terdaftar
                                            </span>
                                        <?php else: ?>
                                            <span class="text-[10px] font-mono font-semibold text-slate-600 bg-slate-100 px-2 py-0.5 rounded border border-slate-200 uppercase tracking-wider">
                                                Tersedia
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Banner Gambar Kelas -->
                                <div class="relative aspect-video overflow-hidden bg-slate-100">
                                    <img 
                                        src="<?= base_url('uploads/courses/' . esc($c['banner_image'])) ?>" 
                                        alt="<?= esc($c['title']) ?>" 
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                        onerror="this.src='https://placehold.co/600x340/1C21AC/FFFFFF?text=<?= urlencode(esc($c['title'])) ?>'"
                                    >
                                </div>

                                <!-- Detail Kelas & Progress Bar -->
                                <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                                    <div class="space-y-1">
                                        <h4 class="font-bold text-sm sm:text-base text-upskill-darkblue line-clamp-2 leading-snug group-hover:text-upskill-pink transition-colors">
                                            <?= esc($c['title']) ?>
                                        </h4>
                                        <p class="text-xs text-slate-500">
                                            Mentor: <span class="font-medium text-slate-700"><?= esc($c['mentor_name'] ?? 'Instruktur MATLA') ?></span>
                                        </p>
                                    </div>

                                    <!-- Progress Bar (bg-upskill-pink) -->
                                    <div class="space-y-1.5 pt-1">
                                        <div class="flex items-center justify-between text-xs font-semibold">
                                            <span class="text-slate-500">Progres Belajar</span>
                                            <span class="text-upskill-pink font-bold"><?= (int) ($c['progress_percentage'] ?? 0) ?>%</span>
                                        </div>
                                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden border border-slate-200/60">
                                            <div class="bg-upskill-pink h-2 rounded-full transition-all duration-500" style="width: <?= min(100, max(0, (int) ($c['progress_percentage'] ?? 0))) ?>%"></div>
                                        </div>
                                    </div>

                                    <!-- Tombol Menuju Detail Kelas (Tahap 19) -->
                                    <div class="pt-2 border-t border-slate-100">
                                        <a 
                                            href="<?= base_url('dashboard/course/' . $c['id']) ?>" 
                                            class="w-full py-2.5 px-4 rounded-xl font-bold text-xs text-white bg-upskill-blue hover:bg-upskill-darkblue transition-colors flex items-center justify-center gap-1.5 shadow-xs"
                                        >
                                            <span>Selengkapnya</span>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </main>

    <!-- Bottom Navigation Bar untuk Mobile (Tahap 21) -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md border-t border-slate-200 px-4 py-2 flex items-center justify-around shadow-lg">
        <a href="<?= base_url('dashboard') ?>" class="flex flex-col items-center gap-1 text-upskill-pink font-semibold">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="text-[10px]">Ringkasan</span>
        </a>
        <a href="<?= base_url('dashboard#katalog-program') ?>" class="flex flex-col items-center gap-1 text-slate-500 hover:text-upskill-darkblue">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <span class="text-[10px]">Katalog</span>
        </a>
        <a href="<?= base_url('dashboard/account') ?>" class="flex flex-col items-center gap-1 text-slate-500 hover:text-upskill-darkblue">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span class="text-[10px]">Akun</span>
        </a>
        <a href="<?= base_url('logout') ?>" class="flex flex-col items-center gap-1 text-rose-500 hover:text-rose-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            <span class="text-[10px]">Keluar</span>
        </a>
    </nav>

</body>
</html>
