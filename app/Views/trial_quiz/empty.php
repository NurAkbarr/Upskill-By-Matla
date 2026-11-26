<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<section class="py-20 sm:py-28 px-4 sm:px-6">
    <div class="max-w-lg mx-auto bg-white border border-slate-200 rounded-2xl p-8 sm:p-10 text-center space-y-5">
        <div class="w-12 h-12 mx-auto rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>

        <div class="space-y-2">
            <h1 class="text-xl sm:text-2xl font-extrabold text-upskill-darkblue tracking-tight">
                Quiz Evaluasi Sedang Dipersiapkan
            </h1>
            <p class="text-sm text-slate-600 leading-relaxed">
                Saat ini belum ada quiz trial yang dipublikasikan. Anda dapat menjelajahi katalog kelas sambil menunggu quiz tersedia.
            </p>
        </div>

        <div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a
                href="<?= base_url('/#program-kelas') ?>"
                class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 rounded-lg text-sm font-bold text-white bg-upskill-pink hover:bg-upskill-magenta transition-colors"
            >
                Lihat Program Kelas
            </a>
            <a
                href="<?= base_url('/') ?>"
                class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 rounded-lg text-sm font-semibold text-upskill-darkblue bg-slate-100 hover:bg-slate-200 transition-colors"
            >
                Kembali ke Beranda
            </a>
        </div>
    </div>
</section>

<?= $this->endSection() ?>

