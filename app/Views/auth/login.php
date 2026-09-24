<?= $this->extend('layout/auth') ?>

<?= $this->section('content') ?>

<div class="w-full py-8 sm:py-12 px-4 sm:px-6">
    <!-- Kontainer Responsif Mobile & Tablet -->
    <div class="w-full max-w-md mx-auto p-6 sm:p-8 bg-white rounded-xl shadow-sm border border-gray-100">
        
        <!-- Header Formulir -->
        <div class="text-center mb-6 sm:mb-8">
            <h2 class="text-2xl font-extrabold tracking-tight text-upskill-darkblue">
                Masuk ke Akun
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1.5">
                Akses materi belajar Anda di Muslim Upskill Academy.
            </p>
        </div>

        <!-- Pesan Sukses Flashdata -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="mb-5 p-3.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-start gap-2.5">
                <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span><?= esc(session()->getFlashdata('success')) ?></span>
            </div>
        <?php endif; ?>

        <!-- Pesan Error Flashdata -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-5 p-3.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm flex items-start gap-2.5">
                <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span><?= esc(session()->getFlashdata('error')) ?></span>
            </div>
        <?php endif; ?>

        <!-- Error Validasi Array -->
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="mb-5 p-3.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm">
                <ul class="list-disc list-inside space-y-1 text-xs">
                    <?php foreach (session()->getFlashdata('errors') as $err): ?>
                        <li><?= esc($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Form Login Responsif -->
        <form action="<?= base_url('login') ?>" method="POST" class="space-y-4 sm:space-y-5">
            <?= csrf_field() ?>

            <!-- Alamat Email -->
            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-upskill-darkblue mb-1.5">
                    Alamat Email
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
                <div class="flex items-center justify-between mb-1.5">
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-upskill-darkblue">
                        Password
                    </label>
                </div>
                <div class="relative">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required 
                        autocomplete="current-password"
                        placeholder="••••••••"
                        class="w-full px-4 py-2.5 sm:py-3 pr-11 rounded-lg border border-slate-300 text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-upskill-pink focus:border-upskill-pink transition-colors"
                    >
                    <button 
                        type="button" 
                        onclick="togglePasswordVisibility('password', this)"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none"
                        title="Tampilkan / Sembunyikan Password"
                        aria-label="Toggle password visibility"
                    >
                        <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg class="w-5 h-5 eye-slash-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Tombol Masuk -->
            <div class="pt-2">
                <button 
                    type="submit" 
                    class="w-full py-3 inline-flex items-center justify-center font-bold text-sm rounded-lg text-white bg-upskill-pink hover:bg-upskill-magenta transition-colors duration-300 shadow-sm"
                >
                    Masuk ke Akun
                </button>
            </div>
        </form>

        <!-- Tautan ke Halaman Register -->
        <div class="mt-6 pt-5 border-t border-slate-100 text-center">
            <p class="text-xs sm:text-sm text-slate-500">
                Belum memiliki akun?
                <a href="<?= base_url('register') ?>" class="font-bold text-upskill-pink hover:text-upskill-magenta ml-1 transition-colors">
                    Daftar sekarang
                </a>
            </p>
        </div>

    </div>
</div>

<?= $this->endSection() ?>
