<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Ruang Belajar - MUSLIM UPSKILL ACADEMY') ?></title>

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
<body class="min-h-screen bg-slate-50 text-slate-900 flex flex-col">

    <!-- ============================================================== -->
    <!-- 1. TOP NAVBAR / HEADER RUANG BELAJAR                           -->
    <!-- ============================================================== -->
    <header class="sticky top-0 z-40 bg-white border-b border-slate-200 shadow-xs">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
            
            <!-- Sisi Kiri: Tombol Kembali & Judul Kelas -->
            <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                <a 
                    href="<?= base_url('dashboard/course/' . $course['id']) ?>" 
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 hover:text-slate-900 rounded-lg transition-colors shrink-0"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Daftar Sesi</span>
                </a>

                <div class="h-5 w-px bg-slate-200 hidden sm:block shrink-0"></div>

                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-upskill-pink bg-pink-50 px-2 py-0.5 rounded border border-pink-100 shrink-0">
                            Sesi <?= esc($currentIndex ?? 1) ?> dari <?= count($allSessions ?? []) ?>
                        </span>
                        <h1 class="text-xs sm:text-sm font-bold text-upskill-darkblue truncate">
                            <?= esc($course['title'] ?? 'Program Pelatihan') ?>
                        </h1>
                    </div>
                </div>
            </div>

            <!-- Sisi Kanan: Status Akun Peserta -->
            <div class="flex items-center gap-3 shrink-0">
                <div class="hidden md:flex flex-col text-right">
                    <span class="text-xs font-bold text-slate-800"><?= esc($user_name ?? 'Peserta') ?></span>
                    <span class="text-[10px] text-slate-500">Siswa Aktif</span>
                </div>
                <div class="w-8 h-8 rounded-full bg-pink-100 text-upskill-pink border border-pink-200 flex items-center justify-center font-bold text-xs">
                    <?= esc(strtoupper(substr($user_name ?? 'P', 0, 1))) ?>
                </div>
            </div>
        </div>
    </header>

    <!-- ============================================================== -->
    <!-- 2. KONTEN RUANG BELAJAR BERSIH & MODERN (Tahap 20)             -->
    <!-- ============================================================== -->
    <main class="flex-1 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 w-full flex flex-col justify-center">
        
        <!-- Breadcrumb Navigasi -->
        <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6">
            <a href="<?= base_url('dashboard') ?>" class="hover:text-upskill-pink transition-colors">Dasbor</a>
            <span>/</span>
            <a href="<?= base_url('dashboard/course/' . $course['id']) ?>" class="hover:text-upskill-pink transition-colors truncate max-w-[200px]">
                <?= esc($course['title']) ?>
            </a>
            <span>/</span>
            <span class="text-upskill-darkblue font-bold truncate max-w-[250px]"><?= esc($session['chapter_title']) ?></span>
        </nav>

        <!-- Kartu Utama Ruang Belajar (macOS Aesthetic) -->
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
            
            <!-- Header macOS 3 Titik -->
            <div class="flex items-center justify-between px-5 py-3.5 bg-slate-50/90 border-b border-slate-100">
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500 inline-block"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500 inline-block"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span>
                </div>
                <span class="text-[11px] font-mono font-semibold text-slate-400 uppercase tracking-wider">
                    Sesi Pembelajaran <?= esc($currentIndex ?? 1) ?>
                </span>
            </div>

            <!-- Konten Kartu -->
            <div class="p-6 sm:p-10 text-center space-y-6">

                <!-- Badge Tipe Materi -->
                <div class="flex items-center justify-center gap-2">
                    <?php if ($session['content_type'] === 'video'): ?>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-upskill-blue border border-blue-200">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Materi Video Pembelajaran
                        </span>
                    <?php else: ?>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Materi Dokumen / Teks / PDF
                        </span>
                    <?php endif; ?>

                    <?php if (!empty($isCompleted)): ?>
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Materi Selesai 100%
                        </span>
                    <?php endif; ?>
                </div>

                <!-- Judul Sesi -->
                <div class="space-y-2 max-w-2xl mx-auto">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-upskill-darkblue tracking-tight leading-tight">
                        <?= esc($session['chapter_title']) ?>
                    </h2>
                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed pt-1">
                        Materi pembelajaran untuk sesi ini telah disediakan oleh instruktur. Silakan klik tombol di bawah untuk membuka dan mempelajari modul secara lengkap.
                    </p>
                </div>

                <!-- Informasi Backend Tracking (Transparan & Jelas) -->
                <div class="max-w-lg mx-auto bg-slate-50 rounded-xl p-4 border border-slate-200 text-xs text-slate-500 flex items-start gap-3 text-left">
                    <div class="w-5 h-5 rounded-full bg-blue-100 text-upskill-blue flex items-center justify-center shrink-0 mt-0.5 font-bold">
                        i
                    </div>
                    <div class="space-y-1">
                        <p class="font-semibold text-slate-700">Pelacakan Progres Otomatis:</p>
                        <p>
                            Saat Anda mengeklik tombol <strong>"Buka Dokumen Materi / Video"</strong>, sistem secara otomatis menandai progres materi ini <strong>selesai 100%</strong> dan membuka kunci Kuis Evaluasi.
                        </p>
                    </div>
                </div>

                <!-- ====================================================== -->
                <!-- TOMBOL UTAMA (CTA) & TOMBOL SEKUNDER (Tahap 20)        -->
                <!-- ====================================================== -->
                <div class="pt-4 flex flex-col items-center justify-center gap-3.5 max-w-md mx-auto">
                    
                    <!-- 1. Tombol Utama (CTA): Besar, Mencolok, bg-upskill-blue, target="_blank" -->
                    <a 
                        href="<?= base_url('dashboard/access_material/' . $session['id']) ?>" 
                        target="_blank" 
                        rel="noopener noreferrer"
                        class="w-full py-4 px-8 rounded-xl font-bold text-base sm:text-lg text-white bg-upskill-blue hover:bg-upskill-darkblue shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-3 transform active:scale-98"
                    >
                        <?php if ($session['content_type'] === 'video'): ?>
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        <?php else: ?>
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        <?php endif; ?>
                        <span>Buka Dokumen Materi / Video</span>
                        <svg class="w-4 h-4 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>

                    <!-- 2. Tombol Sekunder: Kembali ke Halaman Detail Kelas (Accordion) -->
                    <a 
                        href="<?= base_url('dashboard/course/' . $course['id']) ?>" 
                        class="w-full py-3 px-6 rounded-xl font-semibold text-xs sm:text-sm text-slate-700 bg-white hover:bg-slate-100 border border-slate-300 transition-colors flex items-center justify-center gap-2 shadow-2xs"
                    >
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span>Kembali ke Daftar Sesi</span>
                    </a>

                    <!-- Jika sudah pernah diakses: Tombol Opsional Langsung ke Kuis Evaluasi -->
                    <?php if (!empty($isCompleted)): ?>
                        <div class="w-full pt-3 border-t border-slate-100">
                            <a 
                                href="<?= base_url('dashboard/quiz/' . $session['id']) ?>" 
                                class="w-full py-3 px-6 rounded-xl font-bold text-xs sm:text-sm text-white bg-upskill-pink hover:bg-upskill-magenta transition-colors flex items-center justify-center gap-2 shadow-xs"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Kuis Evaluasi Telah Terbuka (Mulai Kuis)</span>
                            </a>
                        </div>
                    <?php endif; ?>

                </div>

            </div>

        </div>

    </main>

    <!-- Footer Ruang Belajar -->
    <footer class="py-4 text-center text-xs text-slate-400">
        &copy; <?= date('Y') ?> Muslim Upskill Academy. Menuntut Ilmu dengan Amanah.
    </footer>

</body>
</html>
