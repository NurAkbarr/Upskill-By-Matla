<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Swiper.js CSS via CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<style>
    /* Styling Kustom Navigasi & Paginasi Swiper */
    .course-swiper-pagination .swiper-pagination-bullet {
        width: 8px;
        height: 8px;
        background-color: #cbd5e1;
        opacity: 1;
        border-radius: 9999px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-block;
        margin: 0 4px !important;
    }
    .course-swiper-pagination .swiper-pagination-bullet-active {
        width: 28px;
        background-color: #FA1886;
        border-radius: 9999px;
    }
    .swiper-button-disabled {
        opacity: 0.35 !important;
        cursor: not-allowed !important;
    }

    /* Ambient Soft Tint halus di sudut sel teks showcase (seperti aksen sudut pada grid arsitektural) */
    .showcase-tint-pink {
        background: radial-gradient(circle at 0% 50%, rgba(250, 24, 134, 0.07) 0%, rgba(255, 255, 255, 0) 65%);
    }
    .showcase-tint-blue {
        background: radial-gradient(circle at 100% 50%, rgba(28, 33, 172, 0.07) 0%, rgba(255, 255, 255, 0) 65%);
    }

    /* Efek garis siku / crosshair (+) presisi pada titik pertemuan grid */
    .grid-crosshair {
        position: absolute;
        width: 15px;
        height: 15px;
        pointer-events: none;
        z-index: 20;
    }
    .grid-crosshair::before,
    .grid-crosshair::after {
        content: "";
        position: absolute;
        background-color: #94a3b8;
    }
    .grid-crosshair::before {
        top: 7px;
        left: 0;
        width: 15px;
        height: 1px;
    }
    .grid-crosshair::after {
        top: 0;
        left: 7px;
        width: 1px;
        height: 15px;
    }

    /* Mikro-animasi halus untuk floating badge & demo UI */
    @keyframes upskillFloatSlow {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-6px); }
    }
    .animate-float-slow {
        animation: upskillFloatSlow 5.5s ease-in-out infinite;
    }
    .animate-float-delayed {
        animation: upskillFloatSlow 6.5s ease-in-out 1.4s infinite;
    }

    /* Transisi halus state demo UI fitur */
    .demo-opt {
        transition: border-color 0.25s ease, background-color 0.25s ease, color 0.25s ease;
    }
    .demo-opt.is-selected {
        border-color: #1C21AC;
        background-color: #EFF6FF;
    }
    .demo-opt.is-selected .demo-opt-badge {
        background-color: #1C21AC;
        border-color: #1C21AC;
        color: #FFFFFF;
    }

    /* Timeline scroll-follow highlight state (zero layout shift) */
    .timeline-card {
        border: 1px solid transparent;
        transition: background-color 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
    }
    .timeline-item.is-active .timeline-card {
        background-color: #FDF2F8;
        border-color: #FBCFE8;
    }
    .timeline-node {
        transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
    }
    .timeline-item.is-passed .timeline-node {
        background-color: #FFFFFF;
        border-color: #FA1886;
        color: #FA1886;
    }
    .timeline-item.is-active .timeline-node {
        background-color: #FA1886;
        border-color: #FA1886;
        color: #FFFFFF;
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(250, 24, 134, 0.25);
    }
    @media (min-width: 768px) {
        .timeline-item.is-active .timeline-node {
            transform: translateX(-50%) scale(1.1);
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .animate-float-slow,
        .animate-float-delayed {
            animation: none;
        }
    }
</style>

<?php
    $trialTitle = $featured_trial['title'] ?? 'Tes Pemahaman Ilmu Tauhid Dasar';
    $trialTopic = $featured_trial['topic_label'] ?? 'Ilmu Tauhid';
    $trialCount = (int) ($featured_trial['question_count'] ?? 5);
?>

<!-- ====================================================================== -->
<!-- 1. HERO SECTION (2 KOLOM: COPY ASLI UPSKILL + ILUSTRASI 3D ISOMETRIK)  -->
<!-- ====================================================================== -->
<section class="relative overflow-hidden bg-[#E8EBF2] border-b border-slate-200/80">
    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14 sm:py-20 lg:py-24">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-10 items-center">

            <!-- Kiri: Konten Asli MUSLIM UPSKILL ACADEMY -->
            <div class="lg:col-span-6 space-y-6">
                <p class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/90 border border-slate-200/90 text-xs font-bold text-upskill-pink tracking-wide shadow-2xs">
                    <span class="w-1.5 h-1.5 rounded-full bg-upskill-pink"></span>
                    <span>MUSLIM UPSKILL ACADEMY</span>
                </p>

                <h1 class="text-3xl sm:text-5xl font-extrabold text-upskill-darkblue tracking-tight leading-[1.14]">
                    Tingkatkan Kompetensi,<br>
                    <span class="text-upskill-pink">Berikan Manfaat bagi Umat</span>
                </h1>

                <p class="text-base sm:text-lg text-slate-700 leading-relaxed max-w-xl">
                    Pelajari keterampilan digital, bisnis, dan profesional relevan bersama mentor praktisi. Dirancang terstruktur untuk akselerasi karier dengan landasan etika dan keberkahan.
                </p>

                <!-- Tombol Aksi Utama -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3.5 pt-1">
                    <a
                        href="#program-kelas"
                        class="inline-flex items-center justify-center gap-2 px-7 py-3.5 text-sm font-bold rounded-xl text-white bg-upskill-pink hover:bg-upskill-magenta focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-upskill-pink focus-visible:ring-offset-2 transition-colors shadow-sm"
                    >
                        <span>Lihat Program Kelas</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </a>

                    <a
                        href="<?= base_url('quiz') ?>"
                        class="inline-flex items-center justify-center gap-2 px-7 py-3.5 text-sm font-bold rounded-xl text-upskill-darkblue bg-white hover:bg-slate-50 border border-slate-300/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-upskill-darkblue focus-visible:ring-offset-2 transition-colors shadow-2xs"
                    >
                        <span>Uji Coba Quiz</span>
                        <svg class="w-4 h-4 text-upskill-pink" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Kanan: Ilustrasi 3D Isometrik Khusus Upskill + Floating Status Badges -->
            <div class="lg:col-span-6 relative flex items-center justify-center">
                <div class="relative w-full max-w-[460px] mx-auto">
                    <!-- Floating Badge Atas Kanan (Klik langsung ke Uji Coba Quiz) -->
                    <a
                        href="<?= base_url('quiz') ?>"
                        class="hidden sm:inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white border border-slate-200/90 shadow-md text-xs font-bold text-upskill-darkblue hover:border-upskill-pink transition-colors absolute top-3 right-0 z-20 animate-float-slow"
                    >
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Uji Coba Quiz &middot; <?= $trialCount ?> Soal</span>
                    </a>

                    <!-- Ilustrasi 3D Isometrik (Seamless pada latar #E8EBF2) -->
                    <picture>
                        <source srcset="<?= base_url('assets/images/hero-upskill-3d.webp') ?>" type="image/webp">
                        <img
                            src="<?= base_url('assets/images/hero-upskill-3d.jpg') ?>"
                            alt="Ilustrasi 3D ruang belajar digital, materi buku, dan evaluasi quiz Muslim Upskill Academy"
                            width="960"
                            height="960"
                            class="w-full h-auto object-contain select-none"
                            fetchpriority="high"
                        >
                    </picture>

                    <!-- Floating Badge Bawah Kiri -->
                    <div class="hidden sm:flex items-center gap-2.5 px-3.5 py-2 rounded-xl bg-white border border-slate-200/90 shadow-md text-xs text-slate-700 absolute bottom-4 left-0 z-20 animate-float-delayed">
                        <span class="w-7 h-7 rounded-lg bg-pink-50 text-upskill-pink flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                        <div>
                            <p class="font-bold text-upskill-darkblue leading-tight">Evaluasi &amp; Pembahasan</p>
                            <p class="text-[11px] text-slate-500">Skor instan tanpa login</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ====================================================================== -->
<!-- 1.5. STRIP INDIKATOR KREDIBILITAS (KURIKULUM, PENGAJAR, FORMAT)       -->
<!-- ====================================================================== -->
<section class="py-7 sm:py-8 bg-white border-b border-slate-200/80">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 sm:gap-0 sm:divide-x sm:divide-slate-200 text-center">
            <div class="sm:px-6">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Kurikulum</p>
                <p class="text-sm sm:text-base font-bold text-upskill-darkblue">Berbasis Kebutuhan Industri</p>
            </div>
            <div class="sm:px-6">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Pengajar</p>
                <p class="text-sm sm:text-base font-bold text-upskill-darkblue">Mentor Praktisi Aktif</p>
            </div>
            <div class="sm:px-6">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Format Belajar</p>
                <p class="text-sm sm:text-base font-bold text-upskill-darkblue">Fleksibel &amp; Terstruktur</p>
            </div>
        </div>
    </div>
</section>

<!-- ====================================================================== -->
<!-- 2. SHOWCASE FITUR ZIG-ZAG BERANIMASI (GARIS SIKU + CROSSHAIR GRID)     -->
<!-- ====================================================================== -->
<section class="bg-white py-10 sm:py-14 overflow-x-clip">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="border border-slate-200/90 divide-y divide-slate-200/90">

            <!-- Band 1: Teks Kiri + Animasi Runner Trial Quiz Kanan -->
            <div class="relative grid grid-cols-1 lg:grid-cols-12 items-stretch">
                <!-- Crosshair sudut pertemuan garis siku -->
                <span class="grid-crosshair -top-[8px] -left-[8px]" aria-hidden="true"></span>
                <span class="grid-crosshair -top-[8px] -right-[8px]" aria-hidden="true"></span>
                <span class="grid-crosshair hidden lg:block -top-[8px] left-[41.6667%] -translate-x-1/2" aria-hidden="true"></span>

                <div class="lg:col-span-5 p-6 sm:p-10 lg:p-12 flex flex-col justify-center space-y-4 showcase-tint-pink lg:border-r lg:border-slate-200/90">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-upskill-darkblue tracking-tight leading-snug">
                        Langsung kerjakan quiz, <span class="font-medium text-slate-600">tanpa repot bikin akun dulu</span>
                    </h2>
                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed">
                        Cukup klik menu Quiz dan langsung jawab <?= $trialCount ?> pertanyaan ringkas dari ponsel maupun laptop. Nyaman, cepat, dan langsung keluar hasilnya tanpa mengisi formulir panjang di awal.
                    </p>
                    <div class="pt-1">
                        <a href="<?= base_url('quiz') ?>" class="inline-flex items-center gap-2 text-sm font-bold text-upskill-pink hover:text-upskill-magenta transition-colors">
                            <span>Mulai Trial Quiz Sekarang</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Mockup UI Beranimasi Tanpa BG Abu-abu Luar -->
                <div class="lg:col-span-7 p-6 sm:p-10 lg:p-12 flex items-center border-t lg:border-t-0 border-slate-200/90">
                    <div class="w-full bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                        <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between text-xs">
                            <span class="font-bold text-upskill-darkblue">Trial Quiz &middot; <?= esc($trialTopic) ?></span>
                            <span id="demo-quiz-step" class="font-mono font-bold text-slate-600 tabular-nums">2 / 5</span>
                        </div>
                        <div class="h-1 bg-slate-100">
                            <div id="demo-quiz-bar" class="h-full bg-upskill-pink transition-all duration-500" style="width: 40%;"></div>
                        </div>
                        <div class="p-5 space-y-3">
                            <p id="demo-quiz-question" class="text-sm font-semibold text-slate-900 leading-relaxed">
                                Manakah dua rukun utama yang terkandung dalam kalimat syahadat Laa ilaaha illallah?
                            </p>
                            <div class="space-y-2" id="demo-quiz-opts">
                                <div class="demo-opt flex items-center gap-3 px-3.5 py-2.5 rounded-lg border border-slate-200 text-xs text-slate-700">
                                    <span class="demo-opt-badge w-5 h-5 rounded border border-slate-300 bg-slate-50 font-bold flex items-center justify-center text-[10px]">A</span>
                                    <span>Niat di hati dan ucapan lisan semata</span>
                                </div>
                                <div class="demo-opt is-selected flex items-center gap-3 px-3.5 py-2.5 rounded-lg border border-slate-200 text-xs text-slate-800 font-medium">
                                    <span class="demo-opt-badge w-5 h-5 rounded border border-slate-300 bg-slate-50 font-bold flex items-center justify-center text-[10px]">B</span>
                                    <span>An-Nafyu (menafikan sesembahan batil) dan Al-Itsbat (menetapkan ibadah bagi Allah)</span>
                                </div>
                                <div class="demo-opt flex items-center gap-3 px-3.5 py-2.5 rounded-lg border border-slate-200 text-xs text-slate-700">
                                    <span class="demo-opt-badge w-5 h-5 rounded border border-slate-300 bg-slate-50 font-bold flex items-center justify-center text-[10px]">C</span>
                                    <span>Rasa takut dan rasa harap dalam doa</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Band 2: Pembahasan & Dalil Kiri + Teks Kanan -->
            <div class="relative grid grid-cols-1 lg:grid-cols-12 items-stretch">
                <span class="grid-crosshair -top-[8px] -left-[8px]" aria-hidden="true"></span>
                <span class="grid-crosshair -top-[8px] -right-[8px]" aria-hidden="true"></span>
                <span class="grid-crosshair hidden lg:block -top-[8px] left-[41.6667%] -translate-x-1/2" aria-hidden="true"></span>
                <span class="grid-crosshair hidden lg:block -top-[8px] left-[58.3333%] -translate-x-1/2" aria-hidden="true"></span>

                <!-- Mockup UI Hasil & Pembahasan Tanpa BG Abu-abu Luar -->
                <div class="lg:col-span-7 order-2 lg:order-1 p-6 sm:p-10 lg:p-12 flex items-center border-t lg:border-t-0 lg:border-r border-slate-200/90">
                    <div class="w-full bg-white border border-slate-200 rounded-xl p-5 space-y-4 shadow-sm">
                        <div class="flex flex-wrap items-center justify-between gap-3 pb-3.5 border-b border-slate-100">
                            <div>
                                <p class="text-xs font-semibold text-slate-500">Hasil Evaluasi Anda</p>
                                <p class="text-2xl font-extrabold text-upskill-darkblue font-mono tabular-nums">
                                    80 <span class="text-xs font-sans font-semibold text-slate-500">/ 100 (4 dari 5 Benar)</span>
                                </p>
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                Pemahaman Dasar Baik
                            </span>
                        </div>

                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-bold text-slate-700 font-mono">Soal #1</span>
                                <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 font-semibold text-[11px]">Benar</span>
                            </div>
                            <p class="text-xs font-bold text-upskill-darkblue">Pembahasan &amp; Dalil</p>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Tauhid Uluhiyyah adalah mengesakan Allah dalam seluruh bentuk ibadah, sebagaimana ditegaskan dalam QS. Al-Fatihah ayat 5: &ldquo;Hanya kepada Engkaulah kami menyembah...&rdquo;
                            </p>
                        </div>

                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-3 text-xs">
                            <div class="flex items-center gap-2 text-slate-600">
                                <svg id="demo-lock-icon" class="w-4 h-4 text-upskill-pink shrink-0 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <span id="demo-lock-text">Daftar gratis untuk membuka pembahasan soal #2&ndash;5 &amp; menyimpan skor</span>
                            </div>
                            <span class="px-2.5 py-1 rounded-md bg-pink-50 text-upskill-pink font-bold text-[11px] shrink-0">
                                Gratis
                            </span>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-5 order-1 lg:order-2 p-6 sm:p-10 lg:p-12 flex flex-col justify-center space-y-4 showcase-tint-blue">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-upskill-darkblue tracking-tight leading-snug">
                        Bukan sekadar angka, <span class="font-medium text-slate-600">tapi lengkap dengan pembahasan dalil</span>
                    </h2>
                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed">
                        Setiap jawaban yang kurang tepat langsung bisa Anda pelajari alasannya beserta rujukan dalilnya. Skor trial otomatis tersimpan ke akun begitu Anda mendaftar, sekaligus membuka quiz topik lainnya.
                    </p>
                </div>
            </div>

            <!-- Band 3: Teks Kiri + Ruang Belajar & Leaderboard Kanan -->
            <div class="relative grid grid-cols-1 lg:grid-cols-12 items-stretch">
                <span class="grid-crosshair -top-[8px] -left-[8px]" aria-hidden="true"></span>
                <span class="grid-crosshair -top-[8px] -right-[8px]" aria-hidden="true"></span>
                <span class="grid-crosshair hidden lg:block -top-[8px] left-[41.6667%] -translate-x-1/2" aria-hidden="true"></span>
                <span class="grid-crosshair hidden lg:block -top-[8px] left-[58.3333%] -translate-x-1/2" aria-hidden="true"></span>
                <span class="grid-crosshair -bottom-[8px] -left-[8px]" aria-hidden="true"></span>
                <span class="grid-crosshair -bottom-[8px] -right-[8px]" aria-hidden="true"></span>
                <span class="grid-crosshair hidden lg:block -bottom-[8px] left-[41.6667%] -translate-x-1/2" aria-hidden="true"></span>

                <div class="lg:col-span-5 p-6 sm:p-10 lg:p-12 flex flex-col justify-center space-y-4 showcase-tint-pink lg:border-r lg:border-slate-200/90">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-upskill-darkblue tracking-tight leading-snug">
                        Materi runut &amp; evaluasi CBT <span class="font-medium text-slate-600">dalam satu ruang belajar</span>
                    </h2>
                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed">
                        Setelah mencoba quiz, lanjutkan belajar secara sistematis lewat video pembelajaran, dokumen PDF, ujian evaluasi CBT dengan timer, serta pantau posisi Anda di papan peringkat sesi.
                    </p>
                </div>

                <!-- Mockup UI Ruang Belajar & Peringkat Tanpa BG Abu-abu Luar -->
                <div class="lg:col-span-7 p-6 sm:p-10 lg:p-12 flex items-center border-t lg:border-t-0 border-slate-200/90">
                    <div class="w-full grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="sm:col-span-7 bg-white border border-slate-200 rounded-xl p-4 space-y-3 shadow-sm">
                            <div class="flex items-center justify-between text-xs pb-2 border-b border-slate-100">
                                <span class="font-bold text-upskill-darkblue">Kurikulum Sesi</span>
                                <span class="font-mono font-bold text-emerald-700 tabular-nums">100%</span>
                            </div>
                            <div class="space-y-2 text-xs">
                                <div class="flex items-center justify-between py-1.5">
                                    <span class="font-medium text-slate-800">Sesi 1 &middot; Pengantar Materi</span>
                                    <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-800 font-semibold text-[10px]">Tuntas</span>
                                </div>
                                <div class="flex items-center justify-between py-1.5 border-t border-slate-100">
                                    <span class="font-medium text-slate-800">Sesi 2 &middot; Pendalaman Konsep</span>
                                    <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-800 font-semibold text-[10px]">Tuntas</span>
                                </div>
                                <div class="flex items-center justify-between py-1.5 border-t border-slate-100">
                                    <span class="font-semibold text-upskill-darkblue">Evaluasi CBT Sesi</span>
                                    <span class="px-2 py-0.5 rounded bg-pink-50 text-upskill-pink font-bold text-[10px]">Terbuka</span>
                                </div>
                            </div>
                        </div>

                        <div class="sm:col-span-5 bg-white border border-slate-200 rounded-xl p-4 space-y-2.5 shadow-sm">
                            <p class="text-xs font-bold text-upskill-darkblue pb-2 border-b border-slate-100">Papan Peringkat</p>
                            <div class="space-y-2 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="font-semibold text-slate-800"><span class="font-mono text-upskill-pink mr-1">#1</span> Ahmad F.</span>
                                    <span class="font-mono font-bold text-upskill-darkblue tabular-nums">100</span>
                                </div>
                                <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                                    <span class="font-semibold text-slate-800"><span class="font-mono text-slate-500 mr-1">#2</span> Siti R.</span>
                                    <span class="font-mono font-bold text-slate-800 tabular-nums">95</span>
                                </div>
                                <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                                    <span class="font-semibold text-slate-800"><span class="font-mono text-slate-500 mr-1">#3</span> Budi S.</span>
                                    <span class="font-mono font-bold text-slate-800 tabular-nums">90</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ====================================================================== -->
<!-- 3. SASARAN PROGRAM / TARGET PESERTA (PITA BIRU TUA EDITORIAL)          -->
<!-- ====================================================================== -->
<section id="target-peserta" class="bg-upskill-darkblue text-white py-16 sm:py-20 scroll-mt-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        <div class="max-w-2xl">
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                Dirancang untuk Berbagai Jenjang Kebutuhan
            </h2>
            <p class="text-sm sm:text-base text-blue-100 mt-2 leading-relaxed">
                Materi disusun sistematis agar mudah dipahami dari tingkat pemula hingga profesional.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-0 lg:divide-x lg:divide-white/15">
            <!-- 1. Mahasiswa -->
            <div class="lg:pr-7 space-y-3">
                <div class="w-9 h-9 rounded-lg bg-white/10 border border-white/15 flex items-center justify-center text-pink-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 14l9-5-9-5-9 5 9 5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-white">Mahasiswa</h3>
                <p class="text-xs sm:text-sm text-blue-100 leading-relaxed">
                    Mempersiapkan portofolio nyata dan keterampilan siap kerja sebelum menyelesaikan studi formal.
                </p>
            </div>

            <!-- 2. Pekerja Profesional -->
            <div class="lg:px-7 space-y-3">
                <div class="w-9 h-9 rounded-lg bg-white/10 border border-white/15 flex items-center justify-center text-pink-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-white">Pekerja Profesional</h3>
                <p class="text-xs sm:text-sm text-blue-100 leading-relaxed">
                    Tingkatkan jenjang karier dan produktivitas harian dengan penguasaan tools modern dan otomasi kerja.
                </p>
            </div>

            <!-- 3. Freelancer -->
            <div class="lg:px-7 space-y-3">
                <div class="w-9 h-9 rounded-lg bg-white/10 border border-white/15 flex items-center justify-center text-pink-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-white">Freelancer</h3>
                <p class="text-xs sm:text-sm text-blue-100 leading-relaxed">
                    Tingkatkan daya tawar jasa, kemampuan manajemen proyek, serta pelayanan klien profesional.
                </p>
            </div>

            <!-- 4. Pelaku UMKM -->
            <div class="lg:pl-7 space-y-3">
                <div class="w-9 h-9 rounded-lg bg-white/10 border border-white/15 flex items-center justify-center text-pink-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-white">Pelaku UMKM</h3>
                <p class="text-xs sm:text-sm text-blue-100 leading-relaxed">
                    Digitalisasi operasional usaha, optimalisasi strategi pemasaran, dan pengelolaan keuangan usaha mandiri.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ====================================================================== -->
<!-- 4. ALUR BELAJAR VERTIKAL (TIMELINE ALTERNATING ALA "ALUR SIDANG")      -->
<!-- ====================================================================== -->
<section id="alur-belajar" class="py-20 sm:py-24 bg-white border-b border-slate-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-xl mx-auto mb-14">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-upskill-darkblue tracking-tight">
                Alur Belajar di Upskill
            </h2>
            <p class="text-sm text-slate-600 mt-2 leading-relaxed">
                Lima langkah ringkas dari mencoba quiz pertama hingga mengikuti pembelajaran terstruktur.
            </p>
        </div>

        <?php
            $steps = [
                [
                    'num'   => 1,
                    'title' => 'Coba Trial Quiz Gratis',
                    'desc'  => 'Buka menu Quiz dan langsung kerjakan ' . $trialCount . ' soal tanpa perlu login atau mendaftar.',
                    'highlight' => true,
                ],
                [
                    'num'   => 2,
                    'title' => 'Lihat Skor & Pratinjau Pembahasan',
                    'desc'  => 'Dapatkan skor instan, status benar/salah tiap nomor, serta pembahasan dalil soal pertama gratis.',
                    'highlight' => false,
                ],
                [
                    'num'   => 3,
                    'title' => 'Daftar Akun',
                    'desc'  => 'Skor trial otomatis tersimpan ke akun Anda dan seluruh pembahasan dalil langsung terbuka penuh.',
                    'highlight' => false,
                ],
                [
                    'num'   => 4,
                    'title' => 'Ikuti Quiz Topik Lainnya',
                    'desc'  => 'Tantang pemahaman Anda pada beragam topik lanjutan yang disiapkan tim pengajar.',
                    'highlight' => false,
                ],
                [
                    'num'   => 5,
                    'title' => 'Gabung Kelas Terstruktur',
                    'desc'  => 'Pelajari materi video/PDF secara runut, selesaikan evaluasi CBT, dan pantau progres belajar.',
                    'highlight' => false,
                ],
            ];
        ?>

        <div id="upskill-timeline" class="relative">
            <!-- Garis Tengah Vertikal Dasar (Desktop) / Kiri (Mobile) -->
            <div class="absolute left-4 md:left-1/2 top-4 bottom-4 w-0.5 bg-slate-200 md:-translate-x-1/2 rounded-full overflow-hidden">
                <!-- Garis Progres Aktif Mengikuti Scroll -->
                <div id="timeline-progress-line" class="w-full bg-upskill-pink transition-all duration-300 ease-out rounded-full" style="height: 0%;"></div>
            </div>

            <div class="space-y-6 sm:space-y-8">
                <?php foreach ($steps as $i => $st): ?>
                    <?php $isLeft = ($i % 2 === 0); ?>
                    <div class="timeline-item <?= $i === 0 ? 'is-active' : '' ?> relative flex flex-col md:flex-row items-start md:items-center" data-step-index="<?= $i ?>">
                        <!-- Kolom Kiri (Desktop) -->
                        <div class="w-full md:w-1/2 pl-12 md:pl-0 <?= $isLeft ? 'md:pr-10 md:text-right' : 'md:pr-10 hidden md:block' ?>">
                            <?php if ($isLeft): ?>
                                <div class="timeline-card block w-full md:inline-block md:w-auto text-left md:text-right p-4 rounded-xl hover:bg-slate-50/80">
                                    <h3 class="text-sm sm:text-base font-bold text-upskill-darkblue"><?= esc($st['title']) ?></h3>
                                    <p class="text-xs sm:text-sm text-slate-600 mt-1 leading-relaxed"><?= esc($st['desc']) ?></p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Titik Nomor Tengah -->
                        <div class="timeline-node absolute left-0 top-3.5 md:top-auto md:left-1/2 md:-translate-x-1/2 w-8 h-8 rounded-full flex items-center justify-center font-mono text-xs font-bold tabular-nums z-10 bg-white border-2 border-slate-300 text-upskill-darkblue">
                            <?= $st['num'] ?>
                        </div>

                        <!-- Kolom Kanan (Desktop) + Fallback Mobile untuk Step Kanan -->
                        <div class="w-full md:w-1/2 pl-12 md:pl-10 <?= $isLeft ? 'hidden md:block' : '' ?>">
                            <?php if (! $isLeft): ?>
                                <div class="timeline-card block w-full md:inline-block md:w-auto text-left p-4 rounded-xl hover:bg-slate-50/80">
                                    <h3 class="text-sm sm:text-base font-bold text-upskill-darkblue"><?= esc($st['title']) ?></h3>
                                    <p class="text-xs sm:text-sm text-slate-600 mt-1 leading-relaxed"><?= esc($st['desc']) ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- ====================================================================== -->
<!-- 5. PROGRAM KELAS PILIHAN (SLIDER INTERAKTIF GAYA JENDELA macOS)        -->
<!-- ====================================================================== -->
<section id="program-kelas" class="py-20 sm:py-24 bg-white overflow-hidden">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header Section -->
        <div class="max-w-2xl mx-auto text-center mb-14">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-upskill-darkblue tracking-tight">
                Program Kelas Pilihan
            </h2>
            <p class="text-sm text-slate-600 mt-2.5 leading-relaxed">
                Pilih bidang yang ingin Anda dalami dan ikuti pembelajaran terstruktur bersama para pengajar.
            </p>
        </div>

        <?php if (!empty($courses)): ?>
            <!-- Swiper Slider Container -->
            <div class="swiper course-swiper !py-4 !px-2 sm:!px-4">
                <div class="swiper-wrapper items-stretch">
                    <?php foreach ($courses as $c): ?>
                        <div class="swiper-slide h-auto flex">
                            <div class="relative w-full rounded-xl p-2 md:p-2.5 overflow-hidden flex flex-col">
                                <div
                                    class="absolute inset-0 bg-cover bg-center opacity-30 blur-2xl scale-110"
                                    style="background-image: url('<?= base_url('uploads/courses/' . (!empty($c['banner_image']) ? $c['banner_image'] : 'course-1.jpg')) ?>');"
                                ></div>
                                <div class="absolute inset-0 bg-white/35 rounded-xl"></div>

                                <div class="relative z-10 bg-white rounded-lg shadow-md border border-slate-200/70 p-4 md:p-6 flex flex-col justify-between h-full">
                                    <div>
                                        <div class="flex items-center justify-start mb-4">
                                            <div class="flex gap-1.5">
                                                <div class="w-2.5 h-2.5 rounded-full bg-red-500 shadow-2xs"></div>
                                                <div class="w-2.5 h-2.5 rounded-full bg-yellow-400 shadow-2xs"></div>
                                                <div class="w-2.5 h-2.5 rounded-full bg-green-500 shadow-2xs"></div>
                                            </div>
                                        </div>

                                        <div class="w-full aspect-[16/10] sm:aspect-video rounded-md overflow-hidden bg-slate-50 border border-slate-100 mb-5 flex items-center justify-center p-1 shadow-inner">
                                            <img
                                                src="<?= base_url('uploads/courses/' . (!empty($c['banner_image']) ? $c['banner_image'] : 'course-1.jpg')) ?>"
                                                alt="<?= esc($c['title']) ?>"
                                                class="w-full h-full object-contain rounded transition-transform duration-300"
                                                loading="lazy"
                                            >
                                        </div>

                                        <div class="text-center">
                                            <h3 class="text-upskill-darkblue font-bold text-2xl mb-2 line-clamp-2">
                                                <?= esc($c['title']) ?>
                                            </h3>
                                            <p class="text-slate-600 mb-6 line-clamp-2 leading-relaxed text-sm">
                                                <?= esc($c['description'] ?? 'Pelajari modul komprehensif bersama pengajar dan mentor praktisi.') ?>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="text-center pt-2">
                                        <a
                                            href="<?= base_url('courses/' . $c['slug']) ?>"
                                            class="inline-flex items-center justify-center gap-2 border border-slate-300 text-upskill-darkblue font-semibold px-8 py-2.5 rounded-full hover:bg-slate-50 transition-colors group"
                                        >
                                            <span>Pelajari</span>
                                            <svg class="w-4 h-4 text-upskill-pink group-hover:translate-x-0.5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Kontrol Navigasi & Paginasi -->
            <div class="flex items-center justify-center gap-5 mt-10">
                <button
                    type="button"
                    class="course-swiper-button-prev w-11 h-11 rounded-full bg-white border border-slate-200 text-slate-700 hover:text-upskill-pink hover:border-upskill-pink shadow-sm flex items-center justify-center transition-all duration-200 focus:outline-none cursor-pointer"
                    aria-label="Kelas Sebelumnya"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>

                <div class="course-swiper-pagination !static !w-auto flex items-center"></div>

                <button
                    type="button"
                    class="course-swiper-button-next w-11 h-11 rounded-full bg-white border border-slate-200 text-slate-700 hover:text-upskill-pink hover:border-upskill-pink shadow-sm flex items-center justify-center transition-all duration-200 focus:outline-none cursor-pointer"
                    aria-label="Kelas Berikutnya"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        <?php else: ?>
            <div class="text-center py-12 bg-slate-50 rounded-2xl border border-slate-200/80">
                <p class="text-slate-700 font-semibold text-sm">Program kelas sedang dipersiapkan untuk publikasi.</p>
                <p class="text-slate-500 text-xs mt-1">Sambil menunggu, Anda dapat langsung mencoba Trial Quiz gratis kami.</p>
            </div>
        <?php endif; ?>

    </div>
</section>

<!-- ====================================================================== -->
<!-- 6. CTA BANNER BESAR DENGAN FLOATING CHECKLIST WIDGET (ALA SIMBA)       -->
<!-- ====================================================================== -->
<section class="pb-16 sm:pb-20 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-upskill-darkblue rounded-3xl p-6 sm:p-12 lg:p-14 text-white shadow-lg">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-center">

                <!-- Kiri: Ajakan Mulai Trial Quiz -->
                <div class="lg:col-span-7 space-y-5">
                    <h2 class="text-2xl sm:text-4xl font-extrabold tracking-tight leading-tight">
                        Mulai dari <?= $trialCount ?> soal <span class="text-pink-300">Trial Quiz</span> sekarang
                    </h2>
                    <p class="text-sm sm:text-base text-blue-100 leading-relaxed max-w-xl">
                        Ukur pemahaman dasar Anda dalam hitungan menit tanpa login, lihat pembahasan dalilnya, dan temukan topik yang tepat untuk Anda pelajari berikutnya.
                    </p>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 pt-1">
                        <a
                            href="<?= base_url('quiz') ?>"
                            class="inline-flex items-center justify-center gap-2 px-7 py-3.5 text-sm font-bold rounded-xl bg-upskill-pink hover:bg-upskill-magenta text-white transition-colors shadow-sm"
                        >
                            <span>Kerjakan Trial Quiz Gratis</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                        <a
                            href="<?= base_url('register') ?>"
                            class="inline-flex items-center justify-center px-6 py-3.5 text-sm font-bold rounded-xl bg-white text-upskill-darkblue hover:bg-slate-100 transition-colors"
                        >
                            Daftar Akun Gratis
                        </a>
                    </div>
                </div>

                <!-- Kanan: Floating Checklist Card -->
                <div class="lg:col-span-5">
                    <div class="bg-white text-slate-900 rounded-2xl p-5 sm:p-6 shadow-xl space-y-3.5">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <span class="text-xs font-extrabold text-upskill-darkblue">Ringkasan Akses Trial</span>
                            <span class="text-[11px] font-bold text-upskill-pink bg-pink-50 px-2 py-0.5 rounded-md">Gratis</span>
                        </div>

                        <div class="space-y-2.5 text-xs sm:text-sm">
                            <div class="flex items-center gap-3">
                                <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <span class="font-medium text-slate-800"><?= $trialCount ?> soal pilihan ganda tanpa login</span>
                            </div>

                            <div class="flex items-center gap-3">
                                <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <span class="font-medium text-slate-800">Skor instan &amp; pratinjau pembahasan dalil</span>
                            </div>

                            <div class="flex items-center gap-3 p-2.5 -mx-2.5 rounded-xl bg-pink-50/70 border border-pink-100">
                                <span class="w-5 h-5 rounded-full bg-upskill-pink text-white flex items-center justify-center shrink-0">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </span>
                                <span class="font-bold text-upskill-darkblue">Skor tersimpan otomatis saat daftar</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- Swiper.js JavaScript via CDN -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Inisialisasi Swiper Slider Program Kelas
        const swiperElement = document.querySelector('.course-swiper');
        if (swiperElement) {
            new Swiper('.course-swiper', {
                slidesPerView: 1.2,
                centeredSlides: true,
                spaceBetween: 20,
                loop: true,
                grabCursor: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                },
                pagination: {
                    el: '.course-swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.course-swiper-button-next',
                    prevEl: '.course-swiper-button-prev',
                },
                breakpoints: {
                    640: { slidesPerView: 1.4, spaceBetween: 24, centeredSlides: true },
                    1024: { slidesPerView: 1.8, spaceBetween: 32, centeredSlides: true },
                    1280: { slidesPerView: 2.3, spaceBetween: 36, centeredSlides: true }
                }
            });
        }

        // 2. Animasi Halus Showcase Band 1 (Simulasi Progres Pilihan Soal)
        const demoOpts = document.querySelectorAll('#demo-quiz-opts .demo-opt');
        const demoBar  = document.getElementById('demo-quiz-bar');
        const demoStep = document.getElementById('demo-quiz-step');
        const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (!prefersReduced && demoOpts.length > 0) {
            let demoIdx = 1;
            const stepsMeta = [
                { step: '1 / 5', pct: '20%' },
                { step: '2 / 5', pct: '40%' },
                { step: '3 / 5', pct: '60%' },
            ];
            setInterval(() => {
                demoIdx = (demoIdx + 1) % demoOpts.length;
                demoOpts.forEach((el, idx) => {
                    el.classList.toggle('is-selected', idx === demoIdx);
                });
                if (demoBar) demoBar.style.width = stepsMeta[demoIdx].pct;
                if (demoStep) demoStep.textContent = stepsMeta[demoIdx].step;
            }, 2600);
        }

        // 3. Scroll-Follow Highlight pada Timeline "Alur Belajar di Upskill"
        const timelineItems = document.querySelectorAll('#upskill-timeline .timeline-item');
        const timelineLine  = document.getElementById('timeline-progress-line');

        if (timelineItems.length > 0) {
            let ticking = false;

            const updateTimelineActive = () => {
                ticking = false;
                const focusY = window.innerHeight * 0.48;
                let activeIdx = 0;
                let minDistance = Infinity;

                timelineItems.forEach((item, idx) => {
                    const rect = item.getBoundingClientRect();
                    const itemCenter = rect.top + (rect.height / 2);
                    const distance = Math.abs(itemCenter - focusY);

                    if (distance < minDistance) {
                        minDistance = distance;
                        activeIdx = idx;
                    }
                });

                timelineItems.forEach((item, idx) => {
                    item.classList.toggle('is-active', idx === activeIdx);
                    item.classList.toggle('is-passed', idx < activeIdx);
                });

                if (timelineLine) {
                    const pct = timelineItems.length > 1
                        ? Math.round((activeIdx / (timelineItems.length - 1)) * 100)
                        : 100;
                    timelineLine.style.height = pct + '%';
                }
            };

            const onScroll = () => {
                if (!ticking) {
                    window.requestAnimationFrame(updateTimelineActive);
                    ticking = true;
                }
            };

            window.addEventListener('scroll', onScroll, { passive: true });
            window.addEventListener('resize', onScroll, { passive: true });
            updateTimelineActive();
        }
    });
</script>

<?= $this->endSection() ?>
