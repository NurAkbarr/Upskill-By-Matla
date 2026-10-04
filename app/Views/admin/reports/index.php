<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<?php
/**
 * Helper pemformat durasi pengerjaan kuis
 */
function formatDuration(?int $seconds): string {
    if ($seconds === null || $seconds <= 0) {
        return '< 1 dtk';
    }
    $minutes = (int) floor($seconds / 60);
    $remainingSeconds = $seconds % 60;
    if ($minutes > 0) {
        return $minutes . ' mnt ' . ($remainingSeconds > 0 ? $remainingSeconds . ' dtk' : '');
    }
    return $remainingSeconds . ' dtk';
}
?>

<!-- ============================================================== -->
<!-- 1. HEADER HALAMAN & RINGKASAN                                  -->
<!-- ============================================================== -->
<div class="space-y-2">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-upskill-darkblue tracking-tight">
                Laporan Nilai & Peringkat Peserta
            </h2>
            <p class="text-sm text-slate-500 mt-1">
                Leaderboard hasil evaluasi kuis CBT. Peringkat diurutkan berdasarkan <strong>Nilai Tertinggi</strong>, lalu <strong>Durasi Tercepat</strong>.
            </p>
        </div>
        <div class="flex items-center gap-2 self-start sm:self-auto">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-white border border-slate-200 text-slate-700 shadow-2xs">
                <span class="w-2 h-2 rounded-full bg-upskill-pink"></span>
                Total Ujian Selesai: <strong><?= count($reports) ?></strong>
            </span>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- 2. FORM FILTER & PENCARIAN (GET)                               -->
<!-- ============================================================== -->
<div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-2xs">
    <form action="<?= base_url('admin/reports') ?>" method="GET" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
        
        <!-- Input Text Pencarian Bebas -->
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <input 
                type="text" 
                name="search" 
                value="<?= esc($search ?? '') ?>" 
                placeholder="Cari nama peserta, kelas, atau sesi..." 
                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-upskill-pink/20 focus:border-upskill-pink transition-all bg-slate-50/50 focus:bg-white"
            >
        </div>

        <!-- Filter Dropdown Sesi Pembelajaran -->
        <div class="w-full sm:w-64">
            <select 
                name="session_id" 
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-upskill-pink/20 focus:border-upskill-pink bg-slate-50/50 focus:bg-white cursor-pointer"
            >
                <option value="">-- Semua Sesi Ujian --</option>
                <?php foreach ($sessions as $s): ?>
                    <option value="<?= $s['id'] ?>" <?= (!empty($sessionId) && $sessionId == $s['id']) ? 'selected' : '' ?>>
                        <?= esc($s['course_title']) ?> - <?= esc($s['chapter_title']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Tombol Aksi Cari & Reset -->
        <div class="flex items-center gap-2">
            <button 
                type="submit" 
                class="px-5 py-2.5 rounded-xl bg-upskill-pink hover:bg-upskill-magenta text-white font-bold text-sm shadow-xs transition-colors cursor-pointer flex items-center justify-center gap-1.5"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <span>Cari</span>
            </button>

            <?php if (!empty($search) || !empty($sessionId)): ?>
                <a 
                    href="<?= base_url('admin/reports') ?>" 
                    class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-600 hover:text-slate-900 hover:bg-slate-100 font-semibold text-sm transition-colors cursor-pointer flex items-center justify-center"
                    title="Reset Pencarian"
                >
                    Reset
                </a>
            <?php endif; ?>
        </div>

    </form>
</div>

<!-- ============================================================== -->
<!-- 3. TABEL LEADERBOARD PERINGKAT PESERTA                          -->
<!-- ============================================================== -->
<div class="bg-white border border-slate-200/80 rounded-2xl shadow-2xs overflow-hidden">
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    <th scope="col" class="py-3.5 px-4 text-center w-16">Peringkat</th>
                    <th scope="col" class="py-3.5 px-4">Nama Peserta</th>
                    <th scope="col" class="py-3.5 px-4">Katalog Kelas</th>
                    <th scope="col" class="py-3.5 px-4">Sesi Evaluasi</th>
                    <th scope="col" class="py-3.5 px-4">Waktu Submit & Durasi</th>
                    <th scope="col" class="py-3.5 px-4 text-center">Jawaban Benar</th>
                    <th scope="col" class="py-3.5 px-4 text-right">Nilai Akhir</th>
                    <th scope="col" class="py-3.5 px-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                <?php if (empty($reports)): ?>
                    <tr>
                        <td colspan="8" class="py-12 px-4 text-center text-slate-400">
                            <div class="w-12 h-12 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <p class="font-bold text-slate-700 text-sm">Tidak Ada Riwayat Ujian</p>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Belum ada peserta yang menyelesaikan kuis evaluasi untuk kriteria pencarian ini.
                            </p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php 
                        $rankCounter = 0;
                        foreach ($reports as $idx => $row): 
                            $isGraded = ($row['grading_status'] ?? 'graded') === 'graded';

                            if ($isGraded) {
                                $rankCounter++;
                                $currentRank = $rankCounter;
                                $isRank1 = ($currentRank === 1);
                                $isRank2 = ($currentRank === 2);
                                $isRank3 = ($currentRank === 3);
                            } else {
                                $currentRank = '-';
                                $isRank1 = false;
                                $isRank2 = false;
                                $isRank3 = false;
                            }

                            // Background khusus peringkat 1 (hanya jika sudah dinilai)
                            $rowClass = $isRank1 
                                ? 'bg-amber-50/70 border-l-4 border-l-amber-400 hover:bg-amber-50 transition-colors' 
                                : ($isGraded ? 'hover:bg-slate-50/70 transition-colors' : 'bg-slate-50/40 hover:bg-slate-50/80 transition-colors');
                    ?>
                        <tr class="<?= $rowClass ?>">
                            
                            <!-- 1. Peringkat -->
                            <td class="py-4 px-4 text-center whitespace-nowrap">
                                <?php if ($isRank1): ?>
                                    <span class="w-8 h-8 mx-auto rounded-xl bg-amber-400 text-amber-950 font-black text-sm flex items-center justify-center shadow-xs">
                                        1
                                    </span>
                                <?php elseif ($isRank2): ?>
                                    <span class="w-8 h-8 mx-auto rounded-xl bg-slate-200 text-slate-700 font-bold text-sm flex items-center justify-center border border-slate-300">
                                        2
                                    </span>
                                <?php elseif ($isRank3): ?>
                                    <span class="w-8 h-8 mx-auto rounded-xl bg-orange-100 text-orange-800 font-bold text-sm flex items-center justify-center border border-orange-200">
                                        3
                                    </span>
                                <?php elseif ($isGraded): ?>
                                    <span class="w-7 h-7 mx-auto rounded-lg bg-slate-100 text-slate-600 font-semibold text-xs flex items-center justify-center">
                                        <?= $currentRank ?>
                                    </span>
                                <?php else: ?>
                                    <span class="w-7 h-7 mx-auto rounded-lg bg-amber-50 text-amber-600 border border-amber-200 font-bold text-xs flex items-center justify-center" title="Menunggu Koreksi Admin">
                                        ⏳
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- 2. Nama Peserta -->
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-bold text-slate-900">
                                        <?= esc($row['student_name']) ?>
                                    </span>
                                    <?php if ($isRank1): ?>
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-200 text-amber-900 border border-amber-300">
                                            🏆 Peringkat 1 (Tercepat)
                                        </span>
                                    <?php elseif ($isRank2): ?>
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-200 text-slate-700 border border-slate-300">
                                            🥈 Peringkat 2
                                        </span>
                                    <?php elseif ($isRank3): ?>
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-orange-100 text-orange-800 border border-orange-200">
                                            🥉 Peringkat 3
                                        </span>
                                    <?php elseif (!$isGraded): ?>
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                            ⏳ Menunggu Penilaian
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <span class="text-xs text-slate-400 block mt-0.5">
                                    <?= esc($row['student_email']) ?>
                                </span>
                            </td>

                            <!-- 3. Katalog Kelas -->
                            <td class="py-4 px-4">
                                <span class="font-semibold text-slate-700 block truncate max-w-xs" title="<?= esc($row['course_title']) ?>">
                                    <?= esc($row['course_title']) ?>
                                </span>
                            </td>

                            <!-- 4. Sesi Evaluasi -->
                            <td class="py-4 px-4">
                                <span class="text-slate-600 block truncate max-w-xs font-medium" title="<?= esc($row['session_title']) ?>">
                                    <?= esc($row['session_title']) ?>
                                </span>
                            </td>

                            <!-- 5. Waktu Submit & Durasi -->
                            <td class="py-4 px-4 whitespace-nowrap">
                                <div class="text-xs text-slate-700 font-medium">
                                    <?= !empty($row['completed_at']) ? date('d M Y, H:i', strtotime($row['completed_at'])) : '-' ?> WIB
                                </div>
                                <div class="mt-1">
                                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md">
                                        <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <?= formatDuration($row['duration_seconds'] ?? 0) ?>
                                    </span>
                                </div>
                            </td>

                            <!-- 6. Jawaban Benar (Tahap 30: Deteksi Pending Review) -->
                            <td class="py-4 px-4 text-center whitespace-nowrap">
                                <?php if (!$isGraded): ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                        <svg class="w-3.5 h-3.5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>⏳ Perlu Koreksi Essay</span>
                                    </span>
                                    <span class="text-[11px] text-slate-400 block mt-1">
                                        (<?= (int) ($row['correct_count'] ?? 0) ?> / <?= (int) ($row['total_questions'] ?? 0) ?> Soal)
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span><?= (int) ($row['correct_count'] ?? 0) ?> / <?= (int) ($row['total_questions'] ?? 0) ?> Soal</span>
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- 7. Nilai Akhir -->
                            <td class="py-4 px-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-baseline gap-0.5">
                                    <span class="text-lg font-mono font-extrabold <?= $isGraded ? 'text-upskill-darkblue' : 'text-slate-400' ?>">
                                        <?= (int) ($row['quiz_score'] ?? 0) ?>
                                    </span>
                                    <span class="text-xs text-slate-400 font-semibold">/ 100</span>
                                </div>
                                <?php if (!$isGraded): ?>
                                    <span class="text-[10px] text-amber-600 font-medium block">Poin Sementara</span>
                                <?php endif; ?>
                            </td>

                            <!-- 8. Kolom Aksi: Periksa / Koreksi Jawaban -->
                            <td class="py-4 px-4 text-center whitespace-nowrap">
                                <button 
                                    type="button" 
                                    class="btn-open-grade inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold <?= !$isGraded ? 'bg-amber-500 hover:bg-amber-600 text-white shadow-xs' : 'bg-slate-100 hover:bg-upskill-pink hover:text-white text-slate-700 border border-slate-200' ?> transition-colors cursor-pointer"
                                    data-id="<?= $row['id'] ?>"
                                    data-student="<?= esc($row['student_name']) ?>"
                                    data-email="<?= esc($row['student_email']) ?>"
                                    data-course="<?= esc($row['course_title']) ?>"
                                    data-session="<?= esc($row['session_title']) ?>"
                                    data-session-id="<?= $row['session_id'] ?>"
                                    data-duration="<?= formatDuration($row['duration_seconds'] ?? 0) ?>"
                                    data-completed-at="<?= !empty($row['completed_at']) ? date('d M Y, H:i', strtotime($row['completed_at'])) . ' WIB' : '-' ?>"
                                    data-correct="<?= (int) ($row['correct_count'] ?? 0) ?>"
                                    data-total="<?= (int) ($row['total_questions'] ?? 0) ?>"
                                    data-score="<?= (int) ($row['quiz_score'] ?? 0) ?>"
                                    data-status="<?= esc($row['grading_status'] ?? 'graded') ?>"
                                    data-answers="<?= esc($row['answers_json'] ?? '{}', 'attr') ?>"
                                >
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <span><?= !$isGraded ? 'Koreksi Jawaban' : 'Periksa Jawaban' ?></span>
                                </button>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<!-- ============================================================== -->
<!-- 4. MODAL KOREKSI & EVALUASI KUIS PESERTA (TAHAP 30)             -->
<!-- ============================================================== -->
<div id="grading-modal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl border border-slate-200 w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden animate-in fade-in zoom-in-95 duration-200">
        
        <!-- Modal Header -->
        <div class="px-6 py-5 border-b border-slate-100 flex items-start justify-between bg-slate-50/50 shrink-0">
            <div>
                <h3 class="text-base font-bold text-upskill-darkblue flex items-center gap-2">
                    <span>Koreksi & Evaluasi Kuis Peserta</span>
                    <span id="modal-status-badge" class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider"></span>
                </h3>
                <p class="text-xs text-slate-500 mt-1">
                    <strong id="modal-student-name" class="text-slate-800"></strong> &bull; <span id="modal-course-title"></span>
                </p>
                <p class="text-[11px] text-slate-400 mt-0.5">
                    Sesi: <span id="modal-session-title"></span> &bull; Durasi: <span id="modal-duration" class="font-semibold text-slate-600"></span> &bull; Submit: <span id="modal-completed-at"></span>
                </p>
            </div>
            <button type="button" onclick="closeGradingModal()" class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-100 transition-colors cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Modal Body: Daftar Pertanyaan & Jawaban Peserta (Scrollable) -->
        <div class="p-6 overflow-y-auto space-y-4 flex-1">
            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Lembar Pertanyaan & Jawaban Peserta</h4>
            <div id="modal-questions-container" class="space-y-4">
                <!-- Diisi secara dinamis oleh JavaScript -->
            </div>
        </div>

        <!-- Modal Footer: Form Koreksi Nilai & Tombol Simpan -->
        <form id="modal-grading-form" method="POST" action="" class="p-6 border-t border-slate-100 bg-slate-50/80 shrink-0">
            <?= csrf_field() ?>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="input-modal-correct" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Jumlah Jawaban Benar
                    </label>
                    <div class="flex items-center gap-2">
                        <input 
                            type="number" 
                            id="input-modal-correct" 
                            name="correct_count" 
                            min="0" 
                            required 
                            class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-upskill-pink/20 focus:border-upskill-pink bg-white"
                        >
                        <span class="text-xs font-semibold text-slate-400 whitespace-nowrap">/ <span id="modal-total-questions">0</span> Soal</span>
                    </div>
                </div>

                <div>
                    <label for="input-modal-score" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Nilai Akhir (0 - 100)
                    </label>
                    <input 
                        type="number" 
                        id="input-modal-score" 
                        name="quiz_score" 
                        min="0" 
                        max="100" 
                        step="any"
                        required 
                        class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-upskill-pink/20 focus:border-upskill-pink bg-white"
                    >
                </div>
            </div>

            <div class="flex items-center justify-end gap-3">
                <button 
                    type="button" 
                    onclick="closeGradingModal()" 
                    class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-100 font-semibold text-xs transition-colors cursor-pointer"
                >
                    Batal
                </button>
                <button 
                    type="submit" 
                    class="px-6 py-2.5 rounded-xl bg-upskill-pink hover:bg-upskill-magenta text-white font-bold text-xs shadow-xs transition-colors cursor-pointer flex items-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Simpan Nilai & Tetapkan Peringkat</span>
                </button>
            </div>
        </form>

    </div>
</div>

<script>
const sessionQuestionsMap = <?= json_encode($sessionQuestions ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
const gradingModal = document.getElementById('grading-modal');
const modalForm = document.getElementById('modal-grading-form');
const modalQuestionsContainer = document.getElementById('modal-questions-container');

function closeGradingModal() {
    if (gradingModal) gradingModal.classList.add('hidden');
}

// Tutup modal jika klik di luar area konten
if (gradingModal) {
    gradingModal.addEventListener('click', function(e) {
        if (e.target === gradingModal) {
            closeGradingModal();
        }
    });
}

document.querySelectorAll('.btn-open-grade').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const student = this.getAttribute('data-student');
        const course = this.getAttribute('data-course');
        const session = this.getAttribute('data-session');
        const sessionId = this.getAttribute('data-session-id');
        const duration = this.getAttribute('data-duration');
        const completedAt = this.getAttribute('data-completed-at');
        const correct = this.getAttribute('data-correct');
        const total = this.getAttribute('data-total');
        const score = this.getAttribute('data-score');
        const status = this.getAttribute('data-status');
        
        let answers = {};
        try {
            answers = JSON.parse(this.getAttribute('data-answers') || '{}');
        } catch (e) {
            answers = {};
        }

        // Set info header modal
        document.getElementById('modal-student-name').textContent = student;
        document.getElementById('modal-course-title').textContent = course;
        document.getElementById('modal-session-title').textContent = session;
        document.getElementById('modal-duration').textContent = duration;
        document.getElementById('modal-completed-at').textContent = completedAt;
        document.getElementById('modal-total-questions').textContent = total;

        const statusBadge = document.getElementById('modal-status-badge');
        if (status === 'pending_review') {
            statusBadge.className = 'px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-amber-100 text-amber-800 border border-amber-200';
            statusBadge.textContent = 'Menunggu Koreksi Manual';
        } else {
            statusBadge.className = 'px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-200';
            statusBadge.textContent = 'Sudah Dinilai';
        }

        // Form action & nilai default
        modalForm.setAttribute('action', '<?= base_url('admin/reports/grade') ?>/' + id);
        const correctInput = document.getElementById('input-modal-correct');
        const scoreInput = document.getElementById('input-modal-score');
        correctInput.value = correct;
        correctInput.setAttribute('max', total);
        scoreInput.value = score;

        // Render daftar soal & jawaban peserta
        const questions = sessionQuestionsMap[sessionId] || [];
        modalQuestionsContainer.innerHTML = '';

        if (questions.length === 0) {
            modalQuestionsContainer.innerHTML = '<p class="text-xs text-slate-400 italic">Data butir pertanyaan tidak ditemukan pada sesi ini.</p>';
        } else {
            questions.forEach((q, idx) => {
                const qId = q.id;
                const studentAnswer = answers[qId] || '';
                const isEssay = (q.question_type === 'essay');

                let qHtml = `
                    <div class="p-4 rounded-2xl border border-slate-200/80 bg-white space-y-2.5">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-slate-900 text-white font-bold text-xs flex items-center justify-center shrink-0">
                                ${idx + 1}
                            </span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider ${isEssay ? 'bg-amber-100 text-amber-800' : 'bg-sky-100 text-sky-800'}">
                                ${isEssay ? 'Essay / Uraian' : 'Pilihan Ganda'}
                            </span>
                        </div>
                        <p class="text-sm font-semibold text-slate-800 whitespace-pre-line leading-relaxed">
                            ${escapeHtml(q.question_text)}
                        </p>
                `;

                if (isEssay) {
                    qHtml += `
                        <div class="mt-2">
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Jawaban Peserta:</label>
                            <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 whitespace-pre-line font-medium leading-relaxed">
                                ${studentAnswer ? escapeHtml(studentAnswer) : '<span class="text-slate-400 italic">(Tidak ada jawaban dikirim)</span>'}
                            </div>
                        </div>
                    `;
                } else {
                    const options = [
                        { key: 'a', text: q.option_a },
                        { key: 'b', text: q.option_b },
                        { key: 'c', text: q.option_c },
                        { key: 'd', text: q.option_d },
                    ];
                    const isCorrect = (studentAnswer && studentAnswer.toLowerCase() === (q.correct_answer || '').toLowerCase());

                    qHtml += `
                        <div class="mt-2 space-y-1.5">
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">
                                Pilihan Jawaban & Pilihan Peserta: 
                                <span class="font-bold ${isCorrect ? 'text-emerald-600' : 'text-rose-600'}">
                                    ${isCorrect ? '✓ Cocok Kunci' : '✗ Salah'}
                                </span>
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                    `;

                    options.forEach(opt => {
                        if (!opt.text) return;
                        const isChosen = (studentAnswer && studentAnswer.toLowerCase() === opt.key);
                        const isKey = ((q.correct_answer || '').toLowerCase() === opt.key);

                        let optClass = 'bg-slate-50 border-slate-200 text-slate-700';
                        if (isKey) {
                            optClass = 'bg-emerald-50 border-emerald-300 text-emerald-900 font-semibold';
                        } else if (isChosen && !isKey) {
                            optClass = 'bg-rose-50 border-rose-300 text-rose-900 font-semibold';
                        }

                        qHtml += `
                            <div class="p-2.5 rounded-lg border flex items-center gap-2 ${optClass}">
                                <span class="w-5 h-5 rounded-md flex items-center justify-center font-bold text-[10px] shrink-0 ${isKey ? 'bg-emerald-600 text-white' : (isChosen ? 'bg-rose-600 text-white' : 'bg-slate-200 text-slate-700')}">
                                    ${opt.key.toUpperCase()}
                                </span>
                                <span class="flex-1">${escapeHtml(opt.text)}</span>
                                ${isChosen ? '<span class="text-[9px] uppercase font-bold px-1.5 py-0.5 rounded bg-slate-900 text-white shrink-0">Dipilih</span>' : ''}
                                ${isKey ? '<span class="text-[9px] uppercase font-bold px-1.5 py-0.5 rounded bg-emerald-600 text-white shrink-0">Kunci</span>' : ''}
                            </div>
                        `;
                    });

                    qHtml += `
                            </div>
                        </div>
                    `;
                }

                qHtml += `</div>`;
                modalQuestionsContainer.innerHTML += qHtml;
            });
        }

        gradingModal.classList.remove('hidden');
    });
});

function escapeHtml(string) {
    const entityMap = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#39;',
        '/': '&#x2F;'
    };
    return String(string).replace(/[&<>"'\/]/g, function (s) {
        return entityMap[s];
    });
}
</script>

<?= $this->endSection() ?>
