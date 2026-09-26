<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Informasi Jadwal Kuis - UPSKILL') ?></title>
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/logo.png') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
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
<body class="bg-slate-900 min-h-screen flex items-center justify-center p-4 font-sans text-slate-100">
    <div class="max-w-md w-full bg-slate-800 border border-slate-700 rounded-3xl p-8 text-center shadow-2xl">
        <div class="w-16 h-16 mx-auto rounded-2xl <?= $status === 'not_started' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20' ?> flex items-center justify-center mb-5">
            <?php if ($status === 'not_started'): ?>
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            <?php else: ?>
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H10m12-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            <?php endif; ?>
        </div>

        <h2 class="text-xl font-bold text-white mb-2">
            <?= $status === 'not_started' ? 'Ujian Belum Dimulai' : 'Ujian Telah Ditutup' ?>
        </h2>

        <p class="text-xs text-slate-400 mb-6 leading-relaxed">
            <?= esc($message) ?>
        </p>

        <div class="p-4 rounded-2xl bg-slate-900/60 border border-slate-700/60 text-left text-xs space-y-1.5 mb-6">
            <p><span class="text-slate-500">Kelas:</span> <strong class="text-white"><?= esc($course['title']) ?></strong></p>
            <p><span class="text-slate-500">Sesi:</span> <strong class="text-white"><?= esc($session['chapter_title']) ?></strong></p>
        </div>

        <a href="<?= base_url('courses') ?>" class="inline-flex items-center justify-center w-full py-3 px-5 rounded-xl font-bold text-xs text-white bg-gradient-to-r from-upskill-pink to-upskill-magenta hover:opacity-95 transition-all shadow-sm shadow-pink-500/20">
            Kembali ke Beranda
        </a>
    </div>
</body>
</html>
