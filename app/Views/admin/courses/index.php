<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<!-- Header Halaman & Tombol CTA Tambah Kelas -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-extrabold text-upskill-darkblue tracking-tight">
            Katalog Kursus & Pelatihan
        </h2>
        <p class="text-sm text-slate-500 mt-1">
            Kelola seluruh kurikulum kelas dan status publikasi materi.
        </p>
    </div>
    <div>
        <a href="<?= base_url('admin/courses/create') ?>" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-xs font-bold rounded-lg text-white bg-upskill-pink hover:bg-upskill-magenta transition-colors duration-300 shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Kelas Baru
        </a>
    </div>
</div>

<!-- Alert Notifikasi Flashdata -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between">
        <div class="flex items-center gap-2.5">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span><?= esc(session()->getFlashdata('success')) ?></span>
        </div>
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

<!-- Tabel Daftar Kelas (Clean Minimalist Tailwind) -->
<div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50/75 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                    <th class="py-3.5 px-6">Judul Kelas</th>
                    <th class="py-3.5 px-6">Status</th>
                    <th class="py-3.5 px-6 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                <?php if (empty($courses)): ?>
                    <tr>
                        <td colspan="3" class="py-12 text-center text-slate-400">
                            <div class="w-12 h-12 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <p class="font-bold text-slate-700">Belum ada kelas yang terdaftar</p>
                            <p class="text-xs text-slate-400 mt-1">Klik tombol di atas untuk membuat kelas pertama Anda.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($courses as $c): ?>
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <!-- Judul Kelas -->
                            <td class="py-4 px-6">
                                <div class="font-bold text-upskill-darkblue">
                                    <?= esc($c['title']) ?>
                                </div>
                                <div class="text-xs text-slate-400 mt-0.5">
                                    Slug: <code class="text-slate-500 font-mono text-[11px]"><?= esc($c['slug']) ?></code> &bull; Mentor: <?= esc($c['mentor_name'] ?? 'Admin') ?>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-6 whitespace-nowrap">
                                <?php if ($c['status'] === 'published'): ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                        Published
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Draft
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Aksi -->
                            <td class="py-4 px-6 whitespace-nowrap text-right text-xs">
                                <div class="inline-flex items-center gap-2">
                                    <span class="text-slate-300">|</span>
                                    <a 
                                        href="<?= base_url('courses/' . $c['slug']) ?>" 
                                        target="_blank" 
                                        class="font-semibold text-upskill-blue hover:text-upskill-pink transition-colors"
                                    >
                                        Lihat
                                    </a>
                                    <span class="text-slate-300">|</span>
                                    <button 
                                        type="button" 
                                        data-href="<?= base_url('admin/courses/delete/' . $c['id']) ?>" 
                                        data-message="Seluruh materi dan data yang berkaitan dengan kelas <?= esc($c['title']) ?> akan ikut terhapus secara permanen."
                                        class="btn-delete font-semibold text-rose-600 hover:text-rose-800 transition-colors cursor-pointer"
                                    >
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
