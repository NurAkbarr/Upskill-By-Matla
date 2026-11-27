<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Quiz - MUSLIM UPSKILL ACADEMY') ?></title>

    <link rel="icon" type="image/png" href="<?= base_url('assets/images/logo.png') ?>">
    <link rel="shortcut icon" href="<?= base_url('favicon.ico') ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: {
              sans: ['Inter', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
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
    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        .option-card {
            transition: border-color 0.18s ease, background-color 0.18s ease, box-shadow 0.18s ease;
        }
        .option-card:has(input:checked) {
            border-color: #FA1886;
            background-color: #FDF2F8;
            box-shadow: 0 1px 4px rgba(250, 24, 134, 0.12);
        }
        .option-card:has(input:checked) .option-badge {
            background-color: #FA1886;
            border-color: #FA1886;
            color: #FFFFFF;
        }
        .option-card:has(input:checked) .option-text {
            color: #0F172A;
            font-weight: 600;
        }
        .option-card:has(input:checked) .option-check {
            opacity: 1;
            transform: scale(1);
        }
    </style>
</head>
<body class="min-h-full flex flex-col bg-slate-50 text-slate-900 selection:bg-pink-100 selection:text-upskill-pink">

    <?php
        $totalQuestions  = count($questions);
        $durationMinutes = (int) ($quiz['duration_minutes'] ?? 0);
        $isTrialQuiz     = ((int) ($quiz['is_trial'] ?? 0) === 1);
        $durationDisplay = $durationMinutes > 0 ? $durationMinutes . ' menit' : 'Tanpa batas waktu';
        $initialPct      = $totalQuestions > 0 ? (int) round((1 / $totalQuestions) * 100) : 0;
    ?>

    <!-- Top Navbar Konsisten dengan Tema Upskill -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-sm border-b border-slate-200/80">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-3">
            <!-- Kiri: Logo -->
            <a href="<?= base_url('/') ?>" class="flex items-center gap-2 shrink-0 group">
                <img src="<?= base_url('assets/images/logo-removebg.png') ?>" alt="Muslim Upskill Academy" class="h-9 sm:h-10 w-auto object-contain transition-transform duration-200 group-hover:scale-105">
            </a>

            <!-- Tengah: Navigasi Ringkas Desktop -->
            <nav class="hidden md:flex items-center gap-2 text-sm font-semibold">
                <a href="<?= base_url('/') ?>" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-slate-600 hover:text-upskill-darkblue hover:bg-slate-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Beranda</span>
                </a>
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-blue-50 text-upskill-darkblue font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span>Quiz</span>
                </span>
                <a href="<?= base_url('/#program-kelas') ?>" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-slate-600 hover:text-upskill-darkblue hover:bg-slate-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <span>Program Kelas</span>
                </a>
            </nav>

            <!-- Kanan: Status Akun / Tombol Aksi -->
            <div class="flex items-center gap-2 shrink-0">
                <?php if (session()->get('isLoggedIn')): ?>
                    <a
                        href="<?= session()->get('role') === 'super_admin' ? base_url('admin/dashboard') : base_url('dashboard') ?>"
                        class="inline-flex items-center justify-center px-3.5 py-1.5 text-xs font-bold rounded-lg text-white bg-upskill-darkblue hover:bg-upskill-blue transition-colors"
                    >
                        Dasbor
                    </a>
                <?php else: ?>
                    <a
                        href="<?= base_url('login') ?>"
                        class="inline-flex items-center justify-center px-3.5 py-1.5 text-xs sm:text-sm font-bold rounded-lg text-upskill-darkblue bg-white border border-slate-200 hover:border-upskill-blue transition-colors"
                    >
                        Masuk
                    </a>
                    <a
                        href="<?= base_url('register') ?>"
                        class="hidden sm:inline-flex items-center justify-center px-4 py-1.5 text-xs sm:text-sm font-bold rounded-lg text-white bg-upskill-pink hover:bg-upskill-magenta transition-colors"
                    >
                        Daftar
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Area Utama 2 Kolom (Kiri: Area Soal 8/12, Kanan: Informasi & Petunjuk 4/12) -->
    <main class="flex-1 w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-5 sm:py-8">

        <!-- Flash Info / Error -->
        <?php if (session()->getFlashdata('info')): ?>
            <div class="mb-5 p-3.5 rounded-xl bg-blue-50 border border-blue-200 text-upskill-darkblue text-xs sm:text-sm font-medium">
                <?= esc(session()->getFlashdata('info')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm font-medium">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- ========================================================== -->
            <!-- KOLOM KIRI (8/12): KARTU PENGERJAAN QUIZ                   -->
            <!-- ========================================================== -->
            <div class="lg:col-span-8">
                <form id="trial-quiz-form" action="<?= base_url('quiz/' . $quiz['slug'] . '/submit') ?>" method="post">
                    <?= csrf_field() ?>
                    <!-- Honeypot anti-bot tersembunyi -->
                    <div class="hidden" aria-hidden="true">
                        <label for="website_url">Website</label>
                        <input type="text" id="website_url" name="website_url" value="" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">

                        <!-- Baris 1: Judul Quiz, Badge & Progress Persentase -->
                        <div class="px-5 sm:px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex flex-wrap items-center gap-2.5 min-w-0">
                                <h1 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight truncate">
                                    <?= esc($quiz['title']) ?>
                                </h1>
                                <?php if ($isTrialQuiz && ! session()->get('isLoggedIn')): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-pink-50 text-upskill-pink border border-pink-100 shrink-0">
                                        Trial Gratis &middot; Tanpa Login
                                    </span>
                                <?php elseif (! empty($quiz['topic_label'])): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-upskill-darkblue border border-blue-100 shrink-0">
                                        <?= esc($quiz['topic_label']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <!-- Progress Bar + Persentase -->
                            <div class="flex items-center gap-3 shrink-0">
                                <div class="w-28 sm:w-32 h-2 rounded-full bg-slate-100 overflow-hidden">
                                    <div
                                        id="progress-bar"
                                        class="h-full rounded-full bg-upskill-blue transition-all duration-300 ease-out"
                                        style="width: <?= $initialPct ?>%;"
                                    ></div>
                                </div>
                                <span id="progress-label" class="font-mono text-xs font-bold text-slate-800 tabular-nums whitespace-nowrap">
                                    <?= $initialPct ?>% (1 / <?= $totalQuestions ?>)
                                </span>
                            </div>
                        </div>

                        <!-- Baris 2: Meta Pertanyaan + Timer + Quick Arrow -->
                        <div class="px-5 sm:px-6 py-3 bg-slate-50/60 border-b border-slate-100 flex items-center justify-between gap-3">
                            <span id="question-counter-text" class="text-xs sm:text-sm font-semibold text-slate-600 tabular-nums">
                                Pertanyaan 1 dari <?= $totalQuestions ?>
                            </span>

                            <div class="flex items-center gap-2">
                                <!-- Timer Pill -->
                                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-pink-50 border border-pink-100 text-upskill-darkblue font-mono text-xs sm:text-sm font-bold tabular-nums">
                                    <svg class="w-4 h-4 text-upskill-pink shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span id="quiz-timer"><?= $durationMinutes > 0 ? sprintf('%02d:00', $durationMinutes) : '00:00' ?></span>
                                </div>

                                <!-- Tombol Cepat Sebelumnya / Selanjutnya -->
                                <div class="hidden sm:flex items-center gap-1.5">
                                    <button
                                        type="button"
                                        id="btn-top-prev"
                                        disabled
                                        aria-label="Soal sebelumnya"
                                        class="w-8 h-8 rounded-lg border border-slate-200 bg-white text-slate-600 hover:border-upskill-darkblue hover:text-upskill-darkblue disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center transition-colors cursor-pointer"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                    </button>
                                    <button
                                        type="button"
                                        id="btn-top-next"
                                        aria-label="Soal selanjutnya"
                                        class="w-8 h-8 rounded-lg border border-upskill-darkblue/40 bg-white text-upskill-darkblue hover:bg-blue-50 disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center transition-colors cursor-pointer"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Baris 3: Daftar Soal (1 Soal Aktif Per Layar) -->
                        <div class="p-5 sm:p-6">
                            <?php foreach ($questions as $index => $q): ?>
                                <section
                                    class="question-step <?= $index === 0 ? '' : 'hidden' ?>"
                                    data-step="<?= $index ?>"
                                    id="step-<?= $index ?>"
                                >
                                    <!-- Nomor Kotak Biru Tengah Vertikal + Garis Pembatas Vertikal + Teks Pertanyaan -->
                                    <div class="flex items-stretch gap-3.5 sm:gap-4 pb-5 mb-4 border-b border-slate-100">
                                        <div class="flex items-center shrink-0">
                                            <span class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-upskill-darkblue text-white font-mono text-sm sm:text-base font-extrabold flex items-center justify-center tabular-nums shadow-2xs">
                                                <?= $index + 1 ?>
                                            </span>
                                        </div>
                                        <div class="w-px self-stretch bg-slate-200 shrink-0" aria-hidden="true"></div>
                                        <div class="flex items-center min-w-0">
                                            <h2 class="text-sm sm:text-base font-bold text-slate-900 leading-relaxed whitespace-pre-line"><?= esc(trim((string) $q['question_text'])) ?></h2>
                                        </div>
                                    </div>

                                    <p class="text-xs font-medium text-slate-500 mb-3.5">
                                        Pilih satu jawaban yang paling tepat.
                                    </p>

                                    <!-- 4 Pilihan Jawaban Interaktif (Highlight Pink + Checkmark Kanan) -->
                                    <div class="space-y-3" role="radiogroup" aria-label="Pilihan jawaban soal <?= $index + 1 ?>">
                                        <?php foreach (['a' => 'option_a', 'b' => 'option_b', 'c' => 'option_c', 'd' => 'option_d'] as $key => $field): ?>
                                            <label class="option-card flex items-center justify-between gap-3 min-h-[54px] px-3.5 sm:px-4 py-3 rounded-xl border border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50/70 cursor-pointer">
                                                <input
                                                    type="radio"
                                                    name="answers[<?= (int) $q['id'] ?>]"
                                                    value="<?= $key ?>"
                                                    data-step="<?= $index ?>"
                                                    class="sr-only"
                                                >
                                                <span class="flex items-center gap-3.5 min-w-0">
                                                    <span class="option-badge w-8 h-8 rounded-lg border border-slate-200 bg-slate-100 text-slate-700 text-xs font-extrabold uppercase flex items-center justify-center shrink-0 transition-colors">
                                                        <?= strtoupper($key) ?>
                                                    </span>
                                                    <span class="option-text text-sm text-slate-700 leading-snug">
                                                        <?= esc($q[$field]) ?>
                                                    </span>
                                                </span>

                                                <!-- Lingkaran Ceklis Pink saat Terpilih -->
                                                <span class="option-check opacity-0 scale-75 transition-all duration-150 w-6 h-6 rounded-full bg-upskill-pink text-white flex items-center justify-center shrink-0" aria-hidden="true">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.8" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </span>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </section>
                            <?php endforeach; ?>

                            <!-- Peringatan Inline Jika Ada Soal Belum Terjawab -->
                            <div id="unanswered-alert" class="hidden mt-5 p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs sm:text-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <span id="unanswered-text" class="font-medium"></span>
                                <div class="flex items-center gap-2 shrink-0">
                                    <button
                                        type="button"
                                        id="btn-jump-unanswered"
                                        class="px-3 py-1.5 rounded-lg bg-white border border-amber-300 text-amber-900 font-semibold text-xs hover:bg-amber-100 transition-colors cursor-pointer"
                                    >
                                        Cek Soal Kosong
                                    </button>
                                    <button
                                        type="submit"
                                        class="px-3 py-1.5 rounded-lg bg-upskill-pink hover:bg-upskill-magenta text-white font-semibold text-xs transition-colors cursor-pointer"
                                    >
                                        Tetap Kumpulkan
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Baris 4: Footer Navigasi (Mobile-Friendly 2 Tombol + Pill Nomor) -->
                        <div class="px-5 sm:px-6 py-4 border-t border-slate-100 bg-slate-50/40 flex flex-col sm:flex-row items-center justify-between gap-4">
                            <!-- Indikator Nomor Soal (Di atas tombol pada mobile, di tengah pada desktop) -->
                            <div class="order-1 sm:order-2 flex items-center flex-wrap justify-center gap-1.5" aria-label="Navigasi nomor soal">
                                <?php foreach ($questions as $index => $q): ?>
                                    <button
                                        type="button"
                                        data-jump="<?= $index ?>"
                                        class="step-dot w-9 h-9 rounded-lg border border-slate-200 bg-white text-xs font-bold text-slate-700 hover:border-upskill-darkblue transition-colors cursor-pointer tabular-nums"
                                        aria-label="Ke soal <?= $index + 1 ?>"
                                    >
                                        <?= $index + 1 ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>

                            <!-- Tombol Sebelumnya & Selanjutnya/Selesai -->
                            <div class="order-2 sm:contents w-full grid grid-cols-2 gap-2.5">
                                <button
                                    type="button"
                                    id="btn-prev"
                                    disabled
                                    class="sm:order-1 inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-xs sm:text-sm font-bold text-slate-700 disabled:opacity-45 disabled:cursor-not-allowed focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-upskill-pink transition-colors cursor-pointer"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                    <span>Sebelumnya</span>
                                </button>

                                <div class="sm:order-3 flex items-center justify-end">
                                    <button
                                        type="button"
                                        id="btn-next"
                                        class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-5 py-2.5 rounded-xl bg-upskill-darkblue hover:bg-upskill-blue text-white text-xs sm:text-sm font-bold focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-upskill-pink focus-visible:ring-offset-2 transition-colors cursor-pointer shadow-2xs"
                                    >
                                        <span>Selanjutnya</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>

                                    <button
                                        type="button"
                                        id="btn-finish"
                                        class="hidden w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-5 py-2.5 rounded-xl bg-upskill-pink hover:bg-upskill-magenta text-white text-xs sm:text-sm font-bold focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-upskill-pink focus-visible:ring-offset-2 transition-colors cursor-pointer shadow-2xs"
                                    >
                                        <span>Lihat Hasil</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>

            <!-- ========================================================== -->
            <!-- KOLOM KANAN (4/12): INFORMASI QUIZ & PETUNJUK PENGERJAAN   -->
            <!-- ========================================================== -->
            <aside class="lg:col-span-4 space-y-5 lg:sticky lg:top-24">

                <!-- Kartu Informasi Quiz -->
                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
                    <h2 class="text-sm sm:text-base font-extrabold text-slate-900 flex items-center gap-2 pb-3.5 border-b border-slate-100">
                        <span class="w-6 h-6 rounded-full bg-upskill-darkblue text-white flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01"/>
                            </svg>
                        </span>
                        <span>Informasi Quiz</span>
                    </h2>

                    <dl class="divide-y divide-slate-100 text-xs sm:text-sm">
                        <div class="py-3 flex items-center justify-between gap-3">
                            <dt class="text-slate-500 flex items-center gap-2 shrink-0">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                <span>Judul</span>
                            </dt>
                            <dd class="font-bold text-slate-900 text-right truncate max-w-[190px]" title="<?= esc($quiz['title']) ?>">
                                <?= esc($quiz['title']) ?>
                            </dd>
                        </div>

                        <div class="py-3 flex items-center justify-between gap-3">
                            <dt class="text-slate-500 flex items-center gap-2 shrink-0">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                <span>Jenis</span>
                            </dt>
                            <dd class="text-right">
                                <?php if ($isTrialQuiz): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-pink-50 text-upskill-pink border border-pink-100">
                                        Trial Gratis &middot; Tanpa Login
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-upskill-darkblue border border-blue-100">
                                        Khusus Member
                                    </span>
                                <?php endif; ?>
                            </dd>
                        </div>

                        <div class="py-3 flex items-center justify-between gap-3">
                            <dt class="text-slate-500 flex items-center gap-2 shrink-0">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                                <span>Jumlah Soal</span>
                            </dt>
                            <dd class="font-bold text-slate-900 tabular-nums"><?= $totalQuestions ?> soal</dd>
                        </div>

                        <div class="py-3 flex items-center justify-between gap-3">
                            <dt class="text-slate-500 flex items-center gap-2 shrink-0">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Durasi</span>
                            </dt>
                            <dd class="font-bold text-slate-900 tabular-nums"><?= esc($durationDisplay) ?></dd>
                        </div>

                        <div class="pt-3 flex items-center justify-between gap-3">
                            <dt class="text-slate-500 flex items-center gap-2 shrink-0">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Terjawab</span>
                            </dt>
                            <dd id="sidebar-answered" class="font-mono text-xs font-bold text-upskill-darkblue tabular-nums">
                                0 / <?= $totalQuestions ?> soal
                            </dd>
                        </div>

                        <?php if (! empty($quiz['description'])): ?>
                            <div class="pt-3 mt-3 space-y-1">
                                <dt class="text-slate-500 font-semibold text-xs">Deskripsi</dt>
                                <dd class="text-xs text-slate-700 leading-relaxed whitespace-pre-line"><?= esc(trim((string) $quiz['description'])) ?></dd>
                            </div>
                        <?php endif; ?>
                    </dl>
                </div>

                <!-- Kartu Petunjuk Pengerjaan -->
                <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-5 space-y-3">
                    <h2 class="text-sm font-extrabold text-upskill-darkblue flex items-center gap-2">
                        <svg class="w-4 h-4 text-upskill-darkblue shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                        <span>Petunjuk Pengerjaan</span>
                    </h2>

                    <ul class="space-y-2 text-xs text-slate-700 leading-relaxed list-disc pl-4 marker:text-upskill-darkblue">
                        <li>Pilih satu jawaban yang paling tepat pada setiap soal.</li>
                        <li>Klik <strong>&ldquo;Selanjutnya&rdquo;</strong> atau nomor soal untuk berpindah pertanyaan.</li>
                        <li>Pastikan menjawab semua soal sebelum klik <strong>&ldquo;Lihat Hasil&rdquo;</strong>.</li>
                    </ul>
                </div>

            </aside>
        </div>
    </main>

    <script>
        (function () {
            const totalSteps      = <?= $totalQuestions ?>;
            const durationMinutes = <?= $durationMinutes ?>;
            const steps           = document.querySelectorAll('.question-step');
            const dots            = document.querySelectorAll('.step-dot');
            const btnPrev         = document.getElementById('btn-prev');
            const btnNext         = document.getElementById('btn-next');
            const btnTopPrev      = document.getElementById('btn-top-prev');
            const btnTopNext      = document.getElementById('btn-top-next');
            const btnFinish       = document.getElementById('btn-finish');
            const progressBar     = document.getElementById('progress-bar');
            const progressLabel   = document.getElementById('progress-label');
            const questionCounter = document.getElementById('question-counter-text');
            const sidebarAnswered = document.getElementById('sidebar-answered');
            const unansweredAlert = document.getElementById('unanswered-alert');
            const unansweredText  = document.getElementById('unanswered-text');
            const btnJumpEmpty    = document.getElementById('btn-jump-unanswered');
            const form            = document.getElementById('trial-quiz-form');

            let currentStep    = 0;
            let firstEmptyStep = -1;

            function isStepAnswered(stepIndex) {
                const section = document.getElementById('step-' + stepIndex);
                return !!(section && section.querySelector('input[type="radio"]:checked'));
            }

            function updateIndicators() {
                let answered = 0;

                dots.forEach((dot, idx) => {
                    const done = isStepAnswered(idx);
                    if (done) answered++;

                    dot.classList.remove(
                        'bg-upskill-darkblue', 'text-white', 'border-upskill-darkblue', 'shadow-2xs',
                        'bg-pink-50', 'text-upskill-pink', 'border-pink-200',
                        'bg-white', 'text-slate-700', 'border-slate-200'
                    );

                    if (idx === currentStep) {
                        dot.classList.add('bg-upskill-darkblue', 'text-white', 'border-upskill-darkblue', 'shadow-2xs');
                    } else if (done) {
                        dot.classList.add('bg-pink-50', 'text-upskill-pink', 'border-pink-200');
                    } else {
                        dot.classList.add('bg-white', 'text-slate-700', 'border-slate-200');
                    }
                });

                const pct = totalSteps > 0 ? Math.round(((currentStep + 1) / totalSteps) * 100) : 0;
                if (progressBar) progressBar.style.width = pct + '%';
                if (progressLabel) progressLabel.textContent = pct + '% (' + (currentStep + 1) + ' / ' + totalSteps + ')';
                if (questionCounter) questionCounter.textContent = 'Pertanyaan ' + (currentStep + 1) + ' dari ' + totalSteps;
                if (sidebarAnswered) sidebarAnswered.textContent = answered + ' / ' + totalSteps + ' soal';
            }

            function goToStep(index) {
                if (index < 0 || index >= totalSteps) return;

                steps.forEach((el, idx) => {
                    el.classList.toggle('hidden', idx !== index);
                });

                currentStep = index;
                const isFirst = (currentStep === 0);
                const isLast  = (currentStep === totalSteps - 1);

                if (btnPrev) btnPrev.disabled = isFirst;
                if (btnTopPrev) btnTopPrev.disabled = isFirst;
                if (btnTopNext) btnTopNext.disabled = isLast;

                if (btnNext) btnNext.classList.toggle('hidden', isLast);
                if (btnFinish) btnFinish.classList.toggle('hidden', !isLast);

                if (unansweredAlert) unansweredAlert.classList.add('hidden');
                updateIndicators();
            }

            document.querySelectorAll('input[type="radio"]').forEach((radio) => {
                radio.addEventListener('change', () => {
                    if (unansweredAlert) unansweredAlert.classList.add('hidden');
                    updateIndicators();
                });
            });

            if (btnPrev) btnPrev.addEventListener('click', () => goToStep(currentStep - 1));
            if (btnNext) btnNext.addEventListener('click', () => goToStep(currentStep + 1));
            if (btnTopPrev) btnTopPrev.addEventListener('click', () => goToStep(currentStep - 1));
            if (btnTopNext) btnTopNext.addEventListener('click', () => goToStep(currentStep + 1));

            dots.forEach((dot) => {
                dot.addEventListener('click', () => {
                    const idx = parseInt(dot.getAttribute('data-jump'), 10);
                    if (!Number.isNaN(idx)) goToStep(idx);
                });
            });

            if (btnFinish) {
                btnFinish.addEventListener('click', () => {
                    const missing = [];
                    for (let i = 0; i < totalSteps; i++) {
                        if (!isStepAnswered(i)) missing.push(i);
                    }

                    if (missing.length > 0) {
                        firstEmptyStep = missing[0];
                        if (unansweredText) {
                            unansweredText.textContent = 'Masih ada ' + missing.length + ' soal yang belum dijawab (mulai nomor ' + (firstEmptyStep + 1) + ').';
                        }
                        if (unansweredAlert) unansweredAlert.classList.remove('hidden');
                        return;
                    }

                    form.submit();
                });
            }

            if (btnJumpEmpty) {
                btnJumpEmpty.addEventListener('click', () => {
                    if (firstEmptyStep >= 0) goToStep(firstEmptyStep);
                });
            }

            // Timer: hitung mundur jika duration_minutes > 0, atau hitung maju (elapsed) jika bebas waktu
            const timerEl = document.getElementById('quiz-timer');
            if (timerEl) {
                if (durationMinutes > 0) {
                    let remaining = durationMinutes * 60;
                    const tickDown = () => {
                        if (remaining <= 0) {
                            timerEl.textContent = '00:00';
                            form.submit();
                            return;
                        }
                        const m = String(Math.floor(remaining / 60)).padStart(2, '0');
                        const s = String(remaining % 60).padStart(2, '0');
                        timerEl.textContent = m + ':' + s;
                        remaining--;
                        setTimeout(tickDown, 1000);
                    };
                    tickDown();
                } else {
                    let elapsed = 0;
                    setInterval(() => {
                        elapsed++;
                        const m = String(Math.floor(elapsed / 60)).padStart(2, '0');
                        const s = String(elapsed % 60).padStart(2, '0');
                        timerEl.textContent = m + ':' + s;
                    }, 1000);
                }
            }

            goToStep(0);
        })();
    </script>
</body>
</html>
