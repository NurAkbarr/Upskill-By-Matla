<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Ruang Belajar - MUSLIM UPSKILL ACADEMY') ?></title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: {
              sans: ['"Plus Jakarta Sans"', 'sans-serif'],
            },
            colors: {
              'upskill-darkblue': '#0A1128',
              'upskill-blue': '#1C21AC',
              'upskill-pink': '#FF385C',
              'upskill-magenta': '#B103C5',
            }
          }
        }
      }
    </script>
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-800 antialiased flex flex-col">

    <!-- ============================================================== -->
    <!-- 1. TOP NAVBAR / HEADER RUANG BELAJAR                           -->
    <!-- ============================================================== -->
    <header class="sticky top-0 z-40 bg-white border-b border-slate-200 shadow-2xs">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
            
            <!-- Sisi Kiri: Tombol Kembali & Info Kelas -->
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
    <!-- 2. KONTEN RUANG BELAJAR (EMBED LANGSUNG + PENGAWAS PROGRES)    -->
    <!-- ============================================================== -->
    <main class="flex-1 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 w-full flex flex-col space-y-6">
        
        <!-- Breadcrumb Navigasi -->
        <nav class="flex items-center gap-2 text-xs text-slate-500">
            <a href="<?= base_url('dashboard') ?>" class="hover:text-upskill-pink transition-colors">Dasbor</a>
            <span>/</span>
            <a href="<?= base_url('dashboard/course/' . $course['id']) ?>" class="hover:text-upskill-pink transition-colors truncate max-w-[200px]">
                <?= esc($course['title']) ?>
            </a>
            <span>/</span>
            <span class="text-upskill-darkblue font-bold truncate max-w-[250px]"><?= esc($session['chapter_title']) ?></span>
        </nav>

        <!-- Kartu Header Materi -->
        <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="space-y-1.5 min-w-0">
                <div class="flex items-center gap-2">
                    <?php if ($session['content_type'] === 'video'): ?>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                            <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                            </svg>
                            Video Pembelajaran
                        </span>
                    <?php else: ?>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Dokumen / PDF
                        </span>
                    <?php endif; ?>

                    <span id="badge-status-completed" class="<?= empty($isCompleted) ? 'hidden' : 'inline-flex' ?> items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Materi Selesai 100%
                    </span>
                </div>
                <h2 class="text-lg sm:text-xl font-extrabold text-upskill-darkblue tracking-tight leading-snug">
                    Sesi <?= esc($currentIndex ?? 1) ?>: <?= esc($session['chapter_title']) ?>
                </h2>
            </div>

            <!-- Petunjuk Singkat Pengawas Progres -->
            <div class="sm:text-right shrink-0">
                <span class="text-xs text-slate-500 block">
                    <?php if ($session['content_type'] === 'video'): ?>
                        Pengawas: <strong>YouTube API</strong> (Wajib tuntas)
                    <?php else: ?>
                        Pengawas: <strong>Timer Baca Ketat</strong> (3 Menit)
                    <?php endif; ?>
                </span>
            </div>
        </div>

        <!-- Peringatan Jika Tab Ditinggalkan (Visibility API) -->
        <div id="timer-warning" class="hidden p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs flex items-center gap-2.5 animate-pulse">
            <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <span><strong>Timer Dijeda Sementara:</strong> Anda sedang beralih ke jendela/tab lain. Mohon kembali aktif pada halaman materi ini agar hitung mundur pembukaan kuis dilanjutkan.</span>
        </div>

        <!-- Banner Sukses Membuka Kuis -->
        <div id="unlocked-banner" class="<?= empty($isCompleted) ? 'hidden' : '' ?> p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-center justify-between gap-3 shadow-2xs">
            <div class="flex items-center gap-2.5">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span><strong>Selamat!</strong> Anda telah menyelesaikan materi sesi ini. Kuis evaluasi telah terbuka dan siap dikerjakan.</span>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- AREA RENDER MATERI LANGSUNG (EMBED)                            -->
        <!-- ============================================================== -->
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xs">
            
            <!-- Header macOS 3 Titik -->
            <div class="flex items-center justify-between px-5 py-3 bg-slate-50/90 border-b border-slate-100">
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500 inline-block"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500 inline-block"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span>
                </div>
                <span class="text-[11px] font-mono font-semibold text-slate-400 uppercase tracking-wider">
                    Ruang Belajar Interaktif
                </span>
            </div>

            <!-- Konten Embed Sesuai Tipe Materi -->
            <div class="p-4 sm:p-6 bg-slate-50/40">
                <?php if ($session['content_type'] === 'video'): ?>
                    <!-- 1. Tipe Video: YouTube Player Container -->
                    <div class="w-full aspect-video rounded-xl overflow-hidden bg-black shadow-md border border-slate-200 relative">
                        <div id="player" class="w-full h-full"></div>
                    </div>
                    <p class="text-xs text-slate-500 mt-2.5 text-center">
                        Tonton video materi hingga tuntas. Sistem akan secara otomatis membuka kunci kuis evaluasi ketika video selesai.
                    </p>
                <?php else: ?>
                    <!-- 2. Tipe Dokumen / Teks / PDF -->
                    <?php if (!empty($embedUrl) && (str_starts_with($embedUrl, 'http://') || str_starts_with($embedUrl, 'https://'))): ?>
                        <!-- Embed Dokumen via Iframe (Google Drive preview / PDF URL) -->
                        <div class="w-full h-[580px] sm:h-[720px] rounded-xl overflow-hidden border border-slate-200 bg-white shadow-2xs">
                            <iframe 
                                src="<?= esc($embedUrl) ?>" 
                                class="w-full h-full border-0" 
                                allow="autoplay"
                                loading="lazy"
                            ></iframe>
                        </div>
                    <?php else: ?>
                        <!-- Plain Text Document Container -->
                        <div class="w-full min-h-[380px] max-h-[600px] overflow-y-auto p-6 sm:p-8 rounded-xl border border-slate-200 bg-white text-slate-800 leading-relaxed text-sm shadow-2xs">
                            <?= nl2br(esc($session['content_url_or_text'] ?? 'Materi belum tersedia.')) ?>
                        </div>
                    <?php endif; ?>
                    <p class="text-xs text-slate-500 mt-2.5 text-center">
                        Pelajari dan baca materi di atas dengan seksama. Tombol kuis akan aktif secara otomatis setelah waktu baca terpenuhi.
                    </p>
                <?php endif; ?>
            </div>

            <!-- ============================================================== -->
            <!-- 3. FOOTER KARTU & TOMBOL KUIS EVALUASI (Tahap 22)             -->
            <!-- ============================================================== -->
            <div class="p-5 sm:p-6 bg-white border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                
                <!-- Navigasi Kembali -->
                <a 
                    href="<?= base_url('dashboard/course/' . $course['id']) ?>" 
                    class="w-full sm:w-auto px-4 py-3 rounded-xl font-semibold text-xs text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors flex items-center justify-center gap-2 shrink-0"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Kembali ke Kurikulum</span>
                </a>

                <!-- Tombol Kuis Evaluasi (Terkunci Bawaan atau Aktif jika Sudah Selesai) -->
                <div class="w-full sm:max-w-md">
                    <?php if (!empty($isCompleted)): ?>
                        <!-- Status: Sudah Selesai (Bebas Akses Kuis) -->
                        <button 
                            id="btn-quiz" 
                            onclick="window.location.href='<?= base_url('dashboard/quiz/' . $session['id']) ?>'"
                            class="w-full bg-upskill-pink text-white font-bold py-3.5 px-6 rounded-xl hover:bg-pink-600 transition-all flex items-center justify-center gap-2 shadow-sm cursor-pointer"
                        >
                            <span>Mulai Kuis Sekarang</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    <?php else: ?>
                        <!-- Status: Terkunci (Diawasi oleh JS Tracker & Dibuka via AJAX) -->
                        <button 
                            id="btn-quiz" 
                            disabled 
                            class="w-full bg-slate-200 text-slate-400 font-bold py-3.5 px-6 rounded-xl cursor-not-allowed transition-all flex items-center justify-center gap-2 shadow-2xs select-none"
                        >
                            <?php if ($session['content_type'] === 'video'): ?>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <span>Tonton Video Sampai Selesai untuk Membuka Kuis</span>
                            <?php else: ?>
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-400 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                    </svg>
                                    <span id="countdown-label">Kuis terbuka dalam 03:00</span>
                                </span>
                            <?php endif; ?>
                        </button>
                    <?php endif; ?>
                </div>

            </div>

        </div>

    </main>

    <!-- Footer Ruang Belajar -->
    <footer class="py-6 text-center text-xs text-slate-400">
        &copy; <?= date('Y') ?> Muslim Upskill Academy. Menuntut Ilmu dengan Amanah.
    </footer>

    <!-- ============================================================== -->
    <!-- 4. LOGIKA JAVASCRIPT: PENGAWAS PROGRES & AJAX UNLOCK KUIS       -->
    <!-- ============================================================== -->
    <script>
    // Flag status apakah materi telah diselesaikan
    const isAlreadyCompleted = <?= !empty($isCompleted) ? 'true' : 'false' ?>;
    const contentType        = '<?= esc($session['content_type']) ?>';
    const markCompletedUrl   = '<?= base_url("dashboard/mark_completed/" . $session["id"]) ?>';
    const quizUrl            = '<?= base_url("dashboard/quiz/" . $session["id"]) ?>';
    const csrfToken          = '<?= csrf_token() ?>';
    const csrfHash           = '<?= csrf_hash() ?>';

    let isUnlocking = false;

    /**
     * Fungsi AJAX untuk membuka kunci kuis di backend secara aman
     */
    function unlockQuiz() {
        if (isUnlocking || isAlreadyCompleted) return;
        isUnlocking = true;

        const btn = document.getElementById('btn-quiz');
        if (btn) {
            btn.innerHTML = `
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 animate-spin text-slate-500" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span>Membuka Akses Kuis...</span>
                </span>
            `;
        }

        // Panggil backend secara diam-diam
        fetch(markCompletedUrl, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                [csrfToken]: csrfHash
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = '<span>Mulai Kuis Sekarang</span> <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>';
                    btn.className = 'w-full bg-upskill-pink text-white font-bold py-3.5 px-6 rounded-xl hover:bg-pink-600 transition-all flex items-center justify-center gap-2 shadow-sm cursor-pointer';
                    btn.onclick = () => window.location.href = quizUrl;
                }

                // Tampilkan banner sukses
                const banner = document.getElementById('unlocked-banner');
                if (banner) banner.classList.remove('hidden');

                const badge = document.getElementById('badge-status-completed');
                if (badge) {
                    badge.classList.remove('hidden');
                    badge.classList.add('inline-flex');
                }
            } else {
                isUnlocking = false;
                if (btn) {
                    btn.innerHTML = 'Gagal membuka kuis. Silakan coba lagi.';
                }
            }
        })
        .catch(err => {
            console.error('Error unlocking quiz:', err);
            isUnlocking = false;
        });
    }

    // Inisialisasi Pengawas Progres berdasarkan Tipe Konten
    if (!isAlreadyCompleted) {
        
        // -------------------------------------------------------------
        // A. PENGADAAN UNTUK PDF / TEKS (TIMER KETAT 180 DETIK)
        // -------------------------------------------------------------
        if (contentType !== 'video') {
            let timeLeft = 180; // 3 menit
            let isTabActive = !document.hidden;
            const warningEl = document.getElementById('timer-warning');

            // Visibility API: Jeda timer jika peserta berpindah tab
            document.addEventListener('visibilitychange', function() {
                isTabActive = !document.hidden;
                if (warningEl) {
                    if (document.hidden && timeLeft > 0) {
                        warningEl.classList.remove('hidden');
                    } else {
                        warningEl.classList.add('hidden');
                    }
                }
            });

            function formatCountdown(sec) {
                const m = Math.floor(sec / 60);
                const s = sec % 60;
                return (m < 10 ? '0' + m : m) + ':' + (s < 10 ? '0' + s : s);
            }

            const countdownInterval = setInterval(function() {
                if (isTabActive && timeLeft > 0) {
                    timeLeft--;
                    const label = document.getElementById('countdown-label');
                    if (label) {
                        label.innerText = 'Kuis terbuka dalam ' + formatCountdown(timeLeft);
                    }

                    if (timeLeft <= 0) {
                        clearInterval(countdownInterval);
                        if (warningEl) warningEl.classList.add('hidden');
                        unlockQuiz();
                    }
                }
            }, 1000);
        }
    }
    </script>

    <?php if ($session['content_type'] === 'video' && !empty($videoId)): ?>
        <!-- ------------------------------------------------------------- -->
        <!-- B. PENGADAAN UNTUK VIDEO (YOUTUBE IFRAME API)                 -->
        <!-- ------------------------------------------------------------- -->
        <script src="https://www.youtube.com/iframe_api"></script>
        <script>
        let ytPlayer;

        function onYouTubeIframeAPIReady() {
            ytPlayer = new YT.Player('player', {
                videoId: '<?= esc($videoId) ?>',
                playerVars: {
                    'playsinline': 1,
                    'rel': 0,
                    'modestbranding': 1
                },
                events: {
                    'onStateChange': onPlayerStateChange
                }
            });
        }

        function onPlayerStateChange(event) {
            // YT.PlayerState.ENDED bernilai 0
            if (event.data === YT.PlayerState.ENDED) {
                unlockQuiz();
            }
        }
        </script>
    <?php endif; ?>

</body>
</html>
