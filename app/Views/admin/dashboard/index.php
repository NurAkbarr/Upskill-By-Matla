<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<!-- Header Ringkasan Operasional -->
<div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-xs">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-pink-50 border border-pink-100 text-upskill-pink text-xs font-bold uppercase tracking-wider mb-2">
                <span class="w-2 h-2 rounded-full bg-upskill-pink animate-pulse"></span>
                Pusat Kendali Operasional
            </div>
            <h2 class="text-xl sm:text-2xl font-extrabold text-upskill-darkblue tracking-tight">
                Ringkasan Operasional Platform
            </h2>
            <p class="text-sm text-slate-500 mt-1 leading-relaxed">
                Pantau statistik peserta, kurikulum kelas aktif, dan aktivitas evaluasi kuis CBT secara real-time.
            </p>
        </div>
        <div class="shrink-0 flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200/80">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Pembaruan: <?= date('d M Y') ?>
            </span>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- 3 KARTU STATISTIK UTAMA                                        -->
<!-- ============================================================== -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    
    <!-- Kartu 1: Total Peserta Terdaftar -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 hover:border-upskill-pink/50 hover:shadow-md transition-all duration-300">
        <div class="flex items-center justify-between mb-4">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Peserta Terdaftar</p>
            <div class="w-10 h-10 rounded-xl bg-pink-50 text-upskill-pink flex items-center justify-center border border-pink-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
        </div>
        <div class="flex items-baseline justify-between gap-2">
            <span class="text-3xl font-extrabold text-upskill-darkblue tracking-tight">
                <?= number_format($total_users, 0, ',', '.') ?> <span class="text-base font-semibold text-slate-500">Peserta</span>
            </span>
            <span class="text-xs font-semibold text-upskill-pink bg-pink-50 px-2.5 py-1 rounded-lg border border-pink-100 shrink-0">
                <?= number_format($total_admins, 0, ',', '.') ?> Admin/Mentor
            </span>
        </div>
        <p class="text-xs text-slate-400 mt-2.5">Pengguna aktif terdaftar di platform</p>
    </div>

    <!-- Kartu 2: Katalog Kelas & Sesi -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 hover:border-upskill-blue/50 hover:shadow-md transition-all duration-300">
        <div class="flex items-center justify-between mb-4">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Katalog Kelas & Sesi</p>
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-upskill-blue flex items-center justify-center border border-blue-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
        </div>
        <div class="flex items-baseline justify-between gap-2">
            <span class="text-3xl font-extrabold text-upskill-darkblue tracking-tight">
                <?= number_format($total_courses, 0, ',', '.') ?> <span class="text-base font-semibold text-slate-500">Program Kelas</span>
            </span>
            <span class="text-xs font-semibold text-upskill-blue bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-100 shrink-0">
                <?= number_format($total_sessions, 0, ',', '.') ?> Sesi Materi
            </span>
        </div>
        <p class="text-xs text-slate-400 mt-2.5">Kurikulum dan modul belajar tersedia (<?= (int)$published_courses ?> Terbit)</p>
    </div>

    <!-- Kartu 3: Total Evaluasi Kuis CBT -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 hover:border-purple-300 hover:shadow-md transition-all duration-300">
        <div class="flex items-center justify-between mb-4">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Evaluasi Kuis CBT</p>
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center border border-purple-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
        </div>
        <div class="flex items-baseline justify-between gap-2">
            <span class="text-3xl font-extrabold text-upskill-darkblue tracking-tight">
                <?= number_format($total_quiz_attempts, 0, ',', '.') ?> <span class="text-base font-semibold text-slate-500">Ujian Selesai</span>
            </span>
            <?php if ($pending_essay_count > 0): ?>
                <span class="text-xs font-bold text-amber-700 bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-200 flex items-center gap-1 shrink-0 animate-pulse">
                    ⏳ <?= number_format($pending_essay_count, 0, ',', '.') ?> Perlu Koreksi
                </span>
            <?php else: ?>
                <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-100 shrink-0">
                    ✓ Semua Terkoreksi
                </span>
            <?php endif; ?>
        </div>
        <p class="text-xs text-slate-400 mt-2.5">Total partisipasi pengerjaan kuis peserta</p>
    </div>

</div>

<!-- ============================================================== -->
<!-- GRID 2 KOLOM (AKTIVITAS KUIS TERBARU & PINTASAN MANAJEMEN)      -->
<!-- ============================================================== -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
    
    <!-- Kolom Kiri (Lebar 2 Kolom - lg:col-span-2): Tabel Aktivitas Evaluasi Kuis Terbaru -->
    <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h3 class="text-base font-bold text-upskill-darkblue flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-upskill-pink"></span>
                    Aktivitas Evaluasi Kuis Terbaru
                </h3>
                <p class="text-xs text-slate-400 mt-0.5">5 peserta terakhir yang baru saja mengumpulkan kuis CBT</p>
            </div>
            <a href="<?= base_url('admin/reports') ?>" class="text-xs font-bold text-upskill-pink hover:text-upskill-magenta transition-colors inline-flex items-center gap-1">
                Lihat Semua di Laporan Nilai
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-3 px-6">Peserta</th>
                        <th class="py-3 px-4">Kelas & Sesi</th>
                        <th class="py-3 px-4 text-center">Durasi</th>
                        <th class="py-3 px-6 text-center">Nilai / Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (empty($recent_submissions)): ?>
                        <tr>
                            <td colspan="4" class="py-12 px-6 text-center text-slate-400">
                                <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <p class="font-medium text-slate-600">Belum Ada Aktivitas Evaluasi Kuis</p>
                                <p class="text-xs text-slate-400 mt-0.5">Pengerjaan kuis CBT oleh peserta akan otomatis tampil di sini secara real-time.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recent_submissions as $sub): ?>
                            <?php
                                $duration = (int) ($sub['duration_seconds'] ?? 0);
                                $min = floor($duration / 60);
                                $sec = $duration % 60;
                                $durationFormatted = $min > 0 ? "{$min}m {$sec}s" : "{$sec}s";
                                $isPending = ($sub['grading_status'] ?? '') === 'pending_review';
                                $score = (float) ($sub['quiz_score'] ?? 0);
                            ?>
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-3.5 px-6">
                                    <div class="font-bold text-slate-800 text-sm">
                                        <?= esc($sub['student_name']) ?>
                                    </div>
                                    <div class="text-[11px] text-slate-400">
                                        <?= esc($sub['student_email']) ?>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 max-w-xs">
                                    <div class="font-semibold text-slate-700 truncate" title="<?= esc($sub['course_title']) ?>">
                                        <?= esc($sub['course_title']) ?>
                                    </div>
                                    <div class="text-[11px] text-slate-400 truncate" title="<?= esc($sub['session_title']) ?>">
                                        <?= esc($sub['session_title']) ?>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-center text-slate-600 font-mono font-medium">
                                    <?= $durationFormatted ?>
                                </td>
                                <td class="py-3.5 px-6 text-center">
                                    <?php if ($isPending): ?>
                                        <a href="<?= base_url('admin/reports?session_id=' . $sub['session_id']) ?>" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100 transition-colors">
                                            ⏳ Perlu Koreksi
                                        </a>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Nilai: <?= (int)$score ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Kolom Kanan (Lebar 1 Kolom): Kartu Pintasan Manajemen -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs">
        <div class="mb-5">
            <h3 class="text-base font-bold text-upskill-darkblue flex items-center gap-2">
                <svg class="w-5 h-5 text-upskill-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                Pintasan Manajemen
            </h3>
            <p class="text-xs text-slate-400 mt-0.5">Aksi cepat administrasi platform</p>
        </div>

        <div class="space-y-3">
            <!-- 1. Tambah Kelas Baru -->
            <a href="<?= base_url('admin/courses/create') ?>" class="group flex items-center justify-between p-3.5 rounded-xl border border-slate-200 hover:border-upskill-pink hover:bg-pink-50/40 transition-all duration-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-pink-50 text-upskill-pink flex items-center justify-center group-hover:scale-105 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-bold text-slate-800 group-hover:text-upskill-pink transition-colors">+ Tambah Kelas Baru</div>
                        <div class="text-xs text-slate-400">Buat program & kurikulum</div>
                    </div>
                </div>
                <svg class="w-4 h-4 text-slate-300 group-hover:text-upskill-pink group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>

            <!-- 2. Kelola Data Pengguna -->
            <a href="<?= base_url('admin/users') ?>" class="group flex items-center justify-between p-3.5 rounded-xl border border-slate-200 hover:border-upskill-blue hover:bg-blue-50/40 transition-all duration-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-50 text-upskill-blue flex items-center justify-center group-hover:scale-105 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-bold text-slate-800 group-hover:text-upskill-blue transition-colors">Kelola Data Pengguna</div>
                        <div class="text-xs text-slate-400">Peserta, mentor & instansi</div>
                    </div>
                </div>
                <svg class="w-4 h-4 text-slate-300 group-hover:text-upskill-blue group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>

            <!-- 3. Periksa Laporan Nilai -->
            <a href="<?= base_url('admin/reports') ?>" class="group flex items-center justify-between p-3.5 rounded-xl border border-slate-200 hover:border-purple-500 hover:bg-purple-50/40 transition-all duration-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-purple-50 text-purple-700 flex items-center justify-center group-hover:scale-105 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-bold text-slate-800 group-hover:text-purple-700 transition-colors">Periksa Laporan Nilai</div>
                        <div class="text-xs text-slate-400">Leaderboard & koreksi essay</div>
                    </div>
                </div>
                <svg class="w-4 h-4 text-slate-300 group-hover:text-purple-700 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>

</div>

<?= $this->endSection() ?>
