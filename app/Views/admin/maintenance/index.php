<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<?php
    $maintenanceLog = session()->getFlashdata('maintenance_log');
?>

<!-- Header Halaman -->
<div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-extrabold text-upskill-darkblue tracking-tight">
            Maintenance Tools
        </h2>
        <p class="text-sm text-slate-600 mt-1">
            Menu ini khusus <strong>Super Admin</strong> untuk menjalankan migrasi database dan membersihkan cache tanpa terminal. Pastikan situs sudah menggunakan HTTPS.
        </p>
    </div>

    <div class="flex flex-wrap items-center gap-2 shrink-0">
        <?php if ($token_ready): ?>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                Token .env Aktif
            </span>
        <?php else: ?>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-200">
                <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span>
                Token .env Belum Diatur
            </span>
        <?php endif; ?>

        <?php if ($pending_count > 0): ?>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-amber-50 text-amber-900 border border-amber-200 tabular-nums">
                <?= (int) $pending_count ?> Migrasi Tertunda
            </span>
        <?php else: ?>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-white text-slate-700 border border-slate-200">
                Database Mutakhir
            </span>
        <?php endif; ?>
    </div>
</div>

<!-- Peringatan Konfigurasi Token Belum Siap (Fail-Closed) -->
<?php if (! $token_ready): ?>
    <div class="p-4 sm:p-5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-sm flex items-start gap-3">
        <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
        </svg>
        <div class="space-y-1.5">
            <p class="font-bold">Fitur terkunci: MAINTENANCE_TOKEN belum dikonfigurasi</p>
            <p class="text-xs text-rose-800 leading-relaxed">
                Demi keamanan, tambahkan baris <code class="px-1.5 py-0.5 rounded bg-white border border-rose-200 font-mono text-[11px]">MAINTENANCE_TOKEN = 'kode_acak_minimal_16_karakter'</code> ke dalam file <code class="font-mono">.env</code> di server hosting Anda terlebih dahulu.
            </p>
        </div>
    </div>
<?php elseif (! $is_https && $environment === 'production'): ?>
    <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs sm:text-sm flex items-start gap-3">
        <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <span>Koneksi saat ini tidak menggunakan HTTPS. Disarankan mengakses halaman ini melalui HTTPS agar pengiriman token tetap terenkripsi.</span>
    </div>
<?php endif; ?>

<!-- Notifikasi Alert Inline Bertema Upskill -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-sm flex items-start gap-3">
        <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="font-semibold"><?= esc(session()->getFlashdata('success')) ?></span>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 text-sm flex items-start gap-3">
        <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="font-semibold"><?= esc(session()->getFlashdata('error')) ?></span>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('warning')): ?>
    <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-sm flex items-start gap-3">
        <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <span class="font-semibold"><?= esc(session()->getFlashdata('warning')) ?></span>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('info')): ?>
    <div class="p-4 rounded-xl bg-blue-50 border border-blue-200 text-upskill-darkblue text-sm flex items-start gap-3">
        <svg class="w-5 h-5 text-upskill-blue shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="font-semibold"><?= esc(session()->getFlashdata('info')) ?></span>
    </div>
<?php endif; ?>

<!-- Output Eksekusi Terakhir -->
<?php if (! empty($maintenanceLog) && is_array($maintenanceLog)): ?>
    <div class="bg-slate-900 text-slate-100 rounded-2xl p-5 border border-slate-800 space-y-2">
        <div class="flex items-center justify-between text-xs text-slate-400 pb-2 border-b border-slate-800">
            <span class="font-bold text-white"><?= esc($maintenanceLog['action'] ?? 'Output Eksekusi') ?></span>
            <span class="font-mono tabular-nums"><?= esc($maintenanceLog['time'] ?? '') ?></span>
        </div>
        <div class="font-mono text-xs space-y-1 leading-relaxed">
            <?php foreach (($maintenanceLog['lines'] ?? []) as $line): ?>
                <p class="<?= str_starts_with((string) $line, '[ERROR]') ? 'text-rose-400' : (str_starts_with((string) $line, '[OK]') ? 'text-emerald-400' : 'text-slate-300') ?>">
                    <?= esc($line) ?>
                </p>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<!-- Panel Utama Aksi Maintenance -->
<div class="space-y-6">

    <!-- 1. Jalankan Migration -->
    <section class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-7 shadow-xs space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h3 class="text-sm font-extrabold uppercase tracking-wider text-upskill-darkblue">
                    Jalankan Migration
                </h3>
                <p class="text-sm text-slate-600 mt-1">
                    Menjalankan migrasi database tertunda secara native (<code class="px-1.5 py-0.5 rounded bg-slate-100 border border-slate-200 text-slate-800 font-mono text-xs">php spark migrate</code>) tanpa akses terminal.
                </p>
            </div>
            <?php if ($pending_count > 0): ?>
                <span class="self-start sm:self-auto px-2.5 py-1 rounded-md text-xs font-bold bg-pink-50 text-upskill-pink border border-pink-200 tabular-nums">
                    <?= (int) $pending_count ?> file siap dijalankan
                </span>
            <?php endif; ?>
        </div>

        <form
            action="<?= base_url('admin/maintenance/migrate') ?>"
            method="POST"
            class="maintenance-form flex flex-col sm:flex-row items-stretch sm:items-end gap-3"
            data-confirm-title="Jalankan Migrasi Database?"
            data-confirm-text="Sistem akan mengeksekusi seluruh file migrasi terbaru pada database aktif."
            data-confirm-btn="Ya, Jalankan Migrate"
        >
            <?= csrf_field() ?>
            <div class="flex-1">
                <label for="token-migrate" class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                    Maintenance Token
                </label>
                <div class="relative">
                    <input
                        type="password"
                        id="token-migrate"
                        name="maintenance_token"
                        required
                        autocomplete="off"
                        <?= ! $token_ready ? 'disabled' : '' ?>
                        placeholder="Isi MAINTENANCE_TOKEN dari .env"
                        class="w-full px-4 py-2.5 pr-11 rounded-xl border border-slate-300 text-slate-900 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-upskill-pink/25 focus:border-upskill-pink disabled:bg-slate-100 disabled:text-slate-400"
                    >
                    <button
                        type="button"
                        onclick="toggleTokenField('token-migrate', this)"
                        class="absolute inset-y-0 right-0 px-3 text-slate-500 hover:text-slate-800 cursor-pointer"
                        aria-label="Tampilkan atau sembunyikan token"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <button
                type="submit"
                <?= ! $token_ready ? 'disabled' : '' ?>
                class="px-6 py-2.5 h-[42px] rounded-xl text-xs font-extrabold uppercase tracking-wider text-white bg-upskill-darkblue hover:bg-upskill-blue disabled:opacity-40 disabled:cursor-not-allowed transition-colors shrink-0 cursor-pointer"
            >
                Migrate
            </button>
        </form>
    </section>

    <!-- 2. Clear Cache -->
    <section class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-7 shadow-xs space-y-4">
        <div>
            <h3 class="text-sm font-extrabold uppercase tracking-wider text-upskill-darkblue">
                Clear Cache
            </h3>
            <p class="text-sm text-slate-600 mt-1">
                Membersihkan cache aplikasi CodeIgniter 4 (<code class="px-1.5 py-0.5 rounded bg-slate-100 border border-slate-200 text-slate-800 font-mono text-xs">writable/cache</code>, <code class="px-1.5 py-0.5 rounded bg-slate-100 border border-slate-200 text-slate-800 font-mono text-xs">writable/debugbar</code>, dan OPcache).
            </p>
        </div>

        <form
            action="<?= base_url('admin/maintenance/clear-cache') ?>"
            method="POST"
            class="maintenance-form flex flex-col sm:flex-row items-stretch sm:items-end gap-3"
            data-confirm-title="Bersihkan Cache Aplikasi?"
            data-confirm-text="Seluruh berkas cache sementara di server akan dihapus."
            data-confirm-btn="Ya, Clear Cache"
        >
            <?= csrf_field() ?>
            <div class="flex-1">
                <label for="token-cache" class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                    Maintenance Token
                </label>
                <div class="relative">
                    <input
                        type="password"
                        id="token-cache"
                        name="maintenance_token"
                        required
                        autocomplete="off"
                        <?= ! $token_ready ? 'disabled' : '' ?>
                        placeholder="Isi MAINTENANCE_TOKEN dari .env"
                        class="w-full px-4 py-2.5 pr-11 rounded-xl border border-slate-300 text-slate-900 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-upskill-pink/25 focus:border-upskill-pink disabled:bg-slate-100 disabled:text-slate-400"
                    >
                    <button
                        type="button"
                        onclick="toggleTokenField('token-cache', this)"
                        class="absolute inset-y-0 right-0 px-3 text-slate-500 hover:text-slate-800 cursor-pointer"
                        aria-label="Tampilkan atau sembunyikan token"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <button
                type="submit"
                <?= ! $token_ready ? 'disabled' : '' ?>
                class="px-6 py-2.5 h-[42px] rounded-xl text-xs font-extrabold uppercase tracking-wider text-white bg-slate-900 hover:bg-slate-800 disabled:opacity-40 disabled:cursor-not-allowed transition-colors shrink-0 cursor-pointer"
            >
                Clear Cache
            </button>
        </form>
    </section>

    <!-- 3. Seed Contoh Trial Quiz (Opsional & Idempoten) -->
    <section class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-7 shadow-xs space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h3 class="text-sm font-extrabold uppercase tracking-wider text-upskill-darkblue">
                    Isi Data Contoh Trial Quiz (Opsional)
                </h3>
                <p class="text-sm text-slate-600 mt-1">
                    Menjalankan <code class="px-1.5 py-0.5 rounded bg-slate-100 border border-slate-200 text-slate-800 font-mono text-xs">TrialQuizSeeder</code> untuk menambahkan contoh 5 soal Ilmu Tauhid &amp; 3 soal Fiqih Ibadah (aman dijalankan ulang, tidak duplikat).
                </p>
            </div>
            <span class="self-start sm:self-auto px-2.5 py-1 rounded-md text-xs font-semibold bg-slate-100 text-slate-700 tabular-nums">
                Saat ini: <?= (int) $trial_quiz_count ?> quiz
            </span>
        </div>

        <form
            action="<?= base_url('admin/maintenance/seed-trial-quiz') ?>"
            method="POST"
            class="maintenance-form flex flex-col sm:flex-row items-stretch sm:items-end gap-3"
            data-confirm-title="Jalankan Seeder Trial Quiz?"
            data-confirm-text="Menambahkan contoh Quiz Ilmu Tauhid & Fiqih jika belum ada di database."
            data-confirm-btn="Ya, Jalankan Seeder"
        >
            <?= csrf_field() ?>
            <div class="flex-1">
                <label for="token-seed" class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                    Maintenance Token
                </label>
                <div class="relative">
                    <input
                        type="password"
                        id="token-seed"
                        name="maintenance_token"
                        required
                        autocomplete="off"
                        <?= (! $token_ready || ! $trial_table_ready) ? 'disabled' : '' ?>
                        placeholder="<?= ! $trial_table_ready ? 'Jalankan Migrate terlebih dahulu' : 'Isi MAINTENANCE_TOKEN dari .env' ?>"
                        class="w-full px-4 py-2.5 pr-11 rounded-xl border border-slate-300 text-slate-900 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-upskill-pink/25 focus:border-upskill-pink disabled:bg-slate-100 disabled:text-slate-400"
                    >
                    <button
                        type="button"
                        onclick="toggleTokenField('token-seed', this)"
                        class="absolute inset-y-0 right-0 px-3 text-slate-500 hover:text-slate-800 cursor-pointer"
                        aria-label="Tampilkan atau sembunyikan token"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <button
                type="submit"
                <?= (! $token_ready || ! $trial_table_ready) ? 'disabled' : '' ?>
                class="px-6 py-2.5 h-[42px] rounded-xl text-xs font-extrabold uppercase tracking-wider text-white bg-upskill-pink hover:bg-upskill-magenta disabled:opacity-40 disabled:cursor-not-allowed transition-colors shrink-0 cursor-pointer"
            >
                Seed Quiz
            </button>
        </form>
    </section>

</div>

<!-- Status Daftar Migrasi -->
<section class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
        <div>
            <h3 class="text-sm font-extrabold text-upskill-darkblue">Daftar &amp; Status Migrasi</h3>
            <p class="text-xs text-slate-500 mt-0.5">
                Environment: <strong class="text-slate-700"><?= esc($environment) ?></strong> &middot; PHP <strong class="text-slate-700 font-mono"><?= esc($php_version) ?></strong>
            </p>
        </div>
        <span class="text-xs font-semibold text-slate-600 tabular-nums">
            <?= (int) $applied_count ?> terpasang &middot; <?= (int) $pending_count ?> tertunda
        </span>
    </div>

    <?php if (! empty($migration_error)): ?>
        <div class="p-5 text-xs text-rose-700 bg-rose-50">
            Gagal membaca daftar migrasi: <?= esc($migration_error) ?>
        </div>
    <?php elseif (empty($migrations_list)): ?>
        <div class="p-6 text-xs text-slate-500 text-center">
            Tidak ada file migrasi ditemukan di <code class="font-mono">app/Database/Migrations</code>.
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <th class="px-6 py-3">Versi</th>
                        <th class="px-6 py-3">Nama Migrasi</th>
                        <th class="px-6 py-3 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($migrations_list as $m): ?>
                        <tr>
                            <td class="px-6 py-3 font-mono text-xs text-slate-600 tabular-nums whitespace-nowrap">
                                <?= esc($m['version']) ?>
                            </td>
                            <td class="px-6 py-3 font-semibold text-slate-800">
                                <?= esc($m['name']) ?>
                            </td>
                            <td class="px-6 py-3 text-right whitespace-nowrap">
                                <?php if ($m['applied']): ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        Terpasang
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-900 border border-amber-200">
                                        Belum Dijalankan
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function toggleTokenField(inputId, btn) {
        const input = document.getElementById(inputId);
        if (!input) return;
        input.type = input.type === 'password' ? 'text' : 'password';
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.maintenance-form').forEach((form) => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const tokenInput = this.querySelector('input[name="maintenance_token"]');
                if (!tokenInput || !tokenInput.value.trim()) {
                    Swal.fire({
                        icon: 'warning',
                        iconColor: '#D97706',
                        title: 'Token Wajib Diisi',
                        text: 'Masukkan MAINTENANCE_TOKEN yang sesuai dengan file .env terlebih dahulu.',
                        confirmButtonColor: '#FA1886',
                        confirmButtonText: 'Mengerti'
                    });
                    return;
                }

                Swal.fire({
                    title: this.dataset.confirmTitle || 'Konfirmasi Aksi Maintenance',
                    text: this.dataset.confirmText || 'Pastikan Anda yakin ingin menjalankan aksi ini.',
                    icon: 'question',
                    iconColor: '#1C21AC',
                    showCancelButton: true,
                    confirmButtonColor: '#FA1886',
                    cancelButtonColor: '#64748B',
                    confirmButtonText: this.dataset.confirmBtn || 'Ya, Lanjutkan',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    });
</script>
<?= $this->endSection() ?>

