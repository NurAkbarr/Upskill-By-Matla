<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Detail Kelas - MUSLIM UPSKILL ACADEMY') ?></title>

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
<body class="h-full bg-slate-50 text-slate-900 flex overflow-hidden">

    <!-- ============================================================== -->
    <!-- 1. SIDEBAR NAVIGASI (Kiri)                                    -->
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

            <!-- Daftar Menu Navigasi Sidebar -->
            <nav class="p-4 space-y-1">
                <!-- 1. Ringkasan -->
                <a href="<?= base_url('dashboard') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold text-upskill-darkblue hover:text-upskill-pink hover:bg-slate-50 transition-colors">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Ringkasan
                </a>

                <!-- 2. Detail Kelas (Aktif) -->
                <a href="<?= base_url('dashboard/course/' . $course['id']) ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold bg-pink-50 text-upskill-pink transition-colors">
                    <svg class="w-4 h-4 text-upskill-pink" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Detail Kelas
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
            <div class="px-3.5 py-3 mb-2 rounded-lg bg-slate-50 border border-slate-200/70">
                <p class="text-xs font-bold text-upskill-darkblue truncate">
                    <?= esc($user_name ?? 'Peserta') ?>
                </p>
                <div class="mt-1 flex items-center justify-between">
                    <span class="inline-flex items-center text-[10px] font-semibold text-upskill-pink bg-pink-50 px-2 py-0.5 rounded border border-pink-100">
                        <?= esc($role_label ?? 'Peserta Mandiri') ?>
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
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 sm:px-10 sticky top-0 z-20">
            <div class="flex items-center gap-3">
                <a href="<?= base_url('dashboard') ?>" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 hover:text-slate-900 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Kembali ke Dasbor</span>
                </a>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold text-slate-500 hidden sm:inline">Kurikulum Terstruktur</span>
            </div>
        </header>

        <!-- Wadah Konten Utama -->
        <div class="p-6 sm:p-10 max-w-5xl mx-auto space-y-8 pb-24 md:pb-10">

            <!-- Breadcrumb -->
            <nav class="flex items-center gap-2 text-xs text-slate-500">
                <a href="<?= base_url('dashboard') ?>" class="hover:text-upskill-pink transition-colors">Dasbor</a>
                <span>/</span>
                <span class="text-slate-400">Detail Program</span>
                <span>/</span>
                <span class="text-upskill-darkblue font-bold truncate max-w-sm"><?= esc($course['title']) ?></span>
            </nav>

            <!-- ========================================================== -->
            <!-- HEADER KELAS (Hero Card)                                   -->
            <!-- ========================================================== -->
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xs">
                
                <!-- Header macOS 3 Titik -->
                <div class="flex items-center justify-between px-5 py-3 bg-slate-50/90 border-b border-slate-100">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500 inline-block"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500 inline-block"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span>
                    </div>
                    <span class="text-[11px] font-mono font-semibold text-slate-400 uppercase tracking-wider">
                        Program Terverifikasi MATLA
                    </span>
                </div>

                <div class="p-6 sm:p-8">
                    <div class="flex flex-col md:flex-row gap-6 items-start">
                        
                        <!-- Thumbnail / Banner Kelas -->
                        <div class="w-full md:w-56 aspect-video rounded-xl overflow-hidden bg-slate-100 shrink-0 border border-slate-200 shadow-xs">
                            <img 
                                src="<?= base_url('uploads/courses/' . esc($course['banner_image'])) ?>" 
                                alt="<?= esc($course['title']) ?>" 
                                class="w-full h-full object-cover"
                                onerror="this.src='https://placehold.co/600x340/1C21AC/FFFFFF?text=<?= urlencode(esc($course['title'])) ?>'"
                            >
                        </div>

                        <!-- Info Kelas & Mentor -->
                        <div class="flex-1 min-w-0 space-y-3">
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-pink-50 text-upskill-pink border border-pink-100">
                                    <?= count($sessions) ?> Sesi Pembelajaran
                                </span>
                                <?php if (!empty($course['price']) && $course['price'] > 0): ?>
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700">
                                        Rp <?= number_format($course['price'], 0, ',', '.') ?>
                                    </span>
                                <?php else: ?>
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Akses Gratis
                                    </span>
                                <?php endif; ?>
                            </div>

                            <h2 class="text-xl sm:text-2xl font-extrabold text-upskill-darkblue tracking-tight leading-snug">
                                <?= esc($course['title']) ?>
                            </h2>

                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                Mentor Pengampu: <strong class="text-slate-800"><?= esc($course['mentor_name'] ?? 'Instruktur Praktisi MATLA') ?></strong>
                            </p>

                            <!-- Progres Bar Pembelajaran -->
                            <div class="pt-2 max-w-md space-y-1.5">
                                <div class="flex items-center justify-between text-xs font-semibold">
                                    <span class="text-slate-500">Penyelesaian Modul (<?= $completedSessions ?> dari <?= $totalSessions ?> Sesi)</span>
                                    <span class="text-upskill-pink font-bold"><?= $courseProgress ?>%</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden border border-slate-200/60">
                                    <div class="bg-upskill-pink h-2.5 rounded-full transition-all duration-500" style="width: <?= min(100, max(0, $courseProgress)) ?>%"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- ========================================================== -->
            <!-- DAFTAR SESI KURIKULUM (ACCORDION UI)                       -->
            <!-- ========================================================== -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-upskill-darkblue">Daftar Sesi Pembelajaran</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Buka masing-masing sesi untuk mengakses materi dan kuis evaluasi pemahaman.</p>
                    </div>
                    <span class="text-xs font-mono font-semibold text-slate-400 bg-white px-3 py-1 rounded-lg border border-slate-200">
                        <?= count($sessions) ?> Modul
                    </span>
                </div>

                <?php if (empty($sessions)): ?>
                    <div class="bg-white border border-slate-200 rounded-2xl p-10 text-center">
                        <div class="w-12 h-12 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <h4 class="text-sm font-bold text-upskill-darkblue">Sesi Belum Tersedia</h4>
                        <p class="text-xs text-slate-500 mt-1">Instruktur sedang menyusun silabus materi untuk kelas ini.</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-3">
                        <?php foreach ($sessions as $idx => $s): ?>
                            <?php 
                                $isCompleted = !empty($progress_status[(int) $s['id']]);
                                $sessionNum  = $idx + 1;
                            ?>
                            <!-- Item Accordion Per Sesi -->
                            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xs transition-all hover:border-slate-300">
                                
                                <!-- Header Accordion -->
                                <div 
                                    onclick="toggleAccordion(<?= (int) $s['id'] ?>)" 
                                    class="p-4 sm:p-5 flex items-center justify-between gap-4 cursor-pointer select-none bg-white hover:bg-slate-50/70 transition-colors"
                                >
                                    <!-- Judul Sesi -->
                                    <div class="flex items-center gap-3.5 min-w-0">
                                        <div class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-bold shrink-0 <?= $isCompleted ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-pink-50 text-upskill-pink border border-pink-100' ?>">
                                            <?php if ($isCompleted): ?>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            <?php else: ?>
                                                <?= $sessionNum ?>
                                            <?php endif; ?>
                                        </div>

                                        <div class="min-w-0">
                                            <h4 class="text-xs sm:text-sm font-bold text-upskill-darkblue truncate">
                                                Sesi <?= $sessionNum ?>: <?= esc($s['chapter_title']) ?>
                                            </h4>
                                            <div class="flex items-center gap-2 mt-0.5 text-[11px] text-slate-400">
                                                <?php if ($s['content_type'] === 'video'): ?>
                                                    <span class="flex items-center gap-1 text-blue-600 font-medium">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                                        </svg>
                                                        Video
                                                    </span>
                                                <?php else: ?>
                                                    <span class="flex items-center gap-1 text-emerald-600 font-medium">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                        </svg>
                                                        Teks / Dokumen
                                                    </span>
                                                <?php endif; ?>

                                                <span>&bull;</span>

                                                <?php if ($isCompleted): ?>
                                                    <span class="text-emerald-600 font-semibold flex items-center gap-1">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                        Materi Selesai 100%
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-amber-600 font-medium flex items-center gap-1">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                                        Belum Diselesaikan
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tombol Biru 'Selengkapnya' Sesuai Instruksi -->
                                    <div class="flex items-center gap-2 shrink-0">
                                        <button 
                                            type="button" 
                                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-bold rounded-lg text-white bg-upskill-blue hover:bg-upskill-darkblue transition-colors shadow-xs"
                                        >
                                            <span>Selengkapnya</span>
                                            <svg id="accordion-arrow-<?= (int) $s['id'] ?>" class="w-3.5 h-3.5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Isi Accordion (Buka - Tutup via Vanilla JS) -->
                                <div id="accordion-body-<?= (int) $s['id'] ?>" class="hidden border-t border-slate-100 p-5 bg-slate-50/50">
                                    
                                    <!-- Kontainer bergaris tepi yang menampung 2 tombol -->
                                    <div class="bg-white border border-slate-200 rounded-xl p-5 space-y-4 shadow-2xs">
                                        
                                        <!-- Ringkasan Singkat Sesi -->
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 pb-3 border-b border-slate-100 text-xs text-slate-500">
                                            <span class="font-medium text-slate-700">Aktivitas Pembelajaran:</span>
                                            <span>
                                                Evaluasi: <strong><?= (int) ($s['quiz_count'] ?? 0) ?> Soal CBT</strong> 
                                                <?php if (!empty($s['quiz_duration_minutes'])): ?>
                                                    (<?= (int) $s['quiz_duration_minutes'] ?> Menit)
                                                <?php endif; ?>
                                            </span>
                                        </div>

                                        <!-- Wadah 2 Tombol (Materi & Kuis) Sesuai Spesifikasi -->
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                                            
                                            <!-- ============================================== -->
                                            <!-- TOMBOL 1 (MATERI): Putih/Abu Terang, Ikon Doc  -->
                                            <!-- ============================================== -->
                                            <a 
                                                href="<?= base_url('dashboard/learn/' . $s['id']) ?>" 
                                                class="w-full py-3 px-4 rounded-xl font-bold text-xs sm:text-sm text-slate-800 bg-white hover:bg-slate-100 border border-slate-300 transition-colors flex items-center justify-center gap-2.5 shadow-xs group"
                                            >
                                                <?php if ($s['content_type'] === 'video'): ?>
                                                    <!-- Ikon Video / Speaker -->
                                                    <svg class="w-4 h-4 text-upskill-blue group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                                                    </svg>
                                                    <span>Buka Materi Video</span>
                                                <?php else: ?>
                                                    <!-- Ikon Dokumen Teks/PDF -->
                                                    <svg class="w-4 h-4 text-emerald-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                    <span>Baca Materi Teks / PDF</span>
                                                <?php endif; ?>
                                                <svg class="w-3.5 h-3.5 text-slate-400 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </a>

                                            <!-- ============================================== -->
                                            <!-- TOMBOL 2 (KUIS): Logika PHP Berdasarkan Status -->
                                            <!-- ============================================== -->
                                            <?php if (!$isCompleted): ?>
                                                <!-- Kuis Belum Selesai (Terkunci) -->
                                                <div class="flex flex-col">
                                                    <button 
                                                        disabled 
                                                        class="w-full py-3 px-4 rounded-xl font-bold text-xs sm:text-sm bg-gray-300 text-gray-500 cursor-not-allowed pointer-events-none flex items-center justify-center gap-2 shadow-xs"
                                                    >
                                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                        </svg>
                                                        <span>Mulai Kuis Evaluasi</span>
                                                    </button>
                                                    <!-- Peringatan wajib selesaikan materi 100% -->
                                                    <span class="text-[11px] text-rose-500 font-medium mt-1.5 flex items-center gap-1">
                                                        <svg class="w-3 h-3 text-rose-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                        </svg>
                                                        Selesaikan materi 100% untuk membuka kuis
                                                    </span>
                                                </div>
                                            <?php else: ?>
                                                <!-- Kuis Terbuka (Materi Selesai 100%) -->
                                                <div class="flex flex-col">
                                                    <a 
                                                        href="<?= base_url('dashboard/quiz/' . $s['id']) ?>" 
                                                        class="w-full py-3 px-4 rounded-xl font-bold text-xs sm:text-sm bg-upskill-pink hover:bg-upskill-magenta text-white transition-colors flex items-center justify-center gap-2 shadow-xs group"
                                                    >
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        <span>Mulai Kuis Evaluasi</span>
                                                        <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                                        </svg>
                                                    </a>
                                                    <span class="text-[11px] text-emerald-600 font-semibold mt-1.5 flex items-center gap-1">
                                                        <svg class="w-3 h-3 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                        Materi telah diselesaikan. Kuis siap dikerjakan!
                                                    </span>
                                                </div>
                                            <?php endif; ?>

                                        </div>

                                    </div>

                                </div>

                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </div>

        </div>
    </main>

    <!-- ============================================================== -->
    <!-- 3. VANILLA JS LOGIKA ACCORDION BUKA - TUTUP                    -->
    <!-- ============================================================== -->
    <script>
    function toggleAccordion(sessionId) {
        const body = document.getElementById('accordion-body-' + sessionId);
        const arrow = document.getElementById('accordion-arrow-' + sessionId);

        if (body) {
            body.classList.toggle('hidden');
        }
        if (arrow) {
            arrow.classList.toggle('rotate-180');
        }
    }
    </script>

    <!-- Bottom Navigation Bar untuk Mobile (Tahap 21) -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md border-t border-slate-200 px-4 py-2 flex items-center justify-around shadow-lg">
        <a href="<?= base_url('dashboard') ?>" class="flex flex-col items-center gap-1 text-slate-500 hover:text-upskill-darkblue">
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
