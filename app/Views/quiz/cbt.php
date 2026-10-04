<!DOCTYPE html>
<html lang="id" class="h-full bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'CBT Ujian - MUSLIM UPSKILL ACADEMY') ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/logo.png') ?>">
    <link rel="shortcut icon" href="<?= base_url('favicon.ico') ?>">
    
    <!-- Google Fonts: Inter & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: {
              sans: ['Inter', 'sans-serif'],
              mono: ['JetBrains Mono', 'monospace'],
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

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Anti-Cheat: Cegah seleksi teks saat ujian */
        body {
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }
    </style>
</head>

<body class="bg-white min-h-screen flex flex-col font-sans text-slate-800" oncontextmenu="return false;" oncopy="return false;" oncut="return false;" onpaste="return false;">

    <!-- ============================================================== -->
    <!-- 1. TOP BAR MINIMALIS (TAHAP 29)                                -->
    <!-- ============================================================== -->
    <header class="sticky top-0 z-30 bg-white border-b border-slate-200 px-6 py-3.5 flex justify-between items-center">
        <!-- Kiri: Nama Kelas & Sesi secara Ringkas -->
        <div class="text-sm font-semibold text-slate-600 truncate mr-4">
            <span><?= esc($course['title']) ?></span>
            <span class="mx-1.5 text-slate-300">&bull;</span>
            <span class="text-slate-800 font-bold"><?= esc($session['chapter_title'] ?? $session['title']) ?></span>
        </div>

        <!-- Kanan: Kotak Timer Minimalis -->
        <div class="border border-slate-200 bg-white px-4 py-1.5 rounded-lg shadow-2xs shrink-0 flex items-center gap-2">
            <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span id="countdown-timer" class="font-mono text-sm font-bold text-rose-600">
                --:--
            </span>
        </div>
    </header>

    <!-- ============================================================== -->
    <!-- 2. STRUKTUR LAYOUT UTAMA (2 KOLOM DICODING EXAM STYLE)         -->
    <!-- ============================================================== -->
    <?php if (empty($questions)): ?>
        <!-- Keadaan Kosong (Empty State) -->
        <div class="min-h-[calc(100vh-61px)] flex items-center justify-center p-6 bg-white">
            <div class="max-w-md w-full text-center p-8 border border-slate-200 rounded-2xl shadow-sm">
                <div class="w-14 h-14 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-4">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-800">Kuis Belum Memiliki Soal</h3>
                <p class="text-xs text-slate-500 mt-1 mb-6">
                    Pengajar belum menambahkan butir soal evaluasi untuk sesi pembelajaran ini.
                </p>
                <a href="<?= base_url('dashboard/course/' . $session['course_id']) ?>" class="inline-block px-5 py-2.5 rounded-xl bg-slate-900 text-white font-bold text-xs hover:bg-slate-800 transition-colors">
                    Kembali ke Detail Kelas
                </a>
            </div>
        </div>
    <?php else: ?>

        <div class="min-h-[calc(100vh-61px)] bg-white flex flex-col md:flex-row">
            
            <!-- Kolom Kiri: Sidebar Navigasi Nomor Soal -->
            <aside class="w-full md:w-72 border-b md:border-b-0 md:border-r border-slate-200 p-6 shrink-0 bg-white">
                <div class="text-sm font-bold text-slate-800 pb-4 mb-4 border-b border-slate-100 truncate">
                    Soal kategori: <?= esc($session['chapter_title'] ?? $session['title'] ?? 'Evaluasi') ?>
                </div>

                <!-- Grid Nomor Soal -->
                <div class="grid grid-cols-5 gap-2.5">
                    <?php foreach ($questions as $index => $q): ?>
                        <button 
                            type="button" 
                            id="nav-btn-<?= $index ?>" 
                            onclick="goToQuestion(<?= $index ?>)"
                            class="w-10 h-10 rounded-lg border border-slate-200 bg-white text-slate-700 font-semibold text-sm flex items-center justify-center hover:border-slate-400 transition-all cursor-pointer select-none <?= $index === 0 ? 'ring-2 ring-slate-800 border-slate-800 font-bold' : '' ?>"
                        >
                            <?= $index + 1 ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </aside>

            <!-- Kolom Kanan: Area Soal Aktif (Single-Question View) -->
            <main class="flex-1 p-6 md:p-12 lg:px-20 max-w-4xl bg-white">
                <form id="form-kuis" action="<?= base_url('sessions/' . $session['id'] . '/cbt/submit') ?>" method="post">
                    <?= csrf_field() ?>

                    <!-- Daftar Soal (Hanya 1 Soal Tampil Sekaligus) -->
                    <?php foreach ($questions as $index => $q): ?>
                        <div 
                            class="question-slide <?= $index === 0 ? '' : 'hidden' ?>" 
                            data-index="<?= $index ?>" 
                            id="question-slide-<?= $index ?>"
                        >
                            <!-- Teks Pertanyaan (Bersih tanpa nomor di depannya) -->
                            <p class="text-slate-800 text-base md:text-lg font-normal leading-relaxed mb-6 whitespace-pre-line">
                                <?= esc($q['question_text']) ?>
                            </p>

                            <!-- Opsi Pilihan Ganda -->
                            <?php if (($q['question_type'] ?? 'pg') === 'pg'): ?>
                                <div class="space-y-3.5">
                                    <?php foreach (['a' => 'option_a', 'b' => 'option_b', 'c' => 'option_c', 'd' => 'option_d'] as $key => $optField): ?>
                                        <?php if (!empty($q[$optField])): ?>
                                            <label class="flex items-start gap-3.5 p-3 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors group border border-transparent hover:border-slate-200">
                                                <input 
                                                    type="radio" 
                                                    name="answers[<?= $q['id'] ?>]" 
                                                    value="<?= $key ?>" 
                                                    onchange="markAnswered(<?= $index ?>)" 
                                                    class="mt-1 w-4 h-4 text-upskill-pink border-slate-300 focus:ring-upskill-pink cursor-pointer shrink-0"
                                                >
                                                <span class="text-slate-700 text-sm md:text-base group-hover:text-slate-900 leading-snug">
                                                    <?= esc($q[$optField]) ?>
                                                </span>
                                            </label>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <!-- Input Jawaban Essay -->
                                <div class="mt-4">
                                    <textarea 
                                        name="answers[<?= $q['id'] ?>]" 
                                        oninput="markAnswered(<?= $index ?>)" 
                                        rows="5" 
                                        class="w-full rounded-xl border border-slate-200 p-4 text-slate-700 focus:border-slate-800 focus:ring-0 outline-none transition-colors" 
                                        placeholder="Ketik jawaban uraian Anda di sini..."
                                    ></textarea>
                                </div>
                            <?php endif; ?>

                        </div>
                    <?php endforeach; ?>

                    <!-- Footer Navigasi Soal -->
                    <div class="border-t border-slate-100 pt-6 mt-10 flex justify-between items-center">
                        <!-- Tombol Sebelumnya -->
                        <button 
                            type="button" 
                            id="btn-prev" 
                            onclick="prevQuestion()" 
                            disabled
                            class="flex items-center gap-2 text-sm font-medium text-slate-400 hover:text-slate-800 disabled:opacity-40 disabled:pointer-events-none transition-colors cursor-pointer"
                        >
                            <span class="w-7 h-7 rounded-full border border-slate-200 flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </span>
                            <span>Sebelumnya</span>
                        </button>

                        <!-- Tombol Selanjutnya & Selesaikan -->
                        <div class="flex items-center">
                            <button 
                                type="button" 
                                id="btn-next" 
                                onclick="nextQuestion()" 
                                class="flex items-center gap-2 text-sm font-semibold text-slate-700 hover:text-slate-900 transition-colors cursor-pointer"
                            >
                                <span>Selanjutnya</span>
                                <span class="w-7 h-7 rounded-full border border-slate-200 flex items-center justify-center shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </span>
                            </button>

                            <button 
                                type="button" 
                                id="btn-submit-exam" 
                                onclick="confirmSubmission()" 
                                class="hidden bg-upskill-pink hover:bg-pink-600 text-white font-bold text-sm px-6 py-2.5 rounded-xl shadow-xs transition-all cursor-pointer"
                            >
                                Selesaikan & Kumpulkan
                            </button>
                        </div>
                    </div>

                </form>
            </main>

        </div>

    <?php endif; ?>

    <!-- ============================================================== -->
    <!-- 3. JAVASCRIPT NAVIGASI, TIMER & ANTI-CHEAT                     -->
    <!-- ============================================================== -->
    <script>
        const totalQuestions = <?= count($questions) ?>;
        let currentIndex = 0;

        // -------------------------------------------------------------
        // Navigasi Pindah Soal (Single-Question View)
        // -------------------------------------------------------------
        function goToQuestion(index) {
            if (index < 0 || index >= totalQuestions) return;

            // 1. Sembunyikan slide lama, munculkan slide tujuan
            const currentSlide = document.getElementById('question-slide-' + currentIndex);
            const targetSlide  = document.getElementById('question-slide-' + index);

            if (currentSlide) currentSlide.classList.add('hidden');
            if (targetSlide) targetSlide.classList.remove('hidden');

            // 2. Update ring indikator nomor aktif di sidebar
            const currentNavBtn = document.getElementById('nav-btn-' + currentIndex);
            const targetNavBtn  = document.getElementById('nav-btn-' + index);

            if (currentNavBtn) {
                currentNavBtn.classList.remove('ring-2', 'ring-slate-800', 'font-bold');
            }
            if (targetNavBtn) {
                targetNavBtn.classList.add('ring-2', 'ring-slate-800', 'font-bold');
            }

            currentIndex = index;

            // 3. Update Tombol Prev (disabled jika di soal pertama)
            const btnPrev = document.getElementById('btn-prev');
            if (btnPrev) {
                btnPrev.disabled = (currentIndex === 0);
            }

            // 4. Update Tombol Next & Submit (jika di soal terakhir)
            const btnNext   = document.getElementById('btn-next');
            const btnSubmit = document.getElementById('btn-submit-exam');

            if (currentIndex === totalQuestions - 1) {
                if (btnNext) btnNext.classList.add('hidden');
                if (btnSubmit) btnSubmit.classList.remove('hidden');
            } else {
                if (btnNext) btnNext.classList.remove('hidden');
                if (btnSubmit) btnSubmit.classList.add('hidden');
            }
        }

        function nextQuestion() {
            if (currentIndex < totalQuestions - 1) {
                goToQuestion(currentIndex + 1);
            }
        }

        function prevQuestion() {
            if (currentIndex > 0) {
                goToQuestion(currentIndex - 1);
            }
        }

        // Tandai tombol nomor kuis jika sudah dijawab
        function markAnswered(index) {
            const slide  = document.getElementById('question-slide-' + index);
            const navBtn = document.getElementById('nav-btn-' + index);
            if (!slide || !navBtn) return;

            const checkedRadio = slide.querySelector('input[type="radio"]:checked');
            const textarea     = slide.querySelector('textarea');
            const hasText      = textarea && textarea.value.trim().length > 0;

            if (checkedRadio || hasText) {
                navBtn.classList.add('bg-slate-800', 'text-white', 'border-slate-800');
                navBtn.classList.remove('bg-white', 'text-slate-700', 'border-slate-200');
            } else {
                navBtn.classList.remove('bg-slate-800', 'text-white', 'border-slate-800');
                navBtn.classList.add('bg-white', 'text-slate-700', 'border-slate-200');
            }
        }

        // -------------------------------------------------------------
        // Timer Hitung Mundur CBT
        // -------------------------------------------------------------
        const durationMinutes = <?= (int) $duration ?>;
        let totalSeconds = durationMinutes * 60;
        const timerElement = document.getElementById('countdown-timer');

        function updateTimer() {
            if (totalSeconds <= 0) {
                if (timerElement) timerElement.textContent = "00m:00s";
                
                Swal.fire({
                    title: 'Waktu Ujian Habis!',
                    text: 'Waktu pengerjaan telah selesai. Jawaban Anda otomatis dikumpulkan oleh sistem.',
                    icon: 'info',
                    timer: 2500,
                    showConfirmButton: false,
                    allowOutsideClick: false
                }).then(() => {
                    const form = document.getElementById('form-kuis');
                    if (form) form.submit();
                });
                return;
            }

            const minutes = Math.floor(totalSeconds / 60);
            const seconds = totalSeconds % 60;

            const formattedMin = String(minutes).padStart(2, '0');
            const formattedSec = String(seconds).padStart(2, '0');

            if (timerElement) {
                timerElement.textContent = `${formattedMin}m:${formattedSec}s`;
            }

            totalSeconds--;
            setTimeout(updateTimer, 1000);
        }

        // Inisialisasi saat DOM siap
        document.addEventListener('DOMContentLoaded', () => {
            if (totalQuestions > 0) {
                goToQuestion(0);
            }
            if (durationMinutes > 0) {
                updateTimer();
            } else if (timerElement) {
                timerElement.textContent = "Tak Terbatas";
            }
        });

        // -------------------------------------------------------------
        // Deteksi Pindah Tab (Anti-Cheat) via Page Visibility API
        // -------------------------------------------------------------
        let cheatCount = 0;
        document.addEventListener("visibilitychange", () => {
            if (document.hidden) {
                cheatCount++;
                if (cheatCount > 3) {
                    Swal.fire({
                        title: 'Diskualifikasi!',
                        text: 'Anda terlalu sering keluar dari halaman ujian.',
                        icon: 'error',
                        confirmButtonText: 'Kumpulkan Sekarang',
                        confirmButtonColor: '#FA1886',
                        allowOutsideClick: false
                    }).then(() => {
                        const form = document.getElementById('form-kuis');
                        if (form) form.submit();
                    });
                } else {
                    Swal.fire({
                        title: 'Peringatan!',
                        text: 'Dilarang membuka tab/aplikasi lain selama ujian berlangsung. Peringatan: ' + cheatCount + '/3',
                        icon: 'warning',
                        confirmButtonText: 'Kembali Ujian',
                        confirmButtonColor: '#FA1886',
                        allowOutsideClick: false
                    });
                }
            }
        });

        // -------------------------------------------------------------
        // Konfirmasi Pengumpulan Manual
        // -------------------------------------------------------------
        function confirmSubmission() {
            Swal.fire({
                title: 'Kumpulkan Jawaban?',
                text: 'Pastikan Anda telah memeriksa semua jawaban pilihan ganda maupun essay.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#FA1886',
                cancelButtonColor: '#64748B',
                confirmButtonText: 'Ya, Kumpulkan Sekarang',
                cancelButtonText: 'Periksa Kembali'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('form-kuis');
                    if (form) form.submit();
                }
            });
        }
    </script>
</body>
</html>
