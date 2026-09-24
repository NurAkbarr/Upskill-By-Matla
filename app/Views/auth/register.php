<?= $this->extend('layout/auth') ?>

<?= $this->section('content') ?>

<div class="w-full py-8 sm:py-12 px-4 sm:px-6">
    <!-- Kontainer Responsif Mobile & Tablet -->
    <div class="w-full max-w-md mx-auto p-6 sm:p-8 bg-white rounded-xl shadow-sm border border-gray-100">
        
        <!-- Header Formulir -->
        <div class="text-center mb-6 sm:mb-8">
            <h2 class="text-2xl font-extrabold tracking-tight text-upskill-darkblue">
                Daftar Akun Baru
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1.5">
                Mulai tingkatkan keahlian praktis bersama Muslim Upskill Academy.
            </p>
        </div>

        <!-- Notifikasi Error Validasi -->
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="mb-5 p-3.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm">
                <p class="font-bold mb-1.5">Mohon lengkapi formulir dengan benar:</p>
                <ul class="list-disc list-inside space-y-1 text-xs">
                    <?php foreach (session()->getFlashdata('errors') as $err): ?>
                        <li><?= esc($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-5 p-3.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <!-- Formulir Registrasi Sederhana -->
        <form action="<?= base_url('register') ?>" method="POST" class="space-y-4 sm:space-y-5">
            <?= csrf_field() ?>

            <!-- Nama Lengkap -->
            <div>
                <label for="full_name" class="block text-xs font-bold uppercase tracking-wider text-upskill-darkblue mb-1.5">
                    Nama Lengkap
                </label>
                <input 
                    type="text" 
                    id="full_name" 
                    name="full_name" 
                    value="<?= old('full_name') ?>" 
                    required 
                    autocomplete="name"
                    placeholder="Contoh: Ahmad Fauzan"
                    class="w-full px-4 py-2.5 sm:py-3 rounded-lg border border-slate-300 text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-upskill-pink focus:border-upskill-pink transition-colors"
                >
            </div>

            <!-- Alamat Email -->
            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-upskill-darkblue mb-1.5">
                    Alamat Email Aktif
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="<?= old('email') ?>" 
                    required 
                    autocomplete="email"
                    placeholder="nama@email.com"
                    class="w-full px-4 py-2.5 sm:py-3 rounded-lg border border-slate-300 text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-upskill-pink focus:border-upskill-pink transition-colors"
                >
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-upskill-darkblue mb-1.5">
                    Password (Min. 6 Karakter)
                </label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required 
                    autocomplete="new-password"
                    placeholder="••••••••"
                    class="w-full px-4 py-2.5 sm:py-3 rounded-lg border border-slate-300 text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-upskill-pink focus:border-upskill-pink transition-colors"
                >
            </div>

            <!-- Konfirmasi Password -->
            <div>
                <label for="password_confirm" class="block text-xs font-bold uppercase tracking-wider text-upskill-darkblue mb-1.5">
                    Ulangi Password
                </label>
                <input 
                    type="password" 
                    id="password_confirm" 
                    name="password_confirm" 
                    required 
                    autocomplete="new-password"
                    placeholder="••••••••"
                    class="w-full px-4 py-2.5 sm:py-3 rounded-lg border border-slate-300 text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-upskill-pink focus:border-upskill-pink transition-colors"
                >
            </div>

            <!-- Tombol Submit -->
            <div class="pt-2">
                <button 
                    type="submit" 
                    class="w-full py-3 inline-flex items-center justify-center font-bold text-sm rounded-lg text-white bg-upskill-pink hover:bg-upskill-magenta transition-colors duration-300 shadow-sm"
                >
                    Daftar Sekarang
                </button>
            </div>
        </form>

        <!-- Tautan ke Halaman Login -->
        <div class="mt-6 pt-5 border-t border-slate-100 text-center">
            <p class="text-xs sm:text-sm text-slate-500">
                Sudah memiliki akun?
                <a href="<?= base_url('login') ?>" class="font-bold text-upskill-pink hover:text-upskill-magenta ml-1 transition-colors">
                    Masuk di sini
                </a>
            </p>
        </div>

    </div>
</div>

<?= $this->endSection() ?>
