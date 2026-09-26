<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<!-- Header Halaman & Breadcrumb -->
<div class="space-y-2">
    <a href="<?= base_url('admin/courses/' . $session['course_id'] . '/lessons') ?>" class="inline-flex items-center text-xs font-bold text-upskill-blue hover:text-upskill-pink transition-colors duration-300 gap-1">
        &larr; Kembali ke Kurikulum Sesi (<?= esc($course['title'] ?? 'Kelas') ?>)
    </a>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-2xl font-extrabold text-upskill-darkblue tracking-tight">
                Kelola Kuis: <?= esc($session['chapter_title']) ?>
            </h2>
            <p class="text-sm text-slate-500 mt-1">
                Susun butir soal pilihan ganda sebagai evaluasi pemahaman peserta di akhir sesi ini.
            </p>
        </div>
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-white border border-slate-200 text-slate-700 shadow-sm self-start sm:self-auto">
            <span class="w-2 h-2 rounded-full bg-upskill-pink"></span>
            Total Butir Soal: <strong><?= count($questions) ?></strong>
        </span>
    </div>
</div>

<!-- Alert Notifikasi Flashdata -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2.5">
        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <span><?= esc(session()->getFlashdata('success')) ?></span>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="p-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-2.5">
        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <span><?= esc(session()->getFlashdata('error')) ?></span>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="p-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-sm">
        <p class="font-bold mb-1.5">Mohon lengkapi formulir soal dengan benar:</p>
        <ul class="list-disc list-inside space-y-1 text-xs">
            <?php foreach (session()->getFlashdata('errors') as $err): ?>
                <li><?= esc($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<!-- Grid 2 Kolom: Kiri (Form Input Soal), Kanan (Daftar Soal) -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
    
    <!-- ============================================================== -->
    <!-- KOLOM KIRI: FORMULIR INPUT BUTIR SOAL KUIS                      -->
    <!-- ============================================================== -->
    <div class="lg:col-span-5 bg-white border border-slate-200 rounded-xl p-6 sm:p-7 shadow-sm sticky top-6">
        <div class="border-b border-slate-100 pb-4 mb-5">
            <h3 class="text-base font-bold text-upskill-darkblue flex items-center gap-2">
                <svg class="w-5 h-5 text-upskill-pink" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                Tambah Butir Soal Kuis
            </h3>
            <p class="text-xs text-slate-500 mt-1">
                Tuliskan pertanyaan, 4 opsi jawaban, dan tentukan kunci jawaban yang benar.
            </p>
        </div>

        <form action="<?= base_url('admin/sessions/' . $session['id'] . '/quiz/store') ?>" method="POST" class="space-y-4">
            <?= csrf_field() ?>

            <!-- 1. Teks Soal / Pertanyaan -->
            <div>
                <label for="question_text" class="block text-xs font-bold uppercase tracking-wider text-upskill-darkblue mb-1.5">
                    Teks Pertanyaan / Soal <span class="text-rose-500">*</span>
                </label>
                <textarea 
                    id="question_text" 
                    name="question_text" 
                    rows="3" 
                    required 
                    placeholder="Ketik pertanyaan kuis di sini (misal: Berapakah jumlah rukun sholat yang wajib diketahui?)"
                    class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-upskill-pink focus:border-upskill-pink"
                ><?= old('question_text') ?></textarea>
            </div>

            <!-- 2. Pilihan Jawaban & Penentu Kunci Jawaban (Google Forms Style) -->
            <div class="space-y-3 pt-2 border-t border-slate-100">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Pilihan Jawaban & Kunci Jawaban <span class="text-rose-500">*</span>
                    </label>
                    <p class="text-xs text-slate-500 mb-3">
                        Ketik pilihan jawaban dan pilih salah satu bulatan di sebelah kiri sebagai kunci jawaban yang benar.
                    </p>
                </div>
                
                <!-- Opsi A -->
                <div class="flex items-center gap-3 mb-3">
                    <!-- Penentu Kunci Jawaban -->
                    <input type="radio" name="correct_answer" id="correct_a" value="a" required 
                           <?= old('correct_answer') === 'a' ? 'checked' : '' ?>
                           class="w-5 h-5 text-upskill-pink focus:ring-upskill-pink border-gray-300 cursor-pointer accent-pink-600 shrink-0"
                           title="Tandai A sebagai jawaban benar">
                    <!-- Label Huruf -->
                    <label for="correct_a" class="font-bold text-gray-500 w-6 text-center cursor-pointer shrink-0">A</label>
                    <!-- Input Teks Jawaban -->
                    <input type="text" name="option_a" value="<?= old('option_a') ?>" required placeholder="Ketik pilihan jawaban A..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-upskill-pink focus:border-upskill-pink">
                </div>

                <!-- Opsi B -->
                <div class="flex items-center gap-3 mb-3">
                    <!-- Penentu Kunci Jawaban -->
                    <input type="radio" name="correct_answer" id="correct_b" value="b" required 
                           <?= old('correct_answer') === 'b' ? 'checked' : '' ?>
                           class="w-5 h-5 text-upskill-pink focus:ring-upskill-pink border-gray-300 cursor-pointer accent-pink-600 shrink-0"
                           title="Tandai B sebagai jawaban benar">
                    <!-- Label Huruf -->
                    <label for="correct_b" class="font-bold text-gray-500 w-6 text-center cursor-pointer shrink-0">B</label>
                    <!-- Input Teks Jawaban -->
                    <input type="text" name="option_b" value="<?= old('option_b') ?>" required placeholder="Ketik pilihan jawaban B..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-upskill-pink focus:border-upskill-pink">
                </div>

                <!-- Opsi C -->
                <div class="flex items-center gap-3 mb-3">
                    <!-- Penentu Kunci Jawaban -->
                    <input type="radio" name="correct_answer" id="correct_c" value="c" required 
                           <?= old('correct_answer') === 'c' ? 'checked' : '' ?>
                           class="w-5 h-5 text-upskill-pink focus:ring-upskill-pink border-gray-300 cursor-pointer accent-pink-600 shrink-0"
                           title="Tandai C sebagai jawaban benar">
                    <!-- Label Huruf -->
                    <label for="correct_c" class="font-bold text-gray-500 w-6 text-center cursor-pointer shrink-0">C</label>
                    <!-- Input Teks Jawaban -->
                    <input type="text" name="option_c" value="<?= old('option_c') ?>" required placeholder="Ketik pilihan jawaban C..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-upskill-pink focus:border-upskill-pink">
                </div>

                <!-- Opsi D -->
                <div class="flex items-center gap-3 mb-3">
                    <!-- Penentu Kunci Jawaban -->
                    <input type="radio" name="correct_answer" id="correct_d" value="d" required 
                           <?= old('correct_answer') === 'd' ? 'checked' : '' ?>
                           class="w-5 h-5 text-upskill-pink focus:ring-upskill-pink border-gray-300 cursor-pointer accent-pink-600 shrink-0"
                           title="Tandai D sebagai jawaban benar">
                    <!-- Label Huruf -->
                    <label for="correct_d" class="font-bold text-gray-500 w-6 text-center cursor-pointer shrink-0">D</label>
                    <!-- Input Teks Jawaban -->
                    <input type="text" name="option_d" value="<?= old('option_d') ?>" required placeholder="Ketik pilihan jawaban D..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-upskill-pink focus:border-upskill-pink">
                </div>
            </div>

            <!-- Tombol Submit -->
            <div class="pt-3">
                <button 
                    type="submit" 
                    class="w-full py-2.5 px-4 inline-flex items-center justify-center gap-2 font-bold text-sm rounded-lg text-white bg-upskill-pink hover:bg-upskill-magenta transition-colors shadow-sm"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Soal Kuis
                </button>
            </div>
        </form>
    </div>

    <!-- ============================================================== -->
    <!-- KOLOM KANAN: DAFTAR BUTIR SOAL KUIS                            -->
    <!-- ============================================================== -->
    <div class="lg:col-span-7 space-y-4">
        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/75">
                <div class="flex items-center gap-2.5">
                    <h3 class="text-sm font-bold text-upskill-darkblue uppercase tracking-wider">
                        Daftar Butir Soal Sesi Ini
                    </h3>
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-upskill-pink/10 text-upskill-pink border border-upskill-pink/20">
                        <?= count($questions) ?> Soal
                    </span>
                </div>
                <span class="text-xs text-slate-500">
                    Kuis Pilihan Ganda (Native)
                </span>
            </div>

            <?php if (empty($questions)): ?>
                <!-- Keadaan Kosong (Empty State) -->
                <div class="py-14 px-6 text-center text-slate-400">
                    <div class="w-14 h-14 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3.5">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </div>
                    <p class="font-bold text-slate-700 text-base">Belum Ada Soal Kuis</p>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1 leading-relaxed">
                        Sesi ini belum memiliki butir soal evaluasi. Silakan gunakan formulir di sebelah kiri untuk menambahkan soal pertama.
                    </p>
                </div>
            <?php else: ?>
                <!-- Daftar Kartu Soal -->
                <div class="divide-y divide-slate-100">
                    <?php foreach ($questions as $index => $q): ?>
                        <div class="p-5 sm:p-6 hover:bg-slate-50/60 transition-colors space-y-3.5">
                            
                            <!-- Header Soal: Nomor & Tombol Hapus -->
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-2">
                                    <span class="w-7 h-7 rounded-lg bg-slate-900 text-white font-bold text-xs flex items-center justify-center shrink-0">
                                        <?= $index + 1 ?>
                                    </span>
                                    <h4 class="font-bold text-sm text-upskill-darkblue">
                                        <?= esc($q['question_text']) ?>
                                    </h4>
                                </div>
                                <button 
                                    type="button" 
                                    data-href="<?= base_url('admin/quiz-questions/delete/' . $q['id']) ?>" 
                                    data-message="Soal nomor <?= $index + 1 ?> akan dihapus permanen dari kuis sesi ini."
                                    class="btn-delete inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-semibold text-rose-600 hover:text-white hover:bg-rose-600 border border-rose-200 hover:border-rose-600 transition-colors shrink-0"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Hapus Soal
                                </button>
                            </div>

                            <!-- Daftar Opsi A, B, C, D (Highlight Kunci Jawaban) -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                                
                                <!-- Opsi A -->
                                <div class="p-2.5 rounded-lg border flex items-center gap-2.5 <?= $q['correct_answer'] === 'a' ? 'bg-emerald-50/80 border-emerald-300 text-emerald-950 font-semibold' : 'bg-slate-50 border-slate-200 text-slate-700' ?>">
                                    <span class="w-5 h-5 rounded-md flex items-center justify-center font-bold text-[11px] shrink-0 <?= $q['correct_answer'] === 'a' ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-700' ?>">
                                        A
                                    </span>
                                    <span class="flex-1"><?= esc($q['option_a']) ?></span>
                                    <?php if ($q['correct_answer'] === 'a'): ?>
                                        <span class="text-[10px] uppercase font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded border border-emerald-200">Kunci Jawaban</span>
                                    <?php endif; ?>
                                </div>

                                <!-- Opsi B -->
                                <div class="p-2.5 rounded-lg border flex items-center gap-2.5 <?= $q['correct_answer'] === 'b' ? 'bg-emerald-50/80 border-emerald-300 text-emerald-950 font-semibold' : 'bg-slate-50 border-slate-200 text-slate-700' ?>">
                                    <span class="w-5 h-5 rounded-md flex items-center justify-center font-bold text-[11px] shrink-0 <?= $q['correct_answer'] === 'b' ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-700' ?>">
                                        B
                                    </span>
                                    <span class="flex-1"><?= esc($q['option_b']) ?></span>
                                    <?php if ($q['correct_answer'] === 'b'): ?>
                                        <span class="text-[10px] uppercase font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded border border-emerald-200">Kunci Jawaban</span>
                                    <?php endif; ?>
                                </div>

                                <!-- Opsi C -->
                                <div class="p-2.5 rounded-lg border flex items-center gap-2.5 <?= $q['correct_answer'] === 'c' ? 'bg-emerald-50/80 border-emerald-300 text-emerald-950 font-semibold' : 'bg-slate-50 border-slate-200 text-slate-700' ?>">
                                    <span class="w-5 h-5 rounded-md flex items-center justify-center font-bold text-[11px] shrink-0 <?= $q['correct_answer'] === 'c' ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-700' ?>">
                                        C
                                    </span>
                                    <span class="flex-1"><?= esc($q['option_c']) ?></span>
                                    <?php if ($q['correct_answer'] === 'c'): ?>
                                        <span class="text-[10px] uppercase font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded border border-emerald-200">Kunci Jawaban</span>
                                    <?php endif; ?>
                                </div>

                                <!-- Opsi D -->
                                <div class="p-2.5 rounded-lg border flex items-center gap-2.5 <?= $q['correct_answer'] === 'd' ? 'bg-emerald-50/80 border-emerald-300 text-emerald-950 font-semibold' : 'bg-slate-50 border-slate-200 text-slate-700' ?>">
                                    <span class="w-5 h-5 rounded-md flex items-center justify-center font-bold text-[11px] shrink-0 <?= $q['correct_answer'] === 'd' ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-700' ?>">
                                        D
                                    </span>
                                    <span class="flex-1"><?= esc($q['option_d']) ?></span>
                                    <?php if ($q['correct_answer'] === 'd'): ?>
                                        <span class="text-[10px] uppercase font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded border border-emerald-200">Kunci Jawaban</span>
                                    <?php endif; ?>
                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
