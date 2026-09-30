<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Pengaturan Akun - UPSKILL by MATLA') ?></title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'upskill-pink': '#FF385C',
                        'upskill-darkblue': '#0A1128',
                        'upskill-blue': '#1C21AC',
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased min-h-screen flex">

    <!-- ============================================================== -->
    <!-- 1. SIDEBAR NAVIGASI (Desktop - Kiri)                           -->
    <!-- ============================================================== -->
    <aside class="hidden md:flex w-64 bg-white border-r border-slate-200 flex-col justify-between shrink-0 h-screen select-none sticky top-0">
        
        <!-- Bagian Atas: Logo & Menu Utama -->
        <div>
            <!-- Identitas Brand -->
            <div class="h-20 flex items-center px-6 border-b border-slate-100">
                <a href="<?= base_url('/') ?>" class="flex items-center gap-2.5">
                    <img src="<?= base_url('assets/images/logo-removebg.png') ?>" alt="Muslim Upskill Academy" class="h-12 w-auto object-contain">
                </a>
            </div>

            <!-- Daftar Menu Navigasi Sidebar -->
            <nav class="p-4 space-y-1">
                <!-- 1. Ringkasan -->
                <a href="<?= base_url('dashboard') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold text-upskill-darkblue hover:text-upskill-pink hover:bg-slate-50 transition-colors">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Ringkasan
                </a>

                <!-- 2. Katalog Program -->
                <a href="<?= base_url('dashboard#katalog-program') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold text-upskill-darkblue hover:text-upskill-pink hover:bg-slate-50 transition-colors">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Katalog Program
                </a>

                <!-- 3. Pengaturan Akun (Aktif) -->
                <a href="<?= base_url('dashboard/account') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold bg-pink-50 text-upskill-pink transition-colors">
                    <svg class="w-4 h-4 text-upskill-pink" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Pengaturan Akun
                </a>
            </nav>
        </div>

        <!-- Bagian Bawah: Profil Singkat & Menu Keluar -->
        <div class="p-4 border-t border-slate-100">
            <!-- Profil Singkat -->
            <div class="block px-3.5 py-3 mb-2 rounded-lg bg-slate-50 border border-slate-200/70">
                <p class="text-xs font-bold text-upskill-darkblue truncate">
                    <?= esc($user['full_name'] ?? $user_name) ?>
                </p>
                <div class="mt-1 flex items-center justify-between">
                    <span class="inline-flex items-center text-[10px] font-semibold text-upskill-pink bg-pink-50 px-2 py-0.5 rounded border border-pink-100">
                        <?= esc(ucfirst($user['role'] ?? 'Peserta')) ?>
                    </span>
                    <span class="text-[10px] text-slate-400 font-medium">Aktif</span>
                </div>
            </div>

            <!-- Tombol Keluar (Logout) -->
            <a href="<?= base_url('logout') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold text-rose-600 hover:bg-rose-50 transition-colors">
                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Keluar
            </a>
        </div>
    </aside>

    <!-- ============================================================== -->
    <!-- 2. MAIN CONTENT AREA (Kanan)                                  -->
    <!-- ============================================================== -->
    <main class="flex-1 flex flex-col min-w-0 min-h-screen">
        
        <!-- Header Atas (Top Nav) -->
        <header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-6 sm:px-10 shrink-0 sticky top-0 z-30">
            <div class="flex items-center gap-3">
                <a href="<?= base_url('dashboard') ?>" class="md:hidden text-slate-500 hover:text-upskill-darkblue">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h1 class="text-base font-bold text-upskill-darkblue">Pengaturan Akun</h1>
            </div>
            <div class="flex items-center gap-3">
                <a href="<?= base_url('dashboard') ?>" class="inline-flex items-center justify-center px-3.5 py-1.5 text-xs font-semibold rounded-lg text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">
                    &larr; Kembali ke Dasbor
                </a>
            </div>
        </header>

        <!-- Wadah Konten Utama -->
        <div class="p-6 sm:p-10 max-w-3xl w-full mx-auto space-y-6 pb-24 md:pb-10">

            <!-- Flashdata Alert Success -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2.5 shadow-2xs">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="font-medium"><?= esc(session()->getFlashdata('success')) ?></span>
                </div>
            <?php endif; ?>

            <!-- Flashdata Alert Error -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-2.5 shadow-2xs">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span class="font-medium"><?= esc(session()->getFlashdata('error')) ?></span>
                </div>
            <?php endif; ?>

            <!-- Kartu Formulir Pengaturan Akun -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
                
                <!-- Header Kartu -->
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-upskill-darkblue">Informasi Profil & Keamanan</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Perbarui nama lengkap dan kata sandi akun Anda.</p>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500 inline-block"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500 inline-block"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span>
                    </div>
                </div>

                <!-- Formulir -->
                <form action="<?= base_url('dashboard/account/update') ?>" method="POST" class="p-6 sm:p-8 space-y-6">
                    <?= csrf_field() ?>

                    <!-- Info Akun Singkat -->
                    <div class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="w-12 h-12 rounded-full bg-pink-100 text-upskill-pink font-extrabold text-lg flex items-center justify-center shrink-0">
                            <?= esc(strtoupper(substr($user['full_name'] ?? 'P', 0, 1))) ?>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="text-sm font-bold text-upskill-darkblue truncate"><?= esc($user['full_name'] ?? '') ?></h3>
                            <p class="text-xs text-slate-500 truncate"><?= esc($user['email'] ?? '') ?></p>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 shrink-0">
                            <?= esc(ucfirst($user['role'] ?? 'Peserta')) ?>
                        </span>
                    </div>

                    <!-- Input 1: Nama Pengguna / Lengkap -->
                    <div class="space-y-2">
                        <label for="full_name" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Nama Pengguna <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="full_name" 
                            name="full_name" 
                            value="<?= esc($user['full_name'] ?? '') ?>" 
                            required 
                            placeholder="Masukkan nama lengkap Anda"
                            class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 bg-white text-slate-900 focus:outline-none focus:ring-2 focus:ring-upskill-pink/20 focus:border-upskill-pink transition-colors"
                        >
                    </div>

                    <!-- Input 2: Email (Readonly) -->
                    <div class="space-y-2">
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Alamat Email
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            value="<?= esc($user['email'] ?? '') ?>" 
                            disabled 
                            class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 bg-slate-100 text-slate-500 cursor-not-allowed select-none"
                        >
                        <p class="text-[11px] text-slate-400">Alamat email terdaftar dan tidak dapat diubah.</p>
                    </div>

                    <hr class="border-slate-100 my-2">

                    <!-- Input 3: Password Baru -->
                    <div class="space-y-2">
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Password Baru
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="••••••••"
                            minlength="6"
                            class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 bg-white text-slate-900 focus:outline-none focus:ring-2 focus:ring-upskill-pink/20 focus:border-upskill-pink transition-colors"
                        >
                        <p class="text-[11px] text-slate-500">
                            <span class="font-medium text-amber-700">Catatan:</span> Kosongkan jika tidak ingin mengubah password. (Minimal 6 karakter bila diisi)
                        </p>
                    </div>

                    <!-- Input 4: Konfirmasi Password Baru -->
                    <div class="space-y-2">
                        <label for="password_confirm" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Konfirmasi Password Baru
                        </label>
                        <input 
                            type="password" 
                            id="password_confirm" 
                            name="password_confirm" 
                            placeholder="••••••••"
                            minlength="6"
                            class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 bg-white text-slate-900 focus:outline-none focus:ring-2 focus:ring-upskill-pink/20 focus:border-upskill-pink transition-colors"
                        >
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                        <a 
                            href="<?= base_url('dashboard') ?>" 
                            class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors"
                        >
                            Batal
                        </a>
                        <button 
                            type="submit" 
                            class="px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-upskill-pink hover:bg-pink-600 transition-colors shadow-sm flex items-center gap-2"
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
    </main>

    <!-- ============================================================== -->
    <!-- 3. BOTTOM NAVIGATION BAR (Mobile)                              -->
    <!-- ============================================================== -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md border-t border-slate-200 px-4 py-2 flex items-center justify-around shadow-lg">
        <a href="<?= base_url('dashboard') ?>" class="flex flex-col items-center gap-1 text-slate-500 hover:text-upskill-darkblue">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="text-[10px]">Ringkasan</span>
        </a>
        <a href="<?= base_url('dashboard#katalog-program') ?>" class="flex flex-col items-center gap-1 text-slate-500 hover:text-upskill-darkblue">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <span class="text-[10px]">Katalog</span>
        </a>
        <a href="<?= base_url('dashboard/account') ?>" class="flex flex-col items-center gap-1 text-upskill-pink font-semibold">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span class="text-[10px]">Akun</span>
        </a>
        <a href="<?= base_url('logout') ?>" class="flex flex-col items-center gap-1 text-rose-500 hover:text-rose-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            <span class="text-[10px]">Keluar</span>
        </a>
    </nav>

</body>
</html>
