<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Ujian Berhasil Dikumpulkan - MUSLIM UPSKILL ACADEMY') ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/logo.png') ?>">
    <link rel="shortcut icon" href="<?= base_url('favicon.ico') ?>">
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: {
              sans: ['Inter', 'sans-serif'],
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
</head>
<body class="bg-slate-50 min-h-screen font-sans text-slate-800">

    <?php $essay_count = $essay_count ?? $totalEssay ?? 0; ?>

    <!-- Wrapper Halaman -->
    <div class="min-h-screen flex items-center justify-center bg-slate-50 p-4">

        <!-- Kartu Utama -->
        <div class="bg-white max-w-md w-full rounded-3xl shadow-xl p-8 md:p-10 text-center border border-slate-100">

            <!-- Ikon Ceklis (Berhasil) -->
            <div class="w-20 h-20 mx-auto bg-emerald-50 rounded-full flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>

            <!-- Judul & Deskripsi -->
            <h2 class="text-2xl font-extrabold text-upskill-darkblue mb-2">Ujian Berhasil Dikumpulkan!</h2>
            <p class="text-slate-500 mb-6 text-sm">Terima kasih telah menyelesaikan ujian evaluasi sesi ini dengan tertib dan jujur.</p>

            <!-- Notifikasi Soal Essay (Tampil Kondisional) -->
            <?php if (!empty($essay_count) && $essay_count > 0): ?>
            <div class="bg-amber-50 border border-amber-200/80 rounded-2xl p-4 text-left mb-6 flex items-start gap-3">
                <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-xs text-amber-800 leading-relaxed">
                    Terdapat <span class="font-bold"><?= esc($essay_count) ?> butir soal essay</span> yang telah tersimpan dan akan dinilai secara manual oleh instruktur.
                </p>
            </div>
            <?php endif; ?>

            <!-- Kotak Informasi Detail -->
            <div class="bg-slate-50 rounded-2xl p-5 text-left mb-8 border border-slate-100">
                <div class="mb-4">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Program Kelas</p>
                    <p class="text-sm font-semibold text-upskill-darkblue"><?= esc($course['title'] ?? 'Program Pembelajaran') ?></p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Sesi Evaluasi</p>
                    <p class="text-sm font-semibold text-upskill-darkblue"><?= esc($session['chapter_title'] ?? $session['title'] ?? 'Sesi Evaluasi') ?></p>
                </div>
            </div>

            <!-- Tombol Kembali -->
            <a href="<?= base_url('dashboard/course/' . ($course['id'] ?? '')) ?>" class="block w-full bg-upskill-pink hover:bg-upskill-magenta text-white font-bold py-3.5 px-4 rounded-xl transition-all duration-300">
                Kembali ke Kelas
            </a>

        </div>
    </div>

</body>
</html>
