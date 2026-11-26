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
        .option-card:has(input:checked) {
            border-color: #1C21AC;
            background-color: #EFF6FF;
        }
        .option-card:has(input:checked) .option-badge {
            background-color: #1C21AC;
            border-color: #1C21AC;
            color: #FFFFFF;
        }
    </style>
</head>
<body class="min-h-full flex flex-col bg-slate-50 text-slate-900 selection:bg-pink-100 selection:text-upskill-pink">

    <?php
        $totalQuestions  = count($questions);
        $durationMinutes = (int) ($quiz['duration_minutes'] ?? 0);
        $isTrialQuiz     = ((int) ($quiz['is_trial'] ?? 0) === 1);
    ?>

    <!-- Sticky Header Fokus Pengerjaan -->
    <header class="sticky top-0 z-30 bg-white border-b border-slate-200">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between gap-4">
            <a
                href="<?= base_url('/') ?>"
                class="inline-flex items-center gap-2 text-xs sm:text-sm font-semibold text-slate-600 hover:text-upskill-darkblue focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-upskill-pink focus-visible:ring-offset-2 rounded-lg py-1 transition-colors shrink-0"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span class="hidden sm:inline">Beranda</span>
            </a>

            <div class="min-w-0 text-center">
                <h1 class="text-sm sm:text-base font-bold text-upskill-darkblue truncate">
                    <?= esc($quiz['title']) ?>
                </h1>
            </div>

            <div class="flex items-center gap-2.5 shrink-0">
                <?php if ($durationMinutes > 0): ?>
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-800 font-mono text-xs font-semibold tabular-nums">
                        <svg class="w-3.5 h-3.5 text-upskill-pink" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span id="quiz-timer">--:--</span>
                    </div>
                <?php endif; ?>
                <span class="font-mono text-xs font-bold text-slate-700 tabular-nums bg-slate-100 px-2.5 py-1 rounded-lg">
                    <span id="current-number">1</span> / <?= $totalQuestions ?>
                </span>
            </div>
        </div>

        <!-- Bar Progres Tipis -->
        <div class="h-1 w-full bg-slate-100">
            <div
                id="progress-bar"
                class="h-full bg-upskill-pink transition-all duration-300 ease-out"
                style="width: <?= $totalQuestions > 0 ? round((1 / $totalQuestions) * 100) : 0 ?>%;"
            ></div>
        </div>
    </header>

    <!-- Konten Utama Soal -->
    <main class="flex-1 w-full max-w-3xl mx-auto px-4 sm:px-6 py-6 sm:py-10 flex flex-col justify-between">
        <div class="space-y-5">

            <!-- Flash Info / Error -->
            <?php if (session()->getFlashdata('info')): ?>
                <div class="p-3.5 rounded-xl bg-blue-50 border border-blue-200 text-blue-900 text-xs sm:text-sm">
                    <?= esc(session()->getFlashdata('info')) ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm">
                    <?= esc(session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>

            <!-- Konteks Singkat Quiz (1 baris meta, langsung masuk soal) -->
            <div class="flex flex-wrap items-center justify-between gap-2 text-xs text-slate-600">
                <div class="flex items-center gap-2">
                    <?php if (!empty($quiz['topic_label'])): ?>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-white border border-slate-200 font-semibold text-upskill-darkblue">
                            <?= esc($quiz['topic_label']) ?>
                        </span>
                    <?php endif; ?>
                    <?php if ($isTrialQuiz && !session()->get('isLoggedIn')): ?>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-pink-50 border border-pink-200 font-semibold text-upskill-pink">
                            Trial Gratis &middot; Tanpa Login
                        </span>
                    <?php endif; ?>
                </div>
                <span id="answered-counter" class="font-medium text-slate-600 tabular-nums">
                    0 dari <?= $totalQuestions ?> terjawab
                </span>
            </div>

            <!-- Form Pengerjaan Quiz -->
            <form id="trial-quiz-form" action="<?= base_url('quiz/' . $quiz['slug'] . '/submit') ?>" method="post">
                <?= csrf_field() ?>
                <div class="hidden" aria-hidden="true">
                    <label for="website_url">Website</label>
                    <input type="text" id="website_url" name="website_url" value="" tabindex="-1" autocomplete="off">
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-8 shadow-xs">
                    <?php foreach ($questions as $index => $q): ?>
                        <section
                            class="question-step <?= $index === 0 ? '' : 'hidden' ?>"
                            data-step="<?= $index ?>"
                            id="step-<?= $index ?>"
                        >
                            <!-- Teks Pertanyaan -->
                            <div class="mb-6">
                                <p class="text-xs font-semibold text-slate-500 mb-2 tabular-nums">
                                    Pertanyaan <?= $index + 1 ?> dari <?= $totalQuestions ?>
                                </p>
                                <h2 class="text-base sm:text-lg font-semibold text-slate-900 leading-relaxed whitespace-pre-line">
                                    <?= esc($q['question_text']) ?>
                                </h2>
                            </div>

                            <!-- 4 Pilihan Jawaban (Mobile-Friendly Tap Targets) -->
                            <div class="space-y-3" role="radiogroup" aria-label="Pilihan jawaban soal <?= $index + 1 ?>">
                                <?php foreach (['a' => 'option_a', 'b' => 'option_b', 'c' => 'option_c', 'd' => 'option_d'] as $key => $field): ?>
                                    <label class="option-card flex items-start gap-3.5 min-h-[52px] p-3.5 sm:p-4 rounded-xl border border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50/70 transition-colors duration-150 cursor-pointer">
                                        <input
                                            type="radio"
                                            name="answers[<?= (int) $q['id'] ?>]"
                                            value="<?= $key ?>"
                                            data-step="<?= $index ?>"
                                            class="sr-only"
                                        >
                                        <span class="option-badge w-7 h-7 rounded-lg border border-slate-300 bg-slate-50 text-slate-700 text-xs font-bold uppercase flex items-center justify-center shrink-0 mt-0.5 transition-colors duration-150">
                                            <?= strtoupper($key) ?>
                                        </span>
                                        <span class="text-sm sm:text-base text-slate-800 leading-snug pt-0.5">
                                            <?= esc($q[$field]) ?>
                                        </span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    <?php endforeach; ?>

                    <!-- Peringatan Inline Jika Ada Soal Belum Terjawab -->
                    <div id="unanswered-alert" class="hidden mt-6 p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs sm:text-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3">
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

                    <!-- Navigasi Soal -->
                    <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <!-- Tombol Sebelumnya -->
                        <button
                            type="button"
                            id="btn-prev"
                            disabled
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg border border-slate-200 bg-white text-sm font-semibold text-slate-700 hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-upskill-pink transition-colors cursor-pointer"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            <span>Sebelumnya</span>
                        </button>

                        <!-- Indikator Nomor Soal -->
                        <div class="flex items-center flex-wrap justify-center gap-1.5" aria-label="Navigasi nomor soal">
                            <?php foreach ($questions as $index => $q): ?>
                                <button
                                    type="button"
                                    data-jump="<?= $index ?>"
                                    class="step-dot w-8 h-8 rounded-lg border border-slate-200 bg-white text-xs font-semibold text-slate-600 hover:border-slate-400 transition-colors cursor-pointer tabular-nums"
                                    aria-label="Ke soal <?= $index + 1 ?>"
                                >
                                    <?= $index + 1 ?>
                                </button>
                            <?php endforeach; ?>
                        </div>

                        <!-- Tombol Selanjutnya / Lihat Hasil -->
                        <div class="w-full sm:w-auto flex items-center justify-end">
                            <button
                                type="button"
                                id="btn-next"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-upskill-darkblue hover:bg-upskill-blue text-white text-sm font-semibold focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-upskill-pink focus-visible:ring-offset-2 transition-colors cursor-pointer"
                            >
                                <span>Selanjutnya</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>

                            <button
                                type="button"
                                id="btn-finish"
                                class="hidden w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-lg bg-upskill-pink hover:bg-upskill-magenta text-white text-sm font-bold focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-upskill-pink focus-visible:ring-offset-2 transition-colors cursor-pointer"
                            >
                                <span>Lihat Hasil</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <p class="text-center text-xs text-slate-500 mt-8">
            MUSLIM UPSKILL ACADEMY &middot; Evaluasi Pemahaman Mandiri
        </p>
    </main>

    <script>
        (function () {
            const totalSteps      = <?= $totalQuestions ?>;
            const durationMinutes = <?= $durationMinutes ?>;
            const steps           = document.querySelectorAll('.question-step');
            const dots            = document.querySelectorAll('.step-dot');
            const btnPrev         = document.getElementById('btn-prev');
            const btnNext         = document.getElementById('btn-next');
            const btnFinish       = document.getElementById('btn-finish');
            const currentNumber   = document.getElementById('current-number');
            const progressBar     = document.getElementById('progress-bar');
            const answeredCounter = document.getElementById('answered-counter');
            const unansweredAlert = document.getElementById('unanswered-alert');
            const unansweredText  = document.getElementById('unanswered-text');
            const btnJumpEmpty    = document.getElementById('btn-jump-unanswered');
            const form            = document.getElementById('trial-quiz-form');

            let currentStep = 0;
            let firstEmptyStep = -1;

            function isStepAnswered(stepIndex) {
                const section = document.getElementById('step-' + stepIndex);
                return !!(section && section.querySelector('input[type="radio"]:checked'));
            }

            function updateProgressIndicators() {
                let answered = 0;
                dots.forEach((dot, idx) => {
                    const done = isStepAnswered(idx);
                    if (done) answered++;

                    dot.classList.remove(
                        'bg-upskill-darkblue', 'text-white', 'border-upskill-darkblue',
                        'bg-slate-800', 'border-slate-800',
                        'bg-white', 'text-slate-600', 'border-slate-200',
                        'ring-2', 'ring-upskill-pink/40'
                    );

                    if (idx === currentStep) {
                        dot.classList.add('bg-upskill-darkblue', 'text-white', 'border-upskill-darkblue', 'ring-2', 'ring-upskill-pink/40');
                    } else if (done) {
                        dot.classList.add('bg-slate-800', 'text-white', 'border-slate-800');
                    } else {
                        dot.classList.add('bg-white', 'text-slate-600', 'border-slate-200');
                    }
                });

                if (answeredCounter) {
                    answeredCounter.textContent = answered + ' dari ' + totalSteps + ' terjawab';
                }
            }

            function goToStep(index) {
                if (index < 0 || index >= totalSteps) return;

                steps.forEach((el, idx) => {
                    el.classList.toggle('hidden', idx !== index);
                });

                currentStep = index;
                if (currentNumber) currentNumber.textContent = String(currentStep + 1);
                if (progressBar) {
                    progressBar.style.width = Math.round(((currentStep + 1) / totalSteps) * 100) + '%';
                }

                if (btnPrev) btnPrev.disabled = (currentStep === 0);

                const isLast = (currentStep === totalSteps - 1);
                if (btnNext) btnNext.classList.toggle('hidden', isLast);
                if (btnFinish) btnFinish.classList.toggle('hidden', !isLast);

                if (unansweredAlert) unansweredAlert.classList.add('hidden');
                updateProgressIndicators();
            }

            document.querySelectorAll('input[type="radio"]').forEach((radio) => {
                radio.addEventListener('change', () => {
                    if (unansweredAlert) unansweredAlert.classList.add('hidden');
                    updateProgressIndicators();
                });
            });

            if (btnPrev) {
                btnPrev.addEventListener('click', () => goToStep(currentStep - 1));
            }
            if (btnNext) {
                btnNext.addEventListener('click', () => goToStep(currentStep + 1));
            }
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

            // Timer opsional jika Admin menetapkan batas durasi (> 0 menit)
            if (durationMinutes > 0) {
                let remaining = durationMinutes * 60;
                const timerEl = document.getElementById('quiz-timer');

                const tick = () => {
                    if (remaining <= 0) {
                        if (timerEl) timerEl.textContent = '00:00';
                        form.submit();
                        return;
                    }
                    const m = String(Math.floor(remaining / 60)).padStart(2, '0');
                    const s = String(remaining % 60).padStart(2, '0');
                    if (timerEl) timerEl.textContent = m + ':' + s;
                    remaining--;
                    setTimeout(tick, 1000);
                };

                tick();
            }

            goToStep(0);
        })();
    </script>
</body>
</html>

