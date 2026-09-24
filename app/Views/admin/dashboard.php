<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<!-- Banner Indikator Otoritas Admin -->
<div class="bg-white border border-slate-200 rounded-xl p-6 sm:p-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-extrabold text-upskill-darkblue tracking-tight">
                Ringkasan Performa Platform
            </h2>
            <p class="text-sm text-slate-500 mt-1 leading-relaxed">
                Akses kendali penuh atas manajemen pengguna, katalog kursus mentor, dan verifikasi keuangan.
            </p>
        </div>
        <div class="shrink-0">
            <span class="text-xs font-medium text-slate-500">
                Pembaruan: <?= date('d M Y') ?>
            </span>
        </div>
    </div>
</div>

<!-- ========================================================== -->
<!-- 3 CARD STATISTIK DI ATAS                                   -->
<!-- ========================================================== -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    
    <!-- Card 1: Total Peserta Aktif -->
    <div class="bg-white border border-slate-200 rounded-xl p-6 hover:border-upskill-pink/40 transition-colors">
        <div class="flex items-center justify-between mb-4">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Peserta Aktif</p>
            <div class="w-9 h-9 rounded-lg bg-pink-50 text-upskill-pink flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
        </div>
        <div class="flex items-baseline justify-between">
            <span class="text-3xl font-extrabold text-upskill-darkblue tracking-tight">
                <?= number_format($total_peserta, 0, ',', '.') ?>
            </span>
            <span class="text-xs font-semibold text-upskill-pink bg-pink-50 px-2 py-0.5 rounded border border-pink-100">
                B2C & B2B
            </span>
        </div>
        <p class="text-xs text-slate-400 mt-2">Terdaftar di seluruh program kursus</p>
    </div>

    <!-- Card 2: Total Pendapatan -->
    <div class="bg-white border border-slate-200 rounded-xl p-6 hover:border-upskill-blue/40 transition-colors">
        <div class="flex items-center justify-between mb-4">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Pendapatan</p>
            <div class="w-9 h-9 rounded-lg bg-blue-50 text-upskill-blue flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <div class="flex items-baseline justify-between">
            <span class="text-3xl font-extrabold text-upskill-darkblue tracking-tight">
                Rp <?= number_format($total_pendapatan, 0, ',', '.') ?>
            </span>
            <span class="text-xs font-semibold text-upskill-blue bg-blue-50 px-2 py-0.5 rounded border border-blue-100">
                IDR
            </span>
        </div>
        <p class="text-xs text-slate-400 mt-2">Akumulasi transaksi sukses tervalidasi</p>
    </div>

    <!-- Card 3: Tingkat Kelulusan & Total Kelas -->
    <div class="bg-white border border-slate-200 rounded-xl p-6 hover:border-purple-300 transition-colors">
        <div class="flex items-center justify-between mb-4">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Tingkat Kelulusan</p>
            <div class="w-9 h-9 rounded-lg bg-purple-50 text-purple-700 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138z"/>
                </svg>
            </div>
        </div>
        <div class="flex items-baseline justify-between">
            <span class="text-3xl font-extrabold text-upskill-darkblue tracking-tight">
                <?= esc($tingkat_kelulusan) ?>
            </span>
            <span class="text-xs font-semibold text-slate-700 bg-slate-100 px-2 py-0.5 rounded">
                <?= esc($total_kelas) ?> Kelas Terbit
            </span>
        </div>
        <p class="text-xs text-slate-400 mt-2">Rasio penyelesaian evaluasi & materi</p>
    </div>

</div>

<!-- Tabel Status Modul -->
<div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
    <div class="px-6 py-4 border-b border-slate-100">
        <h3 class="text-sm font-bold text-upskill-darkblue">Status Modul Ekosistem Platform</h3>
        <p class="text-xs text-slate-400 mt-0.5">Pemantauan kesiapan modul pembelajaran dan integrasi</p>
    </div>
    <div class="divide-y divide-slate-100 text-xs">
        <div class="px-6 py-3.5 flex items-center justify-between hover:bg-slate-50/70">
            <span class="font-medium text-slate-800">1. Skema Basis Data & Konstrain Relasional</span>
            <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 font-semibold">Tersinkronisasi (schema.sql)</span>
        </div>
        <div class="px-6 py-3.5 flex items-center justify-between hover:bg-slate-50/70">
            <span class="font-medium text-slate-800">2. Sistem Autentikasi & Enkripsi Kredensial</span>
            <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 font-semibold">Aktif (UserModel & Auth)</span>
        </div>
        <div class="px-6 py-3.5 flex items-center justify-between hover:bg-slate-50/70">
            <span class="font-medium text-slate-800">3. Filter Keamanan RBAC (Role-Based Access Control)</span>
            <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 font-semibold">Aktif (AuthGuard & RoleGuard)</span>
        </div>
        <div class="px-6 py-3.5 flex items-center justify-between hover:bg-slate-50/70">
            <span class="font-medium text-slate-800">4. Modul Manajemen Katalog Kelas & Materi (CRUD)</span>
            <span class="px-2 py-0.5 rounded bg-pink-50 text-upskill-pink font-semibold">Aktif (CourseModel & Admin\Course)</span>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
