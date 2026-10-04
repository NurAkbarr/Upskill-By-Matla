<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
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
        /* Aktifkan badge huruf saat opsi terpilih */
        label:has(input:checked) .badge-letter {
            background-color: #FA1886;
            color: #ffffff;
            border-color: #FA1886;
        }
    </style>
</head>

<body class="bg-slate-50 min-h-screen flex flex-col font-sans text-slate-800" oncontextmenu="return false;" oncopy="return false;" oncut="return false;" onpaste="return false;">

    <!-- ============================================================== -->
    <!-- 1. STICKY HEADER PUTIH BERSIH (TAHAP 27)                       -->
    <!-- ============================================================== -->
    <header class="sticky top-0 z-30 bg-white/90 backdrop-blur-md border-b border-slate-200 px-6 py-4">
        <div class="max-w-4xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
            
            <!-- Bagian Kiri: Badge CBT, Judul Sesi & Nama Kelas -->
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <span class="bg-pink-50 text-upskill-pink font-extrabold px-3 py-1.5 rounded-xl border border-pink-100 text-xs shrink-0 tracking-wider">
                    CBT
                </span>
                <div class="min-w-0">
                    <h1 class="font-bold text-slate-800 text-sm sm:text-base truncate">
                        <?= esc($session['chapter_title']) ?>
                    </h1>
                    <p class="text-xs text-slate-500 truncate">
                        <?= esc($course['title']) ?> &bull; <?= esc($studentName) ?>
                    </p>
                </div>
            </div>

            <!-- Bagian Kanan: Tampilan Sisa Waktu (Timer Elegan) -->
            <div class="bg-slate-50 border border-slate-200 px-4 py-2 rounded-xl flex items-center gap-2.5 self-end sm:self-auto shrink-0 shadow-2xs">
                <svg class="w-4 h-4 text-upskill-pink shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block leading-none">
                        SISA WAKTU
                    </span>
                    <span id="countdown-timer" class="text-lg font-extrabold text-upskill-pink font-mono tracking-wider leading-none mt-0.5 block">
                        --:--
                    </span>
                </div>
            </div>

        </div>
    </header>

    <!-- ============================================================== -->
    <!-- KONTEN UTAMA: LEMBAR SOAL CBT                                  -->
    <!-- ============================================================== -->
    <main class="flex-1 max-w-4xl w-full mx-auto p-4 sm:p-6 lg:p-8">
        
        <?php if (empty($questions)): ?>
            <!-- State Belum Ada Soal -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-10 text-center shadow-sm">
                <div class="w-16 h-16 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Kuis Belum Memiliki Soal</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1 leading-relaxed">
                    Pengajar belum menambahkan butir soal evaluasi untuk sesi pembelajaran ini.
                </p>
                <div class="mt-6">
                    <a href="<?= base_url('dashboard/course/' . $session['course_id']) ?>" class="px-5 py-2.5 rounded-xl bg-slate-900 text-white font-bold text-xs hover:bg-slate-800 transition-colors">
                        Kembali ke Detail Kelas
                    </a>
                </div>
            </div>
        <?php else: ?>

            <form id="form-kuis" action="<?= base_url('sessions/' . $session['id'] . '/cbt/submit') ?>" method="POST" class="space-y-6">
                <?= csrf_field() ?>

                <!-- ============================================================== -->
                <!-- 2. BANNER INSTRUKSI RINGKAS (TAHAP 27)                         -->
                <!-- ============================================================== -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 mb-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Lembar Evaluasi Peserta</h2>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Jumlah Soal: <strong><?= count($questions) ?> Butir</strong> &bull; Durasi: <strong><?= esc($duration) ?> Menit</strong>
                        </p>
                    </div>
                    <div class="bg-amber-50/70 border border-amber-200/60 text-amber-800 text-xs px-4 py-2.5 rounded-xl flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span><strong>Perhatian:</strong> Dilarang berpindah tab/aplikasi selama ujian berlangsung agar jawaban tidak terkumpul otomatis.</span>
                    </div>
                </div>

                <!-- ============================================================== -->
                <!-- 3. KARTU BUTIR SOAL KUIS (PG & ESSAY) (TAHAP 27)               -->
                <!-- ============================================================== -->
                <div>
                    <?php foreach ($questions as $idx => $q): ?>
                        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 md:p-8 shadow-sm mb-6 transition-all hover:border-slate-300">
                            
                            <!-- Header Butir Soal: Nomor & Tipe -->
                            <div class="flex items-center gap-2.5">
                                <span class="w-8 h-8 rounded-lg bg-slate-900 text-white font-bold text-sm flex items-center justify-center shrink-0">
                                    <?= $idx + 1 ?>
                                </span>
                                <?php if (($q['question_type'] ?? 'pg') === 'essay'): ?>
                                    <span class="bg-slate-100 text-slate-600 text-[11px] font-semibold px-2.5 py-1 rounded-md uppercase tracking-wide">
                                        Soal Essay / Uraian
                                    </span>
                                <?php else: ?>
                                    <span class="bg-slate-100 text-slate-600 text-[11px] font-semibold px-2.5 py-1 rounded-md uppercase tracking-wide">
                                        Pilihan Ganda
                                    </span>
                                <?php endif; ?>
                            </div>

                            <!-- Teks Pertanyaan -->
                            <p class="text-slate-800 font-semibold text-base md:text-lg leading-relaxed my-4 whitespace-pre-line">
                                <?= esc($q['question_text']) ?>
                            </p>

                            <!-- Pilihan Ganda -->
                            <?php if (($q['question_type'] ?? 'pg') === 'pg'): ?>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                                    
                                    <!-- Opsi A -->
                                    <?php if (!empty($q['option_a'])): ?>
                                        <label class="flex items-center gap-3.5 p-4 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50 transition-all has-[:checked]:border-upskill-pink has-[:checked]:bg-pink-50/30 has-[:checked]:ring-2 has-[:checked]:ring-pink-500/10">
                                            <input type="radio" name="answers[<?= $q['id'] ?>]" value="a" class="sr-only">
                                            <span class="badge-letter w-7 h-7 rounded-lg bg-slate-100 text-slate-700 font-bold text-xs flex items-center justify-center shrink-0 border border-slate-200/80 uppercase transition-colors">
                                                A
                                            </span>
                                            <span class="text-sm text-slate-700 flex-1 leading-snug">
                                                <?= esc($q['option_a']) ?>
                                            </span>
                                        </label>
                                    <?php endif; ?>

                                    <!-- Opsi B -->
                                    <?php if (!empty($q['option_b'])): ?>
                                        <label class="flex items-center gap-3.5 p-4 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50 transition-all has-[:checked]:border-upskill-pink has-[:checked]:bg-pink-50/30 has-[:checked]:ring-2 has-[:checked]:ring-pink-500/10">
                                            <input type="radio" name="answers[<?= $q['id'] ?>]" value="b" class="sr-only">
                                            <span class="badge-letter w-7 h-7 rounded-lg bg-slate-100 text-slate-700 font-bold text-xs flex items-center justify-center shrink-0 border border-slate-200/80 uppercase transition-colors">
                                                B
                                            </span>
                                            <span class="text-sm text-slate-700 flex-1 leading-snug">
                                                <?= esc($q['option_b']) ?>
                                            </span>
                                        </label>
                                    <?php endif; ?>

                                    <!-- Opsi C -->
                                    <?php if (!empty($q['option_c'])): ?>
                                        <label class="flex items-center gap-3.5 p-4 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50 transition-all has-[:checked]:border-upskill-pink has-[:checked]:bg-pink-50/30 has-[:checked]:ring-2 has-[:checked]:ring-pink-500/10">
                                            <input type="radio" name="answers[<?= $q['id'] ?>]" value="c" class="sr-only">
                                            <span class="badge-letter w-7 h-7 rounded-lg bg-slate-100 text-slate-700 font-bold text-xs flex items-center justify-center shrink-0 border border-slate-200/80 uppercase transition-colors">
                                                C
                                            </span>
                                            <span class="text-sm text-slate-700 flex-1 leading-snug">
                                                <?= esc($q['option_c']) ?>
                                            </span>
                                        </label>
                                    <?php endif; ?>

                                    <!-- Opsi D -->
                                    <?php if (!empty($q['option_d'])): ?>
                                        <label class="flex items-center gap-3.5 p-4 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50 transition-all has-[:checked]:border-upskill-pink has-[:checked]:bg-pink-50/30 has-[:checked]:ring-2 has-[:checked]:ring-pink-500/10">
                                            <input type="radio" name="answers[<?= $q['id'] ?>]" value="d" class="sr-only">
                                            <span class="badge-letter w-7 h-7 rounded-lg bg-slate-100 text-slate-700 font-bold text-xs flex items-center justify-center shrink-0 border border-slate-200/80 uppercase transition-colors">
                                                D
                                            </span>
                                            <span class="text-sm text-slate-700 flex-1 leading-snug">
                                                <?= esc($q['option_d']) ?>
                                            </span>
                                        </label>
                                    <?php endif; ?>

                                </div>
                            <?php else: ?>
                                <!-- Input Jawaban Essay -->
                                <div class="pt-1">
                                    <textarea 
                                        name="answers[<?= $q['id'] ?>]" 
                                        rows="4" 
                                        placeholder="Ketik jawaban essay Anda di sini secara lengkap..."
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50/50 p-4 text-sm text-slate-700 focus:bg-white focus:border-upskill-pink focus:ring-4 focus:ring-pink-500/10 transition-all outline-none resize-y"
                                    ></textarea>
                                </div>
                            <?php endif; ?>

                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- ============================================================== -->
                <!-- 4. FOOTER PENGUMPULAN (SUBMIT BAR) (TAHAP 27)                  -->
                <!-- ============================================================== -->
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-xs text-slate-500 text-center sm:text-left flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Pastikan semua soal telah Anda jawab dan periksa sebelum mengumpulkan ujian.</span>
                    </div>
                    <button 
                        type="button" 
                        onclick="confirmSubmission()"
                        class="w-full sm:w-auto bg-upskill-pink hover:bg-pink-600 text-white font-bold text-sm px-7 py-3.5 rounded-xl shadow-sm hover:shadow transition-all cursor-pointer"
                    >
                        Selesaikan & Kumpulkan Ujian
                    </button>
                </div>

            </form>

        <?php endif; ?>

    </main>

    <!-- ============================================================== -->
    <!-- JAVASCRIPT MESIN CBT: TIMER & ANTI-CHEAT                        -->
    <!-- ============================================================== -->
    <script>
        // -------------------------------------------------------------
        // 1. Timer Hitung Mundur CBT
        // -------------------------------------------------------------
        const durationMinutes = <?= (int) $duration ?>;
        let totalSeconds = durationMinutes * 60;
        const timerElement = document.getElementById('countdown-timer');

        function updateTimer() {
            if (totalSeconds <= 0) {
                if (timerElement) timerElement.textContent = "00:00";
                
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
                timerElement.textContent = `${formattedMin}:${formattedSec}`;
                
                // Berikan warna merah jika waktu kurang dari 5 menit
                if (totalSeconds < 300) {
                    timerElement.classList.add('text-rose-500');
                    timerElement.classList.remove('text-upskill-pink');
                }
            }

            totalSeconds--;
            setTimeout(updateTimer, 1000);
        }

        // Jalankan timer saat halaman selesai dimuat
        document.addEventListener('DOMContentLoaded', () => {
            if (durationMinutes > 0) {
                updateTimer();
            } else if (timerElement) {
                timerElement.textContent = "Tak Terbatas";
            }
        });

        // -------------------------------------------------------------
        // 2. Deteksi Pindah Tab (Anti-Cheat) via Page Visibility API
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
        // 3. Konfirmasi Pengumpulan Manual
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
