<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<!-- Header Halaman & Tombol Kembali -->
<div class="space-y-2 max-w-2xl mx-auto">
    <a href="<?= base_url('admin/courses/' . $course['id'] . '/lessons') ?>" class="inline-flex items-center text-xs font-bold text-upskill-blue hover:text-upskill-pink transition-colors duration-300 gap-1.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        <span>Kembali ke Sesi Kurikulum</span>
    </a>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div>
            <h2 class="text-2xl font-extrabold text-upskill-darkblue tracking-tight">
                Edit Sesi Pembelajaran
            </h2>
            <p class="text-sm text-slate-500 mt-1">
                Perbarui materi atau urutan sesi untuk kelas: 
                <span class="font-bold text-upskill-darkblue"><?= esc($course['title']) ?></span>
            </p>
        </div>
    </div>
</div>

<!-- Alert Notifikasi Flashdata -->
<div class="max-w-2xl mx-auto mt-4 space-y-3">
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
</div>

<!-- ============================================================== -->
<!-- FORMULIR EDIT SESI                                             -->
<!-- ============================================================== -->
<div class="max-w-2xl mx-auto mt-6">
    <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-xs">
        <div class="border-b border-slate-100 pb-4 mb-6">
            <div class="flex items-center gap-2.5">
                <span class="w-8 h-8 rounded-xl bg-blue-50 text-upskill-blue flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </span>
                <div>
                    <h3 class="text-base font-bold text-upskill-darkblue">
                        Perbarui Data Sesi
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Sesi ID #<?= esc($lesson['id']) ?> - <?= esc($lesson['chapter_title']) ?>
                    </p>
                </div>
            </div>
        </div>

        <form id="form_lesson_edit" action="<?= base_url('admin/sessions/update/' . $lesson['id']) ?>" method="POST" class="space-y-5">
            <?= csrf_field() ?>

            <!-- 1. Urutan Sesi & Nama Sesi -->
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                <div class="sm:col-span-4">
                    <label for="order_index" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5 whitespace-nowrap">
                        Urutan Sesi <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        id="order_index" 
                        name="order_index" 
                        value="<?= old('order_index', $lesson['order_index']) ?>" 
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
                        value="<?= old('chapter_title', $lesson['chapter_title']) ?>" 
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
                        <option value="video" <?= old('content_type', $lesson['content_type']) === 'video' ? 'selected' : '' ?>>Materi Video (Embed YouTube)</option>
                        <option value="text_pdf" <?= old('content_type', $lesson['content_type']) === 'text_pdf' ? 'selected' : '' ?>>Materi Teks / Dokumen PDF</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- 3. URL Video YouTube atau Teks Materi -->
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
                            value="<?= old('content_type', $lesson['content_type']) === 'video' ? old('content_url_or_text', $lesson['content_url_or_text']) : '' ?>"
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
                    ><?= old('content_type', $lesson['content_type']) === 'text_pdf' ? old('content_url_or_text', $lesson['content_url_or_text']) : '' ?></textarea>
                </div>

                <!-- Hidden Input Utama yang dikirimkan ke Server -->
                <input type="hidden" id="content_url_or_text" name="content_url_or_text" value="<?= old('content_url_or_text', $lesson['content_url_or_text']) ?>">

                <p id="content_help" class="text-[11px] text-slate-400 mt-1.5 leading-relaxed">
                    Sistem akan memutar video secara otomatis kepada peserta di ruang belajar.
                </p>
            </div>

            <!-- Tombol Aksi: Simpan Perubahan & Batal -->
            <div class="pt-4 border-t border-slate-100 flex flex-col-reverse sm:flex-row items-center justify-end gap-3">
                <a 
                    href="<?= base_url('admin/courses/' . $course['id'] . '/lessons') ?>" 
                    class="w-full sm:w-auto px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 font-semibold text-sm text-center transition-colors"
                >
                    Batal
                </a>

                <button 
                    type="submit" 
                    class="w-full sm:w-auto py-2.5 px-6 inline-flex items-center justify-center gap-2 font-bold text-sm rounded-xl text-white bg-upskill-pink hover:bg-upskill-magenta transition-all shadow-sm shadow-pink-500/20 active:scale-[0.99] cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
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
        const form = document.getElementById('form_lesson_edit');

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
