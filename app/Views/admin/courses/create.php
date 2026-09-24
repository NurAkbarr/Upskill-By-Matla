<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<!-- Tombol Kembali & Header Halaman -->
<div class="space-y-2">
    <a href="<?= base_url('admin/courses') ?>" class="inline-flex items-center text-xs font-bold text-upskill-blue hover:text-upskill-pink transition-colors duration-300 gap-1">
        &larr; Kembali ke Katalog Kelas
    </a>
    <h2 class="text-2xl font-extrabold text-upskill-darkblue tracking-tight">
        Tambah Kelas Baru
    </h2>
    <p class="text-sm text-slate-500">
        Lengkapi formulir di bawah ini untuk menerbitkan program pelatihan baru.
    </p>
</div>

<!-- Tampilkan Pesan Error Validasi -->
<?php if (session()->getFlashdata('errors')): ?>
    <div class="p-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-sm max-w-3xl">
        <p class="font-bold mb-2">Mohon lengkapi data dengan benar:</p>
        <ul class="list-disc list-inside space-y-1 text-xs">
            <?php foreach (session()->getFlashdata('errors') as $err): ?>
                <li><?= esc($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<!-- Kartu Formulir Tambah Kelas -->
<div class="bg-white border border-slate-200 rounded-xl p-8 sm:p-10 shadow-sm max-w-3xl">
    <form action="<?= base_url('admin/courses/store') ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
        <?= csrf_field() ?>

        <!-- 1. Judul Kelas -->
        <div>
            <label for="title" class="block text-xs font-bold uppercase tracking-wider text-upskill-darkblue mb-2">
                Judul Kelas <span class="text-rose-500">*</span>
            </label>
            <input 
                type="text" 
                id="title" 
                name="title" 
                value="<?= old('title') ?>" 
                required 
                placeholder="Contoh: Pemrograman Web Modern untuk Pemula" 
                class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-upskill-pink focus:border-upskill-pink transition-colors"
            >
            <p class="text-[11px] text-slate-400 mt-1">
                Slug URL ramah SEO akan dibuat secara otomatis dari judul kelas ini.
            </p>
        </div>

        <!-- 2. Deskripsi Kelas -->
        <div>
            <label for="description" class="block text-xs font-bold uppercase tracking-wider text-upskill-darkblue mb-2">
                Deskripsi & Silabus Singkat
            </label>
            <textarea 
                id="description" 
                name="description" 
                rows="5" 
                placeholder="Tuliskan gambaran umum kelas, target kompetensi yang didapatkan, dan prasyarat dasar belajar..." 
                class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-upskill-pink focus:border-upskill-pink transition-colors leading-relaxed"
            ><?= old('description') ?></textarea>
        </div>

        <!-- 3. Banner / Gambar Promosi Kelas -->
        <div>
            <label for="banner_image" class="block text-xs font-bold uppercase tracking-wider text-upskill-darkblue mb-2">
                Banner / Gambar Promosi <span class="text-[11px] font-normal text-slate-400 lowercase">(opsional, maks 2MB)</span>
            </label>
            <input 
                type="file" 
                id="banner_image" 
                name="banner_image" 
                accept="image/*" 
                class="w-full px-4 py-2 border border-slate-300 rounded-lg text-sm text-slate-700 file:mr-4 file:py-1.5 file:px-3.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-pink-50 file:text-upskill-pink hover:file:bg-pink-100 focus:outline-none focus:ring-2 focus:ring-upskill-pink focus:border-upskill-pink transition-colors"
            >
            <p class="text-[11px] text-slate-400 mt-1">
                Gambar materi yang akan ditampilkan pada slider kartu di beranda. Jika dikosongkan, gambar default akan digunakan.
            </p>
        </div>

        <!-- 4. Status Publikasi (Fitur Harga dinonaktifkan sementara untuk rilis MVP) -->
        <div>
            <label for="status" class="block text-xs font-bold uppercase tracking-wider text-upskill-darkblue mb-2">
                Status Publikasi <span class="text-rose-500">*</span>
            </label>
            <select 
                id="status" 
                name="status" 
                required 
                class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-900 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-upskill-pink focus:border-upskill-pink transition-colors"
            >
                <option value="draft" <?= old('status') === 'draft' ? 'selected' : '' ?>>Draft (Belum Ditampilkan)</option>
                <option value="published" <?= old('status', 'published') === 'published' ? 'selected' : '' ?>>Published (Aktif & Terbuka)</option>
            </select>
            <p class="text-[11px] text-slate-400 mt-1">
                Hanya kelas berstatus <strong class="text-slate-600">Published</strong> yang muncul di katalog publik.
            </p>
        </div>

        <!-- Tombol Submit & Batal -->
        <div class="pt-4 border-t border-slate-100 flex items-center gap-4">
            <button 
                type="submit" 
                class="inline-flex items-center justify-center px-6 py-3 text-sm font-bold rounded-lg text-white bg-upskill-pink hover:bg-upskill-magenta transition-colors duration-300 shadow-sm"
            >
                Simpan & Terbitkan Kelas
            </button>
            <a 
                href="<?= base_url('admin/courses') ?>" 
                class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors"
            >
                Batal
            </a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
