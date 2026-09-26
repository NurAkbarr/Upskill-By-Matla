<!DOCTYPE html>
<html lang="id" class="h-full bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Panel Super Admin - MUSLIM UPSKILL ACADEMY') ?></title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/logo.png') ?>">
    <link rel="shortcut icon" href="<?= base_url('favicon.ico') ?>">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: {
              sans: ['Inter', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
            },
            colors: {
              'upskill-darkblue': '#1C21AC',
              'upskill-blue': '#0101FF',
              'upskill-pink': '#FA1886',
              'upskill-magenta': '#B103C5',
            }
          }
        }
      }
    </script>
    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            -webkit-font-smoothing: antialiased;
        }
    </style>
</head>
<body class="h-full bg-white text-slate-900 flex overflow-hidden">

    <!-- ============================================================== -->
    <!-- SIDEBAR SUPER ADMIN (Tema Gelap: bg-slate-900)                 -->
    <!-- ============================================================== -->
    <aside class="w-64 bg-slate-900 border-r border-slate-800 text-slate-300 flex flex-col justify-between shrink-0 h-screen select-none">
        
        <!-- Bagian Atas: Brand & Navigasi -->
        <div>
            <!-- Header Brand Super Admin -->
            <div class="h-20 flex items-center justify-between px-6 border-b border-slate-800/80 bg-slate-950/40">
                <a href="<?= base_url('admin/dashboard') ?>" class="flex items-center gap-2.5">
                    <div class="bg-white/95 px-2.5 py-1.5 rounded-lg shadow-sm flex items-center">
                        <img src="<?= base_url('assets/images/logo-removebg.png') ?>" alt="Muslim Upskill Academy" class="h-7 w-auto object-contain">
                    </div>
                </a>
                <span class="text-[9px] font-bold tracking-wider uppercase px-2 py-0.5 rounded bg-pink-500/10 text-upskill-pink border border-upskill-pink/20">
                    Admin
                </span>
            </div>

            <!-- Menu Navigasi Admin -->
            <nav class="p-4 space-y-1">
                <!-- 1. Ringkasan -->
                <a href="<?= base_url('admin/dashboard') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold <?= uri_string() === 'admin/dashboard' ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' ?> transition-colors duration-300">
                    <svg class="w-4 h-4 <?= uri_string() === 'admin/dashboard' ? 'text-upskill-pink' : 'text-slate-400' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Ringkasan
                </a>

                <!-- 2. Kelola Pengguna (Aktif jika di rute admin/users*) -->
                <a href="<?= base_url('admin/users') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold <?= strpos(uri_string(), 'admin/users') !== false ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' ?> transition-colors duration-300">
                    <svg class="w-4 h-4 <?= strpos(uri_string(), 'admin/users') !== false ? 'text-upskill-pink' : 'text-slate-400' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Kelola Pengguna
                </a>

                <!-- 3. Katalog Kelas (Aktif jika di rute admin/courses*) -->
                <a href="<?= base_url('admin/courses') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold <?= strpos(uri_string(), 'admin/courses') !== false ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' ?> transition-colors duration-300">
                    <svg class="w-4 h-4 <?= strpos(uri_string(), 'admin/courses') !== false ? 'text-upskill-pink' : 'text-slate-400' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    Katalog Kelas
                </a>

                <!-- 4. Transaksi -->
                <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800/60 transition-colors duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    Transaksi
                </a>

                <!-- 5. Laporan -->
                <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800/60 transition-colors duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Laporan
                </a>
            </nav>
        </div>

        <!-- Bagian Bawah: Profil Admin & Logout -->
        <div class="p-4 border-t border-slate-800 bg-slate-950/30">
            <div class="px-3.5 py-2.5 mb-2 rounded-lg bg-slate-800/50 border border-slate-700/50">
                <p class="text-xs font-semibold text-white truncate">
                    <?= esc($admin_name ?? 'Super Admin MATLA') ?>
                </p>
                <p class="text-[10px] text-slate-400 truncate">
                    <?= esc($admin_email ?? 'admin@matla.id') ?>
                </p>
            </div>

            <div class="flex items-center justify-between pt-1 text-xs">
                <a href="<?= base_url('/') ?>" target="_blank" class="text-slate-400 hover:text-white transition-colors duration-300">
                    &larr; Web Publik
                </a>
                <a href="<?= base_url('logout') ?>" class="text-upskill-pink hover:text-upskill-magenta font-semibold transition-colors duration-300">
                    Keluar
                </a>
            </div>
        </div>
    </aside>

    <!-- ============================================================== -->
    <!-- MAIN CONTENT AREA                                              -->
    <!-- ============================================================== -->
    <main class="flex-1 overflow-y-auto bg-slate-50">
        
        <!-- Header Atas Area Konten -->
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 sm:px-10">
            <div>
                <h1 class="text-base font-bold text-upskill-darkblue">Panel Manajemen Super Admin</h1>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-pink-50 text-upskill-pink border border-pink-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-upskill-pink"></span>
                    Sistem Beroperasi Normal
                </span>
            </div>
        </header>

        <!-- Area Konten Utama -->
        <div class="p-6 sm:p-8 lg:p-10 max-w-7xl mx-auto space-y-8">
            <?= $this->renderSection('content') ?>
        </div>
    </main>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Inisialisasi Toast SweetAlert2 untuk Notifikasi Flash Data
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        <?php if (session()->getFlashdata('success')): ?>
            Toast.fire({
                icon: 'success',
                title: '<?= esc(session()->getFlashdata('success')) ?>'
            });
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            Toast.fire({
                icon: 'error',
                title: '<?= esc(session()->getFlashdata('error')) ?>'
            });
        <?php endif; ?>

        // Global Event Listener untuk Semua Tombol .btn-delete
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Cek jika akun sendiri yang sedang aktif
                    if (this.getAttribute('data-self') === 'true') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Aksi Ditolak',
                            text: 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif digunakan.',
                            confirmButtonColor: '#FA1886',
                            confirmButtonText: 'Mengerti'
                        });
                        return;
                    }

                    const deleteUrl = this.getAttribute('data-href');
                    const customMessage = this.getAttribute('data-message') || "Data akses dan riwayat ini akan dihapus secara permanen.";
                    
                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: customMessage,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#FA1886', // upskill-pink
                        cancelButtonColor: '#6B7280', // abu-abu Tailwind
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = deleteUrl;
                        }
                    });
                });
            });
        });
    </script>

    <?= $this->renderSection('scripts') ?>
</body>
</html>
