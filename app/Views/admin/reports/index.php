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
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                <?php if (empty($reports)): ?>
                    <tr>
                        <td colspan="7" class="py-12 px-4 text-center text-slate-400">
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
                    <?php foreach ($reports as $idx => $row): ?>
                        <?php 
                            $isRank1 = ($idx === 0);
                            $isRank2 = ($idx === 1);
                            $isRank3 = ($idx === 2);

                            // Background khusus peringkat 1
                            $rowClass = $isRank1 
                                ? 'bg-amber-50/70 border-l-4 border-l-amber-400 hover:bg-amber-50 transition-colors' 
                                : 'hover:bg-slate-50/70 transition-colors';
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
                                <?php else: ?>
                                    <span class="w-7 h-7 mx-auto rounded-lg bg-slate-100 text-slate-600 font-semibold text-xs flex items-center justify-center">
                                        <?= $idx + 1 ?>
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

                            <!-- 6. Jawaban Benar -->
                            <td class="py-4 px-4 text-center whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span><?= (int) ($row['correct_count'] ?? 0) ?> / <?= (int) ($row['total_questions'] ?? 0) ?> Benar</span>
                                </span>
                            </td>

                            <!-- 7. Nilai Akhir -->
                            <td class="py-4 px-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-baseline gap-0.5">
                                    <span class="text-lg font-mono font-extrabold text-upskill-darkblue">
                                        <?= (int) ($row['quiz_score'] ?? 0) ?>
                                    </span>
                                    <span class="text-xs text-slate-400 font-semibold">/ 100</span>
                                </div>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?= $this->endSection() ?>
