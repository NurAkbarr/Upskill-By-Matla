<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Hasil Evaluasi & Pembahasan Kuis - MUSLIM UPSKILL ACADEMY') ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/logo.png') ?>">
    <link rel="shortcut icon" href="<?= base_url('favicon.ico') ?>">
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: {
              sans: ['Inter', 'sans-serif'],
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
<body class="bg-slate-50 min-h-screen text-slate-800">

    <?php
        // Helper fungsi format durasi
        $formatDuration = function(int $seconds): string {
            $min = floor($seconds / 60);
            $sec = $seconds % 60;
            return $min > 0 ? "{$min}m {$sec}s" : "{$sec}s";
        };

        $my_rank = (string) ($my_rank ?? '-');
        $isPendingReview = (($my_attempt['grading_status'] ?? '') === 'pending_review');
        $myScore = (float) ($my_attempt['quiz_score'] ?? 0);
        $myDuration = (int) ($my_attempt['duration_seconds'] ?? 0);
        $correctCount = (int) ($my_attempt['correct_count'] ?? 0);
        $totalQuestions = (int) ($my_attempt['total_questions'] ?? count($questions));
    ?>

    <!-- ============================================================== -->
    <!-- 1. STICKY TOP NAVIGATION BAR                                   -->
    <!-- ============================================================== -->
    <header class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-slate-200 px-4 sm:px-8 py-3.5">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
            
            <!-- Tombol Kembali ke Kelas -->
            <a 
                href="<?= base_url('dashboard/course/' . $course['id']) ?>" 
                class="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-slate-600 hover:text-upskill-blue transition-colors group"
            >
                <div class="w-8 h-8 rounded-lg bg-slate-100 group-hover:bg-blue-50 text-slate-500 group-hover:text-upskill-blue flex items-center justify-center transition-colors">
                    <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                    </svg>
                </div>
                <span>Kembali ke Silabus Kelas</span>
            </a>

            <!-- Judul Ringkas Header -->
            <div class="hidden md:block text-right">
                <p class="text-xs font-bold text-upskill-darkblue truncate max-w-md">
                    <?= esc($course['title']) ?>
                </p>
                <p class="text-[11px] text-slate-400 truncate max-w-md">
                    Sesi: <?= esc($session['chapter_title']) ?>
                </p>
            </div>

        </div>
    </header>

    <!-- ============================================================== -->
    <!-- 2. KONTEN UTAMA                                                -->
    <!-- ============================================================== -->
    <main class="max-w-7xl mx-auto px-4 sm:px-8 py-6 sm:py-8 space-y-6">

        <!-- Banner Kondisional (Pending Review) -->
        <?php if ($isPendingReview): ?>
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 sm:p-5 flex items-start gap-3.5 shadow-2xs">
                <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 mt-0.5 font-bold">
                    ⏳
                </div>
                <div>
                    <h4 class="text-sm font-bold text-amber-900">Menunggu Koreksi Manual Instruktur</h4>
                    <p class="text-xs text-amber-800 mt-0.5 leading-relaxed">
                        Jawaban Essay Anda sedang diperiksa oleh Instruktur. Nilai akhir dan peringkat definitif akan diperbarui setelah seluruh butir soal essay selesai dikoreksi.
                    </p>
                </div>
            </div>
        <?php endif; ?>

        <!-- ========================================================== -->
        <!-- 3 KARTU RINGKASAN CAPAIAN PESERTA                         -->
        <!-- ========================================================== -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
            
            <!-- Kartu 1: Skor Akhir -->
            <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-xs">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Skor Evaluasi</span>
                    <?php if ($isPendingReview): ?>
                        <span class="text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                            Poin Sementara
                        </span>
                    <?php else: ?>
                        <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                            Nilai Akhir
                        </span>
                    <?php endif; ?>
                </div>
                <div class="flex items-baseline gap-1">
                    <span class="text-3xl sm:text-4xl font-black text-upskill-darkblue tracking-tight">
                        <?= (int) $myScore ?>
                    </span>
                    <span class="text-slate-400 font-semibold text-sm">/ 100</span>
                </div>
                <p class="text-xs text-slate-500 mt-2 flex items-center gap-1.5 font-medium">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Jawaban Benar: <?= $correctCount ?> / <?= $totalQuestions ?> Soal
                </p>
            </div>

            <!-- Kartu 2: Peringkat Peserta -->
            <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-xs">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Peringkat Anda</span>
                    <?php if ($my_rank === '1' && !$isPendingReview): ?>
                        <span class="text-[10px] font-bold text-amber-900 bg-amber-200 px-2 py-0.5 rounded border border-amber-300">
                            🏆 Juara 1 Tercepat
                        </span>
                    <?php elseif ($isPendingReview): ?>
                        <span class="text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                            ⏳ Menunggu Review
                        </span>
                    <?php else: ?>
                        <span class="text-[10px] font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded">
                            Resmi
                        </span>
                    <?php endif; ?>
                </div>
                <div class="flex items-baseline gap-1">
                    <?php if ($isPendingReview): ?>
                        <span class="text-3xl sm:text-4xl font-black text-slate-400 tracking-tight">
                            #—
                        </span>
                    <?php else: ?>
                        <span class="text-3xl sm:text-4xl font-black text-upskill-darkblue tracking-tight">
                            #<?= $my_rank ?>
                        </span>
                    <?php endif; ?>
                    <span class="text-slate-400 font-semibold text-sm">dari <?= $total_participants ?> Peserta</span>
                </div>
                <p class="text-xs text-slate-500 mt-2 font-medium">
                    <?php if ($my_rank === '1' && !$isPendingReview): ?>
                        Selamat! Anda meraih skor tertinggi dengan waktu tercepat.
                    <?php elseif ($isPendingReview): ?>
                        Peringkat akan ditentukan setelah nilai essay selesai diinput.
                    <?php else: ?>
                        Posisi urutan berdasarkan skor tertinggi & durasi tercepat.
                    <?php endif; ?>
                </p>
            </div>

            <!-- Kartu 3: Durasi Pengerjaan -->
            <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-xs">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Durasi Pengerjaan</span>
                    <span class="text-[10px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded border border-blue-100">
                        CBT Timer
                    </span>
                </div>
                <div class="flex items-baseline gap-1">
                    <span class="text-3xl sm:text-4xl font-black text-slate-800 tracking-tight font-mono">
                        <?= $formatDuration($myDuration) ?>
                    </span>
                </div>
                <p class="text-xs text-slate-400 mt-2 truncate">
                    Dikumpulkan: <?= !empty($my_attempt['completed_at']) ? date('d M Y, H:i', strtotime($my_attempt['completed_at'])) . ' WIB' : '-' ?>
                </p>
            </div>

        </div>

        <!-- ========================================================== -->
        <!-- 4. GRID 2 KOLOM (PEMBAHASAN JAWABAN & PAPAN PERINGKAT)     -->
        <!-- ========================================================== -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            
            <!-- ====================================================== -->
            <!-- KOLOM KIRI (LEBAR 2/3): PEMBAHASAN JAWABAN ANDA        -->
            <!-- ====================================================== -->
            <div class="lg:col-span-2 space-y-4">
                
                <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-xs">
                    <h3 class="text-base font-extrabold text-upskill-darkblue flex items-center gap-2">
                        <svg class="w-5 h-5 text-upskill-pink" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Review & Pembahasan Jawaban Anda</span>
                    </h3>
                    <p class="text-xs text-slate-400 mt-1">
                        Periksa kunci jawaban soal pilihan ganda serta catatan evaluasi instruktur untuk butir soal essay.
                    </p>
                </div>

                <!-- Daftar Soal & Jawaban -->
                <?php if (empty($questions)): ?>
                    <div class="bg-white border border-slate-200 rounded-2xl p-8 text-center text-slate-400 text-xs">
                        Tidak ada data butir soal yang ditemukan pada sesi ini.
                    </div>
                <?php else: ?>
                    <?php foreach ($questions as $qIdx => $q): ?>
                        <?php
                            $qId         = (int) $q['id'];
                            $isEssay     = ($q['question_type'] === 'essay');
                            $userAnswer  = $my_answers[$qId] ?? null;
                            $feedback    = $admin_feedback[$qId] ?? null;
                        ?>
                        <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-xs space-y-4">
                            
                            <!-- Header Soal (Nomor & Tipe) -->
                            <div class="flex items-center justify-between gap-2 border-b border-slate-100 pb-3">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-7 h-7 rounded-xl bg-slate-900 text-white font-black text-xs flex items-center justify-center shrink-0">
                                        <?= $qIdx + 1 ?>
                                    </span>
                                    <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-bold uppercase tracking-wider <?= $isEssay ? 'bg-amber-100 text-amber-900 border border-amber-200' : 'bg-blue-50 text-upskill-blue border border-blue-100' ?>">
                                        <?= $isEssay ? 'Soal Essay / Uraian' : 'Pilihan Ganda' ?>
                                    </span>
                                </div>
                            </div>

                            <!-- Teks Soal -->
                            <div class="text-sm font-semibold text-slate-800 leading-relaxed whitespace-pre-line">
                                <?= esc($q['question_text']) ?>
                            </div>

                            <!-- Jawaban & Pembahasan -->
                            <?php if ($isEssay): ?>
                                <!-- SOAL ESSAY -->
                                <div class="space-y-3 pt-1">
                                    <!-- Kotak Jawaban Peserta -->
                                    <div>
                                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1.5">
                                            Jawaban yang Anda Kirim:
                                        </label>
                                        <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-medium whitespace-pre-line leading-relaxed">
                                            <?= !empty($userAnswer) ? esc($userAnswer) : '<span class="text-slate-400 italic">(Tidak ada jawaban dikirim)</span>' ?>
                                        </div>
                                    </div>

                                    <!-- Kotak Catatan Koreksi Instruktur (Tahap 32A / 32B) -->
                                    <div class="p-4 bg-amber-50/60 border border-amber-200 rounded-xl space-y-2">
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="text-xs font-bold text-amber-950 flex items-center gap-1.5">
                                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Koreksi & Masukan Instruktur:
                                            </span>
                                            <?php if (!empty($feedback['status'])): ?>
                                                <?php
                                                    $st = $feedback['status'];
                                                    if ($st === 'correct') {
                                                        $badgeClass = 'bg-emerald-100 text-emerald-800 border-emerald-200';
                                                        $badgeText  = '✓ Tepat / Benar';
                                                    } elseif ($st === 'partial') {
                                                        $badgeClass = 'bg-amber-200 text-amber-900 border-amber-300';
                                                        $badgeText  = '⚠️ Kurang Tepat / Perlu Perbaikan';
                                                    } else {
                                                        $badgeClass = 'bg-rose-100 text-rose-800 border-rose-200';
                                                        $badgeText  = '✕ Salah';
                                                    }
                                                ?>
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border <?= $badgeClass ?>">
                                                    <?= $badgeText ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-500 border border-slate-200">
                                                    ⏳ Menunggu Pemeriksaan
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <?php if (!empty($feedback['note'])): ?>
                                            <p class="text-xs text-slate-700 bg-white p-3 rounded-lg border border-amber-200/80 whitespace-pre-line leading-relaxed font-medium">
                                                <?= esc($feedback['note']) ?>
                                            </p>
                                        <?php elseif (!empty($feedback['status'])): ?>
                                            <p class="text-xs text-slate-400 italic">
                                                (Instruktur tidak menyertakan catatan teks tambahan)
                                            </p>
                                        <?php else: ?>
                                            <p class="text-xs text-amber-700/80 italic font-medium">
                                                Instruktur sedang meninjau jawaban essay ini. Evaluasi akan tampil di sini setelah dinilai.
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>

                            <?php else: ?>
                                <!-- SOAL PILIHAN GANDA (PG) -->
                                <?php
                                    $options = [
                                        'a' => $q['option_a'],
                                        'b' => $q['option_b'],
                                        'c' => $q['option_c'],
                                        'd' => $q['option_d'],
                                    ];
                                    $correctKey  = strtolower((string)$q['correct_answer']);
                                    $chosenKey   = strtolower((string)$userAnswer);
                                    $isCorrect   = ($chosenKey !== '' && $chosenKey === $correctKey);
                                ?>
                                
                                <div class="space-y-2 pt-1">
                                    <div class="flex items-center justify-between text-xs mb-1">
                                        <span class="font-bold text-slate-400 uppercase tracking-wider text-[11px]">Pilihan Jawaban:</span>
                                        <?php if ($isCorrect): ?>
                                            <span class="text-xs font-bold text-emerald-600 flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Jawaban Anda Benar
                                            </span>
                                        <?php else: ?>
                                            <span class="text-xs font-bold text-rose-600 flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                Jawaban Anda Kurang Tepat
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="grid grid-cols-1 gap-2 text-xs">
                                        <?php foreach ($options as $optKey => $optText): ?>
                                            <?php
                                                if (empty($optText)) continue;
                                                $isThisChosen  = ($chosenKey === $optKey);
                                                $isThisCorrect = ($correctKey === $optKey);

                                                if ($isThisCorrect && $isThisChosen) {
                                                    // Jawaban Benar & Dipilih Peserta
                                                    $cardClass  = 'bg-emerald-50 border-emerald-300 text-emerald-900 font-semibold ring-1 ring-emerald-300';
                                                    $badgeLabel = '<span class="text-[10px] font-bold px-2 py-0.5 rounded bg-emerald-600 text-white shrink-0">✓ Jawaban Anda (Benar)</span>';
                                                    $dotClass   = 'bg-emerald-600 text-white';
                                                } elseif ($isThisChosen && !$isThisCorrect) {
                                                    // Jawaban Salah yang Dipilih Peserta
                                                    $cardClass  = 'bg-rose-50 border-rose-300 text-rose-900 font-semibold ring-1 ring-rose-200';
                                                    $badgeLabel = '<span class="text-[10px] font-bold px-2 py-0.5 rounded bg-rose-600 text-white shrink-0">✕ Jawaban Anda</span>';
                                                    $dotClass   = 'bg-rose-600 text-white';
                                                } elseif ($isThisCorrect) {
                                                    // Kunci Jawaban yang Seharusnya
                                                    $cardClass  = 'bg-emerald-50/70 border-emerald-300 text-emerald-900 font-medium';
                                                    $badgeLabel = '<span class="text-[10px] font-bold px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 border border-emerald-200 shrink-0">✓ Kunci Jawaban Benar</span>';
                                                    $dotClass   = 'bg-emerald-200 text-emerald-800';
                                                } else {
                                                    // Opsi Netral
                                                    $cardClass  = 'bg-slate-50/60 border-slate-200 text-slate-600';
                                                    $badgeLabel = '';
                                                    $dotClass   = 'bg-slate-200 text-slate-600';
                                                }
                                            ?>
                                            <div class="p-3 rounded-xl border flex items-center justify-between gap-3 transition-colors <?= $cardClass ?>">
                                                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                                                    <span class="w-6 h-6 rounded-lg flex items-center justify-center font-bold text-xs shrink-0 <?= $dotClass ?>">
                                                        <?= strtoupper($optKey) ?>
                                                    </span>
                                                    <span class="leading-relaxed"><?= esc($optText) ?></span>
                                                </div>
                                                <?= $badgeLabel ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>

            <!-- ====================================================== -->
            <!-- KOLOM KANAN (LEBAR 1/3): PAPAN PERINGKAT (LEADERBOARD) -->
            <!-- ====================================================== -->
            <div class="lg:col-span-1 space-y-4">
                
                <div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
                    
                    <!-- Header Leaderboard -->
                    <div class="p-5 border-b border-slate-100 bg-slate-50/50">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-extrabold text-upskill-darkblue flex items-center gap-2">
                                <span class="text-base">🏆</span>
                                <span>Papan Peringkat Sesi</span>
                            </h3>
                            <span class="text-[11px] font-semibold text-slate-500">
                                <?= $total_participants ?> Peserta
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">
                            Urutan pemenang ditentukan dari skor tertinggi dan durasi tercepat.
                        </p>
                    </div>

                    <!-- List Peringkat Peserta -->
                    <div class="divide-y divide-slate-100 text-xs">
                        <?php if (empty($leaderboard)): ?>
                            <div class="p-6 text-center text-slate-400 text-xs">
                                Belum ada peserta yang menyelesaikan kuis ini.
                            </div>
                        <?php else: ?>
                            <?php
                                $lbRank = 0;
                                foreach ($leaderboard as $row):
                                    $isRowGraded = ($row['grading_status'] === 'graded');
                                    if ($isRowGraded) {
                                        $lbRank++;
                                        $displayRank = $lbRank;
                                    } else {
                                        $displayRank = '-';
                                    }

                                    $isMe = ((int) $row['user_id'] === (int) $user_id);
                                    $isFirstPlace = ($displayRank === 1 && $isRowGraded);
                                    
                                    // Kelas Styling
                                    $itemClass = $isFirstPlace 
                                        ? 'bg-amber-50/80 border-l-4 border-l-amber-400' 
                                        : ($isMe ? 'bg-blue-50/60 border-l-4 border-l-upskill-blue' : 'hover:bg-slate-50/60');
                            ?>
                                <div class="p-3.5 sm:p-4 flex items-center justify-between gap-3 transition-colors <?= $itemClass ?>">
                                    
                                    <!-- Nomor Urut & Nama -->
                                    <div class="flex items-center gap-3 min-w-0 flex-1">
                                        <div class="shrink-0 text-center">
                                            <?php if ($isFirstPlace): ?>
                                                <span class="w-7 h-7 rounded-lg bg-amber-400 text-amber-950 font-black text-xs flex items-center justify-center shadow-xs">
                                                    1
                                                </span>
                                            <?php elseif ($displayRank === 2): ?>
                                                <span class="w-7 h-7 rounded-lg bg-slate-200 text-slate-800 font-bold text-xs flex items-center justify-center">
                                                    2
                                                </span>
                                            <?php elseif ($displayRank === 3): ?>
                                                <span class="w-7 h-7 rounded-lg bg-orange-100 text-orange-900 font-bold text-xs flex items-center justify-center">
                                                    3
                                                </span>
                                            <?php elseif ($isRowGraded): ?>
                                                <span class="w-6 h-6 rounded-md bg-slate-100 text-slate-600 font-semibold text-[11px] flex items-center justify-center">
                                                    <?= $displayRank ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="w-6 h-6 rounded-md bg-amber-50 text-amber-700 border border-amber-200 font-bold text-[10px] flex items-center justify-center" title="Menunggu Koreksi">
                                                    ⏳
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-bold text-slate-800 truncate text-xs <?= $isMe ? 'text-upskill-blue' : '' ?>">
                                                    <?= esc($row['student_name']) ?>
                                                </span>
                                                <?php if ($isMe): ?>
                                                    <span class="text-[9px] font-black uppercase px-1.5 py-0.5 rounded bg-upskill-blue text-white shrink-0">
                                                        Anda
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <span class="text-[11px] text-slate-400 font-mono block mt-0.5">
                                                ⏱ <?= $formatDuration((int) ($row['duration_seconds'] ?? 0)) ?>
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Nilai / Status -->
                                    <div class="text-right shrink-0">
                                        <?php if ($isRowGraded): ?>
                                            <span class="font-black text-sm <?= $isFirstPlace ? 'text-amber-950 font-mono' : 'text-slate-800 font-mono' ?>">
                                                <?= (int) ($row['quiz_score'] ?? 0) ?>
                                            </span>
                                            <span class="text-[10px] text-slate-400 block font-semibold">Poin</span>
                                        <?php else: ?>
                                            <span class="text-[10px] font-bold text-amber-700 bg-amber-100 px-2 py-0.5 rounded">
                                                ⏳ Review
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Footer Info Leaderboard -->
                    <div class="p-3 bg-slate-50 border-t border-slate-100 text-center">
                        <span class="text-[11px] text-slate-400">
                            Peringkat diperbarui secara otomatis setiap kali ada evaluasi selesai.
                        </span>
                    </div>

                </div>

            </div>

        </div>

    </main>

</body>
</html>
