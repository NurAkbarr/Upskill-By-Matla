<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?php
    $scoreInt       = (int) round((float) ($attempt['score'] ?? 0));
    $correctCount   = (int) ($attempt['correct_count'] ?? 0);
    $totalQuestions = (int) ($attempt['total_questions'] ?? count($questions));
    $durationSec    = (int) ($attempt['duration_seconds'] ?? 0);
    $durMin         = (int) floor($durationSec / 60);
    $durRem         = $durationSec % 60;
    $durationLabel  = $durMin > 0 ? "{$durMin}m {$durRem}d" : "{$durRem} detik";

    $toneClasses = match ($predicate['tone'] ?? 'blue') {
        'emerald' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
        'amber'   => 'bg-amber-50 text-amber-900 border-amber-200',
        default   => 'bg-blue-50 text-upskill-darkblue border-blue-200',
    };

    $lockedCount = 0;
    if (! $is_unlocked && count($questions) > 1) {
        $lockedCount = count($questions) - 1;
    }

    $registerUrl = base_url('register?trial_token=' . urlencode((string) $attempt['attempt_token']));
    $loginUrl    = base_url('login?trial_token=' . urlencode((string) $attempt['attempt_token']));
?>

<div class="bg-slate-50/70 py-8 sm:py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Flash Success (misal setelah auto-claim pasca daftar/login) -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="font-medium"><?= esc(session()->getFlashdata('success')) ?></span>
            </div>
        <?php endif; ?>

        <!-- Ringkasan Hasil Utama -->
        <section class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-xs">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">

                <!-- Kiri: Skor & Predikat -->
                <div class="space-y-4 max-w-2xl">
                    <div class="flex flex-wrap items-center gap-2">
                        <?php if (!empty($quiz['topic_label'])): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold bg-slate-100 text-slate-700">
                                <?= esc($quiz['topic_label']) ?>
                            </span>
                        <?php endif; ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold border <?= $toneClasses ?>">
                            <?= esc($predicate['label']) ?>
                        </span>
                    </div>

                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-upskill-darkblue tracking-tight">
                            <?= esc($quiz['title']) ?>
                        </h1>
                        <p class="text-sm text-slate-600 mt-1.5 leading-relaxed">
                            <?= esc($predicate['summary']) ?>
                        </p>
                    </div>

                    <!-- Angka Capaian (Inline Baris Rapi, Tanpa Hero-Metric Boxes) -->
                    <div class="flex flex-wrap items-baseline gap-x-8 gap-y-3 pt-2 border-t border-slate-100">
                        <div>
                            <span class="text-xs font-semibold text-slate-500 block">Skor Akhir</span>
                            <div class="flex items-baseline gap-1 mt-0.5">
                                <span class="text-3xl sm:text-4xl font-extrabold text-upskill-darkblue font-mono tabular-nums"><?= $scoreInt ?></span>
                                <span class="text-sm font-semibold text-slate-500">/ 100</span>
                            </div>
                        </div>

                        <div>
                            <span class="text-xs font-semibold text-slate-500 block">Jawaban Benar</span>
                            <p class="text-lg sm:text-xl font-bold text-slate-900 mt-1 font-mono tabular-nums">
                                <?= $correctCount ?> <span class="text-sm font-normal text-slate-500">dari <?= $totalQuestions ?> soal</span>
                            </p>
                        </div>

                        <div>
                            <span class="text-xs font-semibold text-slate-500 block">Waktu Pengerjaan</span>
                            <p class="text-lg sm:text-xl font-bold text-slate-900 mt-1 font-mono tabular-nums">
                                <?= esc($durationLabel) ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Kanan: Aksi Konversi & Bagikan -->
                <div class="lg:w-80 shrink-0 space-y-3 lg:border-l lg:border-slate-100 lg:pl-8">
                    <?php if (! $is_unlocked): ?>
                        <a
                            href="<?= $registerUrl ?>"
                            class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl text-sm font-bold text-white bg-upskill-pink hover:bg-upskill-magenta focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-upskill-pink focus-visible:ring-offset-2 transition-colors shadow-xs"
                        >
                            <span>Daftar Gratis &amp; Buka Pembahasan</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>

                        <p class="text-xs text-slate-600 text-center">
                            Skor otomatis tersimpan. Sudah punya akun?
                            <a href="<?= $loginUrl ?>" class="font-bold text-upskill-darkblue hover:text-upskill-pink underline underline-offset-2">
                                Masuk
                            </a>
                        </p>
                    <?php else: ?>
                        <a
                            href="<?= base_url('dashboard') ?>"
                            class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl text-sm font-bold text-white bg-upskill-darkblue hover:bg-upskill-blue transition-colors"
                        >
                            <span>Buka Dasbor Belajar</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    <?php endif; ?>

                    <a
                        href="<?= esc($whatsapp_share_url) ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-emerald-800 bg-emerald-50 border border-emerald-200 hover:bg-emerald-100 transition-colors"
                    >
                        <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                        </svg>
                        <span>Bagikan Skor ke WhatsApp</span>
                    </a>

                    <a
                        href="<?= esc($retry_url) ?>"
                        class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:text-upskill-darkblue hover:bg-slate-100 transition-colors"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span>Coba Kerjakan Ulang</span>
                    </a>
                </div>

            </div>
        </section>

        <!-- Grid 2 Kolom: Pembahasan Jawaban & Tantangan Quiz Lainnya -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- Kolom Kiri (8/12): Review & Pembahasan -->
            <div class="lg:col-span-8 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-2 pb-1">
                    <div>
                        <h2 class="text-lg sm:text-xl font-extrabold text-upskill-darkblue tracking-tight">
                            Review Jawaban &amp; Pembahasan
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-600 mt-0.5">
                            <?php if ($is_unlocked): ?>
                                Seluruh kunci jawaban dan pembahasan dalil terbuka untuk akun Anda.
                            <?php else: ?>
                                Pembahasan nomor 1 terbuka gratis. Daftar akun untuk membuka <?= $lockedCount ?> pembahasan lainnya.
                            <?php endif; ?>
                        </p>
                    </div>
                </div>

                <?php foreach ($questions as $idx => $q): ?>
                    <?php
                        $qId           = (int) $q['id'];
                        $userAns       = isset($answers[$qId]) ? strtolower((string) $answers[$qId]) : null;
                        $correctAns    = strtolower((string) $q['correct_answer']);
                        $isCorrect     = ($userAns !== null && $userAns === $correctAns);
                        $showFullDetail = $is_unlocked || ($idx === 0);

                        $optionMap = [
                            'a' => $q['option_a'],
                            'b' => $q['option_b'],
                            'c' => $q['option_c'],
                            'd' => $q['option_d'],
                        ];
                    ?>
                    <article class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-xs space-y-4">
                        <!-- Header Nomor & Status -->
                        <div class="flex items-center justify-between gap-3 pb-3 border-b border-slate-100">
                            <span class="text-xs font-bold text-slate-700 font-mono tabular-nums">
                                Soal #<?= $idx + 1 ?>
                            </span>

                            <?php if ($isCorrect): ?>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Benar
                                </span>
                            <?php elseif ($userAns === null): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                    Tidak Dijawab
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-200">
                                    <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Belum Tepat
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Teks Pertanyaan -->
                        <p class="text-sm sm:text-base font-semibold text-slate-900 leading-relaxed whitespace-pre-line">
                            <?= esc($q['question_text']) ?>
                        </p>

                        <!-- Ringkasan Jawaban -->
                        <div class="space-y-2 text-sm">
                            <div class="flex items-start gap-2.5">
                                <span class="text-xs font-semibold text-slate-500 w-28 shrink-0 pt-0.5">Jawaban Anda:</span>
                                <?php if ($userAns !== null && isset($optionMap[$userAns])): ?>
                                    <span class="font-medium <?= $isCorrect ? 'text-emerald-800' : 'text-rose-800' ?>">
                                        <strong class="uppercase"><?= esc($userAns) ?>.</strong> <?= esc($optionMap[$userAns]) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-slate-500 italic">Kosong</span>
                                <?php endif; ?>
                            </div>

                            <?php if ($showFullDetail): ?>
                                <div class="flex items-start gap-2.5">
                                    <span class="text-xs font-semibold text-slate-500 w-28 shrink-0 pt-0.5">Jawaban Benar:</span>
                                    <span class="font-semibold text-emerald-800">
                                        <strong class="uppercase"><?= esc($correctAns) ?>.</strong> <?= esc($optionMap[$correctAns] ?? '-') ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Pembahasan & Dalil (Terbuka vs Terkunci Rapi) -->
                        <?php if ($showFullDetail): ?>
                            <?php if (!empty($q['explanation'])): ?>
                                <div class="pt-4 border-t border-slate-100">
                                    <p class="text-xs font-bold text-upskill-darkblue mb-1.5">Pembahasan &amp; Dalil</p>
                                    <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                                        <?= esc($q['explanation']) ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div class="flex items-start gap-2.5 text-xs sm:text-sm text-slate-600">
                                    <svg class="w-4 h-4 text-slate-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    <span>Kunci jawaban dan pembahasan dalil nomor ini terbuka gratis setelah daftar atau masuk akun.</span>
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

            <!-- Kolom Kanan (4/12): Quiz Lainnya & Rekomendasi Kelas -->
            <aside class="lg:col-span-4 space-y-6 lg:sticky lg:top-28">

                <!-- Tantangan Quiz Lainnya -->
                <div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
                    <div class="p-5 border-b border-slate-100">
                        <h3 class="text-base font-extrabold text-upskill-darkblue">
                            Tantangan Quiz Lainnya
                        </h3>
                        <p class="text-xs text-slate-600 mt-1">
                            <?= $is_logged_in ? 'Pilih topik lain untuk menguji pemahaman Anda.' : 'Daftar gratis untuk mengerjakan seluruh quiz topik lainnya.' ?>
                        </p>
                    </div>

                    <?php if (empty($other_quizzes)): ?>
                        <div class="p-5 text-xs text-slate-600">
                            Quiz topik lanjutan sedang dipersiapkan oleh tim pengajar.
                        </div>
                    <?php else: ?>
                        <div class="divide-y divide-slate-100">
                            <?php foreach ($other_quizzes as $oq): ?>
                                <?php
                                    $oqLocked = ((int) $oq['is_trial'] !== 1 && ! $is_logged_in);
                                    $oqUrl    = base_url('quiz/' . $oq['slug']);
                                ?>
                                <a
                                    href="<?= $oqUrl ?>"
                                    class="block p-5 hover:bg-slate-50 transition-colors group"
                                >
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="space-y-1 min-w-0">
                                            <p class="text-sm font-bold text-slate-900 group-hover:text-upskill-darkblue transition-colors">
                                                <?= esc($oq['title']) ?>
                                            </p>
                                            <div class="flex flex-wrap items-center gap-2 text-xs text-slate-500">
                                                <?php if (!empty($oq['topic_label'])): ?>
                                                    <span><?= esc($oq['topic_label']) ?></span>
                                                    <span>&middot;</span>
                                                <?php endif; ?>
                                                <span class="tabular-nums"><?= (int) $oq['question_count'] ?> soal</span>
                                            </div>
                                        </div>

                                        <?php if ($oqLocked): ?>
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-slate-100 text-slate-600 text-[11px] font-semibold shrink-0">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                </svg>
                                                Login
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-pink-50 text-upskill-pink text-[11px] font-bold shrink-0">
                                                Mulai
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Rekomendasi Kelas Terkait (Opsional, tampil jika ditautkan Admin) -->
                <?php if (!empty($recommended_course)): ?>
                    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-3">
                        <h3 class="text-sm font-extrabold text-upskill-darkblue">
                            Pelajari Materinya Secara Runut
                        </h3>
                        <p class="text-sm font-bold text-slate-900">
                            <?= esc($recommended_course['title']) ?>
                        </p>
                        <?php if (!empty($recommended_course['description'])): ?>
                            <p class="text-xs text-slate-600 leading-relaxed line-clamp-3">
                                <?= esc($recommended_course['description']) ?>
                            </p>
                        <?php endif; ?>
                        <div class="pt-1">
                            <a
                                href="<?= base_url('courses/' . $recommended_course['slug']) ?>"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-upskill-darkblue bg-slate-100 hover:bg-slate-200 transition-colors"
                            >
                                <span>Lihat Program Kelas</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

            </aside>
        </div>

    </div>
</div>

<?= $this->endSection() ?>

