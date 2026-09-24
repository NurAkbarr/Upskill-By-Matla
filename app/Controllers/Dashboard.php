<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    /**
     * Halaman Dasbor Utama Pengguna (Terproteksi Filter AuthGuard)
     */
    public function index()
    {
        // Jaring pengaman: Jika Super Admin mengakses dasbor reguler, alihkan otomatis ke panel admin
        if (session()->get('role') === 'super_admin') {
            return redirect()->to('/admin/dashboard');
        }

        // Ambil informasi pengguna aktif dari session
        $userName     = session()->get('full_name') ?? 'Pengguna';
        $userRole     = session()->get('role') ?? 'peserta_b2c';
        $userEmail    = session()->get('email') ?? '';
        $instansiName = session()->get('instansi_name') ?? null;

        /**
         * Struktur Switch-Case untuk Query Data Berdasarkan Role (Tahap Mendatang)
         * Data statistik dan daftar kelas akan disesuaikan dengan peran masing-masing:
         */
        $dashboardData = [];

        switch ($userRole) {
            case 'super_admin':
                // TODO (Tahap Administrasi):
                // - Query total users (mentor, peserta B2C, peserta B2B)
                // - Query total courses & revenue transaksi platform
                // - Log aktivitas sistem terbaru
                $dashboardData['role_label'] = 'Super Administrator';
                break;

            case 'mentor':
                // TODO (Tahap Mentor):
                // - Query kursus yang dibuat oleh mentor ini (courses WHERE mentor_id = user_id)
                // - Query total siswa yang terdaftar di kelas mentor
                // - Query statistik performa materi & penugasan
                $dashboardData['role_label'] = 'Mentor Praktisi';
                break;

            case 'peserta_b2b':
                // TODO (Tahap Peserta B2B / Institusi):
                // - Query kelas yang ditugaskan oleh instansi terkait
                // - Query rekap progres pelatihan karyawan / delegasi
                $dashboardData['role_label'] = 'Peserta B2B (' . ($instansiName ?? 'Institusi') . ')';
                break;

            case 'peserta_b2c':
            default:
                // TODO (Tahap Peserta B2C / Individu):
                // - Query kelas aktif yang sedang diikuti (enrollments WHERE user_id = user_id)
                // - Query persentase progres belajar materi
                // - Query sertifikat kelulusan yang telah diterbitkan
                $dashboardData['role_label'] = 'Peserta Mandiri';
                break;
        }

        $data = [
            'title'        => 'Dasbor - UPSKILL by MATLA',
            'user_name'    => $userName,
            'user_role'    => $userRole,
            'role_label'   => $dashboardData['role_label'],
            'user_email'   => $userEmail,
            'instansi_name'=> $instansiName,
        ];

        return view('dashboard/index', $data);
    }
}
