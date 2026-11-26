<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<?php
    $isEdit = ! empty($quiz);
    $actionUrl = $isEdit
        ? base_url('admin/trial-quizzes/update/' . $quiz['id'])
        : base_url('admin/trial-quizzes/store');
?>

<div class="space-y-2">
    <a href="<?= base_url('admin/trial-quizzes') ?>" class="inline-flex items-center text-xs font-bold text-upskill-blue hover:text-upskill-pink transition-colors gap-1">
        &larr; Kembali ke Daftar Trial Quiz
    </a>
    <h2 class="text-2xl font-extrabold text-upskill-darkblue tracking-tight">
        <?= $isEdit ? 'Edit Pengaturan Quiz' : 'Tambah Trial Quiz Baru' ?>
    </h2>
    <p class="text-sm text-slate-600">
        Atur judul, topik, mode akses (Trial tanpa login atau Khusus Member), serta kelas rekomendasi opsional.
    </p>
</div>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
        <p class="font-bold mb-1.5">Mohon periksa kembali isian formulir:</p>
        <ul class="list-disc list-inside space-y-1 text-xs text-rose-700">
            <?php foreach (session()->getFlashdata('errors') as $err): ?>
                <li><?= esc($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-xs max-w-3xl">
    <form action="<?= $actionUrl ?>" method="POST" class="space-y-5">
        <?= csrf_field() ?>

        <div>
            <label for="title" class="block text-xs font-bold uppercase tracking-wider text-upskill-darkblue mb-1.5">
                Judul Quiz
            </label>
            <input
                type="text"
                id="title"
                name="title"
                required
                value="<?= esc(old('title', $quiz['title'] ?? '')) ?>"
                placeholder="Contoh: Tes Pemahaman Ilmu Tauhid Dasar"
                class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-upskill-pink/20 focus:border-upskill-pink"
            >
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label for="topic_label" class="block text-xs font-bold uppercase tracking-wider text-upskill-darkblue mb-1.5">
                    Label Topik (Opsional)
                </label>
                <input
                    type="text"
                    id="topic_label"
                    name="topic_label"
                    value="<?= esc(old('topic_label', $quiz['topic_label'] ?? '')) ?>"
                    placeholder="Contoh: Ilmu Tauhid, Fiqih Ibadah"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-upskill-pink/20 focus:border-upskill-pink"
                >
            </div>

            <div>
                <label for="duration_minutes" class="block text-xs font-bold uppercase tracking-wider text-upskill-darkblue mb-1.5">
                    Batas Durasi (Menit)
                </label>
                <input
                    type="number"
                    id="duration_minutes"
                    name="duration_minutes"
                    min="0"
                    value="<?= esc(old('duration_minutes', $quiz['duration_minutes'] ?? 0)) ?>"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-upskill-pink/20 focus:border-upskill-pink"
                >
                <span class="text-[11px] text-slate-500 mt-1 block">Isi 0 jika tanpa batas waktu (disarankan untuk Trial Quiz).</span>
            </div>
        </div>

        <div>
            <label for="description" class="block text-xs font-bold uppercase tracking-wider text-upskill-darkblue mb-1.5">
                Deskripsi Singkat
            </label>
            <textarea
                id="description"
                name="description"
                rows="3"
                placeholder="Ringkasan singkat tujuan quiz ini..."
                class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-upskill-pink/20 focus:border-upskill-pink"
            ><?= esc(old('description', $quiz['description'] ?? '')) ?></textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label for="status" class="block text-xs font-bold uppercase tracking-wider text-upskill-darkblue mb-1.5">
                    Status Publikasi
                </label>
                <?php $currentStatus = old('status', $quiz['status'] ?? 'published'); ?>
                <select
                    id="status"
                    name="status"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-upskill-pink/20 focus:border-upskill-pink"
                >
                    <option value="published" <?= $currentStatus === 'published' ? 'selected' : '' ?>>Published (Aktif)</option>
                    <option value="draft" <?= $currentStatus === 'draft' ? 'selected' : '' ?>>Draft (Disembunyikan)</option>
                </select>
            </div>

            <div>
                <label for="recommended_course_id" class="block text-xs font-bold uppercase tracking-wider text-upskill-darkblue mb-1.5">
                    Rekomendasi Kelas (Opsional)
                </label>
                <?php $selectedCourse = (int) old('recommended_course_id', $quiz['recommended_course_id'] ?? 0); ?>
                <select
                    id="recommended_course_id"
                    name="recommended_course_id"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-upskill-pink/20 focus:border-upskill-pink"
                >
                    <option value="">-- Tidak ditautkan ke kelas --</option>
                    <?php foreach ($courses as $c): ?>
                        <option value="<?= (int) $c['id'] ?>" <?= $selectedCourse === (int) $c['id'] ? 'selected' : '' ?>>
                            <?= esc($c['title']) ?> (<?= esc($c['status']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="text-[11px] text-slate-500 mt-1 block">Ditampilkan di halaman hasil sebagai jembatan menuju kelas terkait.</span>
            </div>
        </div>

        <!-- Toggle Trial Utama -->
        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
            <label class="flex items-start gap-3 cursor-pointer">
                <input
                    type="checkbox"
                    name="is_trial"
                    value="1"
                    <?= old('is_trial', $quiz['is_trial'] ?? 0) ? 'checked' : '' ?>
                    class="mt-1 w-4 h-4 rounded border-slate-300 text-upskill-pink focus:ring-upskill-pink"
                >
                <div>
                    <span class="text-sm font-bold text-slate-900 block">Jadikan Quiz Trial Utama (Tanpa Login)</span>
                    <span class="text-xs text-slate-600 leading-relaxed block mt-0.5">
                        Pengunjung yang mengklik menu <strong>Quiz</strong> di halaman depan akan langsung mengerjakan quiz ini tanpa login. Mengaktifkan opsi ini otomatis menonaktifkan status Trial Utama pada quiz lain.
                    </span>
                </div>
            </label>
        </div>

        <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
            <a
                href="<?= base_url('admin/trial-quizzes') ?>"
                class="px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors"
            >
                Batal
            </a>
            <button
                type="submit"
                class="px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-upskill-pink hover:bg-upskill-magenta transition-colors shadow-xs cursor-pointer"
            >
                <?= $isEdit ? 'Simpan Perubahan' : 'Buat Quiz & Lanjut Isi Soal' ?>
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

