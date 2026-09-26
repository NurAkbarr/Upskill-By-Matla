<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'CBT Ujian - MUSLIM UPSKILL ACADEMY') ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/logo.png') ?>">
    <link rel="shortcut icon" href="<?= base_url('favicon.ico') ?>">
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@600;700&display=swap" rel="stylesheet">
    
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
        /* Efek fokus lembut pada kartu pilihan */
        .option-card:hover {
            border-color: #FA1886;
        }
        .option-radio:checked + .option-box {
            border-color: #FA1886;
            background-color: rgba(250, 24, 134, 0.05);
        }
        .option-radio:checked + .option-box .badge-letter {
            background-color: #FA1886;
            color: #ffffff;
        }
    </style>
</head>

<body class="bg-slate-100 min-h-screen flex flex-col font-sans text-slate-800" oncontextmenu="return false;" oncopy="return false;" oncut="return false;" onpaste="return false;">

    <!-- ============================================================== -->
    <!-- TOPBAR CBT: TIMER & STATUS KEAMANAN                            -->
    <!-- ============================================================== -->
    <header class="sticky top-0 z-50 bg-slate-900 text-white shadow-md border-b border-slate-800 px-4 sm:px-8 py-3.5">
        <div class="max-w-6xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-3">
            
            <!-- Info Kelas & Sesi -->
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-upskill-pink to-upskill-magenta flex items-center justify-center font-bold text-white text-sm shadow-xs shrink-0">
                    CBT
                </div>
                <div class="min-w-0">
                    <h1 class="text-sm font-bold text-white truncate">
                        <?= esc($session['chapter_title']) ?>
                    </h1>
                    <p class="text-xs text-slate-400 truncate">
                        <?= esc($course['title']) ?> &bull; <?= esc($studentName) ?>
                    </p>
                </div>
            </div>

            <!-- Status Anti-Cheat & Timer -->
            <div class="flex items-center justify-between sm:justify-end gap-4 w-full sm:w-auto">
                
                <!-- Indikator Anti-Cheat -->
                <div class="hidden md:flex items-center gap-2 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700 text-[11px] text-emerald-400">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span class="font-medium tracking-wide">Anti-Cheat Terproteksi</span>
                </div>

                <!-- Timer Hitung Mundur Prominen -->
                <div class="flex items-center gap-2.5 bg-slate-800 px-3.5 py-1.5 rounded-xl border border-slate-700 shadow-inner">
                    <svg class="w-4 h-4 text-upskill-pink animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-right">
                        <span class="text-[9px] uppercase tracking-wider text-slate-400 block font-semibold leading-none">Sisa Waktu</span>
                        <span id="countdown-timer" class="font-mono text-base font-bold text-white tracking-widest leading-none mt-0.5 block">
                            --:--
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </header>

    <!-- ============================================================== -->
    <!-- KONTEN UTAMA: LEMBAR SOAL UJIAN CBT                            -->
    <!-- ============================================================== -->
    <main class="flex-1 max-w-4xl w-full mx-auto p-4 sm:p-6 lg:p-8">
        
        <?php if (empty($questions)): ?>
            <!-- State Belum Ada Soal -->
            <div class="bg-white rounded-2xl border border-slate-200 p-10 text-center shadow-xs">
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
                    <a href="<?= base_url('courses') ?>" class="px-5 py-2.5 rounded-xl bg-slate-900 text-white font-bold text-xs hover:bg-slate-800 transition-colors">
                        Kembali ke Katalog
                    </a>
                </div>
            </div>
        <?php else: ?>

            <form id="form-kuis" action="<?= base_url('sessions/' . $session['id'] . '/cbt/submit') ?>" method="POST" class="space-y-6">
                <?= csrf_field() ?>

                <!-- Banner Informasi Ujian -->
                <div class="bg-gradient-to-r from-upskill-darkblue to-slate-900 text-white p-5 rounded-2xl shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-white/10 text-pink-300 mb-1 border border-white/10">
                            Lembar Jawaban Peserta
                        </span>
                        <h2 class="text-base font-bold">Jawab Seluruh Soal dengan Teliti</h2>
                        <p class="text-xs text-slate-300 mt-0.5">
                            Jumlah Soal: <strong><?= count($questions) ?> Butir</strong> &bull; Durasi: <strong><?= esc($duration) ?> Menit</strong>
                        </p>
                    </div>
                    <div class="text-xs text-slate-300 bg-white/5 border border-white/10 p-3 rounded-xl max-w-xs">
                        <strong class="text-pink-300 block mb-0.5">Tata Tertib CBT:</strong>
                        Membuka tab baru atau berpindah aplikasi lebih dari 3 kali akan menyebabkan ujian otomatis terkumpul.
                    </div>
                </div>

                <!-- Loop Daftar Butir Soal -->
                <div class="space-y-5">
                    <?php foreach ($questions as $idx => $q): ?>
                        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-7 shadow-xs space-y-4">
                            
                            <!-- Header Butir Soal -->
                            <div class="flex items-start gap-3">
                                <span class="w-8 h-8 rounded-xl bg-slate-900 text-white font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                                    <?= $idx + 1 ?>
                                </span>
                                <div class="flex-1">
                                    <div class="mb-1">
                                        <?php if (($q['question_type'] ?? 'pg') === 'essay'): ?>
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-amber-100 text-amber-800 border border-amber-200">
                                                Tipe: Soal Essay (Uraian)
                                            </span>
                                        <?php else: ?>
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-sky-100 text-sky-800 border border-sky-200">
                                                Tipe: Pilihan Ganda (PG)
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <p class="text-sm sm:text-base font-semibold text-slate-800 leading-relaxed whitespace-pre-line">
                                        <?= esc($q['question_text']) ?>
                                    </p>
                                </div>
                            </div>

                            <!-- Opsi Jawaban (Pilihan Ganda) -->
                            <?php if (($q['question_type'] ?? 'pg') === 'pg'): ?>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                                    
                                    <!-- Pilihan A -->
                                    <label class="cursor-pointer relative block">
                                        <input type="radio" name="answers[<?= $q['id'] ?>]" value="a" class="sr-only option-radio">
                                        <div class="option-box flex items-center gap-3 p-3.5 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-slate-50 transition-all">
                                            <span class="badge-letter w-7 h-7 rounded-lg bg-white border border-slate-300 text-slate-700 font-bold text-xs flex items-center justify-center shrink-0 transition-colors shadow-2xs">
                                                A
                                            </span>
                                            <span class="text-xs sm:text-sm text-slate-700 flex-1 leading-snug">
                                                <?= esc($q['option_a']) ?>
                                            </span>
                                        </div>
                                    </label>

                                    <!-- Pilihan B -->
                                    <label class="cursor-pointer relative block">
                                        <input type="radio" name="answers[<?= $q['id'] ?>]" value="b" class="sr-only option-radio">
                                        <div class="option-box flex items-center gap-3 p-3.5 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-slate-50 transition-all">
                                            <span class="badge-letter w-7 h-7 rounded-lg bg-white border border-slate-300 text-slate-700 font-bold text-xs flex items-center justify-center shrink-0 transition-colors shadow-2xs">
                                                B
                                            </span>
                                            <span class="text-xs sm:text-sm text-slate-700 flex-1 leading-snug">
                                                <?= esc($q['option_b']) ?>
                                            </span>
                                        </div>
                                    </label>

                                    <!-- Pilihan C -->
                                    <label class="cursor-pointer relative block">
                                        <input type="radio" name="answers[<?= $q['id'] ?>]" value="c" class="sr-only option-radio">
                                        <div class="option-box flex items-center gap-3 p-3.5 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-slate-50 transition-all">
                                            <span class="badge-letter w-7 h-7 rounded-lg bg-white border border-slate-300 text-slate-700 font-bold text-xs flex items-center justify-center shrink-0 transition-colors shadow-2xs">
                                                C
                                            </span>
                                            <span class="text-xs sm:text-sm text-slate-700 flex-1 leading-snug">
                                                <?= esc($q['option_c']) ?>
                                            </span>
                                        </div>
                                    </label>

                                    <!-- Pilihan D -->
                                    <label class="cursor-pointer relative block">
                                        <input type="radio" name="answers[<?= $q['id'] ?>]" value="d" class="sr-only option-radio">
                                        <div class="option-box flex items-center gap-3 p-3.5 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-slate-50 transition-all">
                                            <span class="badge-letter w-7 h-7 rounded-lg bg-white border border-slate-300 text-slate-700 font-bold text-xs flex items-center justify-center shrink-0 transition-colors shadow-2xs">
                                                D
                                            </span>
                                            <span class="text-xs sm:text-sm text-slate-700 flex-1 leading-snug">
                                                <?= esc($q['option_d']) ?>
                                            </span>
                                        </div>
                                    </label>

                                </div>
                            <?php else: ?>
                                <!-- Opsi Jawaban (Essay) -->
                                <div class="pt-2">
                                    <textarea 
                                        name="answers[<?= $q['id'] ?>]" 
                                        rows="4" 
                                        placeholder="Ketik jawaban essay Anda di sini secara lengkap..."
                                        class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-upskill-pink/20 focus:border-upskill-pink transition-all bg-slate-50/50 focus:bg-white resize-y"
                                    ></textarea>
                                </div>
                            <?php endif; ?>

                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Tombol Submit Ujian -->
                <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-xs text-slate-500 text-center sm:text-left">
                        Pastikan semua soal telah Anda jawab sebelum mengklik tombol selesai.
                    </div>
                    <button 
                        type="button" 
                        onclick="confirmSubmission()"
                        class="w-full sm:w-auto px-7 py-3 rounded-xl font-bold text-sm text-white bg-gradient-to-r from-upskill-pink to-upskill-magenta hover:opacity-95 transition-all shadow-md shadow-pink-500/20 active:scale-[0.99] cursor-pointer"
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
                    timerElement.classList.add('text-rose-400');
                    timerElement.classList.remove('text-white');
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
