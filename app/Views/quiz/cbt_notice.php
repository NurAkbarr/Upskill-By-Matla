<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Ujian Telah Ditutup - MUSLIM UPSKILL ACADEMY') ?></title>
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/logo.png') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: {
              sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
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
<body class="bg-slate-50 text-slate-900 min-h-screen">

    <!-- Wrapper Halaman -->
    <div class="min-h-screen flex items-center justify-center bg-slate-50 p-4">

        <!-- Kartu Utama -->
        <div class="bg-white max-w-md w-full rounded-3xl shadow-xl p-8 md:p-10 text-center border border-slate-100">

            <!-- Ikon Gembok (Terkunci) -->
            <div class="w-20 h-20 mx-auto bg-pink-50 rounded-full flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-upskill-pink" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>

            <!-- Judul & Deskripsi -->
            <h2 class="text-2xl font-extrabold text-upskill-darkblue mb-2">
                <?= ($status ?? '') === 'not_started' ? 'Ujian Belum Dimulai' : 'Ujian Telah Ditutup' ?>
            </h2>
            <p class="text-slate-500 mb-8 text-sm">
                <?= !empty($message) ? esc($message) : 'Waktu pengerjaan ujian CBT ini telah habis atau ditutup secara resmi oleh sistem. Anda tidak dapat lagi mengirimkan jawaban.' ?>
            </p>

            <!-- Kotak Informasi Detail -->
            <div class="bg-slate-50 rounded-2xl p-5 text-left mb-8 border border-slate-100">
                <div class="mb-4">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Program Kelas</p>
                    <p class="text-sm font-semibold text-upskill-darkblue"><?= esc($course['title'] ?? 'Dasar Literasi Finansial & Akuntansi UMKM') ?></p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Sesi Evaluasi</p>
                    <p class="text-sm font-semibold text-upskill-darkblue"><?= esc($session['chapter_title'] ?? $session['title'] ?? 'Dasar Literasi') ?></p>
                </div>
            </div>

            <!-- Tombol Kembali -->
            <!-- Pastikan href mengarah ke base_url dashboard yang tepat -->
            <a href="<?= base_url('dashboard') ?>" class="block w-full bg-upskill-pink hover:bg-upskill-magenta text-white font-bold py-3.5 px-4 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg text-center">
                Kembali ke Beranda
            </a>

        </div>
    </div>

</body>
</html>
