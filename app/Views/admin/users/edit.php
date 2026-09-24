<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<!-- Tombol Kembali & Header Halaman -->
<div class="space-y-2">
    <a href="<?= base_url('admin/users') ?>" class="inline-flex items-center text-xs font-bold text-upskill-blue hover:text-upskill-pink transition-colors duration-300 gap-1">
        &larr; Kembali ke Kelola Pengguna
    </a>
    <h2 class="text-2xl font-extrabold text-upskill-darkblue tracking-tight">
        Edit Data Pengguna
    </h2>
    <p class="text-sm text-slate-500">
        Perbarui informasi profil atau hak akses akun pengguna terdaftar.
    </p>
</div>

<!-- Tampilkan Pesan Error Validasi -->
<?php if (session()->getFlashdata('errors')): ?>
    <div class="p-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-sm max-w-2xl">
        <p class="font-bold mb-2">Mohon perbaiki data berikut:</p>
        <ul class="list-disc list-inside space-y-1 text-xs">
            <?php foreach (session()->getFlashdata('errors') as $err): ?>
                <li><?= esc($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<!-- Kartu Formulir Edit Pengguna -->
<div class="bg-white border border-slate-200 rounded-xl p-8 sm:p-10 shadow-sm max-w-2xl">
    <form action="<?= base_url('admin/users/update/' . $user['id']) ?>" method="POST" class="space-y-6">
        <?= csrf_field() ?>

        <!-- 1. Nama Lengkap -->
        <div>
            <label for="full_name" class="block text-xs font-bold uppercase tracking-wider text-upskill-darkblue mb-2">
                Nama Lengkap <span class="text-rose-500">*</span>
            </label>
            <input 
                type="text" 
                id="full_name" 
                name="full_name" 
                value="<?= old('full_name', $user['full_name']) ?>" 
                required 
                class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-upskill-pink focus:border-upskill-pink transition-colors"
            >
        </div>

        <!-- 2. Alamat Email -->
        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-upskill-darkblue mb-2">
                Alamat Email <span class="text-rose-500">*</span>
            </label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                value="<?= old('email', $user['email']) ?>" 
                required 
                class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-upskill-pink focus:border-upskill-pink transition-colors"
            >
        </div>

        <!-- 3. Peran / Role -->
        <div>
            <label for="role" class="block text-xs font-bold uppercase tracking-wider text-upskill-darkblue mb-2">
                Peran Pengguna (Role) <span class="text-rose-500">*</span>
            </label>
            <select 
                id="role" 
                name="role" 
                required 
                class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-900 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-upskill-pink focus:border-upskill-pink transition-colors"
            >
                <option value="peserta_b2c" <?= old('role', $user['role']) === 'peserta_b2c' ? 'selected' : '' ?>>Peserta B2C (Individu/Mandiri)</option>
                <option value="mentor" <?= old('role', $user['role']) === 'mentor' ? 'selected' : '' ?>>Mentor (Instruktur Praktisi)</option>
                <option value="peserta_b2b" <?= old('role', $user['role']) === 'peserta_b2b' ? 'selected' : '' ?>>Peserta B2B (Mitra Institusi)</option>
                <option value="super_admin" <?= old('role', $user['role']) === 'super_admin' ? 'selected' : '' ?>>Super Admin (Pengelola Sistem)</option>
            </select>
        </div>

        <!-- 4. Nama Instansi (Opsional) -->
        <div>
            <label for="instansi_name" class="block text-xs font-bold uppercase tracking-wider text-upskill-darkblue mb-2">
                Nama Instansi / Lembaga <span class="text-[11px] font-normal text-slate-400 lowercase">(khusus mitra B2B)</span>
            </label>
            <input 
                type="text" 
                id="instansi_name" 
                name="instansi_name" 
                value="<?= old('instansi_name', $user['instansi_name'] ?? '') ?>" 
                placeholder="Contoh: Universitas Islam MATLA" 
                class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-upskill-pink focus:border-upskill-pink transition-colors"
            >
        </div>

        <!-- 5. Password Baru (Opsional) -->
        <div>
            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-upskill-darkblue mb-2">
                Password Baru
            </label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                placeholder="Masukkan password baru (minimal 6 karakter)" 
                class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-upskill-pink focus:border-upskill-pink transition-colors"
            >
            <p class="text-[11px] text-slate-400 mt-1.5">
                <span class="text-amber-600 font-semibold">&bull; Catatan:</span> Kosongkan jika tidak ingin mengubah password pengguna.
            </p>
        </div>

        <!-- Tombol Aksi -->
        <div class="pt-4 border-t border-slate-100 flex items-center gap-4">
            <button 
                type="submit" 
                class="inline-flex items-center justify-center px-6 py-3 text-sm font-bold rounded-lg text-white bg-upskill-pink hover:bg-upskill-magenta transition-colors duration-300 shadow-sm"
            >
                Simpan Perubahan
            </button>
            <a 
                href="<?= base_url('admin/users') ?>" 
                class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors"
            >
                Batal
            </a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
