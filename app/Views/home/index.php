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
</style>

<!-- 1. Hero Section -->
<section class="pt-16 pb-20 sm:pt-24 sm:pb-28 border-b border-slate-100 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <!-- Label Kategori / Badge Sederhana -->
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-pink-50 border border-pink-100 mb-6">
            <span class="w-1.5 h-1.5 rounded-full bg-upskill-pink"></span>
            <span class="text-xs font-semibold text-upskill-pink tracking-wide">MUSLIM UPSKILL ACADEMY</span>
        </div>

        <!-- Judul Utama (text-upskill-darkblue dengan highlight text-upskill-pink) -->
        <h1 class="text-3xl sm:text-5xl font-extrabold text-upskill-darkblue tracking-tight leading-tight sm:leading-tight mb-6">
            Tingkatkan Kompetensi,<br class="hidden sm:block">
            <span class="text-upskill-pink">Berikan Manfaat bagi Umat</span>
        </h1>

        <!-- Sub-Judul Bersih & Terbaca -->
        <p class="text-base sm:text-lg text-slate-600 max-w-2xl mx-auto leading-relaxed mb-10 font-normal">
            Pelajari keterampilan digital, bisnis, dan profesional relevan bersama mentor praktisi. Dirancang terstruktur untuk akselerasi karier dengan landasan etika dan keberkahan.
        </p>

        <!-- Call to Action (CTA) Buttons -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3.5">
            <a href="#program-kelas" class="w-full sm:w-auto inline-flex items-center justify-center px-7 py-3.5 text-sm font-semibold rounded-lg text-white bg-upskill-pink hover:bg-upskill-magenta transition-colors shadow-sm">
                Lihat Program Kelas
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </a>
            <a href="#target-peserta" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3.5 text-sm font-semibold rounded-lg text-upskill-darkblue bg-slate-50 hover:bg-slate-100 border border-slate-200 transition-colors">
                Pelajari Target Peserta
            </a>
        </div>
    </div>
</section>

<!-- 2. Section Indikator Kredibilitas / Statistik Sederhana -->
<section class="py-10 bg-slate-50/60 border-b border-slate-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 sm:gap-8 text-center">
            <div class="p-2">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1">Kurikulum</p>
                <p class="text-sm font-semibold text-upskill-darkblue">Berbasis Kebutuhan Industri</p>
            </div>
            <div class="p-2">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1">Pengajar</p>
                <p class="text-sm font-semibold text-upskill-darkblue">Mentor Praktisi Aktif</p>
            </div>
            <div class="p-2">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1">Penyaluran</p>
                <p class="text-sm font-semibold text-upskill-darkblue">Disalurkan ke Mitra Industri</p>
            </div>
            <div class="p-2">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1">Format Belajar</p>
                <p class="text-sm font-semibold text-upskill-darkblue">Fleksibel & Terstruktur</p>
            </div>
        </div>
    </div>
</section>

<!-- 3. Section Program Kelas (Course Slider Interaktif - Gaya Jendela macOS) -->
<section id="program-kelas" class="py-20 sm:py-24 bg-white overflow-hidden">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="max-w-2xl mx-auto text-center mb-14">
            <h2 class="text-xs font-bold uppercase tracking-widest text-upskill-pink mb-2">Katalog Unggulan</h2>
            <h3 class="text-2xl sm:text-3xl font-extrabold text-upskill-darkblue tracking-tight">
                Program Kelas Pilihan
            </h3>
            <p class="text-sm text-slate-500 mt-2.5 leading-relaxed">
                Pilih bidang yang ingin Anda kembangkan dan pelajari keterampilan praktis bersama para ahli.
            </p>
        </div>

        <?php if (!empty($courses)): ?>
            <!-- Swiper Slider Container -->
            <div class="swiper course-swiper !py-4 !px-2 sm:!px-4">
                <div class="swiper-wrapper items-stretch">
                    <?php foreach ($courses as $c): ?>
                        <div class="swiper-slide h-auto flex">
                            <!-- Kontainer Utama (Outer Wrapper: Sudut Membulat & Overflow Hidden) -->
                            <div class="relative w-full rounded-3xl p-4 md:p-6 overflow-hidden flex flex-col">
                                
                                <!-- Layer Ambient Glow (Background Blur Tajam) -->
                                <div 
                                    class="absolute inset-0 bg-cover bg-center opacity-50 blur-3xl scale-110" 
                                    style="background-image: url('<?= base_url('uploads/courses/' . (!empty($c['banner_image']) ? $c['banner_image'] : 'course-1.jpg')) ?>');"
                                ></div>

                                <!-- Overlay Gelap Tipis (Agar tidak terlalu terang) -->
                                <div class="absolute inset-0 bg-black/20 rounded-3xl"></div>

                                <!-- Kartu Utama (Inner Card - Jendela macOS di Atas Layer Blur) -->
                                <div class="relative z-10 bg-white rounded-2xl shadow-xl p-5 md:p-8 flex flex-col justify-between h-full">
                                    <div>
                                        <!-- Header Kartu: 3 Titik Jendela macOS -->
                                        <div class="flex items-center justify-start mb-5">
                                            <!-- 3 Titik Jendela macOS -->
                                            <div class="flex gap-2">
                                                <div class="w-3 h-3 rounded-full bg-red-500 shadow-sm"></div>
                                                <div class="w-3 h-3 rounded-full bg-yellow-400 shadow-sm"></div>
                                                <div class="w-3 h-3 rounded-full bg-green-500 shadow-sm"></div>
                                            </div>
                                        </div>

                                        <!-- Gambar Promosi Materi (Tampil Penuh & Proporsional Tanpa Terpotong) -->
                                        <div class="w-full aspect-[16/10] sm:aspect-video rounded-xl overflow-hidden bg-slate-50 border border-slate-100 mb-6 flex items-center justify-center p-1 sm:p-2 shadow-inner">
                                            <img 
                                                src="<?= base_url('uploads/courses/' . (!empty($c['banner_image']) ? $c['banner_image'] : 'course-1.jpg')) ?>" 
                                                alt="<?= esc($c['title']) ?>" 
                                                class="w-full h-full object-contain rounded-lg transition-transform duration-300"
                                                loading="lazy"
                                            >
                                        </div>

                                        <!-- Konten Teks -->
                                        <div class="text-center">
                                            <h3 class="text-upskill-darkblue font-bold text-2xl mb-2 line-clamp-2">
                                                <?= esc($c['title']) ?>
                                            </h3>
                                            <p class="text-gray-600 mb-6 line-clamp-2 leading-relaxed text-sm">
                                                <?= esc($c['description'] ?? 'Pelajari modul komprehensif bersama mentor praktisi berpengalaman.') ?>
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Tombol Outline Pelajari -->
                                    <div class="text-center pt-2">
                                        <a 
                                            href="<?= base_url('courses/' . $c['slug']) ?>" 
                                            class="inline-flex items-center justify-center gap-2 border border-gray-300 text-upskill-darkblue font-semibold px-8 py-2.5 rounded-full hover:bg-gray-50 transition-colors group"
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

            <!-- Kontrol Navigasi & Paginasi di Bawah Slider (Gaya Bulat Putih/Abu-Abu) -->
            <div class="flex items-center justify-center gap-5 mt-10">
                <!-- Tombol Prev -->
                <button 
                    type="button" 
                    class="course-swiper-button-prev w-11 h-11 rounded-full bg-white border border-slate-200 text-slate-700 hover:text-upskill-pink hover:border-upskill-pink shadow-sm flex items-center justify-center transition-all duration-200 focus:outline-none"
                    aria-label="Kelas Sebelumnya"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>

                <!-- Indikator Titik Paginasi -->
                <div class="course-swiper-pagination !static !w-auto flex items-center"></div>

                <!-- Tombol Next -->
                <button 
                    type="button" 
                    class="course-swiper-button-next w-11 h-11 rounded-full bg-white border border-slate-200 text-slate-700 hover:text-upskill-pink hover:border-upskill-pink shadow-sm flex items-center justify-center transition-all duration-200 focus:outline-none"
                    aria-label="Kelas Berikutnya"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

        <?php else: ?>
            <div class="text-center py-12 bg-slate-50 rounded-2xl border border-slate-200/80">
                <p class="text-slate-600 font-semibold text-sm">Program kelas sedang dipersiapkan untuk publikasi.</p>
                <p class="text-slate-400 text-xs mt-1">Silakan kunjungi kembali dalam waktu dekat.</p>
            </div>
        <?php endif; ?>

    </div>
</section>

<!-- 4. Section Target Peserta (4 Kolom Polos & Rapi) -->
<section id="target-peserta" class="py-20 sm:py-24 bg-white border-t border-slate-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto text-center mb-16">
            <h2 class="text-xs font-bold uppercase tracking-widest text-upskill-pink mb-2">Sasaran Program</h2>
            <h3 class="text-2xl sm:text-3xl font-extrabold text-upskill-darkblue tracking-tight">
                Dirancang untuk Berbagai Jenjang Kebutuhan
            </h3>
            <p class="text-sm text-slate-500 mt-3 leading-relaxed">
                Materi disusun sistematis agar mudah dipahami dari tingkat pemula hingga profesional.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- 1. Mahasiswa -->
            <div class="p-6 rounded-xl border border-slate-200 bg-white hover:border-upskill-pink/40 transition-colors">
                <div class="w-10 h-10 rounded-lg bg-pink-50 flex items-center justify-center text-upskill-pink mb-5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 14l9-5-9-5-9 5 9 5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                    </svg>
                </div>
                <h4 class="text-base font-bold text-upskill-darkblue mb-2">Mahasiswa</h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Mempersiapkan portofolio nyata dan keterampilan siap kerja sebelum menyelesaikan studi formal.
                </p>
            </div>

            <!-- 2. Pekerja Profesional -->
            <div class="p-6 rounded-xl border border-slate-200 bg-white hover:border-upskill-pink/40 transition-colors">
                <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-upskill-blue mb-5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h4 class="text-base font-bold text-upskill-darkblue mb-2">Pekerja Profesional</h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Tingkatkan jenjang karier dan produktivitas harian dengan penguasaan tools modern dan otomasi kerja.
                </p>
            </div>

            <!-- 3. Freelancer -->
            <div class="p-6 rounded-xl border border-slate-200 bg-white hover:border-upskill-pink/40 transition-colors">
                <div class="w-10 h-10 rounded-lg bg-pink-50 flex items-center justify-center text-upskill-pink mb-5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h4 class="text-base font-bold text-upskill-darkblue mb-2">Freelancer</h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Tingkatkan daya tawar jasa, kemampuan manajemen proyek, serta pelayanan klien profesional.
                </p>
            </div>

            <!-- 4. Pengusaha UMKM -->
            <div class="p-6 rounded-xl border border-slate-200 bg-white hover:border-upskill-pink/40 transition-colors">
                <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-upskill-blue mb-5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <h4 class="text-base font-bold text-upskill-darkblue mb-2">Pelaku UMKM</h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Digitalisasi operasional usaha, optimalisasi strategi pemasaran, dan pengelolaan keuangan usaha mandiri.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- 5. Call to Action Banner -->
<section class="pb-24 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-upskill-darkblue rounded-2xl p-8 sm:p-12 text-center text-white shadow-sm">
            <h3 class="text-2xl sm:text-3xl font-extrabold tracking-tight mb-3">
                Mulai Perjalanan Peningkatan Kompetensi Anda
            </h3>
            <p class="text-sm text-slate-200 max-w-xl mx-auto mb-8 leading-relaxed">
                Pilih topik pelatihan yang sesuai dengan target karier Anda saat ini dan ikuti pembelajaran praktis bersama mentor ahli.
            </p>
            <a href="<?= base_url('/#program-kelas') ?>" class="inline-flex items-center justify-center px-7 py-3.5 text-sm font-semibold rounded-lg bg-upskill-pink hover:bg-upskill-magenta text-white transition-colors shadow-sm">
                Eksplorasi Katalog Kursus
            </a>
        </div>
    </div>
</section>

<!-- Swiper.js JavaScript via CDN -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- Inisialisasi Swiper Slider -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const swiperElement = document.querySelector('.course-swiper');
        if (swiperElement) {
            new Swiper('.course-swiper', {
                // Tampilkan sebagian dari kartu berikutnya
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
                    640: {
                        slidesPerView: 1.4,
                        spaceBetween: 24,
                        centeredSlides: true,
                    },
                    1024: {
                        slidesPerView: 1.8,
                        spaceBetween: 32,
                        centeredSlides: true,
                    },
                    1280: {
                        slidesPerView: 2.3,
                        spaceBetween: 36,
                        centeredSlides: true,
                    }
                }
            });
        }
    });
</script>

<?= $this->endSection() ?>
