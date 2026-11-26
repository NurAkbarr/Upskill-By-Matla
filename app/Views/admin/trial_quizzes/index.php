<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<!-- Header Halaman -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-extrabold text-upskill-darkblue tracking-tight">
            Manajemen Trial Quiz &amp; Quiz Mandiri
        </h2>
        <p class="text-sm text-slate-600 mt-1">
            Kelola quiz publik tanpa login (Trial Utama) serta quiz lanjutan khusus member beserta pembahasan dalilnya.
        </p>
    </div>

    <div class="flex items-center gap-2.5 shrink-0">
        <a
            href="<?= base_url('quiz') ?>"
            target="_blank"
            class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl text-xs font-bold text-upskill-darkblue bg-white border border-slate-200 hover:border-upskill-darkblue transition-colors"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            <span>Lihat Halaman Quiz</span>
        </a>

        <a
            href="<?= base_url('admin/trial-quizzes/create') ?>"
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-white bg-upskill-pink hover:bg-upskill-magenta transition-colors shadow-xs"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Tambah Quiz</span>
        </a>
    </div>
</div>

<!-- Daftar Quiz -->
<div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
    <?php if (empty($quizzes)): ?>
        <div class="p-12 text-center space-y-3">
            <div class="w-12 h-12 mx-auto rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <h3 class="text-sm font-bold text-slate-800">Belum ada Trial Quiz</h3>
            <p class="text-xs text-slate-500 max-w-sm mx-auto">
                Buat 1 quiz bertanda Trial Utama (misal 5 soal Ilmu Tauhid) agar pengunjung bisa langsung mencoba dari menu Quiz tanpa login.
            </p>
            <div class="pt-2">
                <a href="<?= base_url('admin/trial-quizzes/create') ?>" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-bold text-white bg-upskill-pink hover:bg-upskill-magenta transition-colors">
                    Buat Quiz Pertama
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <th class="px-6 py-3.5">Judul &amp; Topik</th>
                        <th class="px-6 py-3.5">Mode Akses</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5 text-center">Soal</th>
                        <th class="px-6 py-3.5 text-center">Pengerjaan</th>
                        <th class="px-6 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    <?php foreach ($quizzes as $q): ?>
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-900"><?= esc($q['title']) ?></p>
                                <div class="flex flex-wrap items-center gap-2 mt-1 text-xs text-slate-500">
                                    <?php if (!empty($q['topic_label'])): ?>
                                        <span class="font-medium text-upskill-darkblue"><?= esc($q['topic_label']) ?></span>
                                        <span>&middot;</span>
                                    <?php endif; ?>
                                    <span class="font-mono text-[11px] text-slate-500">/quiz/<?= esc($q['slug']) ?></span>
                                    <?php if (!empty($q['recommended_course_title'])): ?>
                                        <span>&middot;</span>
                                        <span>Kelas: <?= esc($q['recommended_course_title']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ((int) $q['is_trial'] === 1): ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-pink-50 text-upskill-pink border border-pink-200">
                                        Trial Utama (Tanpa Login)
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                        Khusus Member (Login)
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($q['status'] === 'published'): ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                        Published
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                        Draft
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td class="px-6 py-4 text-center font-mono font-bold text-slate-800 tabular-nums">
                                <?= (int) $q['question_count'] ?>
                            </td>

                            <td class="px-6 py-4 text-center font-mono font-semibold text-slate-700 tabular-nums">
                                <?= (int) $q['attempt_count'] ?>
                            </td>

                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-2">
                                    <a
                                        href="<?= base_url('admin/trial-quizzes/' . $q['id'] . '/questions') ?>"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-upskill-darkblue hover:bg-upskill-blue transition-colors"
                                    >
                                        Kelola Soal
                                    </a>
                                    <a
                                        href="<?= base_url('admin/trial-quizzes/edit/' . $q['id']) ?>"
                                        class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-100 transition-colors"
                                    >
                                        Edit
                                    </a>
                                    <button
                                        type="button"
                                        data-href="<?= base_url('admin/trial-quizzes/delete/' . $q['id']) ?>"
                                        data-message="Quiz '<?= esc($q['title'], 'attr') ?>' beserta seluruh butir soal dan riwayat pengerjaannya akan dihapus permanen."
                                        class="btn-delete inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold text-rose-700 bg-rose-50 border border-rose-200 hover:bg-rose-100 transition-colors cursor-pointer"
                                    >
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

