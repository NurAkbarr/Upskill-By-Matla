<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<!-- Header Halaman -->
<div class="space-y-2">
    <a href="<?= base_url('admin/trial-quizzes') ?>" class="inline-flex items-center text-xs font-bold text-upskill-blue hover:text-upskill-pink transition-colors gap-1">
        &larr; Kembali ke Daftar Trial Quiz
    </a>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-2xl font-extrabold text-upskill-darkblue tracking-tight">
                Kelola Soal: <?= esc($quiz['title']) ?>
            </h2>
            <p class="text-sm text-slate-600 mt-1">
                Tambahkan soal pilihan ganda beserta <strong>pembahasan &amp; dalil</strong>. Soal #1 terbuka gratis untuk tamu sebagai pratinjau.
            </p>
        </div>

        <div class="flex items-center gap-2 shrink-0 flex-wrap">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-white border border-slate-200 text-slate-700 tabular-nums">
                Total Soal: <strong><?= count($questions) ?></strong>
            </span>
            <a
                href="<?= base_url((int) $quiz['is_trial'] === 1 ? 'quiz' : 'quiz/' . $quiz['slug']) ?>"
                target="_blank"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-upskill-darkblue bg-white border border-slate-200 hover:border-upskill-darkblue transition-colors"
            >
                Pratinjau Quiz
            </a>
        </div>
    </div>
</div>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
        <p class="font-bold mb-1.5">Mohon lengkapi butir soal dengan benar:</p>
        <ul class="list-disc list-inside space-y-1 text-xs text-rose-700">
            <?php foreach (session()->getFlashdata('errors') as $err): ?>
                <li><?= esc($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<!-- Grid 2 Kolom: Kiri (Form Tambah/Edit), Kanan (Daftar Soal) -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

    <!-- Form Tambah / Edit Soal -->
    <div class="lg:col-span-5 bg-white border border-slate-200 rounded-2xl p-6 shadow-xs sticky top-6">
        <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100">
            <h3 id="question-form-title" class="text-base font-bold text-upskill-darkblue">
                Tambah Butir Soal Baru
            </h3>
            <button
                type="button"
                id="btn-cancel-edit"
                class="hidden text-xs font-semibold text-slate-600 hover:text-rose-700 cursor-pointer"
            >
                Batal Edit
            </button>
        </div>

        <form
            id="trial-question-form"
            action="<?= base_url('admin/trial-quizzes/' . $quiz['id'] . '/questions/store') ?>"
            data-store-url="<?= base_url('admin/trial-quizzes/' . $quiz['id'] . '/questions/store') ?>"
            method="POST"
            class="space-y-4"
        >
            <?= csrf_field() ?>

            <div>
                <label for="input-question" class="block text-xs font-bold uppercase tracking-wider text-upskill-darkblue mb-1.5">
                    Pertanyaan
                </label>
                <textarea
                    id="input-question"
                    name="question_text"
                    rows="3"
                    required
                    placeholder="Tulis teks pertanyaan di sini..."
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-upskill-pink/20 focus:border-upskill-pink"
                ><?= esc(old('question_text')) ?></textarea>
            </div>

            <div class="grid grid-cols-1 gap-3">
                <?php foreach (['a' => 'Pilihan A', 'b' => 'Pilihan B', 'c' => 'Pilihan C', 'd' => 'Pilihan D'] as $key => $label): ?>
                    <div>
                        <label for="input-option-<?= $key ?>" class="block text-xs font-semibold text-slate-700 mb-1">
                            <?= $label ?>
                        </label>
                        <input
                            type="text"
                            id="input-option-<?= $key ?>"
                            name="option_<?= $key ?>"
                            required
                            value="<?= esc(old('option_' . $key)) ?>"
                            placeholder="Isi <?= $label ?>..."
                            class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-upskill-pink/20 focus:border-upskill-pink"
                        >
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="input-correct" class="block text-xs font-bold uppercase tracking-wider text-upskill-darkblue mb-1.5">
                        Kunci Jawaban
                    </label>
                    <select
                        id="input-correct"
                        name="correct_answer"
                        required
                        class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-slate-900 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-upskill-pink/20 focus:border-upskill-pink"
                    >
                        <option value="a" <?= old('correct_answer') === 'a' ? 'selected' : '' ?>>Opsi A</option>
                        <option value="b" <?= old('correct_answer') === 'b' ? 'selected' : '' ?>>Opsi B</option>
                        <option value="c" <?= old('correct_answer') === 'c' ? 'selected' : '' ?>>Opsi C</option>
                        <option value="d" <?= old('correct_answer') === 'd' ? 'selected' : '' ?>>Opsi D</option>
                    </select>
                </div>

                <div>
                    <label for="input-order" class="block text-xs font-bold uppercase tracking-wider text-upskill-darkblue mb-1.5">
                        Urutan Soal
                    </label>
                    <input
                        type="number"
                        id="input-order"
                        name="order_index"
                        min="1"
                        value="<?= esc(old('order_index', $nextOrder)) ?>"
                        class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-slate-900 text-sm text-center font-mono focus:outline-none focus:ring-2 focus:ring-upskill-pink/20 focus:border-upskill-pink"
                    >
                </div>
            </div>

            <div>
                <label for="input-explanation" class="block text-xs font-bold uppercase tracking-wider text-upskill-darkblue mb-1.5">
                    Pembahasan &amp; Dalil
                </label>
                <textarea
                    id="input-explanation"
                    name="explanation"
                    rows="3"
                    placeholder="Jelaskan alasan jawaban benar beserta rujukan ayat/hadis atau referensi..."
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-upskill-pink/20 focus:border-upskill-pink"
                ><?= esc(old('explanation')) ?></textarea>
            </div>

            <button
                type="submit"
                id="btn-submit-question"
                class="w-full py-2.5 px-4 rounded-xl text-sm font-bold text-white bg-upskill-pink hover:bg-upskill-magenta transition-colors shadow-xs cursor-pointer"
            >
                Simpan Butir Soal
            </button>
        </form>
    </div>

    <!-- Daftar Butir Soal -->
    <div class="lg:col-span-7 space-y-4">
        <?php if (empty($questions)): ?>
            <div class="bg-white border border-slate-200 rounded-2xl p-10 text-center space-y-2">
                <p class="text-sm font-bold text-slate-800">Belum ada butir soal</p>
                <p class="text-xs text-slate-500">Gunakan formulir di samping untuk menambahkan minimal 5 soal pilihan ganda beserta pembahasannya.</p>
            </div>
        <?php else: ?>
            <?php foreach ($questions as $idx => $q): ?>
                <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-xs space-y-4">
                    <div class="flex items-start justify-between gap-3 pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-md bg-slate-900 text-white font-mono text-xs font-bold tabular-nums">
                                #<?= (int) $q['order_index'] ?>
                            </span>
                            <?php if ($idx === 0 && (int) $quiz['is_trial'] === 1): ?>
                                <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-800 border border-emerald-200 text-[11px] font-semibold">
                                    Pembahasan Gratis (Pratinjau)
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <button
                                type="button"
                                class="btn-edit-question px-2.5 py-1 rounded-lg text-xs font-semibold text-upskill-darkblue bg-slate-100 hover:bg-slate-200 transition-colors cursor-pointer"
                                data-update-url="<?= base_url('admin/trial-quizzes/questions/update/' . $q['id']) ?>"
                                data-question="<?= esc($q['question_text'], 'attr') ?>"
                                data-a="<?= esc($q['option_a'], 'attr') ?>"
                                data-b="<?= esc($q['option_b'], 'attr') ?>"
                                data-c="<?= esc($q['option_c'], 'attr') ?>"
                                data-d="<?= esc($q['option_d'], 'attr') ?>"
                                data-correct="<?= esc($q['correct_answer'], 'attr') ?>"
                                data-order="<?= (int) $q['order_index'] ?>"
                                data-explanation="<?= esc($q['explanation'] ?? '', 'attr') ?>"
                            >
                                Edit
                            </button>
                            <button
                                type="button"
                                data-href="<?= base_url('admin/trial-quizzes/questions/delete/' . $q['id']) ?>"
                                data-message="Butir soal nomor <?= (int) $q['order_index'] ?> akan dihapus permanen."
                                class="btn-delete px-2.5 py-1 rounded-lg text-xs font-semibold text-rose-700 bg-rose-50 border border-rose-200 hover:bg-rose-100 transition-colors cursor-pointer"
                            >
                                Hapus
                            </button>
                        </div>
                    </div>

                    <p class="text-sm font-semibold text-slate-900 leading-relaxed whitespace-pre-line">
                        <?= esc($q['question_text']) ?>
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                        <?php foreach (['a' => 'option_a', 'b' => 'option_b', 'c' => 'option_c', 'd' => 'option_d'] as $optKey => $optField): ?>
                            <?php $isKey = strtolower((string) $q['correct_answer']) === $optKey; ?>
                            <div class="px-3 py-2 rounded-lg border <?= $isKey ? 'bg-emerald-50 border-emerald-200 text-emerald-900 font-semibold' : 'bg-slate-50 border-slate-200 text-slate-700' ?>">
                                <span class="uppercase font-bold mr-1"><?= $optKey ?>.</span>
                                <?= esc($q[$optField]) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if (!empty($q['explanation'])): ?>
                        <div class="pt-3 border-t border-slate-100">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-upskill-darkblue mb-1">Pembahasan &amp; Dalil</p>
                            <p class="text-xs text-slate-600 leading-relaxed whitespace-pre-line">
                                <?= esc($q['explanation']) ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form       = document.getElementById('trial-question-form');
        const formTitle  = document.getElementById('question-form-title');
        const btnSubmit  = document.getElementById('btn-submit-question');
        const btnCancel  = document.getElementById('btn-cancel-edit');
        const defaultUrl = form.getAttribute('data-store-url');
        const defaultOrd = '<?= (int) $nextOrder ?>';

        const fQuestion    = document.getElementById('input-question');
        const fA           = document.getElementById('input-option-a');
        const fB           = document.getElementById('input-option-b');
        const fC           = document.getElementById('input-option-c');
        const fD           = document.getElementById('input-option-d');
        const fCorrect     = document.getElementById('input-correct');
        const fOrder       = document.getElementById('input-order');
        const fExplanation = document.getElementById('input-explanation');

        function resetToCreate() {
            form.action         = defaultUrl;
            formTitle.textContent = 'Tambah Butir Soal Baru';
            btnSubmit.textContent = 'Simpan Butir Soal';
            btnCancel.classList.add('hidden');
            fQuestion.value    = '';
            fA.value           = '';
            fB.value           = '';
            fC.value           = '';
            fD.value           = '';
            fCorrect.value     = 'a';
            fOrder.value       = defaultOrd;
            fExplanation.value = '';
        }

        document.querySelectorAll('.btn-edit-question').forEach((btn) => {
            btn.addEventListener('click', function () {
                form.action           = this.dataset.updateUrl;
                formTitle.textContent = 'Edit Butir Soal #' + this.dataset.order;
                btnSubmit.textContent = 'Perbarui Butir Soal';
                btnCancel.classList.remove('hidden');

                fQuestion.value    = this.dataset.question || '';
                fA.value           = this.dataset.a || '';
                fB.value           = this.dataset.b || '';
                fC.value           = this.dataset.c || '';
                fD.value           = this.dataset.d || '';
                fCorrect.value     = this.dataset.correct || 'a';
                fOrder.value       = this.dataset.order || '1';
                fExplanation.value = this.dataset.explanation || '';

                form.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });

        if (btnCancel) {
            btnCancel.addEventListener('click', resetToCreate);
        }
    });
</script>
<?= $this->endSection() ?>

