<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<!-- Header Halaman & Breadcrumb -->
<div class="space-y-2">
    <a href="<?= base_url('admin/courses') ?>" class="inline-flex items-center text-xs font-bold text-upskill-blue hover:text-upskill-pink transition-colors duration-300 gap-1.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        <span>Kembali ke Katalog Kelas</span>
    </a>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-upskill-darkblue tracking-tight">
                Manajemen Kurikulum Sesi & Kuis
            </h2>
            <p class="text-sm text-slate-500 mt-1">
                Atur rangkaian materi belajar per sesi beserta evaluasi kuis wajib untuk kelas: 
                <span class="font-bold text-upskill-darkblue"><?= esc($course['title']) ?></span>
            </p>
        </div>
        <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-white border border-slate-200 text-slate-700 shadow-xs self-start sm:self-auto shrink-0">
            <span class="w-2 h-2 rounded-full bg-upskill-pink"></span>
            Total Sesi: <strong class="text-slate-900"><?= count($lessons) ?></strong>
        </span>
    </div>
</div>

<!-- Alert Notifikasi Flashdata -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2.5 shadow-xs">
        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <span class="font-medium"><?= esc(session()->getFlashdata('success')) ?></span>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-2.5 shadow-xs">
        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <span class="font-medium"><?= esc(session()->getFlashdata('error')) ?></span>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm shadow-xs">
        <p class="font-bold mb-1.5 flex items-center gap-1.5">
            <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Mohon lengkapi formulir sesi dengan benar:
        </p>
        <ul class="list-disc list-inside space-y-1 text-xs pl-1 text-rose-700">
            <?php foreach (session()->getFlashdata('errors') as $err): ?>
                <li><?= esc($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<!-- Grid 2 Kolom: Kiri (Form Buat Sesi 5 Kolom), Kanan (Daftar Sesi Kurikulum 7 Kolom) -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">
    
    <!-- ============================================================== -->
    <!-- KOLOM KIRI: FORMULIR PEMBUATAN SESI                            -->
    <!-- ============================================================== -->
    <div class="lg:col-span-5 bg-white border border-slate-200 rounded-2xl p-6 sm:p-7 shadow-xs sticky top-6">
        <div class="border-b border-slate-100 pb-4 mb-5">
            <h3 class="text-base font-bold text-upskill-darkblue flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-pink-50 text-upskill-pink flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </span>
                Tambah Sesi Baru
            </h3>
            <p class="text-xs text-slate-500 mt-1">
                Lengkapi materi utama dan lampirkan kuis evaluasi akhir sesi.
            </p>
        </div>

        <form id="form_lesson" action="<?= base_url('admin/courses/' . $course['id'] . '/lessons/store') ?>" method="POST" class="space-y-4">
            <?= csrf_field() ?>

            <!-- 1. Urutan Sesi & Nama Sesi (Proporsional & Tidak Terjepit) -->
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-3.5">
                <div class="sm:col-span-4">
                    <label for="order_index" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5 whitespace-nowrap">
                        Urutan <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        id="order_index" 
                        name="order_index" 
                        value="<?= old('order_index', $nextOrder) ?>" 
                        min="1" 
                        required 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-slate-900 font-bold text-sm focus:outline-none focus:ring-2 focus:ring-upskill-pink/20 focus:border-upskill-pink transition-all bg-slate-50/50 focus:bg-white text-center"
                    >
                </div>
                <div class="sm:col-span-8">
                    <label for="chapter_title" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Nama Sesi <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="chapter_title" 
                        name="chapter_title" 
                        value="<?= old('chapter_title') ?>" 
                        required 
                        placeholder="Contoh: Sesi 1 - Rukun Sholat"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-upskill-pink/20 focus:border-upskill-pink transition-all bg-slate-50/50 focus:bg-white"
                    >
                </div>
            </div>

            <!-- 2. Tipe Konten Utama -->
            <div>
                <label for="content_type" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    Tipe Konten Utama <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <select 
                        id="content_type" 
                        name="content_type" 
                        required 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-slate-900 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-upskill-pink/20 focus:border-upskill-pink bg-slate-50/50 focus:bg-white transition-all appearance-none pr-10 cursor-pointer"
                        onchange="updateContentTypeUI(this.value)"
                    >
                        <option value="video" <?= old('content_type', 'video') === 'video' ? 'selected' : '' ?>>Materi Video (Embed YouTube)</option>
                        <option value="text_pdf" <?= old('content_type') === 'text_pdf' ? 'selected' : '' ?>>Materi Teks / Dokumen PDF</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- 3. URL Video YouTube atau Teks Materi (Bebas Scrollbar Berantakan) -->
            <div>
                <label id="content_label" for="content_url_or_text" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    URL Video YouTube
                </label>
                
                <!-- Tampilan Input Single-line Bersih untuk Video -->
                <div id="video_input_wrap">
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-rose-500">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            id="video_url_field"
                            placeholder="https://www.youtube.com/watch?v=..."
                            value="<?= old('content_type', 'video') === 'video' ? old('content_url_or_text') : '' ?>"
                            class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-upskill-pink/20 focus:border-upskill-pink bg-slate-50/50 focus:bg-white transition-all font-mono text-xs sm:text-sm"
                        >
                    </div>
                </div>

                <!-- Tampilan Textarea untuk Teks / Link Dokumen PDF -->
                <div id="text_input_wrap" class="hidden">
                    <textarea 
                        id="text_content_field"
                        rows="4" 
                        placeholder="Tuliskan rangkuman teks materi atau tautan dokumen PDF..."
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-upskill-pink/20 focus:border-upskill-pink bg-slate-50/50 focus:bg-white transition-all resize-y"
                    ><?= old('content_type') === 'text_pdf' ? old('content_url_or_text') : '' ?></textarea>
                </div>

                <!-- Hidden Input Utama yang dikirimkan ke Server -->
                <input type="hidden" id="content_url_or_text" name="content_url_or_text" value="<?= old('content_url_or_text') ?>">

                <p id="content_help" class="text-[11px] text-slate-400 mt-1.5 leading-relaxed">
                    Sistem akan memutar video secara otomatis kepada peserta di ruang belajar.
                </p>
            </div>

            <!-- Tombol Simpan -->
            <div class="pt-2">
                <button 
                    type="submit" 
                    class="w-full py-2.5 px-4 inline-flex items-center justify-center gap-2 font-bold text-sm rounded-xl text-white bg-gradient-to-r from-upskill-pink to-upskill-magenta hover:opacity-95 transition-all shadow-sm shadow-pink-500/20 active:scale-[0.99] cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Simpan Sesi Pembelajaran</span>
                </button>
            </div>
        </form>
    </div>

    <!-- ============================================================== -->
    <!-- KOLOM KANAN: DAFTAR SESI KURIKULUM                             -->
    <!-- ============================================================== -->
    <div class="lg:col-span-7 space-y-4 min-w-0">
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xs">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/70">
                <h3 class="text-xs font-bold text-upskill-darkblue uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    Struktur Sesi Kurikulum
                </h3>
                <span class="text-xs text-slate-500">
                    Terurut berdasarkan nomor sesi
                </span>
            </div>

            <?php if (empty($lessons)): ?>
                <!-- Keadaan Kosong (Empty State) yang Informatif & Elegan -->
                <div class="py-16 px-6 sm:px-10 text-center">
                    <div class="w-16 h-16 mx-auto rounded-2xl bg-pink-50 border border-pink-100 text-upskill-pink flex items-center justify-center mb-4 shadow-xs">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h4 class="font-bold text-slate-800 text-lg">Belum Ada Sesi Pembelajaran</h4>
                    <p class="text-xs text-slate-500 max-w-md mx-auto mt-1.5 leading-relaxed">
                        Kurikulum kelas ini belum memiliki materi. Gunakan formulir di sebelah kiri untuk membuat sesi pertama Anda.
                    </p>

                    <!-- Alur Panduan Singkat -->
                    <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-3 max-w-md mx-auto text-left">
                        <div class="p-3.5 rounded-xl border border-dashed border-slate-200 bg-slate-50/60 flex items-start gap-3">
                            <span class="w-6 h-6 rounded-full bg-upskill-darkblue text-white text-xs font-bold flex items-center justify-center shrink-0">1</span>
                            <div>
                                <p class="text-xs font-bold text-slate-800">Buat Sesi & Video</p>
                                <p class="text-[11px] text-slate-500 mt-0.5">Isi nama sesi dan sematkan link video materi.</p>
                            </div>
                        </div>
                        <div class="p-3.5 rounded-xl border border-dashed border-slate-200 bg-slate-50/60 flex items-start gap-3">
                            <span class="w-6 h-6 rounded-full bg-upskill-pink text-white text-xs font-bold flex items-center justify-center shrink-0">2</span>
                            <div>
                                <p class="text-xs font-bold text-slate-800">Lampirkan Kuis</p>
                                <p class="text-[11px] text-slate-500 mt-0.5">Setelah sesi tersimpan, klik 'Kelola Kuis' untuk soal evaluasi.</p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- Daftar Kartu Sesi -->
                <div class="divide-y divide-slate-100">
                    <?php foreach ($lessons as $index => $l): ?>
                        <div class="p-5 sm:p-6 hover:bg-slate-50/60 transition-colors flex flex-col xl:flex-row xl:items-center justify-between gap-4">
                            <!-- Informasi Sesi -->
                            <div class="space-y-2 min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <!-- Badge Urutan Sesi -->
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold bg-slate-900 text-white shrink-0">
                                        Sesi <?= esc($l['order_index']) ?>
                                    </span>

                                    <!-- Badge Tipe Konten -->
                                    <?php if ($l['content_type'] === 'video'): ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200 shrink-0">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Video YouTube
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200 shrink-0">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            Teks / PDF
                                        </span>
                                    <?php endif; ?>

                                    <!-- Indikator Kuis Evaluasi Manual -->
                                    <?php if (!empty($l['quiz_count']) && $l['quiz_count'] > 0): ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-xs font-semibold bg-pink-50 text-upskill-pink border border-pink-200 shrink-0">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <?= $l['quiz_count'] ?> Soal Kuis
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-xs font-medium bg-slate-100 text-slate-500 border border-slate-200 shrink-0">
                                            Belum Ada Kuis
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <!-- Judul Sesi -->
                                <h4 class="font-bold text-base text-upskill-darkblue break-words">
                                    <?= esc($l['chapter_title']) ?>
                                </h4>

                                <!-- Preview Konten Sesi -->
                                <div class="text-xs text-slate-500">
                                    <?php if ($l['content_type'] === 'video' && !empty($l['content_url_or_text'])): ?>
                                        <p class="truncate max-w-xs sm:max-w-md">
                                             <span class="font-semibold text-slate-600">Video:</span> 
                                             <a href="<?= esc($l['content_url_or_text']) ?>" target="_blank" class="text-upskill-blue hover:underline">
                                                 <?= esc($l['content_url_or_text']) ?>
                                             </a>
                                        </p>
                                    <?php elseif (!empty($l['content_url_or_text'])): ?>
                                        <p class="line-clamp-1 max-w-xs sm:max-w-md text-slate-600">
                                             <span class="font-semibold">Materi:</span> <?= esc($l['content_url_or_text']) ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Tombol Aksi: Edit, Kelola Kuis & Hapus Sesi -->
                            <div class="shrink-0 flex items-center gap-2 self-start xl:self-center pt-2 xl:pt-0">
                                <!-- Tombol Edit Sesi -->
                                <a 
                                    href="<?= base_url('admin/sessions/edit/' . $l['id']) ?>" 
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-upskill-blue hover:text-white hover:bg-upskill-blue border border-upskill-blue/40 hover:border-upskill-blue transition-colors shrink-0 whitespace-nowrap shadow-2xs"
                                    title="Edit Sesi Pembelajaran"
                                >
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    <span>Edit</span>
                                </a>

                                <!-- Tombol Kelola Kuis -->
                                <a 
                                    href="<?= base_url('admin/sessions/' . $l['id'] . '/quiz') ?>" 
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-upskill-pink hover:text-white hover:bg-upskill-pink border border-upskill-pink transition-colors shrink-0 whitespace-nowrap shadow-2xs"
                                >
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                    </svg>
                                    <span>Kelola Kuis</span>
                                </a>

                                <!-- Tombol Hapus Sesi -->
                                <button 
                                    type="button" 
                                    data-href="<?= base_url('admin/lessons/delete/' . $l['id']) ?>" 
                                    data-message="Sesi '<?= esc($l['chapter_title']) ?>' beserta seluruh butir soal kuisnya akan dihapus permanen dari kurikulum."
                                    class="btn-delete inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-rose-600 hover:text-white hover:bg-rose-600 border border-rose-200 hover:border-rose-600 transition-colors shrink-0 whitespace-nowrap"
                                >
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    <span>Hapus</span>
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
        const videoWrap = document.getElementById('video_input_wrap');
        const textWrap = document.getElementById('text_input_wrap');
        const help = document.getElementById('content_help');

        if (type === 'video') {
            label.textContent = 'URL Video YouTube';
            videoWrap.classList.remove('hidden');
            textWrap.classList.add('hidden');
            help.textContent = 'Sistem akan memutar video secara otomatis kepada peserta di ruang belajar.';
        } else {
            label.textContent = 'Teks Materi / Dokumen PDF';
            videoWrap.classList.add('hidden');
            textWrap.classList.remove('hidden');
            help.textContent = 'Peserta dapat membaca rangkuman materi teks atau membuka tautan dokumen PDF.';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('content_type');
        const videoInput = document.getElementById('video_url_field');
        const textInput = document.getElementById('text_content_field');
        const hiddenInput = document.getElementById('content_url_or_text');
        const form = document.getElementById('form_lesson');

        if (select) {
            updateContentTypeUI(select.value);
        }

        // Sinkronisasi otomatis ke hidden input sebelum form disubmit
        if (form) {
            form.addEventListener('submit', function() {
                if (select.value === 'video') {
                    hiddenInput.value = videoInput.value.trim();
                } else {
                    hiddenInput.value = textInput.value.trim();
                }
            });
        }
    });
</script>
<?= $this->endSection() ?>
