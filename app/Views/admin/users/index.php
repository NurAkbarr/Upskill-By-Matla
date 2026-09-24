<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<!-- Header Halaman -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-extrabold text-upskill-darkblue tracking-tight">
            Kelola Pengguna
        </h2>
        <p class="text-sm text-slate-500 mt-1">
            Daftar seluruh akun pengguna yang terdaftar di platform Muslim Upskill Academy.
        </p>
    </div>
    <div class="flex items-center gap-3">
        <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-white border border-slate-200 text-slate-600 shadow-sm">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            Total: <strong class="text-upskill-darkblue"><?= count($users) ?></strong>
        </span>
        <a href="<?= base_url('admin/users/create') ?>" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-xs font-bold rounded-lg text-white bg-upskill-pink hover:bg-upskill-magenta transition-colors duration-300 shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            Tambah Pengguna Baru
        </a>
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

<!-- Tabel Daftar Pengguna (Minimalist & Bersih) -->
<div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50/75 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                    <th class="py-3.5 px-6 w-16">No</th>
                    <th class="py-3.5 px-6">Nama Lengkap</th>
                    <th class="py-3.5 px-6">Email</th>
                    <th class="py-3.5 px-6">Role</th>
                    <th class="py-3.5 px-6">Tanggal Daftar</th>
                    <th class="py-3.5 px-6 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400">
                            <div class="w-12 h-12 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <p class="font-bold text-slate-700">Belum ada data pengguna</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php 
                        $no = (($currentPage - 1) * $perPage) + 1; 
                        foreach ($users as $u): 
                    ?>
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <!-- Kolom No -->
                            <td class="py-4 px-6 font-semibold text-slate-400 text-xs">
                                <?= $no++ ?>
                            </td>

                            <!-- Nama Lengkap -->
                            <td class="py-4 px-6 font-semibold text-slate-900">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 text-upskill-darkblue font-bold text-xs flex items-center justify-center shrink-0 border border-slate-200 uppercase">
                                        <?= mb_substr($u['full_name'], 0, 1) ?>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-upskill-darkblue">
                                            <?= esc($u['full_name']) ?>
                                        </div>
                                        <?php if (!empty($u['instansi_name'])): ?>
                                            <div class="text-[11px] text-slate-400">
                                                Instansi: <?= esc($u['instansi_name']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>

                            <!-- Email -->
                            <td class="py-4 px-6 text-slate-600 font-mono text-xs">
                                <?= esc($u['email']) ?>
                            </td>

                            <!-- Role Pengguna -->
                            <td class="py-4 px-6 whitespace-nowrap">
                                <?php if ($u['role'] === 'super_admin'): ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-900 text-white border border-slate-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-pink-400"></span>
                                        Super Admin
                                    </span>
                                <?php elseif ($u['role'] === 'peserta_b2b'): ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Peserta B2B
                                    </span>
                                <?php elseif ($u['role'] === 'mentor'): ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                        Mentor
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-upskill-blue border border-blue-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-upskill-blue"></span>
                                        Peserta B2C
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Tanggal Pendaftaran -->
                            <td class="py-4 px-6 whitespace-nowrap text-xs text-slate-500">
                                <?= date('d M Y, H:i', strtotime($u['created_at'])) ?>
                            </td>

                            <!-- Aksi -->
                            <td class="py-4 px-6 whitespace-nowrap text-right text-xs">
                                <div class="inline-flex items-center gap-2.5 justify-end">
                                    <!-- Tombol Edit -->
                                    <a 
                                        href="<?= base_url('admin/users/edit/' . $u['id']) ?>" 
                                        class="inline-flex items-center gap-1 font-semibold text-upskill-blue hover:text-upskill-pink transition-colors"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </a>

                                    <span class="text-slate-300">|</span>

                                    <!-- Tombol Hapus (Merah) -->
                                    <a 
                                        href="<?= base_url('admin/users/delete/' . $u['id']) ?>" 
                                        onclick="<?= (int)$u['id'] === $currentAdminId ? "alert('Peringatan: Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif digunakan.'); return false;" : "return confirm('Yakin ingin menghapus pengguna " . addslashes(esc($u['full_name'])) . "? Seluruh data akses dan riwayatnya akan dihapus.')" ?>" 
                                        class="inline-flex items-center gap-1 font-semibold text-rose-600 hover:text-rose-800 transition-colors"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Paginasi CI4 (Jika data melebihi 1 halaman) -->
    <?php if ($pager->getPageCount() > 1): ?>
        <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 bg-slate-50/50">
            <div>
                Menampilkan halaman <strong><?= $currentPage ?></strong> dari <strong><?= $pager->getPageCount() ?></strong>
            </div>
            <div>
                <?= $pager->links('default', 'default_simple') ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
