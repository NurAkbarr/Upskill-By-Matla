<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Hasil Quiz - MUSLIM UPSKILL ACADEMY') ?></title>

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
    </style>
</head>
<body class="min-h-full flex flex-col bg-slate-50 text-slate-900 selection:bg-pink-100 selection:text-upskill-pink">

<?php
    $scoreInt        = (int) round((float) ($attempt['score'] ?? 0));
    $correctCount    = (int) ($attempt['correct_count'] ?? 0);
    $totalQuestions  = (int) ($attempt['total_questions'] ?? count($questions));
    $durationSec     = (int) ($attempt['duration_seconds'] ?? 0);
    $durMin          = (int) floor($durationSec / 60);
    $durRem          = $durationSec % 60;
    $durationLabel   = $durMin > 0 ? "{$durMin}m {$durRem} detik" : "{$durRem} detik";
    $isTrialQuiz     = ((int) ($quiz['is_trial'] ?? 0) === 1);
    $durationMinutes = (int) ($quiz['duration_minutes'] ?? 0);
    $durationInfo    = $durationMinutes > 0 ? $durationMinutes . ' menit' : 'Tanpa batas waktu';

    $predicateTone = $predicate['tone'] ?? 'indigo';
    $predicatePill = match ($predicateTone) {
        'emerald' => 'bg-emerald-100 text-emerald-900 border-emerald-200',
        'blue'    => 'bg-blue-100 text-upskill-darkblue border-blue-200',
        'indigo'  => 'bg-indigo-100 text-upskill-darkblue border-indigo-200',
        'amber'   => 'bg-amber-100 text-amber-900 border-amber-200',
        default   => 'bg-rose-100 text-rose-900 border-rose-200',
    };

    $medalColors = match ($predicateTone) {
        'emerald' => ['outer' => '#10B981', 'inner' => '#D1FAE5', 'ribbon' => '#059669', 'star' => '#047857'],
        'blue'    => ['outer' => '#1C21AC', 'inner' => '#DBEAFE', 'ribbon' => '#FA1886', 'star' => '#1C21AC'],
        'indigo'  => ['outer' => '#F59E0B', 'inner' => '#FEF3C7', 'ribbon' => '#FA1886', 'star' => '#D97706'],
        'amber'   => ['outer' => '#F59E0B', 'inner' => '#FEF3C7', 'ribbon' => '#D97706', 'star' => '#B45309'],
        default   => ['outer' => '#E11D48', 'inner' => '#FFE4E6', 'ribbon' => '#BE123C', 'star' => '#9F1239'],
    };

    $lockedCount = (! $is_unlocked && count($questions) > 1) ? (count($questions) - 1) : 0;
    $registerUrl = base_url('register?trial_token=' . urlencode((string) $attempt['attempt_token']));
    $loginUrl    = base_url('login?trial_token=' . urlencode((string) $attempt['attempt_token']));

    // Palet chip halus berotasi untuk kelas-kelas published dari dashboard
    $courseChipStyles = [
        'bg-blue-50 text-upskill-darkblue border-blue-200 hover:bg-blue-100',
        'bg-purple-50 text-purple-900 border-purple-200 hover:bg-purple-100',
        'bg-emerald-50 text-emerald-900 border-emerald-200 hover:bg-emerald-100',
        'bg-pink-50 text-upskill-pink border-pink-200 hover:bg-pink-100',
        'bg-amber-50 text-amber-900 border-amber-200 hover:bg-amber-100',
    ];
?>

    <!-- Top Navbar Seragam dengan Halaman Pengerjaan Quiz -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-sm border-b border-slate-200/80">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-3">
            <a href="<?= base_url('/') ?>" class="flex items-center gap-2 shrink-0 group">
                <img src="<?= base_url('assets/images/logo-removebg.png') ?>" alt="Muslim Upskill Academy" class="h-9 sm:h-10 w-auto object-contain transition-transform duration-200 group-hover:scale-105">
            </a>

            <nav class="hidden md:flex items-center gap-2 text-sm font-semibold">
                <a href="<?= base_url('/') ?>" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-slate-600 hover:text-upskill-darkblue hover:bg-slate-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Beranda</span>
                </a>
                <a href="<?= base_url('quiz') ?>" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-blue-50 text-upskill-darkblue font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span>Quiz</span>
                </a>
                <a href="<?= base_url('/#program-kelas') ?>" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-slate-600 hover:text-upskill-darkblue hover:bg-slate-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <span>Program Kelas</span>
                </a>
            </nav>

            <div class="flex items-center gap-2 shrink-0">
                <?php if ($is_logged_in): ?>
                    <a
                        href="<?= session()->get('role') === 'super_admin' ? base_url('admin/dashboard') : base_url('dashboard') ?>"
                        class="inline-flex items-center justify-center px-3.5 py-1.5 text-xs font-bold rounded-lg text-white bg-upskill-darkblue hover:bg-upskill-blue transition-colors"
                    >
                        Dasbor
                    </a>
                <?php else: ?>
                    <a
                        href="<?= $loginUrl ?>"
                        class="inline-flex items-center justify-center px-3.5 py-1.5 text-xs sm:text-sm font-bold rounded-lg text-upskill-darkblue bg-white border border-slate-200 hover:border-upskill-blue transition-colors"
                    >
                        Masuk
                    </a>
                    <a
                        href="<?= $registerUrl ?>"
                        class="hidden sm:inline-flex items-center justify-center px-4 py-1.5 text-xs sm:text-sm font-bold rounded-lg text-white bg-upskill-pink hover:bg-upskill-magenta transition-colors"
                    >
                        Daftar
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Konten Utama 2 Kolom (Kiri 8/12: Hasil + Pembahasan | Kanan 4/12: Info + Kelas Lain + Rekomendasi) -->
    <main class="flex-1 w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-5 sm:py-8 space-y-5">

        <!-- Flash Notifications -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs sm:text-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="font-medium"><?= esc(session()->getFlashdata('success')) ?></span>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('info')): ?>
            <div class="p-4 rounded-xl bg-blue-50 border border-blue-200 text-upskill-darkblue text-xs sm:text-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-upskill-blue shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-medium"><?= esc(session()->getFlashdata('info')) ?></span>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- ========================================================== -->
            <!-- KOLOM KIRI (8/12): RINGKASAN HASIL + REVIEW PEMBAHASAN     -->
            <!-- ========================================================== -->
            <div class="lg:col-span-8 space-y-6">

                <!-- Kartu 1: Hasil Quiz & Medali Predikat (Mumtaz / Jayyid) -->
                <section class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-xs space-y-5">
                    <!-- Header Judul + Badge -->
                    <div class="flex flex-wrap items-center gap-2.5">
                        <h1 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight">
                            <?= esc($quiz['title']) ?>
                        </h1>
                        <?php if ($isTrialQuiz && ! $is_logged_in): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-pink-50 text-upskill-pink border border-pink-100">
                                Trial Gratis &middot; Tanpa Login
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Isi Utama: Metrik Kiri + Medali Predikat & Nilai Kanan -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                        <div class="space-y-4 flex-1 min-w-0">
                            <!-- 2 Metrik Kompak (Nilai dipindahkan ke bawah predikat di kanan) -->
                            <div class="flex flex-wrap items-center gap-5 sm:gap-8">
                                <!-- Jawaban Benar -->
                                <div class="flex items-center gap-3">
                                    <span class="w-9 h-9 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </span>
                                    <div>
                                        <span class="text-[11px] font-semibold text-slate-500 block">Jawaban Benar</span>
                                        <p class="text-sm sm:text-base font-extrabold text-slate-900 tabular-nums">
                                            <?= $correctCount ?> <span class="font-medium text-slate-500">dari <?= $totalQuestions ?> soal</span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Waktu Pengerjaan -->
                                <div class="flex items-center gap-3">
                                    <span class="w-9 h-9 rounded-xl bg-blue-50 border border-blue-100 text-upskill-darkblue flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </span>
                                    <div>
                                        <span class="text-[11px] font-semibold text-slate-500 block">Waktu Pengerjaan</span>
                                        <p class="text-sm sm:text-base font-extrabold text-slate-900 tabular-nums"><?= esc($durationLabel) ?></p>
                                    </div>
                                </div>
                            </div>

                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                <?= esc($predicate['summary']) ?>
                            </p>
                        </div>

                        <!-- Medali, Label Predikat & Nilai di Bawahnya -->
                        <div class="flex sm:flex-col items-center justify-center gap-3 sm:gap-2 pt-4 sm:pt-0 border-t sm:border-t-0 sm:border-l border-slate-100 sm:pl-7 shrink-0 min-w-[155px]">
                            <svg class="w-16 h-16 sm:w-20 sm:h-20 shrink-0" viewBox="0 0 80 80" fill="none" aria-hidden="true">
                                <!-- Pita Medali -->
                                <path d="M29 48L24 72L35 65L40 73V49H29Z" fill="<?= $medalColors['ribbon'] ?>" opacity="0.9"/>
                                <path d="M51 48L56 72L45 65L40 73V49H51Z" fill="<?= $medalColors['ribbon'] ?>"/>
                                <!-- Lingkaran Medali -->
                                <circle cx="40" cy="34" r="22" fill="<?= $medalColors['inner'] ?>" stroke="<?= $medalColors['outer'] ?>" stroke-width="3"/>
                                <circle cx="40" cy="34" r="15" fill="#FFFFFF" stroke="<?= $medalColors['outer'] ?>" stroke-width="1.5" stroke-dasharray="3 2"/>
                                <!-- Bintang Tengah -->
                                <path d="M40 24.5L42.8 30.2L49 31.1L44.5 35.5L45.6 41.7L40 38.7L34.4 41.7L35.5 35.5L31 31.1L37.2 30.2L40 24.5Z" fill="<?= $medalColors['star'] ?>"/>
                            </svg>

                            <div class="text-left sm:text-center space-y-1">
                                <span class="inline-flex items-center justify-center px-3.5 py-1 rounded-xl text-sm font-extrabold border <?= $predicatePill ?> whitespace-nowrap">
                                    <?= esc($predicate['label']) ?>
                                </span>
                                <p class="text-sm font-extrabold text-upskill-darkblue font-mono tabular-nums">
                                    Nilai <?= $scoreInt ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Baris Tombol Aksi Utama (Daftar / Bagikan WA / Coba Ulang) -->
                    <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
                            <?php if (! $is_unlocked): ?>
                                <a
                                    href="<?= $registerUrl ?>"
                                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-white bg-upskill-pink hover:bg-upskill-magenta transition-colors shadow-2xs"
                                >
                                    <span>Daftar &amp; Buka Pembahasan</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </a>
                            <?php else: ?>
                                <a
                                    href="<?= base_url('dashboard') ?>"
                                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-white bg-upskill-darkblue hover:bg-upskill-blue transition-colors"
                                >
                                    <span>Buka Dasbor Belajar</span>
                                </a>
                            <?php endif; ?>

                            <a
                                href="<?= esc($whatsapp_share_url) ?>"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-emerald-800 bg-emerald-50 border border-emerald-200 hover:bg-emerald-100 transition-colors"
                            >
                                <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                                </svg>
                                <span>Bagikan ke WhatsApp</span>
                            </a>
                        </div>

                        <a
                            href="<?= esc($retry_url) ?>"
                            class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold text-slate-600 hover:text-upskill-darkblue hover:bg-slate-100 transition-colors"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <span>Coba Ulang</span>
                        </a>
                    </div>
                </section>

                <!-- Kartu 2: Review Jawaban & Pembahasan -->
                <section class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-xs space-y-5">
                    <div class="pb-4 border-b border-slate-100">
                        <h2 class="text-base sm:text-lg font-extrabold text-upskill-darkblue flex items-center gap-2">
                            <svg class="w-5 h-5 text-upskill-darkblue shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <span>Review Jawaban &amp; Pembahasan</span>
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-600 mt-1">
                            <?php if ($is_unlocked): ?>
                                Seluruh kunci jawaban dan pembahasan dalil terbuka untuk akun Anda.
                            <?php else: ?>
                                Pembahasan nomor 1 terbuka gratis. Daftar akun untuk membuka <?= $lockedCount ?> pembahasan lainnya.
                            <?php endif; ?>
                        </p>
                    </div>

                    <!-- Daftar Soal & Pembahasan (Dipisah Garis Bersih, Tanpa Nested Card Berat) -->
                    <div class="divide-y divide-slate-200/80">
                        <?php foreach ($questions as $idx => $q): ?>
                            <?php
                                $qId            = (int) $q['id'];
                                $userAns        = isset($answers[$qId]) ? strtolower((string) $answers[$qId]) : null;
                                $correctAns     = strtolower((string) $q['correct_answer']);
                                $isCorrect      = ($userAns !== null && $userAns === $correctAns);
                                $showFullDetail = $is_unlocked || ($idx === 0);

                                $optionMap = [
                                    'a' => $q['option_a'],
                                    'b' => $q['option_b'],
                                    'c' => $q['option_c'],
                                    'd' => $q['option_d'],
                                ];
                            ?>
                            <article class="py-5 first:pt-0 last:pb-0 space-y-3.5">
                                <!-- Header Pill Soal + Status -->
                                <div class="flex items-center justify-between gap-3">
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg bg-blue-50 text-upskill-darkblue font-bold text-xs tabular-nums">
                                        Soal <?= $idx + 1 ?>
                                    </span>

                                    <?php if ($isCorrect): ?>
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Benar
                                        </span>
                                    <?php elseif ($userAns === null): ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                            Tidak Dijawab
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg text-xs font-bold bg-rose-50 text-rose-800 border border-rose-200">
                                            <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Belum Tepat
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <!-- Teks Pertanyaan -->
                                <p class="text-sm sm:text-[15px] font-bold text-slate-900 leading-relaxed whitespace-pre-line"><?= ($idx + 1) ?>. <?= esc(trim((string) $q['question_text'])) ?></p>

                                <!-- Jawaban Anda & Jawaban Benar -->
                                <div class="space-y-1.5 text-xs sm:text-sm">
                                    <div class="flex flex-col sm:flex-row sm:items-start gap-0.5 sm:gap-2">
                                        <span class="text-slate-500 font-semibold sm:w-28 shrink-0">Jawaban Anda</span>
                                        <span class="hidden sm:inline text-slate-400">:</span>
                                        <?php if ($userAns !== null && isset($optionMap[$userAns])): ?>
                                            <span class="font-semibold <?= $isCorrect ? 'text-emerald-700' : 'text-rose-700' ?>">
                                                <span class="uppercase"><?= esc($userAns) ?>.</span> <?= esc($optionMap[$userAns]) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-slate-500 italic">Kosong</span>
                                        <?php endif; ?>
                                    </div>

                                    <?php if ($showFullDetail): ?>
                                        <div class="flex flex-col sm:flex-row sm:items-start gap-0.5 sm:gap-2">
                                            <span class="text-slate-500 font-semibold sm:w-28 shrink-0">Jawaban Benar</span>
                                            <span class="hidden sm:inline text-slate-400">:</span>
                                            <span class="font-semibold text-emerald-700">
                                                <span class="uppercase"><?= esc($correctAns) ?>.</span> <?= esc($optionMap[$correctAns] ?? '-') ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Blok Pembahasan & Dalil (Terbuka vs Terkunci) -->
                                <?php if ($showFullDetail): ?>
                                    <?php if (! empty($q['explanation'])): ?>
                                        <div class="pt-2 space-y-2">
                                            <div class="inline-flex items-center gap-1.5 text-xs font-extrabold text-upskill-darkblue">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                </svg>
                                                <span>Pembahasan &amp; Dalil</span>
                                            </div>
                                            <p class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line"><?= esc(trim((string) $q['explanation'])) ?></p>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                        <div class="flex items-start gap-2.5 text-xs text-slate-600">
                                            <svg class="w-4 h-4 text-slate-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                            <span>Kunci jawaban dan pembahasan dalil nomor ini terbuka setelah daftar atau masuk akun.</span>
                                        </div>
                                        <a
                                            href="<?= $registerUrl ?>"
                                            class="inline-flex items-center justify-center px-3.5 py-1.5 rounded-lg text-xs font-bold text-upskill-pink bg-pink-50 border border-pink-200 hover:bg-pink-100 transition-colors shrink-0"
                                        >
                                            Buka Pembahasan
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </section>

            </div>

            <!-- ========================================================== -->
            <!-- KOLOM KANAN (4/12): INFORMASI QUIZ + KELAS + REKOMENDASI   -->
            <!-- ========================================================== -->
            <aside class="lg:col-span-4 space-y-5 lg:sticky lg:top-24">

                <!-- Kartu 1: Informasi Quiz (100% dari Database Admin) -->
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

                        <div class="pt-3 flex items-center justify-between gap-3">
                            <dt class="text-slate-500 flex items-center gap-2 shrink-0">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Durasi</span>
                            </dt>
                            <dd class="font-bold text-slate-900 tabular-nums"><?= esc($durationInfo) ?></dd>
                        </div>

                        <?php if (! empty($quiz['description'])): ?>
                            <div class="pt-3 mt-3 space-y-1">
                                <dt class="text-slate-500 font-semibold text-xs">Deskripsi</dt>
                                <dd class="text-xs text-slate-700 leading-relaxed whitespace-pre-line"><?= esc(trim((string) $quiz['description'])) ?></dd>
                            </div>
                        <?php endif; ?>
                    </dl>
                </div>

                <!-- Kartu 2: Tantangan Quiz Lainnya (Daftar Topik Statis) -->
                <?php
                    $staticTopics = [
                        ['label' => 'Siroh Nabi',     'cls' => 'bg-blue-50 text-upskill-darkblue border-blue-200 hover:bg-blue-100',     'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                        ['label' => 'Fiqh',           'cls' => 'bg-purple-50 text-purple-900 border-purple-200 hover:bg-purple-100',     'icon' => 'M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3'],
                        ['label' => 'Tauhid',         'cls' => 'bg-emerald-50 text-emerald-900 border-emerald-200 hover:bg-emerald-100', 'icon' => 'M12 3l8 4v5c0 5-3.5 8.5-8 9-4.5-.5-8-4-8-9V7l8-4z'],
                        ['label' => 'Bahasa Arab',    'cls' => 'bg-pink-50 text-upskill-pink border-pink-200 hover:bg-pink-100',         'icon' => 'M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129'],
                        ['label' => 'Bahasa Inggris', 'cls' => 'bg-sky-50 text-sky-900 border-sky-200 hover:bg-sky-100',                 'icon' => 'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9'],
                        ['label' => 'Matematika',     'cls' => 'bg-amber-50 text-amber-900 border-amber-200 hover:bg-amber-100',         'icon' => 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z'],
                    ];
                    $topicTargetUrl = $is_logged_in ? base_url('dashboard') : $registerUrl;
                ?>
                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-3.5">
                    <div>
                        <h2 class="text-sm sm:text-base font-extrabold text-upskill-darkblue flex items-center gap-2">
                            <svg class="w-4 h-4 text-upskill-darkblue shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                            <span>Tantangan Quiz Lainnya</span>
                        </h2>
                        <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                            Uji pemahamanmu di berbagai topik menarik lainnya, seperti:
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($staticTopics as $topic): ?>
                            <a
                                href="<?= $topicTargetUrl ?>"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold border transition-colors <?= $topic['cls'] ?>"
                            >
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $topic['icon'] ?>"/>
                                </svg>
                                <span><?= esc($topic['label']) ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Kartu 3: Pelajari Materinya Secara Runut (Hanya tampil jika ada kelas published) -->
                <?php if (! empty($recommended_course)): ?>
                    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-3">
                        <h2 class="text-sm sm:text-base font-extrabold text-upskill-darkblue flex items-center gap-2">
                            <svg class="w-4 h-4 text-upskill-darkblue shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            <span>Pelajari Materinya Secara Runut</span>
                        </h2>

                        <div class="space-y-1">
                            <p class="text-sm font-bold text-slate-900">
                                <?= esc($recommended_course['title']) ?>
                            </p>
                            <?php if (! empty($recommended_course['description'])): ?>
                                <p class="text-xs text-slate-600 leading-relaxed line-clamp-2">
                                    <?= esc($recommended_course['description']) ?>
                                </p>
                            <?php endif; ?>
                        </div>

                        <a
                            href="<?= base_url('courses/' . $recommended_course['slug']) ?>"
                            class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-upskill-darkblue bg-blue-50 hover:bg-blue-100 border border-blue-100 transition-colors"
                        >
                            <span>Lihat Program Kelas</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                <?php endif; ?>

            </aside>
        </div>
    </main>

</body>
</html>
