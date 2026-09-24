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
                <div class="relative">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required 
                        autocomplete="new-password"
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

            <!-- Konfirmasi Password -->
            <div>
                <label for="password_confirm" class="block text-xs font-bold uppercase tracking-wider text-upskill-darkblue mb-1.5">
                    Ulangi Password
                </label>
                <div class="relative">
                    <input 
                        type="password" 
                        id="password_confirm" 
                        name="password_confirm" 
                        required 
                        autocomplete="new-password"
                        placeholder="••••••••"
                        class="w-full px-4 py-2.5 sm:py-3 pr-11 rounded-lg border border-slate-300 text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-upskill-pink focus:border-upskill-pink transition-colors"
                    >
                    <button 
                        type="button" 
                        onclick="togglePasswordVisibility('password_confirm', this)"
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
