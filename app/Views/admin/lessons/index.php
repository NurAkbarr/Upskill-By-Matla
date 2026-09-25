<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<!-- Header Halaman & Breadcrumb -->
<div class="space-y-2">
    <a href="<?= base_url('admin/courses') ?>" class="inline-flex items-center text-xs font-bold text-upskill-blue hover:text-upskill-pink transition-colors duration-300 gap-1">
        &larr; Kembali ke Katalog Kelas
    </a>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-2xl font-extrabold text-upskill-darkblue tracking-tight">
                Manajemen Kurikulum Sesi & Kuis
            </h2>
            <p class="text-sm text-slate-500 mt-1">
                Atur rangkaian materi belajar per sesi beserta evaluasi kuis wajib untuk kelas: 
                <span class="font-bold text-upskill-darkblue"><?= esc($course['title']) ?></span>
            </p>
        </div>
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-white border border-slate-200 text-slate-700 shadow-sm self-start sm:self-auto">
            <span class="w-2 h-2 rounded-full bg-upskill-pink"></span>
            Total Sesi: <strong><?= count($lessons) ?></strong>
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
        <p class="font-bold mb-1.5">Mohon lengkapi formulir sesi dengan benar:</p>
        <ul class="list-disc list-inside space-y-1 text-xs">
            <?php foreach (session()->getFlashdata('errors') as $err): ?>
                <li><?= esc($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<!-- Grid 2 Kolom: Kiri (Form Buat Sesi), Kanan (Daftar Sesi Kurikulum) -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
    
    <!-- ============================================================== -->
    <!-- KOLOM KIRI: FORMULIR PEMBUATAN SESI (Materi + Kuis)             -->
    <!-- ============================================================== -->
    <div class="lg:col-span-5 bg-white border border-slate-200 rounded-xl p-6 sm:p-7 shadow-sm sticky top-6">
        <div class="border-b border-slate-100 pb-4 mb-5">
            <h3 class="text-base font-bold text-upskill-darkblue flex items-center gap-2">
                <svg class="w-5 h-5 text-upskill-pink" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                Tambah Sesi Pembelajaran Baru
            </h3>
            <p class="text-xs text-slate-500 mt-1">
                Lengkapi konten materi utama dan lampirkan kuis evaluasi akhir sesi.
            </p>
        </div>

        <form action="<?= base_url('admin/courses/' . $course['id'] . '/lessons/store') ?>" method="POST" class="space-y-4">
            <?= csrf_field() ?>

            <!-- 1. Urutan & Nama Sesi -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="sm:col-span-1">
                    <label for="order_index" class="block text-xs font-bold uppercase tracking-wider text-upskill-darkblue mb-1.5">
                        Urutan Sesi <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        id="order_index" 
                        name="order_index" 
                        value="<?= old('order_index', $nextOrder) ?>" 
                        min="1" 
                        required 
                        class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-upskill-pink focus:border-upskill-pink"
                    >
                </div>
                <div class="sm:col-span-2">
                    <label for="chapter_title" class="block text-xs font-bold uppercase tracking-wider text-upskill-darkblue mb-1.5">
                        Nama Sesi <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="chapter_title" 
                        name="chapter_title" 
                        value="<?= old('chapter_title') ?>" 
                        required 
                        placeholder="Contoh: Sesi 1 - Rukun Sholat"
                        class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-upskill-pink focus:border-upskill-pink"
                    >
                </div>
            </div>

            <!-- 2. Tipe Konten Utama -->
            <div>
                <label for="content_type" class="block text-xs font-bold uppercase tracking-wider text-upskill-darkblue mb-1.5">
                    Tipe Konten Utama <span class="text-rose-500">*</span>
                </label>
                <select 
                    id="content_type" 
                    name="content_type" 
                    required 
                    class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-upskill-pink focus:border-upskill-pink bg-white"
                    onchange="updateContentTypeUI(this.value)"
                >
                    <option value="video" <?= old('content_type') === 'video' ? 'selected' : '' ?>>Materi Video (Embed YouTube)</option>
                    <option value="text_pdf" <?= old('content_type') === 'text_pdf' ? 'selected' : '' ?>>Materi Teks / Dokumen PDF</option>
                </select>
            </div>

            <!-- 3. URL Video atau Teks Materi -->
            <div>
                <label id="content_label" for="content_url_or_text" class="block text-xs font-bold uppercase tracking-wider text-upskill-darkblue mb-1.5">
                    URL Video YouTube
                </label>
                <textarea 
                    id="content_url_or_text" 
                    name="content_url_or_text" 
                    rows="3" 
                    placeholder="Masukkan URL Video YouTube, misal: https://www.youtube.com/watch?v=xxxx atau https://youtu.be/xxxx"
                    class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-upskill-pink focus:border-upskill-pink"
                ><?= old('content_url_or_text') ?></textarea>
                <p id="content_help" class="text-[11px] text-slate-400 mt-1">
                    Sistem akan memutar video secara otomatis kepada peserta di halaman belajar.
                </p>
            </div>

            <!-- 4. Tautan Kuis Evaluasi Sesi -->
            <div class="pt-2 border-t border-slate-100">
                <div class="flex items-center gap-1.5 mb-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <label for="quiz_url" class="block text-xs font-bold uppercase tracking-wider text-upskill-darkblue">
                        Tautan Kuis Evaluasi (Opsional/Wajib)
                    </label>
                </div>
                <input 
                    type="url" 
                    id="quiz_url" 
                    name="quiz_url" 
                    value="<?= old('quiz_url') ?>" 
                    placeholder="https://forms.gle/... atau tautan kuis"
                    class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                >
                <p class="text-[11px] text-slate-500 mt-1.5 leading-relaxed">
                    Masukkan tautan formulir kuis yang harus diselesaikan peserta di akhir sesi ini (misal: Google Forms, Typeform, atau platform asesmen).
                </p>
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
                    Simpan Sesi Pembelajaran
                </button>
            </div>
        </form>
    </div>

    <!-- ============================================================== -->
    <!-- KOLOM KANAN: DAFTAR SESI KURIKULUM                             -->
    <!-- ============================================================== -->
    <div class="lg:col-span-7 space-y-4">
        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/75">
                <h3 class="text-sm font-bold text-upskill-darkblue uppercase tracking-wider">
                    Struktur Sesi Kurikulum
                </h3>
                <span class="text-xs text-slate-500">
                    Terurut berdasarkan nomor sesi
                </span>
            </div>

            <?php if (empty($lessons)): ?>
                <!-- Keadaan Kosong (Empty State) -->
                <div class="py-14 px-6 text-center text-slate-400">
                    <div class="w-14 h-14 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3.5">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <p class="font-bold text-slate-700 text-base">Belum Ada Sesi Pembelajaran</p>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1 leading-relaxed">
                        Kurikulum kelas ini masih kosong. Silakan gunakan formulir di sebelah kiri untuk menambahkan Sesi 1.
                    </p>
                </div>
            <?php else: ?>
                <!-- Daftar Kartu Sesi -->
                <div class="divide-y divide-slate-100">
                    <?php foreach ($lessons as $index => $l): ?>
                        <div class="p-5 sm:p-6 hover:bg-slate-50/60 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <!-- Informasi Sesi -->
                            <div class="space-y-2">
                                <div class="flex flex-wrap items-center gap-2">
                                    <!-- Badge Urutan Sesi -->
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold bg-slate-900 text-white">
                                        Sesi <?= esc($l['order_index']) ?>
                                    </span>

                                    <!-- Badge Tipe Konten -->
                                    <?php if ($l['content_type'] === 'video'): ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Video YouTube
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            Teks / PDF
                                        </span>
                                    <?php endif; ?>

                                    <!-- Indikator Kuis Evaluasi -->
                                    <?php if (!empty($l['quiz_url'])): ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Kuis Terlampir
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-xs font-medium bg-slate-100 text-slate-500 border border-slate-200">
                                            Tanpa Kuis
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <!-- Judul Sesi -->
                                <h4 class="font-bold text-base text-upskill-darkblue">
                                    <?= esc($l['chapter_title']) ?>
                                </h4>

                                <!-- Preview Konten / Kuis -->
                                <div class="text-xs text-slate-500 space-y-1">
                                    <?php if ($l['content_type'] === 'video' && !empty($l['content_url_or_text'])): ?>
                                        <p class="truncate max-w-md">
                                            <span class="font-semibold text-slate-600">Video:</span> 
                                            <a href="<?= esc($l['content_url_or_text']) ?>" target="_blank" class="text-upskill-blue hover:underline">
                                                <?= esc($l['content_url_or_text']) ?>
                                            </a>
                                        </p>
                                    <?php elseif (!empty($l['content_url_or_text'])): ?>
                                        <p class="line-clamp-1 max-w-md text-slate-600">
                                            <span class="font-semibold">Materi:</span> <?= esc($l['content_url_or_text']) ?>
                                        </p>
                                    <?php endif; ?>

                                    <?php if (!empty($l['quiz_url'])): ?>
                                        <p class="truncate max-w-md text-emerald-700">
                                            <span class="font-semibold">Kuis:</span> 
                                            <a href="<?= esc($l['quiz_url']) ?>" target="_blank" class="hover:underline">
                                                <?= esc($l['quiz_url']) ?> &nearr;
                                            </a>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Tombol Aksi Hapus -->
                            <div class="shrink-0 flex items-center justify-end">
                                <button 
                                    type="button" 
                                    data-href="<?= base_url('admin/lessons/delete/' . $l['id']) ?>" 
                                    data-message="Sesi '<?= esc($l['chapter_title']) ?>' beserta lampiran materi dan kuisnya akan dihapus permanen dari kurikulum."
                                    class="btn-delete inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-rose-600 hover:text-white hover:bg-rose-600 border border-rose-200 hover:border-rose-600 transition-colors"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Hapus Sesi
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function updateContentTypeUI(type) {
        const label = document.getElementById('content_label');
        const textarea = document.getElementById('content_url_or_text');
        const help = document.getElementById('content_help');

        if (type === 'video') {
            label.textContent = 'URL Video YouTube';
            textarea.placeholder = 'Masukkan URL Video YouTube, misal: https://www.youtube.com/watch?v=xxxx atau https://youtu.be/xxxx';
            help.textContent = 'Sistem akan memutar video secara otomatis kepada peserta di halaman belajar.';
        } else {
            label.textContent = 'Teks Materi / Tautan Dokumen PDF';
            textarea.placeholder = 'Ketik isi materi pembelajaran lengkap di sini, atau tempelkan URL dokumen materi PDF.';
            help.textContent = 'Peserta dapat membaca teks atau membuka dokumen PDF pada sesi ini.';
        }
    }

    // Jalankan inisialisasi awal saat halaman selesai dimuat
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('content_type');
        if (select) {
            updateContentTypeUI(select.value);
        }
    });
</script>
<?= $this->endSection() ?>
