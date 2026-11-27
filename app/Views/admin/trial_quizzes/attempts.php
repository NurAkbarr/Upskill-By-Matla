<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<?php
    $formatDuration = static function (int $seconds): string {
        $min = (int) floor($seconds / 60);
        $sec = $seconds % 60;
        return $min > 0 ? "{$min}m {$sec}d" : "{$sec} detik";
    };

    $toneBadge = static function (string $tone): string {
        return match ($tone) {
            'emerald' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
            'blue'    => 'bg-blue-50 text-upskill-darkblue border-blue-200',
            'indigo'  => 'bg-indigo-50 text-upskill-darkblue border-indigo-200',
            'amber'   => 'bg-amber-50 text-amber-900 border-amber-200',
            default   => 'bg-rose-50 text-rose-800 border-rose-200',
        };
    };
?>

<!-- Header Halaman -->
<div class="space-y-2">
    <a href="<?= base_url('admin/trial-quizzes') ?>" class="inline-flex items-center text-xs font-bold text-upskill-blue hover:text-upskill-pink transition-colors gap-1">
        &larr; Kembali ke Daftar Trial Quiz
    </a>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-2xl font-extrabold text-upskill-darkblue tracking-tight">
                Riwayat Pengerjaan: <?= esc($quiz['title']) ?>
            </h2>
            <p class="text-sm text-slate-600 mt-1">
                Pantau total pengerjaan, rasio konversi tamu menjadi akun terdaftar, serta nilai masing-masing peserta.
            </p>
        </div>

        <a
            href="<?= base_url('admin/trial-quizzes/' . $quiz['id'] . '/questions') ?>"
            class="self-start sm:self-auto inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-upskill-darkblue bg-white border border-slate-200 hover:border-upskill-darkblue transition-colors shrink-0"
        >
            Kelola Soal
        </a>
    </div>
</div>

<!-- Ringkasan Statistik Konversi -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-2xs">
        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Pengerjaan</p>
        <p class="text-2xl font-extrabold text-upskill-darkblue font-mono tabular-nums mt-1.5">
            <?= (int) $total_attempts ?>
        </p>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-2xs">
        <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">Daftar / Member</p>
        <div class="flex items-baseline gap-2 mt-1.5">
            <span class="text-2xl font-extrabold text-emerald-800 font-mono tabular-nums"><?= (int) $registered_count ?></span>
            <span class="text-xs font-semibold text-emerald-700 font-mono tabular-nums">(<?= (int) $conversion_rate ?>%)</span>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-2xs">
        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Tamu (Belum Daftar)</p>
        <p class="text-2xl font-extrabold text-slate-800 font-mono tabular-nums mt-1.5">
            <?= (int) $guest_count ?>
        </p>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-2xs">
        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Rata-rata Nilai</p>
        <p class="text-2xl font-extrabold text-upskill-darkblue font-mono tabular-nums mt-1.5">
            <?= esc($avg_score) ?>
        </p>
    </div>
</div>

<!-- Filter & Pencarian -->
<div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-2xs flex flex-col md:flex-row md:items-center justify-between gap-3">
    <div class="flex flex-wrap items-center gap-2">
        <?php
            $baseAttemptsUrl = base_url('admin/trial-quizzes/' . $quiz['id'] . '/attempts');
            $tabs = [
                'all'        => 'Semua (' . (int) $total_attempts . ')',
                'registered' => 'Member / Terdaftar (' . (int) $registered_count . ')',
                'guest'      => 'Tamu Anonim (' . (int) $guest_count . ')',
            ];
        ?>
        <?php foreach ($tabs as $key => $label): ?>
            <a
                href="<?= $baseAttemptsUrl . '?filter=' . $key . ($search !== '' ? '&search=' . urlencode($search) : '') ?>"
                class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-colors <?= $filter === $key ? 'bg-upskill-darkblue text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' ?>"
            >
                <?= esc($label) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <form action="<?= $baseAttemptsUrl ?>" method="GET" class="flex items-center gap-2 w-full md:w-auto">
        <input type="hidden" name="filter" value="<?= esc($filter) ?>">
        <input
            type="text"
            name="search"
            value="<?= esc($search) ?>"
            placeholder="Cari nama atau email peserta..."
            class="flex-1 md:w-64 px-3.5 py-1.5 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-upskill-pink/25 focus:border-upskill-pink"
        >
        <button
            type="submit"
            class="px-3.5 py-1.5 rounded-lg text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 transition-colors cursor-pointer"
        >
            Cari
        </button>
    </form>
</div>

<!-- Tabel Riwayat Pengerjaan -->
<div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
    <?php if (empty($attempts)): ?>
        <div class="p-10 text-center space-y-1.5">
            <p class="text-sm font-bold text-slate-800">Belum ada data pengerjaan pada filter ini</p>
            <p class="text-xs text-slate-500">Riwayat akan muncul otomatis setiap kali tamu maupun member menyelesaikan quiz ini.</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <th class="px-5 py-3.5">Peserta</th>
                        <th class="px-5 py-3.5">Status Akun</th>
                        <th class="px-5 py-3.5">Nilai &amp; Predikat</th>
                        <th class="px-5 py-3.5">Durasi</th>
                        <th class="px-5 py-3.5">Waktu Selesai</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($attempts as $row): ?>
                        <?php
                            $isRegistered = ! empty($row['user_id']);
                            $scoreInt     = (int) round((float) ($row['score'] ?? 0));
                            $predLabel    = $row['predicate']['label'] ?? '-';
                            $predTone     = $row['predicate']['tone'] ?? 'rose';
                        ?>
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-5 py-3.5">
                                <?php if ($isRegistered): ?>
                                    <p class="font-bold text-slate-900"><?= esc($row['user_name'] ?? 'Pengguna') ?></p>
                                    <p class="text-xs text-slate-500"><?= esc($row['user_email'] ?? '-') ?></p>
                                <?php else: ?>
                                    <p class="font-semibold text-slate-700">Tamu (Belum Daftar)</p>
                                    <p class="text-[11px] font-mono text-slate-400">Token: <?= esc(substr((string) $row['attempt_token'], 0, 10)) ?>...</p>
                                <?php endif; ?>
                            </td>

                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <?php if ($isRegistered): ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                        Terdaftar
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                        Tamu Anonim
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <div class="flex items-center gap-2.5">
                                    <div>
                                        <span class="font-mono font-extrabold text-upskill-darkblue tabular-nums"><?= $scoreInt ?></span>
                                        <span class="text-xs text-slate-500 tabular-nums ml-1">(<?= (int) $row['correct_count'] ?>/<?= (int) $row['total_questions'] ?>)</span>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold border <?= $toneBadge($predTone) ?>">
                                        <?= esc($predLabel) ?>
                                    </span>
                                </div>
                            </td>

                            <td class="px-5 py-3.5 whitespace-nowrap font-mono text-xs font-semibold text-slate-700 tabular-nums">
                                <?= esc($formatDuration((int) ($row['duration_seconds'] ?? 0))) ?>
                            </td>

                            <td class="px-5 py-3.5 whitespace-nowrap text-xs text-slate-600 tabular-nums">
                                <?= ! empty($row['completed_at']) ? date('d M Y, H:i', strtotime((string) $row['completed_at'])) . ' WIB' : '-' ?>
                            </td>

                            <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                <button
                                    type="button"
                                    data-href="<?= base_url('admin/trial-quizzes/attempts/delete/' . $row['id']) ?>"
                                    data-message="Riwayat pengerjaan ini akan dihapus permanen dari daftar."
                                    class="btn-delete inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold text-rose-700 bg-rose-50 border border-rose-200 hover:bg-rose-100 transition-colors cursor-pointer"
                                >
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

