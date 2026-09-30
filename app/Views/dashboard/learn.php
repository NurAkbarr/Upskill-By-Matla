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
        /* Custom scrollbar untuk silabus */
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 flex flex-col">

    <!-- ============================================================== -->
    <!-- 1. TOP NAVBAR / HEADER RUANG BELAJAR                           -->
    <!-- ============================================================== -->
    <header class="sticky top-0 z-40 bg-white border-b border-slate-200 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
            
            <!-- Sisi Kiri: Tombol Kembali & Judul Kelas -->
            <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                <a href="<?= base_url('dashboard') ?>" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 hover:text-slate-900 rounded-lg transition-colors shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span class="hidden sm:inline">Dasbor</span>
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
                    <span class="text-[10px] text-slate-500">Siswa Terdaftar</span>
                </div>
                <div class="w-8 h-8 rounded-full bg-pink-100 text-upskill-pink border border-pink-200 flex items-center justify-center font-bold text-xs">
                    <?= esc(strtoupper(substr($user_name ?? 'P', 0, 1))) ?>
                </div>
            </div>
        </div>
    </header>

    <!-- ============================================================== -->
    <!-- 2. KONTEN UTAMA: RUANG BELAJAR & SILABUS                      -->
    <!-- ============================================================== -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex-1 w-full">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- ========================================================== -->
            <!-- KOLOM KIRI (LEBAR): AREA MATERI & TOMBOL KUIS              -->
            <!-- ========================================================== -->
            <main class="lg:col-span-8 space-y-6">

                <!-- Breadcrumb Navigasi -->
                <nav class="flex items-center gap-2 text-xs text-slate-500">
                    <a href="<?= base_url('dashboard') ?>" class="hover:text-upskill-pink transition-colors">Dasbor</a>
                    <span>/</span>
                    <span class="text-slate-400 truncate max-w-[200px]"><?= esc($course['title'] ?? 'Kelas') ?></span>
                    <span>/</span>
                    <span class="text-upskill-darkblue font-bold truncate max-w-[250px]"><?= esc($session['chapter_title']) ?></span>
                </nav>

                <!-- Judul Sesi & Metadata -->
                <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-7 shadow-xs">
                    <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <?php if ($session['content_type'] === 'video'): ?>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-upskill-blue border border-blue-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Materi Video
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Teks / PDF Pembelajaran
                                </span>
                            <?php endif; ?>

                            <?php if (!empty($quizCount)): ?>
                                <span class="inline-flex items-center gap-1 text-xs font-medium text-slate-500 bg-slate-100 px-2.5 py-1 rounded-full">
                                    <svg class="w-3 h-3 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    <?= (int) $quizCount ?> Soal Evaluasi
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Info Durasi Sesi / Kuis -->
                        <?php if (!empty($session['quiz_duration_minutes'])): ?>
                            <div class="text-xs text-slate-500 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Durasi Kuis: <?= (int) $session['quiz_duration_minutes'] ?> Menit</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <h2 class="text-xl sm:text-2xl font-extrabold text-upskill-darkblue mt-4 tracking-tight">
                        <?= esc($session['chapter_title']) ?>
                    </h2>
                </div>

                <!-- Banner Informasi UX Pengamanan Materi -->
                <div id="ux-notice" class="rounded-xl p-4 bg-amber-50 border border-amber-200 text-amber-800 text-xs sm:text-sm flex items-start gap-3 shadow-xs">
                    <div class="p-1 rounded-md bg-amber-100 text-amber-600 shrink-0 mt-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <strong class="font-bold block mb-0.5 text-amber-900">Proteksi Kehadiran & Progres Belajar</strong>
                        <?php if ($session['content_type'] === 'video'): ?>
                            <span>Tombol Kuis Evaluasi di bagian bawah masih <strong>terkunci</strong>. Anda wajib memutar dan menonton video materi ini hingga selesai agar tombol evaluasi terbuka otomatis.</span>
                        <?php else: ?>
                            <span>Tombol Kuis Evaluasi di bagian bawah masih <strong>terkunci</strong>. Anda wajib membaca isi materi (scroll hingga minimal 95%) dan meluangkan waktu membaca minimal 60 detik.</span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- ====================================================== -->
                <!-- AREA KONTEN MATERI (VIDEO / TEKS-PDF)                  -->
                <!-- ====================================================== -->
                <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-xs">
                    
                    <?php if ($session['content_type'] === 'video'): ?>
                        <!-- 1. KONTEN VIDEO (YouTube IFrame API) -->
                        <div class="space-y-4">
                            <!-- Container <div> kosong dengan id player untuk YouTube API -->
                            <div class="w-full aspect-video rounded-xl overflow-hidden shadow-md bg-slate-900 flex items-center justify-center">
                                <div id="player" class="w-full h-full"></div>
                            </div>

                            <!-- Indikator Live Status Pemutaran Video -->
                            <div class="flex items-center justify-between text-xs text-slate-500 px-1 pt-2">
                                <div class="flex items-center gap-2">
                                    <span id="video-dot" class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-pulse"></span>
                                    <span id="video-status-text" class="font-medium text-slate-600">Video belum dimulai. Klik play untuk memulai sesi.</span>
                                </div>
                                <span class="text-[11px] text-slate-400 hidden sm:inline">YouTube Official Player</span>
                            </div>
                        </div>

                    <?php else: ?>
                        <!-- 2. KONTEN TEKS / PDF (Scrollable Container) -->
                        <div class="space-y-6">
                            
                            <!-- Status Panel Pelacak Bacaan (Timer + Scroll) -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                                <!-- Indikator Waktu Membaca -->
                                <div>
                                    <div class="flex items-center justify-between mb-1.5 font-semibold text-slate-700">
                                        <span class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-upskill-pink" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Waktu Membaca Minimal
                                        </span>
                                        <span id="timer-display" class="font-mono text-upskill-pink font-bold">0 / 60 dtk</span>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                                        <div id="timer-progress" class="bg-upskill-pink h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                                    </div>
                                </div>

                                <!-- Indikator Scroll Dokumen -->
                                <div>
                                    <div class="flex items-center justify-between mb-1.5 font-semibold text-slate-700">
                                        <span class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-upskill-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                            </svg>
                                            Scroll Hingga Akhir (>95%)
                                        </span>
                                        <span id="scroll-display" class="font-mono text-upskill-blue font-bold">0%</span>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                                        <div id="scroll-progress" class="bg-upskill-blue h-2 rounded-full transition-all duration-200" style="width: 0%"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Kontainer Teks yang bisa di-scroll -->
                            <div id="text-material-container" class="prose max-w-none text-slate-700 leading-relaxed text-sm sm:text-base border-t border-slate-100 pt-6">
                                <?php 
                                    $rawContent = $session['content_url_or_text'] ?? '';
                                    $isUrl = filter_var($rawContent, FILTER_VALIDATE_URL);
                                ?>

                                <?php if ($isUrl): ?>
                                    <!-- Jika konten berupa URL Dokumen/PDF/Drive -->
                                    <div class="mb-6 p-5 rounded-xl bg-blue-50/70 border border-blue-200 text-slate-700">
                                        <div class="flex items-start gap-3">
                                            <div class="w-10 h-10 rounded-lg bg-upskill-blue text-white flex items-center justify-center shrink-0">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-sm font-bold text-upskill-darkblue">Tautan Dokumen Pembelajaran</h4>
                                                <p class="text-xs text-slate-600 mt-0.5 truncate"><?= esc($rawContent) ?></p>
                                                <div class="mt-3">
                                                    <a href="<?= esc($rawContent) ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-bold text-white bg-upskill-blue hover:bg-upskill-darkblue rounded-lg transition-colors shadow-xs">
                                                        <span>Buka Dokumen di Tab Baru</span>
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- Isi Teks Pembelajaran Lengkap -->
                                <div class="space-y-4 text-justify">
                                    <?php if (!empty($rawContent) && !$isUrl): ?>
                                        <?= nl2br(esc($rawContent)) ?>
                                    <?php elseif (empty($rawContent) || $isUrl): ?>
                                        <h3 class="text-lg font-bold text-upskill-darkblue not-prose">Panduan & Catatan Modul Pembelajaran</h3>
                                        <p>
                                            Selamat datang di sesi pembelajaran <strong><?= esc($session['chapter_title']) ?></strong>. Modul ini dirancang secara komprehensif untuk memberikan pemahaman teoritis sekaligus studi kasus praktis yang dapat langsung Anda implementasikan dalam dunia industri.
                                        </p>
                                        <p>
                                            Silakan cermati setiap bagian materi dengan saksama. Bacalah keseluruhan panduan ini hingga ke bagian paling bawah untuk memastikan seluruh konsep esensial telah Anda pahami dengan tuntas. Sistem pelacak kehadiran mengharuskan Anda meluangkan waktu membaca minimal 60 detik serta menyelesaikan scroll halaman sebelum tombol evaluasi kuis terbuka.
                                        </p>
                                        <h4 class="text-base font-bold text-slate-800 not-prose pt-2">Poin-Poin Kunci Pembelajaran:</h4>
                                        <ul class="list-disc pl-5 space-y-2 text-slate-600">
                                            <li><strong>Pemahaman Fondasi:</strong> Menguasai konsep dasar arsitektur modul dan implementasi alur kerja terstruktur.</li>
                                            <li><strong>Standar Industri:</strong> Menerapkan metodologi terkini yang relevan dengan kebutuhan praktisi profesional.</li>
                                            <li><strong>Studi Kasus Nyata:</strong> Menyelesaikan tantangan aplikatif dengan solusi efisien dan berorientasi hasil.</li>
                                            <li><strong>Evaluasi Mandiri:</strong> Menguji pemahaman Anda melalui kuis CBT di akhir sesi ini.</li>
                                        </ul>
                                        <p class="pt-2">
                                            Setelah Anda menuntaskan seluruh bacaan ini dan timer 60 detik terpenuhi, tombol <em>Mulai Kuis Evaluasi</em> di bawah akan berubah warna menjadi merah muda aktif dan dapat diklik untuk memulai ujian pemahaman Anda.
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>

                <!-- ====================================================== -->
                <!-- TOMBOL KUIS EVALUASI (TERKUNCI SECARA DEFAULT)         -->
                <!-- ====================================================== -->
                <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-7 shadow-xs">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h3 class="text-sm font-bold text-upskill-darkblue">Kuis Evaluasi Pemahaman</h3>
                            <p id="kuis-help-text" class="text-xs text-slate-500 mt-0.5">
                                <?php if ($session['content_type'] === 'video'): ?>
                                    Tonton video sampai tuntas untuk membuka kunci kuis.
                                <?php else: ?>
                                    Baca materi selama 60 detik dan scroll sampai bawah untuk membuka kunci.
                                <?php endif; ?>
                            </p>
                        </div>

                        <!-- Tombol Kuis (Terkunci Default) Sesuai Spesifikasi Instruksi -->
                        <div class="shrink-0">
                            <button id="btn-kuis" disabled class="bg-gray-400 cursor-not-allowed text-white px-6 py-3 rounded-lg font-bold text-sm shadow-xs flex items-center justify-center gap-2">
                                <svg id="lock-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <span id="btn-kuis-text">Mulai Kuis Evaluasi</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Navigasi Antar Sesi (Sebelumnya / Selanjutnya) -->
                <div class="flex items-center justify-between pt-2">
                    <?php if ($prevSession): ?>
                        <a href="<?= base_url('dashboard/learn/' . $prevSession['id']) ?>" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-600 hover:text-upskill-pink px-4 py-2 rounded-lg border border-slate-200 bg-white hover:border-slate-300 transition-colors shadow-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            <span>Sesi Sebelumnya</span>
                        </a>
                    <?php else: ?>
                        <div></div>
                    <?php endif; ?>

                    <?php if ($nextSession): ?>
                        <a href="<?= base_url('dashboard/learn/' . $nextSession['id']) ?>" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-600 hover:text-upskill-pink px-4 py-2 rounded-lg border border-slate-200 bg-white hover:border-slate-300 transition-colors shadow-xs">
                            <span>Sesi Selanjutnya</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    <?php endif; ?>
                </div>

            </main>

            <!-- ========================================================== -->
            <!-- KOLOM KANAN: SILABUS KURIKULUM KELAS                       -->
            <!-- ========================================================== -->
            <aside class="lg:col-span-4 space-y-6">

                <!-- Ringkasan Kelas -->
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Program Pelatihan</h3>
                    <h4 class="text-base font-extrabold text-upskill-darkblue leading-snug">
                        <?= esc($course['title'] ?? 'Nama Kelas') ?>
                    </h4>

                    <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                        <span>Total Sesi: <strong class="text-slate-800"><?= count($allSessions ?? []) ?> Sesi</strong></span>
                        <span>Sesi Aktif: <strong class="text-upskill-pink">#<?= esc($currentIndex ?? 1) ?></strong></span>
                    </div>
                </div>

                <!-- Daftar Silabus Sesi -->
                <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xs">
                    <div class="p-4 sm:p-5 bg-slate-50/70 border-b border-slate-200 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-upskill-darkblue flex items-center gap-2">
                            <svg class="w-4 h-4 text-upskill-pink" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                            </svg>
                            <span>Daftar Sesi Pembelajaran</span>
                        </h3>
                        <span class="text-[11px] font-bold text-slate-400 font-mono"><?= count($allSessions ?? []) ?> Modul</span>
                    </div>

                    <div class="divide-y divide-slate-100 max-h-[500px] overflow-y-auto custom-scrollbar">
                        <?php if (!empty($allSessions)): ?>
                            <?php foreach ($allSessions as $idx => $s): ?>
                                <?php 
                                    $isCurrent = ((int) $s['id'] === (int) $session['id']);
                                ?>
                                <a 
                                    href="<?= base_url('dashboard/learn/' . $s['id']) ?>" 
                                    class="p-4 flex items-start gap-3 transition-colors <?= $isCurrent ? 'bg-pink-50/80 border-l-4 border-upskill-pink' : 'hover:bg-slate-50' ?>"
                                >
                                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold shrink-0 mt-0.5 <?= $isCurrent ? 'bg-upskill-pink text-white' : 'bg-slate-100 text-slate-600' ?>">
                                        <?= $idx + 1 ?>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-semibold leading-snug <?= $isCurrent ? 'text-upskill-pink font-bold' : 'text-slate-800' ?>">
                                            <?= esc($s['chapter_title']) ?>
                                        </p>
                                        <div class="flex items-center gap-2 mt-1 text-[11px] text-slate-400">
                                            <?php if ($s['content_type'] === 'video'): ?>
                                                <span class="flex items-center gap-1 text-blue-600">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                                    </svg>
                                                    Video
                                                </span>
                                            <?php else: ?>
                                                <span class="flex items-center gap-1 text-emerald-600">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                    Teks/PDF
                                                </span>
                                            <?php endif; ?>

                                            <?php if ($isCurrent): ?>
                                                <span class="font-bold text-upskill-pink">&bull; Sedang Dibuka</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="p-6 text-center text-xs text-slate-400">
                                Belum ada daftar sesi kurikulum.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </aside>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- 3. LOGIKA JAVASCRIPT PELACAK PROGRESS & PEMBUKA KUNCI         -->
    <!-- ============================================================== -->
    <script>
    // 5. Fungsi Pembuka Kunci (unlockQuizButton)
    function unlockQuizButton() {
        const btn = document.getElementById('btn-kuis');
        if (!btn || !btn.disabled) return;

        btn.disabled = false;
        btn.className = "bg-upskill-pink hover:bg-upskill-magenta text-white px-6 py-3 rounded-lg transition-colors cursor-pointer font-bold text-sm shadow-md flex items-center justify-center gap-2 transform active:scale-95";
        
        // Tambahkan aksi onclick untuk redirect ke URL Kuis CBT
        btn.onclick = () => window.location.href = '<?= base_url("dashboard/quiz/" . $session["id"]) ?>';

        // Update teks dan ikon tombol
        const btnText = document.getElementById('btn-kuis-text');
        if (btnText) btnText.textContent = "Mulai Kuis Evaluasi (Terbuka)";
        
        const lockIcon = document.getElementById('lock-icon');
        if (lockIcon) {
            lockIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>';
        }

        const helpText = document.getElementById('kuis-help-text');
        if (helpText) {
            helpText.innerHTML = '<span class="text-emerald-600 font-bold">✓ Syarat materi terpenuhi! Silakan klik tombol untuk memulai evaluasi.</span>';
        }

        const uxNotice = document.getElementById('ux-notice');
        if (uxNotice) {
            uxNotice.className = "rounded-xl p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-start gap-3 shadow-xs";
            uxNotice.innerHTML = `
                <div class="p-1 rounded-md bg-emerald-100 text-emerald-600 shrink-0 mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <strong class="font-bold block mb-0.5 text-emerald-900">Materi Selesai!</strong>
                    <span>Selamat, Anda telah menyelesaikan materi sesi ini. Tombol Kuis Evaluasi di bawah telah dibuka.</span>
                </div>
            `;
        }
    }
    </script>

    <?php if ($session['content_type'] === 'video'): ?>
        <!-- ============================================================== -->
        <!-- 3. JAVASCRIPT PELACAK VIDEO (YOUTUBE API)                     -->
        <!-- ============================================================== -->
        <script>
        // Asumsi variabel videoId sudah di-extract dari URL YouTube
        var tag = document.createElement('script');
        tag.src = "https://www.youtube.com/iframe_api";
        var firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

        var player;
        function onYouTubeIframeAPIReady() {
            player = new YT.Player('player', {
                height: '390', width: '100%', videoId: '<?= esc($videoId) ?>',
                events: { 'onStateChange': onPlayerStateChange }
            });
        }
        function onPlayerStateChange(event) {
            const statusText = document.getElementById('video-status-text');
            const videoDot = document.getElementById('video-dot');

            if (event.data == YT.PlayerState.PLAYING) {
                if (statusText) statusText.textContent = "Sedang memutar video... Tonton hingga akhir.";
                if (videoDot) videoDot.className = "w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse";
            } else if (event.data == YT.PlayerState.PAUSED) {
                if (statusText) statusText.textContent = "Video dijeda.";
                if (videoDot) videoDot.className = "w-2.5 h-2.5 rounded-full bg-amber-400";
            } else if (event.data == YT.PlayerState.ENDED) {
                if (statusText) statusText.textContent = "Video selesai ditonton! Kuis evaluasi telah dibuka.";
                if (videoDot) videoDot.className = "w-2.5 h-2.5 rounded-full bg-emerald-600";
                unlockQuizButton();
            }
        }
        </script>
    <?php else: ?>
        <!-- ============================================================== -->
        <!-- 4. JAVASCRIPT PELACAK TEKS (SCROLL + TIMER DELAY)              -->
        <!-- ============================================================== -->
        <script>
        let timeSpent = 0;
        let isScrolled = false;
        let requiredTime = 60; // Minimal 60 detik baca

        // Timer
        setInterval(() => { 
            timeSpent++; 
            updateTimerDisplay();
            checkProgress(); 
        }, 1000);

        // Scroll Tracker
        window.addEventListener('scroll', () => {
            let scrollTop = window.scrollY;
            let docHeight = document.body.offsetHeight;
            let winHeight = window.innerHeight;
            let scrollableHeight = docHeight - winHeight;
            let scrollPercent = scrollableHeight > 0 ? (scrollTop / scrollableHeight) : 1;
            
            updateScrollDisplay(scrollPercent);

            if(scrollPercent > 0.95) { 
                isScrolled = true; 
                checkProgress(); 
            }
        });

        // Cek inisial saat halaman dimuat (jika halaman pendek & tidak butuh scroll)
        window.addEventListener('DOMContentLoaded', () => {
            let docHeight = document.body.offsetHeight;
            let winHeight = window.innerHeight;
            if (docHeight <= winHeight + 50) {
                isScrolled = true;
                updateScrollDisplay(1);
            }
        });

        function updateTimerDisplay() {
            const timerDisplay = document.getElementById('timer-display');
            const timerProgress = document.getElementById('timer-progress');
            if (timerDisplay) {
                timerDisplay.textContent = Math.min(timeSpent, requiredTime) + ' / ' + requiredTime + ' dtk';
            }
            if (timerProgress) {
                const percent = Math.min(100, Math.round((timeSpent / requiredTime) * 100));
                timerProgress.style.width = percent + '%';
            }
        }

        function updateScrollDisplay(percent) {
            const scrollDisplay = document.getElementById('scroll-display');
            const scrollProgress = document.getElementById('scroll-progress');
            const pctClamped = Math.min(100, Math.max(0, Math.round(percent * 100)));
            if (scrollDisplay) {
                scrollDisplay.textContent = pctClamped + '%';
            }
            if (scrollProgress) {
                scrollProgress.style.width = pctClamped + '%';
            }
        }

        function checkProgress() {
            if(timeSpent >= requiredTime && isScrolled) { 
                unlockQuizButton(); 
            }
        }
        </script>
    <?php endif; ?>

</body>
</html>
