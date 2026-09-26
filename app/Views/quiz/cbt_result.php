<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Hasil Ujian CBT - UPSKILL') ?></title>
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
        <div class="w-16 h-16 mx-auto rounded-2xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 flex items-center justify-center mb-5">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

        <h2 class="text-xl font-bold text-white mb-1">
            Ujian Berhasil Dikumpulkan!
        </h2>
        <p class="text-xs text-slate-400 mb-6">
            Terima kasih telah menyelesaikan ujian sesi ini dengan tertib.
        </p>

        <!-- Ringkasan Hasil Pilihan Ganda -->
        <?php if ($totalPg > 0): ?>
            <div class="bg-slate-900/80 border border-slate-700 rounded-2xl p-5 mb-5 space-y-3">
                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Skor Evaluasi Otomatis (PG)</span>
                <div class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-pink-400 to-purple-400">
                    <?= $score ?> <span class="text-lg font-normal text-slate-500">/ 100</span>
                </div>
                <div class="flex items-center justify-center gap-4 text-xs text-slate-300 pt-2 border-t border-slate-800">
                    <div>Benar: <strong class="text-emerald-400"><?= $correctPg ?></strong></div>
                    <div>Salah: <strong class="text-rose-400"><?= $totalPg - $correctPg ?></strong></div>
                    <div>Total PG: <strong><?= $totalPg ?></strong></div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Catatan Soal Essay -->
        <?php if ($totalEssay > 0): ?>
            <div class="p-3.5 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-300 text-xs text-left mb-6 flex items-start gap-2.5">
                <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Terdapat <strong><?= $totalEssay ?> butir soal essay</strong> yang telah tersimpan dan akan dinilai secara manual oleh instruktur.</span>
            </div>
        <?php endif; ?>

        <a href="<?= base_url('courses') ?>" class="inline-flex items-center justify-center w-full py-3 px-5 rounded-xl font-bold text-xs text-white bg-gradient-to-r from-upskill-pink to-upskill-magenta hover:opacity-95 transition-all shadow-sm shadow-pink-500/20">
            Kembali ke Kelas
        </a>
    </div>
</body>
</html>
